# دليل الإعداد السريع - Telegram Bot

## ⚡ خطوات سريعة (5 دقائق)

### 1. أنشئ Bot على Telegram

1. افتح Telegram وابحث عن: **@BotFather**
2. أرسل: `/newbot`
3. اختر اسم: `فكرة استور Bot`
4. اختر username: `fekra_store_bot` (يجب أن ينتهي بـ bot)
5. **انسخ Token** الذي سيرسله

### 2. احصل على Chat ID

**الطريقة الأسهل:**
1. ابحث عن: **@userinfobot**
2. اضغط `/start`
3. **انسخ Chat ID** الذي سيرسله

### 3. أضف للـ .env

```bash
TELEGRAM_BOT_TOKEN=123456789:ABCdefGHIjklMNOpqrsTUVwxyz
TELEGRAM_RECIPIENTS=123456789
```

لإضافة عدة مستلمين:
```bash
TELEGRAM_RECIPIENTS=123456789,987654321,555666777
```

### 4. شغّل Queue Worker

```bash
php artisan queue:work --queue=high,default
```

### 5. اختبر!

قم بإنشاء طلب من الموقع وستصلك رسالة فوراً على Telegram! 🎉

---

## 📝 ملاحظات مهمة

### للإرسال لمجموعة:

1. أنشئ مجموعة في Telegram
2. أضف Bot للمجموعة
3. اجعل Bot **Admin**
4. احصل على Chat ID من:
   ```
   https://api.telegram.org/bot<YOUR_TOKEN>/getUpdates
   ```
5. ابحث عن `"chat":{"id":` واحصل على الرقم (سيبدأ بـ `-`)
6. استخدمه في `.env`:
   ```bash
   TELEGRAM_RECIPIENTS=-987654321
   ```

### اختبار Bot Token:

```bash
curl "https://api.telegram.org/bot<YOUR_TOKEN>/getMe"
```

إذا كان صحيحاً، ستحصل على معلومات Bot.

### اختبار إرسال رسالة:

```bash
curl -X POST "https://api.telegram.org/bot<YOUR_TOKEN>/sendMessage" \
     -d "chat_id=<YOUR_CHAT_ID>" \
     -d "text=اختبار من فكرة استور 🎉"
```

---

## 🔍 استكشاف الأخطاء

### لا تصل الرسائل؟

```bash
# 1. تحقق من Queue Worker
ps aux | grep queue:work

# 2. راجع الـ Logs
tail -f storage/logs/laravel.log | grep -i telegram

# 3. تحقق من .env
cat .env | grep TELEGRAM

# 4. أعد تشغيل Queue
pkill -f queue:work
php artisan queue:work --queue=high,default --tries=3 &
```

---

## ✅ جاهز!

الآن عند كل طلب جديد، ستصلك رسالة Telegram فورية مع:
- رقم الطلب
- إجمالي المبلغ  
- اسم العميل
- زر للذهاب لصفحة الطلب في لوحة التحكم

**راجع التوثيق الكامل في:** `TELEGRAM_NOTIFICATIONS_README.md`

---

**آخر تحديث:** 21 أكتوبر 2025
