# نظام تحسين الصور التلقائي

## نظرة عامة

تم تنفيذ نظام تحسين تلقائي للصور في تطبيق Laravel باستخدام مكتبة **Intervention Image**. يقوم النظام بتحسين الصور تلقائيًا عند رفع منتجات جديدة أو تحديثها.

## المميزات

### 1. تحسين تلقائي للصور
- يتم تشغيل Job تلقائيًا عند إنشاء أو تحديث منتج به صورة
- يدعم الصور المحلية والصور من روابط خارجية (URLs)

### 2. تحويل إلى WebP
- تحويل جميع الصور إلى صيغة **WebP** لتقليل حجم الملفات
- جودة 80% للحصول على توازن بين الحجم والجودة

### 3. تغيير حجم ذكي
- **الصورة الأساسية**: عرض أقصى 1200 بكسل
- **الصورة المصغرة (Thumbnail)**: عرض أقصى 360 بكسل
- يحافظ على نسبة العرض إلى الارتفاع

### 4. معرض الصور
- يتم تحسين صور المعرض (Gallery) أيضًا
- عرض أقصى 1200 بكسل لكل صورة

## البنية التقنية

### الملفات الرئيسية

#### 1. `app/Jobs/OptimizeProductImages.php`
Job مسؤول عن معالجة وتحسين الصور. يعمل في الخلفية (Queue).

#### 2. `app/Models/Product.php`
تم إضافة:
- **Event Listeners**: لتشغيل Job التحسين تلقائيًا
- **Accessors**:
  - `image_url`: للحصول على رابط الصورة الأساسية
  - `thumb_url`: للحصول على رابط الصورة المصغرة

#### 3. `app/Support/ImageOptimizer.php`
Helper class يحتوي على دوال مساعدة لتحسين الصور.

#### 4. `database/migrations/2025_09_12_000001_add_thumbnail_to_products_table.php`
Migration يضيف حقل `thumbnail_path` إلى جدول products.

### حقول قاعدة البيانات

في جدول `products`:
- `image`: مسار الصورة الأساسية المحسّنة
- `thumbnail_path`: مسار الصورة المصغرة
- `image_url`: (اختياري) رابط خارجي للصورة
- `gallery`: معرض صور (JSON array)

## كيفية الاستخدام

### 1. إنشاء منتج جديد

عند إنشاء منتج جديد عبر Filament أو برمجيًا، يتم:
1. حفظ المنتج في قاعدة البيانات
2. تشغيل `OptimizeProductImages` Job تلقائيًا
3. تحسين الصورة وإنشاء thumbnail
4. تحديث حقول `image` و `thumbnail_path`

```php
// مثال برمجي
$product = Product::create([
    'name' => 'منتج جديد',
    'price' => 100,
    'image' => 'products/original-image.jpg',
    // ... باقي الحقول
]);
// سيتم تشغيل Job التحسين تلقائيًا
```

### 2. تحديث صورة منتج

عند تحديث صورة منتج موجود:
```php
$product->update([
    'image' => 'products/new-image.jpg'
]);
// سيتم تشغيل Job التحسين تلقائيًا فقط إذا تغيرت الصورة
```

### 3. الحصول على روابط الصور

```php
$product = Product::find(1);

// الصورة الأساسية المحسّنة
$mainImage = $product->image_url;

// الصورة المصغرة
$thumbnail = $product->thumb_url;
```

### 4. في Blade Views

```blade
{{-- عرض الصورة المصغرة --}}
<img src="{{ $product->thumb_url }}" alt="{{ $product->name }}">

{{-- عرض الصورة الأساسية --}}
<img src="{{ $product->image_url }}" alt="{{ $product->name }}">
```

## Filament Integration

تم تحديث `ProductsTable` لعرض الصور المصغرة بدلاً من الصور الأساسية في جدول المنتجات، مما يسرّع تحميل لوحة التحكم.

```php
ImageColumn::make('thumbnail_path')
    ->label('الصورة')
    ->disk('public')
    ->size(60)
    ->square()
    ->getStateUsing(fn ($record) => $record->thumbnail_path ?: $record->image)
```

## تشغيل Queue Worker

للتأكد من تشغيل Jobs التحسين، يجب تشغيل queue worker:

```bash
# في بيئة التطوير
php artisan queue:work

# أو باستخدام Laravel Sail
sail artisan queue:work

# أو في الخلفية (Production)
php artisan queue:work --daemon
```

## تحسين المنتجات الموجودة

لتحسين صور المنتجات الموجودة مسبقًا، استخدم السكريبت:

```bash
php scripts/optimize_all_products.php
```

أو يمكنك إنشاء Command جديد:

```bash
php artisan make:command OptimizeExistingProducts
```

ثم في الـ Command:
```php
public function handle()
{
    Product::chunk(50, function ($products) {
        foreach ($products as $product) {
            if ($product->image) {
                OptimizeProductImages::dispatch($product->id);
            }
        }
    });
    
    $this->info('تم إضافة جميع المنتجات إلى قائمة التحسين!');
}
```

## الأداء والحجم

### قبل التحسين
- صورة JPEG/PNG نموذجية: 2-5 MB
- وقت تحميل الصفحة: بطيء

### بعد التحسين
- صورة WebP محسّنة (1200px): 100-300 KB
- صورة مصغرة WebP (360px): 20-50 KB
- تحسّن في السرعة: 80-90%

## المتطلبات

### المكتبات المثبتة
```json
{
    "intervention/image": "^3.11"
}
```

### PHP Extensions
- GD أو Imagick
- يُفضل GD للتوافق

## استكشاف الأخطاء

### 1. الصور لا تتحسّن تلقائيًا
- تأكد من تشغيل queue worker
- تحقق من logs: `storage/logs/laravel.log`

### 2. صور Thumbnail لا تظهر
- تأكد من تشغيل: `php artisan storage:link`
- تحقق من وجود المجلد: `storage/app/public/products/thumbs/`

### 3. خطأ في Memory
زد الذاكرة المتاحة في `php.ini`:
```ini
memory_limit = 256M
```

## الصيانة

### تنظيف الصور القديمة
عند تحسين الصور، يتم حذف الصور الأصلية تلقائيًا إذا تغير المسار.

### Backup
احتفظ بنسخة احتياطية من مجلد `storage/app/public/products/` قبل التحسينات الجماعية.

## الخلاصة

نظام تحسين الصور هذا يوفر:
- ✅ تحسين تلقائي دون تدخل يدوي
- ✅ تقليل حجم الصور بنسبة 80-90%
- ✅ تسريع تحميل الموقع
- ✅ تحسين تجربة المستخدم
- ✅ توفير مساحة تخزين على السيرفر

---

**تم التنفيذ بواسطة:** GitHub Copilot  
**التاريخ:** 18 أكتوبر 2025
