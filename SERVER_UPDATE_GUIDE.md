# تعليمات سحب التحديثات على السيرفر 🚀

## الخطوات المطلوبة:

### 1. الاتصال بالسيرفر
```bash
ssh username@your-server.com
```

### 2. الانتقال لمجلد المشروع
```bash
cd /path/to/your/laravel-project
```

### 3. عمل نسخة احتياطية (مهم!)
```bash
# نسخة احتياطية من قاعدة البيانات
cp database/database.sqlite database/database.sqlite.backup

# نسخة احتياطية من ملف .env
cp .env .env.backup
```

### 4. سحب التحديثات من GitHub
```bash
git pull origin main
```

### 5. تحديث الحزم (إذا لزم الأمر)
```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

### 6. **مهم جداً:** إعداد php.ini على السيرفر

اختر الطريقة المناسبة لسيرفرك:

#### أ) إذا كنت تستخدم Apache مع Shared Hosting:
ملف `.htaccess` موجود بالفعل ويحتوي على الإعدادات المطلوبة في:
- `/public/.htaccess` (للموقع)
- `/.htaccess` (في الجذر)

تأكد من أن `.htaccess` يحتوي على:
```apache
php_value upload_max_filesize 100M
php_value post_max_size 100M
php_value max_execution_time 600
php_value max_input_time 600
php_value memory_limit 1G
```

#### ب) إذا كنت تستخدم VPS/Dedicated Server:

##### لـ Apache:
1. عدّل ملف php.ini:
   ```bash
   sudo nano /etc/php/8.2/apache2/php.ini
   ```

2. ابحث وعدّل القيم التالية:
   ```ini
   upload_max_filesize = 100M
   post_max_size = 100M
   memory_limit = 512M
   max_execution_time = 300
   max_input_time = 300
   ```

3. أعد تشغيل Apache:
   ```bash
   sudo systemctl restart apache2
   ```

##### لـ Nginx + PHP-FPM:
1. عدّل ملف php.ini:
   ```bash
   sudo nano /etc/php/8.2/fpm/php.ini
   ```

2. عدّل القيم كما في الأعلى

3. عدّل ملف Nginx (موجود في المشروع: `nginx.conf`):
   ```bash
   sudo nano /etc/nginx/sites-available/your-site
   ```

4. تأكد من وجود:
   ```nginx
   client_max_body_size 100M;
   ```

5. أعد تشغيل الخدمات:
   ```bash
   sudo systemctl restart php8.2-fpm
   sudo systemctl restart nginx
   ```

### 7. ضبط الأذونات
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

أو استخدم السكريبت:
```bash
chmod +x fix-permissions.sh
./fix-permissions.sh
```

### 8. مسح الكاش
```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 9. اختبار رفع الصور
1. اذهب إلى `/admin` على موقعك
2. حاول إضافة منتج جديد
3. ارفع صورة (يُفضل صورة 5-10MB للاختبار)
4. تأكد من نجاح الرفع

### 10. مراقبة الأخطاء
```bash
tail -f storage/logs/laravel.log
```

## التحقق من الإعدادات 🔍

### على السيرفر، قم بإنشاء ملف اختبار:
```bash
echo "<?php phpinfo(); ?>" > public/test-php.php
```

ثم افتح في المتصفح:
```
https://yourdomain.com/test-php.php
```

ابحث عن:
- `upload_max_filesize`
- `post_max_size`
- `memory_limit`

**لا تنسَ حذف الملف بعد الاختبار:**
```bash
rm public/test-php.php
```

## استكشاف الأخطاء 🔧

### إذا استمرت مشكلة رفع الصور:

1. **تحقق من إعدادات PHP:**
   ```bash
   php -i | grep upload_max_filesize
   php -i | grep post_max_size
   ```

2. **تحقق من سجلات الأخطاء:**
   ```bash
   # Laravel logs
   tail -50 storage/logs/laravel.log
   
   # Apache logs
   tail -50 /var/log/apache2/error.log
   
   # Nginx logs
   tail -50 /var/log/nginx/error.log
   
   # PHP-FPM logs
   tail -50 /var/log/php8.2-fpm.log
   ```

3. **تحقق من الأذونات:**
   ```bash
   ls -la storage/
   ls -la bootstrap/cache/
   ```

4. **اختبر رفع ملف صغير أولاً:**
   - جرب صورة 1MB أولاً
   - ثم زد الحجم تدريجياً

## ملاحظات مهمة ⚠️

1. **استضافة مشتركة (Shared Hosting):**
   - بعض الاستضافات تحد من إعدادات PHP
   - قد تحتاج للتواصل مع الدعم الفني
   - بعض الاستضافات تسمح بـ `.user.ini` بدلاً من `.htaccess`

2. **CloudFlare:**
   - إذا كنت تستخدم CloudFlare، الحد الأقصى للرفع هو:
     - مجاني: 100MB
     - مدفوع: 500MB
   - قد تحتاج لتعطيل proxy مؤقتاً

3. **الأمان:**
   - لا تترك ملفات الاختبار (مثل phpinfo.php)
   - تأكد من `APP_DEBUG=false` في الإنتاج

## اختبار سريع ✅

أسرع طريقة للاختبار:
```bash
# على السيرفر
curl -X POST -F "file=@/path/to/large-image.jpg" https://yourdomain.com/admin/products/create
```

## الحصول على المساعدة 🆘

إذا واجهتك مشاكل:
1. افحص `storage/logs/laravel.log`
2. شغّل `./check-requirements.sh` للتحقق من المتطلبات
3. راجع `DEPLOYMENT_TROUBLESHOOTING_AR.md` للحلول الشائعة

---

**آخر تحديث:** أكتوبر 15، 2025  
**الإصدار:** 1.0.0
