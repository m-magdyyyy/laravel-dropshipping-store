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

        try {
            // إنشاء صورة PNG حقيقية باستخدام GD
            $width = 800;
            $height = 600;
            $image = imagecreatetruecolor($width, $height);
            
            // إضافة ألوان عشوائية
            for ($i = 0; $i < 50; $i++) {
                $color = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
                imagefilledrectangle($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $color);
            }
            
            // حفظ في buffer
            ob_start();
            imagepng($image, null, 0); // 0 = no compression (أكبر حجم)
            $imageData = ob_get_clean();
            imagedestroy($image);

            $this->info('📸 تم إنشاء صورة PNG حقيقية (' . round(strlen($imageData) / 1024, 2) . ' KB)');

            // رفع الملف إلى local storage
            $filename = 'test-images/test-large-' . Str::random(8) . '.png';
            Storage::disk('public')->put($filename, $imageData);

            $this->info('💾 تم حفظ الملف في storage/app/public (' . round(strlen($imageData) / 1024 / 1024, 2) . ' MB)');

            // إنشاء سجل في Curator
            $media = Media::create([
                'name' => 'Test Large Image',
                'path' => $filename,
                'disk' => 'public',
                'size' => strlen($imageData),
                'type' => 'image',
                'ext' => 'png',
            ]);

            $this->info('🗄️ تم إنشاء سجل في قاعدة البيانات');
            $this->info('ID: ' . $media->id);
            $this->info('الحجم الأصلي: ' . round(strlen($imageData) / 1024, 2) . ' KB');

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

            if (Storage::disk('public')->exists($media->path)) {
                $this->info('✅ تمت المعالجة بنجاح!');
                
                // عرض معلومات إضافية
                $finalSize = Storage::disk('public')->size($media->path);
                $this->info('الحجم النهائي: ' . round($finalSize / 1024, 2) . ' KB');
                $this->info('نسبة الضغط: ' . round((1 - $finalSize / strlen($imageData)) * 100, 2) . '%');
            } else {
                $this->error('❌ فشلت المعالجة');
            }

        } catch (\Exception $e) {
            $this->error('❌ خطأ: ' . $e->getMessage());
        }

        $this->info('🎉 انتهى الاختبار!');
    }
}
