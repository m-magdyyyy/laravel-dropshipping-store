# دليل اختبار نظام WhatsApp - خطوة بخطوة

## ✅ الإعداد الحالي

تم إضافة البيانات التالية في `.env`:
```bash
WHATSAPP_TOKEN=EAAYkVZCZBDG38BP... (موجود ✓)
WHATSAPP_PHONE_NUMBER_ID=893307607188992 (موجود ✓)
WHATSAPP_RECIPIENTS=201091450342,201069430567 (موجود ✓)
```

## 🚀 خطوات الاختبار

### 1. تأكد من تشغيل Queue Worker

```bash
# تحقق من Queue Worker يعمل
ps aux | grep "queue:work"

# إذا لم يكن يعمل، شغله:
php artisan queue:work --queue=high,default --tries=3 &
```

### 2. أنشئ Template في Meta (إذا لم تنشئه بعد)

1. اذهب إلى: https://business.facebook.com/wa/manage/message-templates/
2. اضغط "Create Template"
3. املأ البيانات:
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

5. Submit وانتظر الموافقة (عادة دقائق)

### 3. أضف أرقام الاختبار في Meta

1. في لوحة WhatsApp → "Getting Started"
2. في قسم "To" أضف: `+201091450342`
3. اضغط "Send code"
4. أدخل الرمز من WhatsApp
5. كرر للرقم: `+201069430567`

### 4. اختبر إنشاء طلب

#### أ. من الموقع (الطريقة الأسهل):
1. افتح الموقع: http://127.0.0.1:8001
2. اختر أي منتج
3. اضغط "اطلب الآن"
4. املأ البيانات:
   - الاسم: أحمد محمد
   - الهاتف: 01091450342
   - العنوان: القاهرة
   - المحافظة: القاهرة
5. اضغط "تأكيد الطلب"

#### ب. من Tinker (للمبرمجين):
```bash
php artisan tinker

# انسخ والصق:
$product = \App\Models\Product::first();
$order = \App\Models\Order::create([
    'product_id' => $product->id,
    'quantity' => 2,
    'customer_name' => 'أحمد محمد - اختبار',
    'phone' => '01091450342',
    'address' => 'القاهرة - شارع التحرير',
    'governorate' => 'القاهرة',
    'status' => 'pending'
]);

echo "تم إنشاء الطلب رقم: " . $order->id;
exit
```

### 5. راقب النتائج

#### تحقق من Logs:
```bash
# شاهد الـ Logs مباشرة
tail -f storage/logs/laravel.log

# ابحث عن:
# - "Sending WhatsApp notification for order"
# - "WhatsApp message sent successfully"
# أو
# - "WhatsApp API error" (إذا كان هناك خطأ)
```

#### تحقق من Queue:
```bash
# عرض Failed Jobs إن وجدت
php artisan queue:failed

# إعادة محاولة Failed Jobs
php artisan queue:retry all
```

#### تحقق من WhatsApp:
- افتح WhatsApp على 01091450342 أو 01069430567
- يجب أن تصل رسالة مثل:
```
تم تسجيل طلب جديد في فكرة استور 🛍️

رقم الطلب: 42
اسم العميل: أحمد محمد
المبلغ الإجمالي: 450 ج.م

شكراً لثقتك بنا ✨
```

## 🔍 استكشاف الأخطاء

### الخطأ: "Cannot assign null to property $token"

**الحل:**
```bash
# 1. تنظيف الـ cache
php artisan config:clear
php artisan cache:clear

# 2. تحقق من .env
cat .env | grep WHATSAPP

# 3. إعادة تشغيل Queue Worker
pkill -f "queue:work"
php artisan queue:work --queue=high,default --tries=3 &
```

### الخطأ: "Template not found"

**الأسباب المحتملة:**
- ❌ Template لم يتم إنشاؤه في Meta
- ❌ Template لم توافق عليه Meta بعد
- ❌ اسم Template خطأ في .env

**الحل:**
1. تأكد من إنشاء Template في Meta
2. انتظر الموافقة (10-60 دقيقة عادة)
3. تحقق من `WHATSAPP_TEMPLATE=order_created` في .env

### الخطأ: "Recipient not in allowed list"

**السبب:** الأرقام غير مُسجلة في Test Numbers

**الحل:**
1. اذهب لـ WhatsApp Manager
2. أضف الأرقام كـ Test Numbers
3. أدخل رمز التحقق

### الخطأ: "Invalid phone number format"

**السبب:** صيغة الأرقام خاطئة

**الحل:**
تأكد أن الأرقام في .env بصيغة E.164 **بدون "+"**:
```bash
# ✅ صحيح
WHATSAPP_RECIPIENTS=201091450342,201069430567

# ❌ خطأ
WHATSAPP_RECIPIENTS=01091450342,01069430567
WHATSAPP_RECIPIENTS=+201091450342,+201069430567
```

## 📊 تحقق من الإحصائيات

```bash
# عدد الطلبات
php artisan tinker
\App\Models\Order::count();

# آخر 5 طلبات
\App\Models\Order::latest()->take(5)->get(['id', 'customer_name', 'created_at']);

# عدد Jobs في Queue
\Illuminate\Support\Facades\DB::table('jobs')->count();

# عدد Failed Jobs
\Illuminate\Support\Facades\DB::table('failed_jobs')->count();
```

## ✅ قائمة التحقق

قبل الاتصال بالدعم، تأكد من:

- [ ] WHATSAPP_TOKEN موجود في .env
- [ ] WHATSAPP_PHONE_NUMBER_ID موجود في .env
- [ ] WHATSAPP_RECIPIENTS بصيغة صحيحة (E.164 بدون +)
- [ ] Template تم إنشاؤه وموافق عليه في Meta
- [ ] الأرقام مُسجلة كـ Test Numbers في Meta
- [ ] Queue Worker يعمل (ps aux | grep queue:work)
- [ ] config:clear تم تشغيله
- [ ] لا توجد أخطاء في storage/logs/laravel.log

## 🎉 نجح الاختبار؟

إذا وصلت الرسالة على WhatsApp:
- ✅ النظام يعمل بشكل صحيح!
- ✅ يمكنك الآن استخدامه في Production
- ✅ تذكر تشغيل Queue Worker دائماً (أو استخدم Supervisor)

## 📞 تحتاج مساعدة؟

راجع الملفات:
- `WHATSAPP_README.md` - الدليل الرئيسي
- `WHATSAPP_TOKEN_GUIDE.md` - كيفية الحصول على Token
- `WHATSAPP_NOTIFICATIONS_README.md` - التوثيق الكامل

---

**حظاً موفقاً! 🚀**
