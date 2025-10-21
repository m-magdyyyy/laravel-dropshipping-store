# نظام إشعارات WhatsApp - فكرة استور 🛍️

نظام متكامل لإرسال إشعارات WhatsApp تلقائية عند إنشاء الطلبات الجديدة.

## 📱 ماذا يفعل هذا النظام؟

عند إنشاء طلب جديد في المتجر، يُرسل تلقائياً رسالة WhatsApp إلى أرقامك تحتوي على:
- ✅ رقم الطلب
- ✅ اسم العميل  
- ✅ المبلغ الإجمالي

**مثال الرسالة:**
```
تم تسجيل طلب جديد في فكرة استور 🛍️

رقم الطلب: 42
اسم العميل: أحمد محمد
المبلغ الإجمالي: 450 ج.م

شكراً لثقتك بنا ✨
```

## 🚀 البدء السريع

### 1. احصل على Access Token

اتبع: **[WHATSAPP_TOKEN_GUIDE.md](WHATSAPP_TOKEN_GUIDE.md)**

### 2. أضف البيانات إلى .env

```bash
WHATSAPP_TOKEN=your_access_token_here
WHATSAPP_PHONE_NUMBER_ID=your_phone_id_here
WHATSAPP_RECIPIENTS=201091450342,201069430567
```

### 3. أنشئ Template في Meta

اتبع: **[WHATSAPP_QUICK_SETUP.md](WHATSAPP_QUICK_SETUP.md)**

### 4. شغّل Queue Worker

```bash
php artisan queue:work --queue=high,default
```

### 5. اختبر!

```bash
php artisan tinker

$product = \App\Models\Product::first();
\App\Models\Order::create([
    'product_id' => $product->id,
    'quantity' => 2,
    'customer_name' => 'أحمد محمد',
    'phone' => '01091450342',
    'address' => 'القاهرة',
    'governorate' => 'القاهرة',
    'status' => 'pending'
]);
```

تحقق من WhatsApp! 📲

## 📚 الوثائق الكاملة

1. **[WHATSAPP_TOKEN_GUIDE.md](WHATSAPP_TOKEN_GUIDE.md)** - كيفية الحصول على Access Token
2. **[WHATSAPP_QUICK_SETUP.md](WHATSAPP_QUICK_SETUP.md)** - الإعداد السريع
3. **[WHATSAPP_NOTIFICATIONS_README.md](WHATSAPP_NOTIFICATIONS_README.md)** - التوثيق الكامل
4. **[WHATSAPP_CHANGES_SUMMARY.md](WHATSAPP_CHANGES_SUMMARY.md)** - ملخص التغييرات

## ⚡ المميزات

- ✅ **Event-driven** - معمارية احترافية
- ✅ **Queued** - لا يؤثر على سرعة الموقع
- ✅ **Retry Logic** - 3 محاولات تلقائية
- ✅ **Error Handling** - لا يعطل إنشاء الطلبات
- ✅ **Multi-recipient** - إرسال لعدة أرقام
- ✅ **Logging** - سجل كامل للعمليات
- ✅ **Tested** - اختبارات شاملة
- ✅ **Documented** - توثيق كامل بالعربية

## 🛠️ التقنيات المستخدمة

- Laravel 12 Events & Listeners
- WhatsApp Cloud API (Meta)
- Laravel Queue System
- Laravel HTTP Client
- PHPUnit Testing

## 📁 الملفات الرئيسية

```
app/
├── Services/WhatsAppService.php           # خدمة WhatsApp
├── Events/OrderPlaced.php                 # Event الطلب
├── Listeners/SendOrderPlacedWhatsApp.php  # إرسال الإشعار
├── Models/Order.php                       # موديل الطلب (محدث)
└── Providers/AppServiceProvider.php       # تسجيل Events

config/
└── services.php                           # إعدادات WhatsApp

tests/
└── Feature/WhatsAppNotificationTest.php   # الاختبارات
```

## 🔧 متطلبات الإعداد

### المتغيرات المطلوبة (.env):

| المتغير | الوصف | مثال |
|---------|-------|------|
| `WHATSAPP_TOKEN` | Access Token من Meta | `EAABsbCS1...` |
| `WHATSAPP_PHONE_NUMBER_ID` | معرف رقم الهاتف | `123456789012345` |
| `WHATSAPP_TEMPLATE` | اسم الـ Template | `order_created` |
| `WHATSAPP_LANG` | اللغة | `ar` |
| `WHATSAPP_GRAPH_VERSION` | إصدار API | `v21.0` |
| `WHATSAPP_RECIPIENTS` | الأرقام المستقبلة | `201091450342,201069430567` |

### الـ Template المطلوب:

**الاسم:** `order_created`  
**الفئة:** UTILITY  
**اللغة:** Arabic

**المحتوى:**
```
تم تسجيل طلب جديد في فكرة استور 🛍️

رقم الطلب: {{1}}
اسم العميل: {{2}}
المبلغ الإجمالي: {{3}}

شكراً لثقتك بنا ✨
```

## 🧪 الاختبارات

```bash
# تشغيل جميع الاختبارات
php artisan test

# اختبار WhatsApp فقط
php artisan test --filter=WhatsAppNotificationTest
```

## 📊 المراقبة

```bash
# مراقبة Logs
tail -f storage/logs/laravel.log

# حالة Queue
php artisan queue:failed
php artisan queue:retry all
```

## 🆘 استكشاف الأخطاء

### الرسائل لا تُرسل؟

1. تحقق من تشغيل Queue Worker:
   ```bash
   ps aux | grep "queue:work"
   ```

2. تحقق من الـ Logs:
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. تحقق من Failed Jobs:
   ```bash
   php artisan queue:failed
   ```

### خطأ "Template not found"?

- تأكد من موافقة Meta على الـ Template
- انتظر بضع دقائق بعد الموافقة
- تحقق من صحة اسم Template في `.env`

### خطأ "Invalid phone number"?

- الأرقام يجب أن تكون E.164 **بدون "+"**
- صحيح: `201091450342`
- خطأ: `01091450342` or `+201091450342`

## 🔒 الأمان

⚠️ **مهم:**
- لا تشارك Access Token أبداً
- احتفظ به في `.env` فقط
- لا تنشره في Git
- استخدم System User Token للإنتاج

## 📈 الخطوات التالية

بعد الإعداد الأساسي، يمكنك:

- [ ] إضافة إشعارات تحديث حالة الطلب
- [ ] إرسال تأكيد الاستلام
- [ ] إشعارات العروض والتخفيضات
- [ ] Dashboard لإحصائيات الرسائل

## 📞 الدعم والمساعدة

### روابط مفيدة:

- **WhatsApp Docs:** https://developers.facebook.com/docs/whatsapp
- **Graph API:** https://developers.facebook.com/docs/graph-api
- **Business Manager:** https://business.facebook.com/
- **Laravel Queue:** https://laravel.com/docs/queues

### الملفات المساعدة:

- [كيفية الحصول على Token](WHATSAPP_TOKEN_GUIDE.md)
- [الإعداد السريع](WHATSAPP_QUICK_SETUP.md)
- [التوثيق الكامل](WHATSAPP_NOTIFICATIONS_README.md)
- [ملخص التغييرات](WHATSAPP_CHANGES_SUMMARY.md)

## ✨ الخلاصة

نظام متكامل وجاهز للإنتاج يُرسل إشعارات WhatsApp احترافية عند كل طلب جديد! 🚀

---

**تم التطوير بـ ❤️ لفريق فكرة استور**  
**التاريخ:** أكتوبر 2025  
**الإصدار:** 1.0.0
