# 🚀 دليل إعداد إشعارات Telegram على السيرفر

## ✅ التحسينات المطبقة

تم إصلاح 3 مشاكل رئيسية:

1. ⏱️ **تأخير الإرسال** - الآن الإشعارات تُرسل فوراً
2. 🔁 **الإرسال المزدوج** - تم إضافة حماية باستخدام Cache
3. 🚫 **الاعتماد على Queue Worker** - لم تعد بحاجة لـ queue worker

## 📋 إعداد السيرفر (خطوة بخطوة)

### 1. رفع الكود على السيرفر

```bash
# على السيرفر
cd /path/to/your/project
git pull origin main
```

### 2. تحديث الاعتمادات

```bash
composer install --optimize-autoloader --no-dev
```

### 3. إعداد ملف .env

تأكد من وجود هذه المتغيرات في `.env`:

```env
# Telegram Bot Configuration
TELEGRAM_BOT_TOKEN=8327189763:AAFiy9dWtF-dnEYc-1GL6gXV-rtsxtfZaWU
TELEGRAM_RECIPIENTS=2079237980

# Cache (مهم لمنع الإرسال المزدوج)
CACHE_STORE=database  # أو redis إذا كان متاحاً
```

### 4. تحديث الـ Cache

```bash
php artisan config:cache
php artisan cache:clear
php artisan route:cache
```

### 5. إعداد الصلاحيات

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 6. اختبار الإشعارات

```bash
# اختبار بسيط
php artisan tinker

# داخل tinker:
$order = App\Models\Order::latest()->first();
event(new App\Events\OrderPlaced($order));
```

## 🔧 إعدادات إضافية (اختيارية)

### إذا كنت تستخدم Nginx

أضف هذا في ملف الإعدادات:

```nginx
# /etc/nginx/sites-available/your-site

location / {
    try_files $uri $uri/ /index.php?$query_string;
}

# زيادة timeout للطلبات الطويلة
proxy_read_timeout 300;
proxy_connect_timeout 300;
proxy_send_timeout 300;
```

ثم أعد تشغيل Nginx:

```bash
sudo nginx -t
sudo systemctl restart nginx
```

### إذا كنت تستخدم Apache

أضف في `.htaccess`:

```apache
# زيادة timeout
php_value max_execution_time 300
php_value max_input_time 300
```

## 📊 المراقبة والتحقق

### 1. مراقبة الـ Logs

```bash
# مشاهدة logs مباشرة
tail -f storage/logs/laravel.log | grep Telegram

# أو
tail -f storage/logs/laravel.log | grep "order_id"
```

### 2. التحقق من الإشعارات المرسلة

```bash
# عرض آخر 50 سطر من logs
tail -50 storage/logs/laravel.log | grep "Telegram notification"
```

### 3. فحص الـ Cache

```bash
php artisan tinker

# داخل tinker:
cache()->get('telegram_notification_sent_14');  // استبدل 14 برقم الطلب
```

## 🐛 استكشاف الأخطاء الشائعة

### المشكلة: Telegram notifications not sent

**الحل:**

```bash
# 1. تحقق من الإعدادات
php artisan config:clear
php artisan config:cache

# 2. تحقق من logs
tail -30 storage/logs/laravel.log

# 3. اختبار الاتصال بـ Telegram API
curl https://api.telegram.org/bot8327189763:AAFiy9dWtF-dnEYc-1GL6gXV-rtsxtfZaWU/getMe
```

### المشكلة: SSL/TLS errors

قد تحتاج لتحديث CA certificates:

```bash
sudo apt-get update
sudo apt-get install ca-certificates
sudo update-ca-certificates
```

### المشكلة: Permission denied

```bash
# إصلاح الصلاحيات
sudo chown -R www-data:www-data storage
sudo chmod -R 775 storage
```

### المشكلة: Cache not working

تأكد من أن جدول cache موجود:

```bash
# إنشاء جدول cache
php artisan cache:table
php artisan migrate

# أو استخدام file cache
# في .env:
CACHE_STORE=file
```

## 🔐 الأمان

### حماية Bot Token

لا تُشارك الـ Bot Token أبداً!

```bash
# تأكد من أن .env غير متاح للعامة
chmod 600 .env

# تأكد من وجود .env في .gitignore
echo ".env" >> .gitignore
```

### Webhook Security (اختياري)

إذا أردت استخدام webhooks بدلاً من polling:

```bash
# إعداد webhook
curl -X POST "https://api.telegram.org/bot8327189763:AAFiy9dWtF-dnEYc-1GL6gXV-rtsxtfZaWU/setWebhook?url=https://yourdomain.com/telegram/webhook"
```

## 📱 اختبار نهائي

### 1. اختبار إنشاء طلب جديد

```bash
# على السيرفر
php artisan tinker

# داخل tinker:
$product = App\Models\Product::first();
$order = App\Models\Order::create([
    'customer_name' => 'Test User',
    'phone' => '01012345678',
    'address' => 'Test Address',
    'governorate' => 'Cairo',
    'product_id' => $product->id,
    'quantity' => 1,
    'status' => 'pending'
]);

# يجب أن ترى إشعار في Telegram خلال ثوانٍ!
```

### 2. التحقق من Logs

```bash
tail -20 storage/logs/laravel.log | grep "Telegram notification"
```

يجب أن تشاهد:
```
✅ Sending Telegram notification for order
✅ Telegram notification sent successfully
✅ Telegram notifications completed
✅ Notification marked as sent
```

## 🎯 النتيجة المتوقعة

- ⚡ **إرسال فوري**: الإشعار يصل خلال 1-3 ثوانٍ
- 🔒 **بدون تكرار**: كل طلب يُرسل مرة واحدة فقط
- 🚀 **لا حاجة لـ Queue Worker**: يعمل مباشرة بدون إعدادات إضافية
- 📱 **مستقر على السيرفر**: لا يعتمد على خدمات خارجية

## 💡 نصائح إضافية

1. **استخدم Redis للـ Cache** (أسرع من database):
```bash
sudo apt-get install redis-server
composer require predis/predis

# في .env:
CACHE_STORE=redis
```

2. **قم بإعداد Log Rotation**:
```bash
# /etc/logrotate.d/laravel
/path/to/project/storage/logs/*.log {
    daily
    rotate 14
    compress
    delaycompress
    notifempty
    missingok
}
```

3. **استخدم Supervisor للتطبيقات الكبيرة** (إذا أردت العودة لـ Queue):
```bash
sudo apt-get install supervisor
```

---

## ✅ Checklist للنشر

- [ ] رفع الكود على السيرفر
- [ ] تحديث `.env` بـ Bot Token وChat IDs
- [ ] تشغيل `composer install`
- [ ] تشغيل `php artisan config:cache`
- [ ] إصلاح صلاحيات `storage/`
- [ ] اختبار إرسال طلب جديد
- [ ] التحقق من وصول الإشعار في Telegram
- [ ] مراقبة الـ logs للتأكد من عدم وجود أخطاء

---

تم بنجاح! 🎉
