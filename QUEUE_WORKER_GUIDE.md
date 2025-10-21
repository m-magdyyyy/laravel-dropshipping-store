# 🔔 دليل تشغيل Queue Worker لإشعارات Telegram

## المشكلة التي تم حلها
كانت إشعارات Telegram لا تُرسل لأن Queue Worker لم يكن يعمل.

## ✅ الحل الفوري
```bash
cd /home/mohmed/Downloads/aravel-dropshipping-store/laravel-dropshipping-store
php artisan queue:work --queue=high,default --tries=3 --timeout=90
```

## 🚀 حلول دائمة

### الطريقة 1: استخدام Supervisor (موصى بها للإنتاج)

1. **تثبيت Supervisor:**
```bash
sudo apt-get install supervisor
```

2. **نسخ ملف الإعدادات:**
```bash
sudo cp laravel-worker.conf /etc/supervisor/conf.d/
```

3. **تحديث Supervisor:**
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

4. **التحقق من الحالة:**
```bash
sudo supervisorctl status
```

### الطريقة 2: استخدام Cron (بسيطة)

1. **فتح crontab:**
```bash
crontab -e
```

2. **إضافة هذا السطر:**
```cron
* * * * * cd /home/mohmed/Downloads/aravel-dropshipping-store/laravel-dropshipping-store && php artisan schedule:run >> /dev/null 2>&1
@reboot /home/mohmed/Downloads/aravel-dropshipping-store/laravel-dropshipping-store/start-queue-worker.sh
```

### الطريقة 3: تشغيل يدوي عند بدء التشغيل

أضف هذا إلى `~/.bashrc`:
```bash
# Auto-start Laravel Queue Worker
/home/mohmed/Downloads/aravel-dropshipping-store/laravel-dropshipping-store/start-queue-worker.sh
```

## 🔍 فحص الإشعارات

### 1. التحقق من Queue Worker:
```bash
ps aux | grep "queue:work"
```

### 2. مشاهدة Logs:
```bash
tail -f storage/logs/queue.log
tail -f storage/logs/laravel.log | grep Telegram
```

### 3. فحص Jobs الفاشلة:
```bash
php artisan queue:failed
```

### 4. إعادة محاولة Jobs الفاشلة:
```bash
php artisan queue:retry all
```

## 📱 اختبار الإشعارات

1. افتح المتصفح وقدم طلب جديد
2. راقب الـ logs:
```bash
tail -f storage/logs/laravel.log | grep Telegram
```

يجب أن تشاهد:
```
✅ Sending Telegram notification for order
✅ Telegram notification sent successfully
✅ Telegram notifications completed
```

## ⚙️ إعدادات Telegram الحالية

في `.env`:
```env
TELEGRAM_BOT_TOKEN=8327189763:AAFiy9dWtF-dnEYc-1GL6gXV-rtsxtfZaWU
TELEGRAM_RECIPIENTS=2079237980
```

## 🐛 استكشاف الأخطاء

### المشكلة: لا تصل إشعارات
```bash
# 1. تحقق من Queue Worker
ps aux | grep queue:work

# 2. إذا لم يكن يعمل، شغله:
php artisan queue:work --queue=high,default

# 3. تحقق من الـ logs
tail -30 storage/logs/laravel.log | grep Telegram
```

### المشكلة: Jobs عالقة
```bash
# إعادة تشغيل Queue Worker
pkill -f "queue:work"
php artisan queue:restart
php artisan queue:work --queue=high,default
```

## 📊 المراقبة

### Dashboard Laravel Horizon (اختياري)
```bash
composer require laravel/horizon
php artisan horizon:install
php artisan horizon
```

ثم افتح: `http://127.0.0.1:8000/horizon`

---

## 💡 ملاحظات مهمة

1. ⚠️ **Queue Worker يجب أن يعمل دائماً** لإرسال الإشعارات
2. 🔄 عند تغيير الكود، أعد تشغيل Worker: `php artisan queue:restart`
3. 📝 راقب الـ logs بانتظام للتأكد من عدم وجود أخطاء
4. 🎯 استخدم Supervisor في بيئة الإنتاج لضمان إعادة التشغيل التلقائي

---

تم الإصلاح بنجاح! ✅
