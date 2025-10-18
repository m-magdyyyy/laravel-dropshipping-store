# 🚀 إصلاح سريع - مشكلة رفع الملفات

## المشكلة
```
Error during upload
POST Content-Length exceeds the limit
```

## الحل السريع (5 دقائق)

### 1️⃣ شغّل السكريبت التلقائي
```bash
./scripts/fix_upload_limits.sh
```

### 2️⃣ عدّل php.ini
```bash
sudo nano /etc/php/8.3/cli/php.ini
```

**ابحث وعدّل:**
```ini
upload_max_filesize = 50M
post_max_size = 64M
max_execution_time = 120
```

**احفظ:** `Ctrl+O` ثم `Enter` ثم `Ctrl+X`

### 3️⃣ أعد تشغيل السيرفر
```bash
# في terminal السيرفر: اضغط Ctrl+C
php artisan serve
```

### 4️⃣ تحقق من النجاح
```bash
php -i | grep -E "(upload_max_filesize|post_max_size)"
```

**يجب أن ترى:**
```
post_max_size => 64M => 64M
upload_max_filesize => 50M => 50M
```

## ✅ انتهى!

الآن يمكنك رفع صور حتى 50 ميجابايت في Filament.

---

**للتفاصيل الكاملة:** راجع `PHP_UPLOAD_FIX_GUIDE.md`
