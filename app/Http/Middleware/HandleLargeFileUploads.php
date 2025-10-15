<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleLargeFileUploads
{
    public function handle(Request $request, Closure $next)
    {
        // تطبيق إعدادات PHP لرفع الملفات الكبيرة
        ini_set('upload_max_filesize', '100M');
        ini_set('post_max_size', '100M');
        ini_set('memory_limit', '1G');
        ini_set('max_execution_time', 600);
        ini_set('max_input_time', 600);
        ini_set('max_file_uploads', 20);

        return $next($request);
    }
}