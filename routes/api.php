<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Aws\S3\S3Client;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->post('/s3/presigned-url', function (Request $request) {
    // التحقق من البيانات المطلوبة
    $request->validate([
        'filename' => 'required|string|max:255',
        'filetype' => 'required|string|max:100',
    ]);

    // إنشاء اسم ملف فريد
    $originalName = pathinfo($request->filename, PATHINFO_FILENAME);
    $extension = pathinfo($request->filename, PATHINFO_EXTENSION);

    // إنشاء اسم ملف فريد باستخدام Str::slug و uniqid
    $slugifiedName = Str::slug($originalName);
    $uniqueId = uniqid();
    $uniqueFilename = $slugifiedName . '-' . $uniqueId . '.' . $extension;

    // إنشاء S3 client مباشرة
    $s3Config = config('filesystems.disks.s3_raw');
    $client = new S3Client([
        'version' => 'latest',
        'region' => $s3Config['region'],
        'credentials' => [
            'key' => $s3Config['key'],
            'secret' => $s3Config['secret'],
        ],
    ]);

    // إنشاء presigned URL للرفع
    $command = $client->getCommand('PutObject', [
        'Bucket' => $s3Config['bucket'],
        'Key' => $uniqueFilename,
        'ContentType' => $request->filetype,
        'ACL' => 'private', // الملفات الخام تكون خاصة
    ]);

    // إنشاء presigned URL صالح لمدة 5 دقائق
    $presignedUrl = $client->createPresignedRequest($command, '+5 minutes');
    $presignedUrl = (string) $presignedUrl->getUri();

    // إنشاء اسم الملف النهائي (سيتم تحويله إلى webp)
    $finalFilename = $slugifiedName . '-' . $uniqueId . '.webp';

    // إرجاع الاستجابة JSON
    return response()->json([
        'upload_url' => $presignedUrl,
        'final_filename_to_save' => $finalFilename,
    ]);
});