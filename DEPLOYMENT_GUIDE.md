# دليل رفع المشروع على السيرفر (Deployment Guide)

## الخطوات المطلوبة لرفع المشروع على السيرفر:

### 1. رفع الملفات
```bash
# رفع جميع ملفات المشروع عدا:
# - node_modules
# - vendor
# - .env
# - storage/logs/*
# - storage/framework/cache/*
```

### 2. إعدادات السيرفر الأساسية

#### أ) تثبيت المتطلبات
```bash
# على السيرفر، تأكد من تثبيت:
# - PHP 8.2 أو أحدث
# - Composer
# - Node.js & NPM
```

#### ب) تثبيت الحزم
```bash
# في مجلد المشروع على السيرفر:
composer install --optimize-autoloader --no-dev

npm install
npm run build
```

### 3. إعداد ملف .env
```bash
# انسخ ملف .env.production إلى .env
cp .env.production .env

# قم بتعديل الإعدادات التالية:
# - APP_URL (رابط موقعك)
# - APP_DEBUG=false (مهم جداً للأمان)
# - APP_ENV=production
# - إعدادات قاعدة البيانات
```

### 4. توليد مفتاح التطبيق
```bash
php artisan key:generate
```

### 5. ضبط أذونات المجلدات (مهم جداً!)
```bash
# أعطِ صلاحيات الكتابة لـ storage و bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# إذا كنت تستخدم Apache/Nginx
chown -R www-data:www-data storage bootstrap/cache

# أو إذا كنت تستخدم مستخدم آخر
chown -R [username]:www-data storage bootstrap/cache
```

### 6. ربط مجلد التخزين العام
```bash
php artisan storage:link
```

### 7. تشغيل قاعدة البيانات
```bash
# إنشاء الجداول
php artisan migrate --force

# إضافة بيانات افتراضية (إذا لزم الأمر)
php artisan db:seed --force
```

### 8. تحسين الأداء
```bash
# تخزين إعدادات التطبيق مؤقتاً
php artisan config:cache
php artisan route:cache
php artisan view:cache

# تحسين autoloader
composer dump-autoload --optimize
```

### 9. إعدادات Apache/Nginx

#### Apache (.htaccess)
تأكد من وجود ملف `.htaccess` في مجلد `public`:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

#### Nginx
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/project/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 10. التحقق من الأخطاء

#### إذا ظهرت أخطاء 500:
```bash
# تحقق من سجلات الأخطاء
tail -f storage/logs/laravel.log

# أو سجلات السيرفر
tail -f /var/log/apache2/error.log
# أو
tail -f /var/log/nginx/error.log
```

#### إذا ظهرت أخطاء أذونات:
```bash
# تأكد من الأذونات الصحيحة
ls -la storage/
ls -la bootstrap/cache/

# إعادة ضبط الأذونات
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### إذا ظهرت أخطاء في CSS/JS:
```bash
# تأكد من بناء الأصول
npm run build

# تحقق من APP_URL في ملف .env
```

### 11. أمان إضافي

```bash
# تعطيل عرض الأخطاء في الإنتاج
# في ملف .env:
APP_DEBUG=false
APP_ENV=production

# حذف الملفات غير الضرورية
rm -f public/phpinfo.php
rm -f public/fix-upload.php
rm -f php-custom.ini
rm -f uploads.ini
```

## الأخطاء الشائعة وحلولها:

### خطأ: "The stream or file could not be opened"
```bash
chmod -R 775 storage
chown -R www-data:www-data storage
```

### خطأ: "No application encryption key"
```bash
php artisan key:generate
```

### خطأ: "Class not found"
```bash
composer dump-autoload
php artisan clear-compiled
```

### خطأ: "CSRF token mismatch"
```bash
php artisan config:clear
php artisan cache:clear
php artisan session:clear
```

### خطأ: "Mix manifest not found"
```bash
npm run build
```

## نصائح إضافية:

1. **النسخ الاحتياطي**: احتفظ دائماً بنسخة احتياطية من قاعدة البيانات والملفات
2. **المراقبة**: راقب سجلات الأخطاء بانتظام
3. **التحديثات**: حافظ على تحديث Laravel والحزم
4. **الأمان**: استخدم HTTPS وقم بتحديث مفتاح APP_KEY بشكل دوري

## اختبار المشروع:

```bash
# تحقق من حالة التطبيق
php artisan about

# اختبر الاتصال بقاعدة البيانات
php artisan tinker
>>> DB::connection()->getPdo();

# مسح الكاش بعد أي تغيير
php artisan optimize:clear
```
