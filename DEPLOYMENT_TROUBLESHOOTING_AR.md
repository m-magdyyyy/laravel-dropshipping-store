# دليل حل مشاكل رفع المشروع على السيرفر 🚀

## المشكلة
المشروع يعمل محليًا (Local) ولكن يظهر أخطاء عند رفعه على السيرفر.

## الحلول السريعة ⚡

### الحل الأول: استخدام السكريبت الآلي
قمت بإنشاء سكريبت آلي لحل معظم المشاكل:

```bash
# على السيرفر، في مجلد المشروع:
./deploy.sh
```

هذا السكريبت سيقوم بـ:
- ✓ تثبيت جميع الحزم المطلوبة
- ✓ بناء ملفات CSS و JavaScript
- ✓ إنشاء ملف .env
- ✓ توليد مفتاح التطبيق
- ✓ ضبط الأذونات
- ✓ ربط مجلد التخزين
- ✓ تشغيل قاعدة البيانات
- ✓ تحسين الأداء

### الحل الثاني: إصلاح مشاكل الأذونات فقط
إذا كانت المشكلة في الأذونات فقط:

```bash
./fix-permissions.sh
```

## الأخطاء الشائعة وحلولها 🔧

### ❌ خطأ 500 - Internal Server Error

**الأسباب المحتملة:**
1. **ملف .env مفقود أو خاطئ**
   ```bash
   # الحل:
   cp .env.production .env
   php artisan key:generate
   ```

2. **مشاكل الأذونات**
   ```bash
   # الحل:
   chmod -R 775 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

3. **قاعدة البيانات غير موجودة**
   ```bash
   # الحل:
   touch database/database.sqlite
   php artisan migrate --force
   ```

### ❌ خطأ: "The stream or file could not be opened"

**السبب:** مشكلة في أذونات مجلد `storage`

**الحل:**
```bash
chmod -R 775 storage
chown -R www-data:www-data storage
```

### ❌ خطأ: "No application encryption key"

**السبب:** مفتاح APP_KEY غير موجود في ملف .env

**الحل:**
```bash
php artisan key:generate --force
```

### ❌ خطأ: "Mix manifest not found" أو مشاكل CSS/JS

**السبب:** ملفات الأصول (Assets) لم يتم بناؤها

**الحل:**
```bash
npm install
npm run build
```

### ❌ خطأ: "CSRF token mismatch"

**الحل:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan session:clear
```

### ❌ الصفحة تعرض كود PHP بدلاً من الموقع

**السبب:** PHP لم يتم تفعيله أو إعدادات Apache/Nginx خاطئة

**الحل:**
1. تأكد من تثبيت PHP
2. في Apache، فعّل mod_php:
   ```bash
   sudo a2enmod php8.2
   sudo systemctl restart apache2
   ```

### ❌ خطأ 404 - الصفحات لا تعمل

**السبب:** mod_rewrite غير مفعل (Apache)

**الحل:**
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

## خطوات الرفع على السيرفر خطوة بخطوة 📋

### 1️⃣ رفع الملفات
```bash
# قم برفع كل الملفات عدا:
# ❌ node_modules/
# ❌ vendor/
# ❌ .env
# ❌ storage/logs/*.log
```

### 2️⃣ الاتصال بالسيرفر عبر SSH
```bash
ssh username@your-server.com
cd /path/to/your/project
```

### 3️⃣ تثبيت المتطلبات
```bash
# تثبيت حزم Composer
composer install --optimize-autoloader --no-dev

# تثبيت حزم NPM
npm install

# بناء الأصول
npm run build
```

### 4️⃣ إعداد البيئة
```bash
# نسخ ملف .env
cp .env.production .env

# تعديل الإعدادات المهمة:
nano .env
```

تأكد من تعديل:
```env
APP_ENV=production
APP_DEBUG=false          # ⚠️ مهم جداً!
APP_URL=https://yourdomain.com

DB_CONNECTION=sqlite     # أو mysql حسب السيرفر
# إعدادات قاعدة البيانات الأخرى...
```

### 5️⃣ توليد المفاتيح
```bash
php artisan key:generate --force
```

### 6️⃣ ضبط الأذونات
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 7️⃣ ربط التخزين
```bash
php artisan storage:link
```

### 8️⃣ قاعدة البيانات
```bash
# إنشاء الجداول
php artisan migrate --force

# إضافة المسؤول (إذا لزم الأمر)
php artisan db:seed --class=AdminSeeder --force
```

### 9️⃣ تحسين الأداء
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 🔟 اختبار
```bash
# تحقق من حالة التطبيق
php artisan about

# شاهد سجل الأخطاء
tail -f storage/logs/laravel.log
```

## إعدادات السيرفر الويب 🌐

### Apache
أضف هذا في ملف `.htaccess` في جذر الموقع (خارج مجلد public):

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### Nginx
أضف هذا في ملف التكوين:

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

## أوامر مفيدة للصيانة 🛠️

```bash
# مسح جميع أنواع الكاش
php artisan optimize:clear

# مسح الكاش فقط
php artisan cache:clear

# مسح كاش التكوين
php artisan config:clear

# مسح كاش المسارات
php artisan route:clear

# مسح كاش العروض
php artisan view:clear

# إعادة بناء autoloader
composer dump-autoload

# مشاهدة سجل الأخطاء مباشرة
tail -f storage/logs/laravel.log
```

## نصائح مهمة ⚠️

1. **الأمان أولاً:**
   - ✓ تأكد من `APP_DEBUG=false` في الإنتاج
   - ✓ استخدم HTTPS
   - ✓ لا ترفع ملف `.env` إلى Git

2. **النسخ الاحتياطي:**
   - ✓ احتفظ بنسخة احتياطية من قاعدة البيانات
   - ✓ احتفظ بنسخة من ملف `.env`

3. **المراقبة:**
   - ✓ راقب سجلات الأخطاء بانتظام
   - ✓ تحقق من أداء الموقع

4. **التحديثات:**
   - ✓ حافظ على تحديث Laravel
   - ✓ حدّث حزم Composer و NPM بانتظام

## تشخيص المشاكل 🔍

### لعرض معلومات التطبيق:
```bash
php artisan about
```

### للتحقق من اتصال قاعدة البيانات:
```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

### لمشاهدة الأخطاء:
```bash
# أخطاء Laravel
tail -f storage/logs/laravel.log

# أخطاء Apache
tail -f /var/log/apache2/error.log

# أخطاء Nginx
tail -f /var/log/nginx/error.log
```

## الحصول على المساعدة 🆘

إذا استمرت المشاكل:

1. **تحقق من سجل الأخطاء:**
   ```bash
   tail -50 storage/logs/laravel.log
   ```

2. **فعّل وضع التصحيح مؤقتاً** (فقط للتشخيص):
   ```env
   APP_DEBUG=true
   ```
   ⚠️ لا تنسَ إعادة تعيينه إلى `false` بعد التشخيص!

3. **تحقق من متطلبات PHP:**
   ```bash
   php -v
   php -m
   ```

## الملفات المساعدة 📁

- `deploy.sh` - سكريبت النشر الآلي
- `fix-permissions.sh` - إصلاح الأذونات
- `DEPLOYMENT_GUIDE.md` - الدليل الكامل بالإنجليزية
- `.env.production` - ملف البيئة للإنتاج

## الدعم الفني 💬

للمزيد من المعلومات، راجع:
- [Laravel Documentation](https://laravel.com/docs)
- [Deployment Guide](./DEPLOYMENT_GUIDE.md)

---

**تم إنشاء هذا الدليل بواسطة GitHub Copilot** 🤖
