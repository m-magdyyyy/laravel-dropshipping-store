<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageController extends Controller
{
    public function uploadImage(Request $request)
    {
        // 1. التحقق من صحة الملف المرفوع
        $request->validate([
            'image_file' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:20480', // 20MB = 20480KB
        ]);

        // 2. الحصول على الملف المرفوع
        $uploadedFile = $request->file('image_file');

        // 3. إنشاء اسم فريد جديد للصورة
        $newFileName = time() . '_' . uniqid() . '.webp';

        // 4. إنشاء مجلد التخزين إذا لم يكن موجوداً
        $uploadPath = public_path('uploads/images');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // 5. إنشاء مسار الملف الكامل
        $fullPath = $uploadPath . '/' . $newFileName;

        // 6. معالجة الصورة باستخدام Intervention Image
        $manager = new ImageManager(new Driver());

        try {
            // قراءة الصورة
            $image = $manager->read($uploadedFile->getRealPath());

            // تصغير الصورة لأقصى عرض 1200 بكسل مع الحفاظ على النسبة
            $image->scaleDown(width: 1200);

            // حفظ الصورة كـ WebP بجودة 85%
            $image->toWebp(85)->save($fullPath);

        } catch (\Exception $e) {
            // في حالة حدوث خطأ في معالجة الصورة
            return back()->with('error', 'حدث خطأ في معالجة الصورة: ' . $e->getMessage());
        }

        // 7. إرجاع المستخدم للصفحة السابقة مع رسالة نجاح
        return back()->with('success', 'تم رفع ومعالجة الصورة بنجاح! اسم الملف: ' . $newFileName);
    }
}
