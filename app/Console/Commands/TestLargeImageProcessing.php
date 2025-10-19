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

        // إنشاء صورة PNG حقيقية كبيرة
        $width = 2000;
        $height = 1500;
        $image = imagecreatetruecolor($width, $height);
        
        // إضافة ألوان عشوائية لجعل الصورة أكبر
        for ($i = 0; $i < 500; $i++) {
            $color = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
            imagefilledrectangle($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $color);
        }
        
        // حفظ في buffer بدون ضغط
        ob_start();
        imagepng($image, null, 0); // 0 = no compression
        $largeImageData = ob_get_clean();
        imagedestroy($image);

        $originalSize = strlen($largeImageData);
        $this->info('📏 حجم الصورة: ' . round($originalSize / 1024 / 1024, 2) . ' MB');

        try {
            // رفع الملف إلى local storage
            $filename = 'test-images/test-large-' . Str::random(8) . '.png';
            Storage::disk('public')->put($filename, $largeImageData);

            $this->info('💾 تم حفظ الملف في storage/app/public');

            // إنشاء سجل في Curator
            $media = Media::create([
                'name' => 'Test Large Image',
                'path' => $filename,
                'disk' => 'public',
                'size' => $originalSize,
                'type' => 'image',
                'ext' => 'png',
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

            if (Storage::disk('public')->exists($media->path)) {
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
