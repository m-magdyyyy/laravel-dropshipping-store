# S3 Direct Upload Component for Filament

مكون مخصص لـ Filament يتيح رفع الملفات مباشرة إلى AWS S3 باستخدام Presigned URLs.

## الميزات

- ✅ رفع مباشر إلى S3 بدون المرور بالخادم
- ✅ دعم السحب والإفلات (Drag & Drop)
- ✅ عرض تقدم الرفع
- ✅ إمكانية إلغاء الرفع
- ✅ التحقق من نوع وحجم الملف
- ✅ واجهة مستخدم جميلة ومتجاوبة
- ✅ دعم المصادقة (Sanctum)
- ✅ رسائل خطأ واضحة

## المتطلبات

1. إعداد AWS S3 في Laravel
2. إنشاء API endpoint للـ Presigned URLs
3. تثبيت axios في المشروع

## الاستخدام

### 1. في نموذج Filament

```php
use App\Filament\Forms\Components\S3DirectUpload;

public static function form(Form $form): Form
{
    return $form
        ->schema([
            S3DirectUpload::make('image')
                ->label('صورة المنتج')
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                ->maxFileSize(10 * 1024 * 1024) // 10MB
                ->disk('s3_processed'),

            // أو للملفات المتعددة
            S3DirectUpload::make('gallery')
                ->label('معرض الصور')
                ->multiple()
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                ->maxFileSize(5 * 1024 * 1024) // 5MB لكل ملف
                ->disk('s3_processed'),
        ]);
}
```

### 2. إعداد API Token

في layout الخاص بـ Filament، أضف meta tag للتوكن:

```blade
<meta name="api-token" content="{{ auth()->user()?->currentAccessToken()?->plainTextToken }}">
```

أو في ملف `config/sanctum.php`:

```php
'stateful' => [
    // أضف domain الخاص بـ Filament
    'localhost:8000',
    '127.0.0.1:8000',
],
```

## API Endpoint

يجب أن يكون لديك endpoint في `routes/api.php`:

```php
Route::middleware('auth:sanctum')->post('/s3/presigned-url', function (Request $request) {
    // منطق إنشاء Presigned URL
    // راجع الملف المرفق routes/api.php
});
```

## الإعدادات

### في `.env`

```env
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_DEFAULT_REGION=us-west-2
AWS_RAW_UPLOADS_BUCKET=your-raw-bucket
AWS_PROCESSED_IMAGES_BUCKET=your-processed-bucket
```

### في `config/filesystems.php`

```php
's3_raw' => [
    'driver' => 's3',
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_DEFAULT_REGION'),
    'bucket' => env('AWS_RAW_UPLOADS_BUCKET'),
],

's3_processed' => [
    'driver' => 's3',
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_DEFAULT_REGION'),
    'bucket' => env('AWS_PROCESSED_IMAGES_BUCKET'),
    'url' => 'https://' . env('AWS_PROCESSED_IMAGES_BUCKET') . '.s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com',
    'visibility' => 'public',
],
```

## الأمان

- الملفات تُرفع مباشرة إلى S3 بدون تخزين مؤقت على الخادم
- Presigned URLs صالحة لمدة 5 دقائق فقط
- يتطلب المصادقة للحصول على Presigned URL
- التحقق من نوع وحجم الملف على الجانب العميل والخادم

## الملفات المطلوبة

- `app/Filament/Forms/Components/S3DirectUpload.php` - المكون الرئيسي
- `resources/views/filament/forms/components/s3-direct-upload.blade.php` - القالب
- `resources/js/s3-direct-upload.js` - كلاس JavaScript (اختياري)
- `routes/api.php` - API endpoint للـ Presigned URLs

## التخصيص

يمكن تخصيص المكون من خلال:

```php
S3DirectUpload::make('file')
    ->acceptedFileTypes(['application/pdf']) // أنواع ملفات مخصصة
    ->maxFileSize(20 * 1024 * 1024) // حجم مخصص
    ->multiple() // ملفات متعددة
    ->disk('s3_custom') // قرص تخزين مخصص
```

## استكشاف الأخطاء

### مشاكل شائعة:

1. **خطأ في التوكن**: تأكد من وجود meta tag أو localStorage للتوكن
2. **خطأ في CORS**: تأكد من إعداد CORS في S3 bucket
3. **خطأ في الصلاحيات**: تأكد من صلاحيات IAM للمستخدم

### تصحيح الأخطاء:

افتح Developer Tools في المتصفح وتحقق من:
- Network tab للطلبات
- Console tab للأخطاء
- Laravel logs لأخطاء الخادم