<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleLargeFileUpload
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Set PHP configuration for large file uploads
        ini_set('upload_max_filesize', '100M');
        ini_set('post_max_size', '100M');
        ini_set('memory_limit', '1G');
        ini_set('max_execution_time', '600');
        ini_set('max_input_time', '600');
        ini_set('max_file_uploads', '20');
        
        // Set additional settings for better file handling
        ignore_user_abort(true);
        set_time_limit(600);
        
        return $next($request);
    }
}