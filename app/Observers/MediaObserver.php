<?php

namespace App\Observers;

use Awcodes\Curator\Models\Media;
use App\Jobs\ProcessImageImmediately;
use App\Jobs\ProcessLargeImageAsync;
use Illuminate\Support\Facades\Storage;

class MediaObserver
{
    public function created(Media $media): void
    {
        // تخطي المعالجة إذا لم يكن صورة
        if ($media->type !== 'image') {
            return;
        }

        // تقدير حجم الصورة من البيانات المخزنة
        $estimatedSize = $media->size ?? 0;

        // إذا كانت الصورة كبيرة جداً (> 10MB)، استخدم المعالجة غير المتزامنة
        if ($estimatedSize > 10 * 1024 * 1024) { // 10MB
            ProcessLargeImageAsync::dispatch($media->id);
        } else {
            // الصور العادية والكبيرة تُعالج فوراً
            ProcessImageImmediately::dispatch($media->id);
        }
    }
}