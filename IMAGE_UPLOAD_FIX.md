# حل مشكلة رفع الصور 📸

## المشكلة
عند محاولة رفع صورة حجمها 10MB، ظهر الخطأ التالي:
```
PHP Warning: POST Content-Length of 10014269 bytes exceeds the limit of 8388608 bytes
```

## السبب
الحد الأقصى الافتراضي لرفع الملفات في PHP هو 8MB فقط.

## الحل ✅

تم إجراء التعديلات التالية:

### 1. إنشاء ملف `php.ini` مخصص
أنشأنا ملف `php.ini` في جذر المشروع بالإعدادات التالية:
- `upload_max_filesize = 100M` - حد رفع الملفات
- `post_max_size = 100M` - حد حجم POST
- `memory_limit = 512M` - حد الذاكرة
- `max_execution_time = 300` - مهلة التنفيذ
- `max_file_uploads = 20` - عدد الملفات المسموح برفعها

### 2. تحديث `start-dev.sh`
تم تعديل السكريبت ليستخدم ملف `php.ini` المخصص:
```bash
php -c php.ini -S 127.0.0.1:8000 -t public
```

### 3. تحديث `package.json`
تم تغيير أمر `dev` ليستخدم `php.ini` بدلاً من `uploads.ini`:
```json
"dev": "vite & php -c php.ini artisan serve"
```

### 4. تحديث إعدادات Filament
تم تعديل `ProductForm.php`:
- تقليل الحد الأقصى لحجم الملف من 100MB إلى **50MB** (قيمة معقولة)
- إضافة نص مساعد يوضح الحد الأقصى للمستخدم

## كيفية الاستخدام 🚀

### محليًا (Local Development)

1. **أوقف السيرفر الحالي** (إذا كان يعمل):
   ```bash
   # اضغط Ctrl+C في نافذة الترمينال
   ```

2. **ابدأ السيرفر بالإعدادات الجديدة**:
   ```bash
   ./start-dev.sh
   # أو
   npm run dev
   ```

3. **اختبر رفع الصورة**:
   - افتح http://127.0.0.1:8000/admin
   - اذهب لإضافة منتج جديد
   - جرب رفع صورة حجمها حتى 50MB

### على السيرفر (Production)

#### لـ Apache:
تأكد من وجود الإعدادات التالية في `.htaccess` (موجودة بالفعل):
```apache
php_value upload_max_filesize 100M
php_value post_max_size 100M
php_value max_execution_time 600
php_value max_input_time 600
php_value memory_limit 1G
```

#### لـ Nginx:
أضف في ملف التكوين:
```nginx
client_max_body_size 100M;
```

وفي ملف `php.ini` الخاص بـ PHP-FPM (عادة في `/etc/php/8.2/fpm/php.ini`):
```ini
upload_max_filesize = 100M
post_max_size = 100M
memory_limit = 512M
max_execution_time = 300
```

ثم أعد تشغيل الخدمات:
```bash
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx
```

## التحقق من الإعدادات 🔍

للتحقق من إعدادات PHP الحالية:
```bash
php -c php.ini -r "echo 'upload_max_filesize: ' . ini_get('upload_max_filesize') . PHP_EOL; echo 'post_max_size: ' . ini_get('post_max_size') . PHP_EOL;"
```

النتيجة المتوقعة:
```
upload_max_filesize: 100M
post_max_size: 100M
```

## ملاحظات مهمة ⚠️

1. **حجم الصور الموصى به**: 
   - للأداء الأفضل، استخدم صور حجمها أقل من 5MB
   - يمكن ضغط الصور قبل رفعها باستخدام أدوات مثل TinyPNG

2. **الحد الأقصى الآن**: 50MB لكل صورة

3. **معرض الصور**: يمكن رفع حتى 5 صور، كل صورة حتى 50MB

4. **أنواع الصور المدعومة**:
   - JPEG/JPG
   - PNG
   - GIF
   - WebP

## الملفات المعدلة 📝

- ✅ `php.ini` - جديد
- ✅ `package.json` - معدل
- ✅ `start-dev.sh` - معدل
- ✅ `app/Filament/Resources/Products/Schemas/ProductForm.php` - معدل

## الخطوات التالية 📌

1. ✅ حل المشكلة محليًا
2. ⏳ اختبار رفع الصور
3. ⏳ رفع على GitHub
4. ⏳ سحب التحديثات على السيرفر
5. ⏳ اختبار على السيرفر

---

**تم الحل في:** أكتوبر 15، 2025  
**الحالة:** ✅ جاهز للاختبار
