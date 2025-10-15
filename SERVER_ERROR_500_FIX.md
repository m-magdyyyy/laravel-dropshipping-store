#!/bin/bash

echo "================================================"
echo "    دليل حل خطأ 500 على السيرفر"
echo "================================================"
echo ""

cat << 'EOF'
🔴 المشكلة: Internal Server Error (500)

الخطأ 500 يعني أن هناك مشكلة في التكوين أو الأكواد على السيرفر.

## الأسباب المحتملة والحلول:

### 1️⃣ مشكلة الأذونات (الأكثر شيوعاً)
```bash
# على السيرفر، نفذ:
cd /path/to/your/project

# إصلاح الأذونات
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# أو استخدم السكريبت
chmod +x fix-permissions.sh
sudo ./fix-permissions.sh
```

### 2️⃣ ملف .env مفقود أو APP_KEY فارغ
```bash
# تحقق من وجود .env
ls -la .env

# إذا كان مفقوداً:
cp .env.example .env

# توليد APP_KEY
php artisan key:generate
```

### 3️⃣ الكاش القديم
```bash
# مسح كل أنواع الكاش
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 4️⃣ مشاكل Composer
```bash
# إعادة تثبيت الحزم
composer install --optimize-autoloader --no-dev
composer dump-autoload
```

### 5️⃣ مجلدات storage المطلوبة مفقودة
```bash
# إنشاء المجلدات المطلوبة
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# ضبط الأذونات
chmod -R 775 storage bootstrap/cache
```

### 6️⃣ ملف storage/logs/laravel.log
```bash
# اقرأ آخر 50 سطر من سجل الأخطاء
tail -50 storage/logs/laravel.log

# أو راقب الأخطاء مباشرة
tail -f storage/logs/laravel.log
```

### 7️⃣ ملفات Apache/Nginx error logs
```bash
# Apache
sudo tail -50 /var/log/apache2/error.log

# Nginx
sudo tail -50 /var/log/nginx/error.log
```

### 8️⃣ مشكلة في php.ini أو .htaccess
```bash
# تحقق من ملف .htaccess في public
cat public/.htaccess

# تحقق من ملف .htaccess في الجذر
cat .htaccess

# إذا كانت هناك مشكلة، يمكنك تعطيله مؤقتاً:
mv .htaccess .htaccess.bak
```

### 9️⃣ APP_DEBUG للتشخيص (مؤقتاً فقط!)
```bash
# في ملف .env، غيّر مؤقتاً:
APP_DEBUG=true
APP_ENV=local

# ثم افتح الموقع لرؤية الخطأ بالتفصيل
# ⚠️ لا تنسَ إعادته بعد التشخيص:
APP_DEBUG=false
APP_ENV=production
```

### 🔟 التحقق من إصدار PHP
```bash
# تحقق من إصدار PHP
php -v

# Laravel يحتاج PHP 8.2+
# إذا كان أقل، حدّث PHP
```

## 🚀 الحل السريع (جرب هذا أولاً):

```bash
# على السيرفر، نفذ هذا السكريبت بالترتيب:

cd /path/to/your/project

# 1. مسح الكاش
php artisan optimize:clear

# 2. إصلاح الأذونات
chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache

# 3. إعادة بناء autoloader
composer dump-autoload

# 4. التحقق من APP_KEY
php artisan key:generate

# 5. إعادة تخزين الكاش
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. إعادة تشغيل الخدمة
sudo systemctl restart apache2
# أو
sudo systemctl restart nginx php8.2-fpm
```

## 📋 سكريبت تشخيصي شامل:

```bash
#!/bin/bash

echo "=== Laravel Error Diagnostics ==="
echo ""

echo "1. Checking PHP version:"
php -v
echo ""

echo "2. Checking .env file:"
ls -la .env
echo ""

echo "3. Checking APP_KEY:"
grep "APP_KEY=" .env
echo ""

echo "4. Checking storage permissions:"
ls -la storage/
echo ""

echo "5. Checking bootstrap/cache permissions:"
ls -la bootstrap/cache/
echo ""

echo "6. Checking Laravel logs:"
if [ -f "storage/logs/laravel.log" ]; then
    echo "Last 20 lines of laravel.log:"
    tail -20 storage/logs/laravel.log
else
    echo "⚠️  laravel.log not found!"
fi
echo ""

echo "7. Checking web server error logs:"
if [ -f "/var/log/apache2/error.log" ]; then
    echo "Last 10 lines of Apache error.log:"
    sudo tail -10 /var/log/apache2/error.log
fi
if [ -f "/var/log/nginx/error.log" ]; then
    echo "Last 10 lines of Nginx error.log:"
    sudo tail -10 /var/log/nginx/error.log
fi
echo ""

echo "8. Checking composer autoload:"
if [ -d "vendor" ]; then
    echo "✓ vendor directory exists"
else
    echo "✗ vendor directory missing! Run: composer install"
fi
echo ""

echo "=== End of Diagnostics ==="
```

## ⚠️ ملاحظات مهمة:

1. **لا تترك APP_DEBUG=true في الإنتاج!**
2. **اقرأ سجل الأخطاء أولاً** قبل تجربة الحلول عشوائياً
3. **اعمل نسخة احتياطية** قبل أي تعديل
4. **استخدم SSH** للوصول للسيرفر وتنفيذ الأوامر

## 🆘 إذا استمرت المشكلة:

1. نفّذ سكريبت التشخيص أعلاه
2. اقرأ سجل الأخطاء بعناية
3. ابحث عن الخطأ المحدد في Google
4. تواصل مع دعم الاستضافة إذا لزم الأمر

---

**ملف:** SERVER_ERROR_500_FIX.md
**التاريخ:** أكتوبر 15، 2025
EOF
