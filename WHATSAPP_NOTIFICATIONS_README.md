# WhatsApp Order Notifications - إشعارات الطلبات عبر WhatsApp

نظام متكامل لإرسال إشعارات WhatsApp تلقائية عند إنشاء طلب جديد باستخدام WhatsApp Cloud API (Meta).

## 📋 المتطلبات

- Laravel 12+
- PHP 8.4+
- حساب WhatsApp Business مع Meta Business Manager
- Queue connection مضبوط (database بشكل افتراضي)

## 🚀 الإعداد السريع

### 1. إعداد WhatsApp Cloud API

#### أ. إنشاء حساب Meta Business و WhatsApp Business App

1. انتقل إلى [Meta for Developers](https://developers.facebook.com/)
2. أنشئ تطبيق جديد من نوع "Business"
3. أضف منتج "WhatsApp" للتطبيق
4. في لوحة WhatsApp، ستجد:
   - **Phone Number ID** - معرف رقم الهاتف
   - **Access Token** - مفتاح الوصول المؤقت

#### ب. الحصول على Access Token دائم

1. انتقل إلى [Business Settings](https://business.facebook.com/settings)
2. اختر "System Users" من القائمة
3. أنشئ System User جديد أو استخدم موجود
4. اختر "Generate Token" واختر التطبيق الخاص بك
5. حدد الصلاحيات: `whatsapp_business_messaging`, `whatsapp_business_management`
6. انسخ الـ Access Token (لن تتمكن من رؤيته مرة أخرى)

### 2. إنشاء WhatsApp Template

يجب إنشاء Template واعتماده من Meta قبل إرسال الرسائل.

#### خطوات إنشاء Template:

1. افتح [WhatsApp Manager](https://business.facebook.com/wa/manage/message-templates/)
2. اضغط "Create Template"
3. املأ البيانات:
   - **Template Name**: `order_created`
   - **Category**: `UTILITY` (للإشعارات)
   - **Language**: Arabic (ar)
4. أضف محتوى الرسالة:

```
تم تسجيل طلب جديد في فكرة استور 🛍️

رقم الطلب: {{1}}
اسم العميل: {{2}}
المبلغ الإجمالي: {{3}}

شكراً لثقتك بنا ✨
```

5. اضغط "Submit"
6. انتظر الموافقة (عادة خلال دقائق إلى 24 ساعة)

#### ملاحظات مهمة:
- يجب أن يكون اسم الـ Template بالإنجليزية وبأحرف صغيرة فقط مع underscore
- المعاملات {{1}}, {{2}}, {{3}} يتم استبدالها ببيانات الطلب
- لا يمكن تعديل Template بعد الموافقة، يجب إنشاء واحد جديد

### 3. إضافة أرقام الاختبار

قبل نشر التطبيق، يمكنك إرسال رسائل فقط لأرقام مُسجلة:

1. في لوحة WhatsApp، اذهب إلى "Getting Started"
2. أضف رقم الاختبار في "To" field
3. أرسل رمز التحقق للرقم
4. أدخل الرمز لتأكيد الرقم

**الأرقام المضافة حالياً:**
- 01091450342
- 01069430567

### 4. تكوين البيئة (.env)

أضف المتغيرات التالية لملف `.env`:

```bash
# WhatsApp Cloud API Configuration
WHATSAPP_TOKEN=your_permanent_access_token_here
WHATSAPP_PHONE_NUMBER_ID=your_phone_number_id_here
WHATSAPP_TEMPLATE=order_created
WHATSAPP_LANG=ar
WHATSAPP_GRAPH_VERSION=v21.0
WHATSAPP_RECIPIENTS=201091450342,201069430567
```

**ملاحظات:**
- الأرقام يجب أن تكون بصيغة E.164 بدون "+" (مثال: 201091450342 لمصر)
- افصل الأرقام بفاصلة بدون مسافات
- `WHATSAPP_TOKEN`: Access Token من Meta
- `WHATSAPP_PHONE_NUMBER_ID`: معرف الرقم من لوحة WhatsApp

### 5. تشغيل الـ Queue Worker

نظام الإشعارات يستخدم Queues لإرسال الرسائل بشكل غير متزامن:

```bash
# تشغيل Queue Worker
php artisan queue:work --queue=high,default

# أو استخدم Supervisor في Production
php artisan queue:work --queue=high,default --tries=3 --timeout=60
```

#### إعداد Supervisor (Production)

قم بإنشاء ملف `/etc/supervisor/conf.d/laravel-queue.conf`:

```ini
[program:laravel-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/your/project/artisan queue:work --queue=high,default --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/your/project/storage/logs/queue-worker.log
stopwaitsecs=3600
```

بعدها:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-queue-worker:*
```

## 🏗️ البنية المعمارية

### الملفات المنشأة:

```
app/
├── Events/
│   └── OrderPlaced.php                    # Event يُطلق عند إنشاء طلب
├── Listeners/
│   └── SendOrderPlacedWhatsApp.php        # Listener لإرسال WhatsApp
├── Models/
│   └── Order.php                          # تم تحديثه بـ boot hook
├── Services/
│   └── WhatsAppService.php                # خدمة WhatsApp API
└── Providers/
    └── AppServiceProvider.php             # تسجيل Event Listener

config/
└── services.php                           # إعدادات WhatsApp

tests/
└── Feature/
    └── WhatsAppNotificationTest.php       # اختبارات التكامل
```

### تدفق البيانات (Flow):

```
1. إنشاء طلب جديد (Order::create())
   ↓
2. Model Boot Hook يطلق OrderPlaced Event
   ↓
3. SendOrderPlacedWhatsApp Listener يستقبل Event
   ↓
4. يُضاف للـ Queue (high priority)
   ↓
5. Queue Worker ينفذ الـ Listener
   ↓
6. WhatsAppService يرسل Template Message لكل رقم
   ↓
7. Graph API يستقبل الطلب ويرسل الرسالة
   ↓
8. تسجيل النتيجة في Logs
```

## 🧪 الاختبار

### تشغيل الاختبارات:

```bash
# تشغيل جميع الاختبارات
php artisan test

# تشغيل اختبار WhatsApp فقط
php artisan test --filter=WhatsAppNotificationTest

# مع تفاصيل
php artisan test --filter=WhatsAppNotificationTest --verbose
```

### الاختبارات المتاحة:

1. **test_whatsapp_notification_sent_on_order_creation**
   - يتحقق من إرسال الإشعار عند إنشاء طلب
   - يتحقق من صحة البيانات المُرسلة (template, params)

2. **test_whatsapp_service_handles_errors_gracefully**
   - يتحقق من معالجة الأخطاء بشكل صحيح
   - لا تمنع الأخطاء في WhatsApp إنشاء الطلب

3. **test_whatsapp_notification_contains_correct_order_data**
   - يتحقق من صحة معاملات الـ Template
   - رقم الطلب، اسم العميل، المبلغ الإجمالي

### اختبار يدوي:

```bash
# 1. تأكد من تشغيل Queue Worker
php artisan queue:work --queue=high,default

# 2. في نافذة أخرى، أنشئ طلب تجريبي
php artisan tinker

# 3. في Tinker:
$product = \App\Models\Product::first();
$order = \App\Models\Order::create([
    'product_id' => $product->id,
    'quantity' => 2,
    'customer_name' => 'أحمد محمد',
    'phone' => '01091450342',
    'address' => 'القاهرة',
    'governorate' => 'القاهرة',
    'status' => 'pending'
]);

# 4. تحقق من الـ Logs
tail -f storage/logs/laravel.log
```

## 📊 المراقبة والـ Logs

### مستويات Logging:

```php
// نجاح الإرسال
Log::info('WhatsApp message sent successfully', [
    'recipient' => '201091450342',
    'template' => 'order_created',
    'message_id' => 'wamid.xxx'
]);

// فشل الإرسال
Log::error('WhatsApp API error', [
    'status' => 400,
    'error_message' => 'Invalid parameter',
    'error_code' => 100
]);
```

### مراقبة الـ Queue:

```bash
# عرض Jobs المعلقة
php artisan queue:work --once

# عرض Failed Jobs
php artisan queue:failed

# إعادة محاولة Failed Job
php artisan queue:retry {job-id}

# إعادة محاولة جميع Failed Jobs
php artisan queue:retry all
```

## 🔧 استكشاف الأخطاء

### 1. الرسائل لا تُرسل

```bash
# تحقق من Queue Worker
ps aux | grep "queue:work"

# تحقق من الـ Logs
tail -f storage/logs/laravel.log

# تحقق من Failed Jobs
php artisan queue:failed
```

### 2. خطأ "Template not found"

- تأكد من موافقة Meta على الـ Template
- تأكد من صحة اسم Template في `.env`
- انتظر بضع دقائق بعد الموافقة

### 3. خطأ "Invalid phone number"

- الأرقام يجب أن تكون بصيغة E.164 بدون "+"
- مثال صحيح: `201091450342` (مصر)
- مثال خاطئ: `01091450342` أو `+201091450342`

### 4. خطأ "Recipient not in allowed list"

- في وضع Development، يجب إضافة الأرقام كـ Test Numbers
- اذهب لـ WhatsApp Manager → Add phone numbers

## 🔒 الأمان

### Best Practices:

1. **لا تشارك Access Token**
   - احتفظ به في `.env`
   - لا تنشره في Git
   - استخدم Environment Variables في Production

2. **استخدم System User Token**
   - لا تستخدم User Access Token
   - System User Token لا ينتهي

3. **قيود الإرسال**
   - Meta تحدد عدد الرسائل حسب tier
   - ابدأ بـ 250 رسالة/24 ساعة
   - تزيد تلقائياً مع الاستخدام الجيد

## 📈 التطوير المستقبلي

### إمكانيات إضافية:

- [ ] إشعارات تحديث حالة الطلب
- [ ] رسائل تأكيد الاستلام
- [ ] إشعارات العروض والتخفيضات
- [ ] دعم Media Templates (صور، ملفات)
- [ ] نظام تتبع معدل التسليم
- [ ] Dashboard لإحصائيات الرسائل

## 📞 الدعم

للمساعدة والاستفسارات:
- [WhatsApp Business API Docs](https://developers.facebook.com/docs/whatsapp)
- [Graph API Reference](https://developers.facebook.com/docs/graph-api)
- [Laravel Queue Documentation](https://laravel.com/docs/queues)

## 📝 الترخيص

هذا المشروع مرخص تحت [MIT License](LICENSE).

---

**تم التطوير بواسطة:** فريق فكرة استور  
**التاريخ:** أكتوبر 2025  
**الإصدار:** 1.0.0
