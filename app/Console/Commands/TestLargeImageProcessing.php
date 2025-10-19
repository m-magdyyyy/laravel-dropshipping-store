<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Awcodes\Curator\Models\Media;
use App\Jobs\ProcessImageImmediately;

class TestLargeImageProcessing extends Command
{
    protected $signature = 'test:large-image';
    protected $description = 'اختبار معالجة الصور الكبيرة (15MB+)';

    public function handle()
    {
        $this->info('🧪 بدء اختبار معالجة الصور الكبيرة...');

        // إنشاء صورة وهمية كبيرة (محاكاة 15MB)
        $baseImage = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
        $largeImageData = str_repeat($baseImage, 200000); // ~15MB

        $originalSize = strlen($largeImageData);
        $this->info('📏 حجم الصورة المحاكاة: ' . round($originalSize / 1024 / 1024, 2) . ' MB');

        try {
            // رفع الملف إلى S3 raw
            $filename = 'test-large-' . Str::random(8) . '.jpg';
            Storage::disk('s3_raw')->put($filename, $largeImageData);

            $this->info('☁️ تم رفع الملف الكبير إلى S3 Raw');

            // إنشاء سجل في Curator
            $media = Media::create([
                'name' => 'Test Large Image (15MB+)',
                'path' => $filename,
                'disk' => 's3_raw',
                'size' => $originalSize,
                'type' => 'image',
                'ext' => 'jpg',
            ]);

            $this->info('🗄️ تم إنشاء سجل في قاعدة البيانات');
            $this->info('ID: ' . $media->id);

            // تشغيل Job المعالجة فوراً
            $startTime = microtime(true);
            $this->info('⚡ تشغيل Job المعالجة فوراً...');

            $job = new \App\Jobs\ProcessImageImmediately($media->id);
            $job->handle();

            $endTime = microtime(true);
            $processingTime = round($endTime - $startTime, 2);

            // فحص النتيجة
            $media->refresh();
            $finalSize = $media->size;

            $this->info('📊 النتيجة النهائية:');
            $this->info('القرص: ' . $media->disk);
            $this->info('المسار: ' . $media->path);
            $this->info('الحجم النهائي: ' . round($finalSize / 1024, 2) . ' KB');
            $this->info('وقت المعالجة: ' . $processingTime . ' ثانية');

            $compressionRatio = round((1 - $finalSize / $originalSize) * 100, 2);
            $this->info('نسبة الضغط: ' . $compressionRatio . '%');

            if (Storage::disk('s3_processed')->exists($media->path)) {
                $this->info('✅ تمت المعالجة بنجاح!');
                $this->info('💾 توفير في المساحة: ' . round(($originalSize - $finalSize) / 1024 / 1024, 2) . ' MB');
            } else {
                $this->error('❌ فشلت المعالجة');
            }

        } catch (\Exception $e) {
            $this->error('❌ خطأ: ' . $e->getMessage());
        }

        $this->info('🎉 انتهى الاختبار!');
    }
}
