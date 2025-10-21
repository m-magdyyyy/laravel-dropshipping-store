# إعداد سريع - إشعارات WhatsApp

## الخطوات المطلوبة:

### 1. إضافة متغيرات البيئة

أضف إلى ملف `.env`:

```bash
WHATSAPP_TOKEN=your_access_token_here
WHATSAPP_PHONE_NUMBER_ID=your_phone_number_id_here
WHATSAPP_TEMPLATE=order_created
WHATSAPP_LANG=ar
WHATSAPP_GRAPH_VERSION=v21.0
WHATSAPP_RECIPIENTS=201091450342,201069430567
```

### 2. إنشاء Template في Meta

1. اذهب إلى: https://business.facebook.com/wa/manage/message-templates/
2. اضغط "Create Template"
3. التفاصيل:
   - **Name**: `order_created`
   - **Category**: `UTILITY`
   - **Language**: Arabic (ar)
4. محتوى الرسالة:

```
تم تسجيل طلب جديد في فكرة استور 🛍️

رقم الطلب: {{1}}
اسم العميل: {{2}}
المبلغ الإجمالي: {{3}}

شكراً لثقتك بنا ✨
```

5. Submit وانتظر الموافقة

### 3. إضافة أرقام الاختبار

في لوحة WhatsApp → Getting Started → Add test numbers:
- 01091450342
- 01069430567

### 4. تشغيل Queue Worker

```bash
php artisan queue:work --queue=high,default
```

### 5. اختبار

```bash
# في Tinker
php artisan tinker

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
```

تحقق من الرسالة على WhatsApp! 📱

## الملفات المُنشأة:

✅ `app/Services/WhatsAppService.php` - خدمة WhatsApp  
✅ `app/Events/OrderPlaced.php` - Event الطلب  
✅ `app/Listeners/SendOrderPlacedWhatsApp.php` - Listener الإرسال  
✅ `app/Models/Order.php` - تم التحديث  
✅ `app/Providers/AppServiceProvider.php` - تم التحديث  
✅ `config/services.php` - تم التحديث  
✅ `tests/Feature/WhatsAppNotificationTest.php` - الاختبارات  
✅ `WHATSAPP_NOTIFICATIONS_README.md` - التوثيق الكامل

## التوثيق الكامل:

راجع `WHATSAPP_NOTIFICATIONS_README.md` للتفاصيل الكاملة.
