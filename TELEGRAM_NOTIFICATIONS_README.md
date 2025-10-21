# Telegram Order Notifications - دليل الإعداد والاستخدام

نظام إشعارات Telegram بسيط وفعال لإرسال إشعارات فورية عند إنشاء طلبات جديدة.

## 🚀 لماذا Telegram؟

- ✅ **أسهل في الإعداد** - لا حاجة لـ Business Account أو موافقات
- ✅ **مجاني تماماً** - بدون قيود على عدد الرسائل
- ✅ **سريع وموثوق** - تسليم فوري بدون تأخير
- ✅ **API بسيط** - سهل الاستخدام والصيانة
- ✅ **إشعارات فورية** - تصل للهاتف مباشرة

---

## 📋 الإعداد السريع

### 1. إنشاء Bot في Telegram

#### الخطوات:

1. **افتح Telegram** على هاتفك أو الكمبيوتر
2. **ابحث عن** `@BotFather`
3. **ابدأ محادثة** واضغط `/start`
4. **أنشئ Bot جديد:**
   - اكتب: `/newbot`
   - أدخل اسم Bot (مثال: `فكرة استور Bot`)
   - أدخل username للـ Bot (يجب أن ينتهي بـ `bot`، مثال: `fekra_store_bot`)
5. **انسخ الـ Token** الذي سيرسله BotFather
   - سيكون بهذا الشكل: `123456789:ABCdefGHIjklMNOpqrsTUVwxyz`

### 2. الحصول على Chat ID

#### طريقة 1: باستخدام Bot خاص

1. **ابحث عن** `@userinfobot` في Telegram
2. **ابدأ محادثة** واضغط `/start`
3. **سيرسل لك Chat ID** الخاص بك
4. **انسخه** (سيكون رقم مثل: `123456789`)

#### طريقة 2: من المجموعة (للإرسال لمجموعة)

1. **أنشئ مجموعة** جديدة أو استخدم موجودة
2. **أضف Bot الخاص بك** للمجموعة
3. **اجعل Bot Admin** في المجموعة
4. **افتح المتصفح** واذهب إلى:
   ```
   https://api.telegram.org/bot<YOUR_BOT_TOKEN>/getUpdates
   ```
5. **ابحث عن** `"chat":{"id":` وستجد Chat ID للمجموعة
6. **انسخ الرقم** (سيبدأ بـ `-` للمجموعات، مثل: `-987654321`)

### 3. تكوين المتغيرات

افتح `.env` وأضف:

```bash
# Telegram Bot Configuration
TELEGRAM_BOT_TOKEN=123456789:ABCdefGHIjklMNOpqrsTUVwxyz
TELEGRAM_RECIPIENTS=123456789,-987654321
```

**ملاحظات:**
- يمكنك إضافة عدة Chat IDs مفصولة بفواصل
- لإرسال لشخص: استخدم Chat ID الإيجابي
- لإرسال لمجموعة: استخدم Chat ID السالب (يبدأ بـ `-`)

---

## 📱 شكل الرسالة

عند إنشاء طلب جديد، ستصل رسالة بهذا الشكل:

```
🎉 طلب جديد في فكرة ستور! 🎉

رقم الطلب: 42
إجمالي المبلغ: 450 ج.م
اسم العميل: أحمد محمد

[زر: عرض الطلب] ← يفتح صفحة الطلب في لوحة التحكم
```

---

## 🧪 الاختبار

### 1. تشغيل Queue Worker

```bash
php artisan queue:work --queue=high,default
```

### 2. إنشاء طلب تجريبي

**من الموقع:**
- افتح المتجر
- اختر منتج
- اضغط "اطلب الآن"
- املأ البيانات واضغط "تأكيد"

**من Tinker:**
```bash
php artisan tinker

$product = \App\Models\Product::first();
\App\Models\Order::create([
    'product_id' => $product->id,
    'quantity' => 2,
    'customer_name' => 'أحمد محمد - اختبار Telegram',
    'phone' => '01234567890',
    'address' => 'القاهرة',
    'governorate' => 'القاهرة',
    'status' => 'pending'
]);
```

### 3. التحقق

- ✅ افتح Telegram
- ✅ يجب أن تصل رسالة فورية من Bot الخاص بك
- ✅ اضغط زر "عرض الطلب" للتأكد من الرابط

---

## 🔍 استكشاف الأخطاء

### المشكلة: الرسائل لا تصل

**الحلول:**

1. **تحقق من Bot Token:**
   ```bash
   # اختبر Token عبر Curl
   curl -X GET "https://api.telegram.org/bot<YOUR_TOKEN>/getMe"
   ```
   إذا كان Token صحيح، ستحصل على معلومات Bot

2. **تحقق من Chat ID:**
   ```bash
   # أرسل رسالة اختبار
   curl -X POST "https://api.telegram.org/bot<YOUR_TOKEN>/sendMessage" \
        -d "chat_id=<YOUR_CHAT_ID>" \
        -d "text=اختبار"
   ```

