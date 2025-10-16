<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // حل مشكلة رفع الملفات الكبيرة - يجب أن يكون في أول السطر
        @ini_set('upload_max_filesize', '100M');
        @ini_set('post_max_size', '100M');
        @ini_set('memory_limit', '1G');
        @ini_set('max_execution_time', 600);
        @ini_set('max_input_time', 600);
        @ini_set('max_file_uploads', 20);
    }
}
