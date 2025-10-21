<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Awcodes\Curator\Models\Media;
use App\Observers\MediaObserver;
use App\Models\Product;
use App\Observers\ProductObserver;
use App\Events\OrderPlaced;
use App\Listeners\SendOrderTelegramNotification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // تسجيل Event Listeners
        Event::listen(
            OrderPlaced::class,
            SendOrderTelegramNotification::class
        );

        // تسجيل Media Observer لمعالجة الصور فوراً (للصور من Curator)
        Media::observe(MediaObserver::class);

        // تسجيل Product Observer لمعالجة الصور الجديدة (للصور من FileUpload)
        Product::observe(ProductObserver::class);

        // حل مشكلة رفع الملفات الكبيرة - يجب أن يكون في أول السطر
        @ini_set('upload_max_filesize', '100M');
        @ini_set('post_max_size', '100M');
        @ini_set('memory_limit', '1G');
        @ini_set('max_execution_time', 600);
        @ini_set('max_input_time', 600);
        @ini_set('max_file_uploads', 20);
        
        // تحسينات الأداء
        @ini_set('opcache.enable', '1');
        @ini_set('opcache.memory_consumption', '256');
        @ini_set('opcache.max_accelerated_files', '20000');
        @ini_set('opcache.revalidate_freq', '2');
        @ini_set('opcache.fast_shutdown', '1');
    }
}