3. **تحقق من Queue Worker:**
   ```bash
   ps aux | grep "queue:work"
   ```

4. **راجع Logs:**
   ```bash
   tail -f storage/logs/laravel.log | grep -i telegram
   ```

### المشكلة: Bot لا يستطيع الإرسال للمجموعة

**الحل:**
- تأكد أن Bot عضو في المجموعة
- اجعل Bot **Admin** في المجموعة
- استخدم Chat ID السالب (يبدأ بـ `-`)

### المشكلة: الرابط لا يعمل

**الحل:**
- تأكد من أن `APP_URL` في `.env` صحيح
- تأكد من تشغيل السيرفر

---

## 📊 المراقبة

### عرض Logs:

```bash
# جميع logs Telegram
tail -f storage/logs/laravel.log | grep Telegram

# logs النجاح فقط
tail -f storage/logs/laravel.log | grep "Telegram notification sent successfully"

# logs الأخطاء فقط
tail -f storage/logs/laravel.log | grep "Telegram notification failed"
```

### الإحصائيات:

```bash
php artisan tinker

# عدد الطلبات اليوم
\App\Models\Order::whereDate('created_at', today())->count();

# آخر 5 طلبات
\App\Models\Order::latest()->take(5)->get(['id', 'customer_name', 'created_at']);
```

---

## 🎯 المميزات

| الميزة | الوصف |
|--------|-------|
| **Queued** | لا يؤثر على سرعة الموقع |
| **Multi-recipient** | إرسال لعدة أشخاص/مجموعات |
| **Retry Logic** | 3 محاولات تلقائية عند الفشل |
| **Rich Formatting** | Markdown و Bold و Italic |
| **Action Buttons** | أزرار تفاعلية في الرسائل |
| **Logging** | سجل كامل لكل عملية |

---

## 🔧 التخصيص

### تغيير شكل الرسالة

عدّل ملف: `app/Notifications/NewOrderTelegram.php`

```php
$message = "🎉 *طلب جديد في فكرة ستور!* 🎉\n\n";
$message .= "*رقم الطلب:* {$orderId}\n";
$message .= "*إجمالي المبلغ:* {$totalPrice}\n";
$message .= "*اسم العميل:* {$customerName}";
```

### إضافة معلومات إضافية

```php
$message .= "\n*رقم الهاتف:* {$this->order->phone}\n";
$message .= "*العنوان:* {$this->order->address}\n";
$message .= "*المحافظة:* {$this->order->governorate}";
```

### تغيير النص على الزر

```php
->button('🔍 عرض التفاصيل', $url)
// أو
->button('📦 معالجة الطلب', $url)
```

---

## 🆚 المقارنة مع WhatsApp

| الميزة | Telegram | WhatsApp |
|--------|----------|----------|
| **الإعداد** | 5 دقائق ✅ | 30+ دقيقة ❌ |
| **التكلفة** | مجاني ✅ | مجاني بحدود ⚠️ |
| **الموافقات** | لا يوجد ✅ | Templates تحتاج موافقة ❌ |
| **عدد الرسائل** | غير محدود ✅ | 250-1000/يوم ⚠️ |
| **سهولة API** | بسيط جداً ✅ | معقد ❌ |
| **الأزرار التفاعلية** | مدعوم ✅ | محدود ⚠️ |
| **Business Account** | غير مطلوب ✅ | مطلوب ❌ |

---

## 📚 الملفات المتأثرة

### تم إنشاؤها:
- ✅ `app/Notifications/NewOrderTelegram.php`
- ✅ `app/Listeners/SendOrderTelegramNotification.php`

### تم تحديثها:
- ✅ `config/services.php` - إعدادات Telegram
- ✅ `.env` - متغيرات البيئة
- ✅ `.env.example` - مثال للمتغيرات
- ✅ `app/Providers/AppServiceProvider.php` - تسجيل Listener

### تم حذفها:
- ❌ `app/Services/WhatsAppService.php`
- ❌ `app/Listeners/SendOrderPlacedWhatsApp.php`

---

## 🔗 روابط مفيدة

- **Telegram Bot API Docs:** https://core.telegram.org/bots/api
- **BotFather:** https://t.me/BotFather
- **Package Docs:** https://github.com/laravel-notification-channels/telegram
- **Laravel Notifications:** https://laravel.com/docs/notifications

---

## ✨ الخلاصة

نظام Telegram أبسط بكثير من WhatsApp ويوفر نفس الوظائف الأساسية:
- ✅ إشعارات فورية
- ✅ أزرار تفاعلية
- ✅ إرسال لعدة مستلمين
- ✅ بدون تكاليف أو قيود

**جاهز للاستخدام! قم بإعداد Bot الآن وابدأ في استقبال الإشعارات 🚀**

---

**تاريخ الإنشاء:** 21 أكتوبر 2025  
**الإصدار:** 2.0.0  
**الحالة:** ✅ جاهز للإنتاج
