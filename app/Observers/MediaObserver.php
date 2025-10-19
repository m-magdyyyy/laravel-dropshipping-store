<?php

namespace App\Observers;

use Awcodes\Curator\Models\Media;
use App\Jobs\ProcessImageImmediately;

class MediaObserver
{
    public function created(Media $media): void
    {
        // تشغيل Job معالجة الصور فوراً للسيرفرات الصغيرة
        ProcessImageImmediately::dispatch($media->id);
    }
}