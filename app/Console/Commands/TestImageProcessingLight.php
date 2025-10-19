<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Awcodes\Curator\Models\Media;
use App\Jobs\ProcessImageImmediately;

class TestImageProcessingLight extends Command
{
    protected $signature = 'test:image-light';
    protected $description = 'اختبار سريع لمعالجة الصور الخفيفة';

    public function handle()
    {
        $this->info('🧪 بدء اختبار معالجة الصور الخفيفة...');

        // إنشاء صورة وهمية أكبر (للاختبار)
        $imageData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
        // تكرار البيانات لجعلها أكبر (محاكاة صورة كبيرة)
        $largeImageData = str_repeat($imageData, 1000); // ~70KB * 1000 = ~70MB

        try {
            // رفع الملف إلى S3 raw
            $filename = 'test-large-' . Str::random(8) . '.jpg';
            Storage::disk('s3_raw')->put($filename, $largeImageData);

            $this->info('☁️ تم رفع الملف الكبير إلى S3 Raw (' . round(strlen($largeImageData) / 1024 / 1024, 2) . ' MB)');

            // إنشاء سجل في Curator
            $media = Media::create([
                'name' => 'Test Large Image',
                'path' => $filename,
                'disk' => 's3_raw',
                'size' => strlen($largeImageData),
                'type' => 'image',
                'ext' => 'jpg',
            ]);

            $this->info('🗄️ تم إنشاء سجل في قاعدة البيانات');
            $this->info('ID: ' . $media->id);
            $this->info('الحجم الأصلي: ' . round(strlen($largeImageData) / 1024 / 1024, 2) . ' MB');

            // تشغيل Job المعالجة فوراً (بدون queue)
            $this->info('⚡ تشغيل Job المعالجة فوراً...');
            $job = new \App\Jobs\ProcessImageImmediately($media->id);
            $job->handle();

            // انتظار قصير
            sleep(2);

            $media->refresh();
            $this->info('📊 النتيجة:');
            $this->info('القرص: ' . $media->disk);
            $this->info('المسار: ' . $media->path);
            $this->info('الحجم: ' . $media->size . ' bytes');
            $this->info('النوع: ' . $media->mime_type);

            if (Storage::disk('s3_processed')->exists($media->path)) {
                $this->info('✅ تمت المعالجة بنجاح!');
            } else {
                $this->error('❌ فشلت المعالجة');
            }

        } catch (\Exception $e) {
            $this->error('❌ خطأ: ' . $e->getMessage());
        }

        $this->info('🎉 انتهى الاختبار!');
    }
}
