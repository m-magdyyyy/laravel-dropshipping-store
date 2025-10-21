# كيفية الحصول على WhatsApp Access Token و Phone Number ID

## 🔑 خطوات الحصول على البيانات المطلوبة

### 1. إنشاء تطبيق Meta للأعمال

1. اذهب إلى: https://developers.facebook.com/
2. سجل دخول أو أنشئ حساب
3. اضغط "My Apps" → "Create App"
4. اختر نوع التطبيق: **"Business"**
5. املأ البيانات:
   - App Name: `فكرة استور WhatsApp`
   - App Contact Email: بريدك الإلكتروني
   - اختر Business Portfolio أو أنشئ واحد جديد
6. اضغط "Create App"

### 2. إضافة WhatsApp إلى التطبيق

1. في صفحة التطبيق، ابحث عن "WhatsApp" في القائمة
2. اضغط "Set Up" على منتج WhatsApp
3. ستُفتح لوحة WhatsApp

### 3. الحصول على Phone Number ID (معرف الرقم)

في لوحة WhatsApp:

1. اذهب إلى "Getting Started"
2. ستجد قسم "Phone number ID"
3. **انسخ هذا الرقم** - هذا هو `WHATSAPP_PHONE_NUMBER_ID`
4. مثال: `123456789012345`

### 4. الحصول على Access Token المؤقت (للاختبار)

في نفس صفحة "Getting Started":

1. ستجد قسم "Temporary access token"
2. اضغط "Copy" لنسخ الـ Token
3. **هذا Token صالح لـ 24 ساعة فقط** - للاختبار السريع
4. مثال: `EAABsbCS1iHgBO...`

⚠️ **تحذير:** هذا Token مؤقت! استخدمه فقط للاختبار. للإنتاج، اتبع الخطوة 5.

### 5. الحصول على Access Token دائم (Production)

للحصول على Token لا ينتهي:

#### أ. إنشاء System User

1. اذهب إلى: https://business.facebook.com/settings
2. من القائمة اليمنى، اختر "System Users"
3. اضغط "Add" لإنشاء System User جديد
4. اختر اسم (مثل: `WhatsApp Bot`)
5. اختر الدور: `Admin`
6. اضغط "Create System User"

#### ب. توليد Access Token

1. اضغط "Generate New Token" للـ System User المُنشأ
2. اختر التطبيق الخاص بك من القائمة
3. حدد الصلاحيات (Permissions):
   - ✅ `whatsapp_business_messaging`
   - ✅ `whatsapp_business_management`
4. اضغط "Generate Token"
5. **انسخ الـ Token فوراً** - لن تستطيع رؤيته مرة أخرى!
6. احفظه في مكان آمن

#### ج. تخصيص الأصول (Assets)

1. في صفحة System User، اذهب لقسم "Assign Assets"
2. اختر "Apps"
3. أضف تطبيقك
4. اختر "Full control"

### 6. إضافة الأرقام إلى الـ .env

الأرقام يجب أن تكون بصيغة **E.164** بدون "+":

```bash
# ✅ صحيح (E.164 بدون +)
WHATSAPP_RECIPIENTS=201091450342,201069430567

# ❌ خطأ
WHATSAPP_RECIPIENTS=01091450342,01069430567
WHATSAPP_RECIPIENTS=+201091450342,+201069430567
```

#### تحويل الأرقام المصرية:

- رقم محلي: `01091450342`
- كود مصر: `20`
- E.164: `201091450342` ✅

### 7. وضع البيانات في .env

افتح ملف `.env` وأضف:

```bash
WHATSAPP_TOKEN=your_permanent_token_here
WHATSAPP_PHONE_NUMBER_ID=123456789012345
WHATSAPP_TEMPLATE=order_created
WHATSAPP_LANG=ar
WHATSAPP_GRAPH_VERSION=v21.0
WHATSAPP_RECIPIENTS=201091450342,201069430567
```

## 🧪 إضافة أرقام الاختبار

قبل نشر التطبيق، يمكنك الإرسال فقط لأرقام مُسجلة:

### الخطوات:

1. في لوحة WhatsApp، اذهب إلى "Getting Started"
2. في قسم "Send and receive messages"
3. أدخل الرقم بصيغة **E.164 مع +**
   - مثال: `+201091450342`
4. اضغط "Send code"
5. ستصلك رسالة WhatsApp برمز التحقق
6. أدخل الرمز في الموقع
7. كرر لكل رقم تريد إضافته

**الأرقام المطلوبة:**
- `+201091450342`
- `+201069430567`

## ✅ التحقق من الإعداد

بعد إضافة البيانات، تحقق:

```bash
# 1. تحقق من .env
cat .env | grep WHATSAPP

# 2. تحقق من Config
php artisan tinker
config('services.whatsapp')

# يجب أن ترى:
# [
#   "token" => "EAABsbCS1...",
#   "phone_number_id" => "123456789012345",
#   "template" => "order_created",
#   ...
# ]
```

## 🚨 أخطاء شائعة

### 1. "Invalid access token"
- تأكد من نسخ الـ Token كاملاً
- تحقق من عدم وجود مسافات في .env
- للـ Production، استخدم System User Token

### 2. "Phone number not found"
- تحقق من Phone Number ID
- تأكد أنه من نفس التطبيق

### 3. "Recipient not allowed"
- أضف الرقم كـ Test Number
- في Development mode فقط الأرقام المُسجلة

### 4. "Template not found"
- انتظر موافقة Meta على الـ Template
- تحقق من اسم Template في .env

## 📞 الدعم

- **WhatsApp Docs:** https://developers.facebook.com/docs/whatsapp
- **Business Manager:** https://business.facebook.com/
- **Developer Console:** https://developers.facebook.com/apps

## 🎉 الخطوة التالية

بعد إضافة البيانات:
1. راجع `WHATSAPP_QUICK_SETUP.md` لإنشاء الـ Template
2. شغل Queue Worker
3. اختبر النظام!

---

**آخر تحديث:** أكتوبر 2025
