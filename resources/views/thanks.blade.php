<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شكراً لك - تم تسجيل طلبك بنجاح</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .bounce-in {
            animation: bounceIn 1s ease-out;
        }
        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4 text-center">
        <div class="bounce-in bg-white rounded-3xl shadow-2xl p-12 max-w-2xl mx-auto">
            <!-- Success Icon -->
            <div class="float-animation text-8xl mb-6">✅</div>
            
            <!-- Thank You Message -->
            <h1 class="text-4xl lg:text-5xl font-bold text-gray-800 mb-6">
                شكراً لك!
            </h1>
            
            <h2 class="text-2xl lg:text-3xl font-semibold text-blue-600 mb-8">
                تم تسجيل طلبك بنجاح 🎉
            </h2>
            
            <!-- Order Details -->
            <div class="bg-gray-50 rounded-2xl p-6 mb-8">
                <div class="grid md:grid-cols-2 gap-4 text-right">
                    @if(session('order'))
                        @php $order = session('order'); @endphp
                        <div>
                            <span class="font-bold text-gray-700">رقم الطلب:</span>
                            <span class="text-blue-600 font-bold">#{{ $order->id }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-gray-700">اسم العميل:</span>
                            <span class="text-gray-600">{{ $order->customer_name }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-gray-700">رقم الهاتف:</span>
                            <span class="text-gray-600">{{ $order->phone }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-gray-700">المحافظة:</span>
                            <span class="text-gray-600">{{ $order->governorate }}</span>
                        </div>
                        @if($order->product)
                        <div class="md:col-span-2">
                            <span class="font-bold text-gray-700">المنتج:</span>
                            <span class="text-gray-600">{{ $order->product->name }}</span>
                        </div>
                        @endif
                    @endif
                </div>
            </div>
            
            <!-- What's Next -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">ماذا بعد؟</h3>
                <div class="space-y-3 text-gray-700">
                    <div class="flex items-center justify-center">
                        <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm ml-3">1</span>
                        <span>سيتم التواصل معك خلال 24 ساعة لتأكيد الطلب</span>
                    </div>
                    <div class="flex items-center justify-center">
                        <span class="bg-green-100 text-green-600 w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm ml-3">2</span>
                        <span>سيتم شحن طلبك خلال 2-3 أيام عمل</span>
                    </div>
                    <div class="flex items-center justify-center">
                        <span class="bg-orange-100 text-orange-600 w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm ml-3">3</span>
                        <span>الدفع عند الاستلام</span>
                    </div>
                </div>
            </div>
            
            <!-- Contact Info -->
            <div class="bg-blue-50 rounded-2xl p-6 mb-8">
                <h4 class="font-bold text-gray-800 mb-3">للاستفسار أو المتابعة:</h4>
                <div class="space-y-2 text-gray-700">
                    <div>📞 الهاتف: 01000000000</div>
                    <div>📧 البريد الإلكتروني: info@shop.com</div>
                    <div>🕐 أوقات العمل: من 9 صباحاً إلى 6 مساءً</div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('landing') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-full transition duration-300 shadow-lg">
                    🏠 العودة للصفحة الرئيسية
                </a>
                <a href="{{ route('landing') }}#products" 
                   class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-full transition duration-300 shadow-lg">
                    🛍️ تسوق المزيد
                </a>
            </div>
            
            <!-- Thank You Note -->
            <div class="mt-8 p-4 bg-yellow-50 rounded-xl border border-yellow-200">
                <p class="text-gray-700 italic">
                    "شكراً لثقتك في متجرنا. نحن نعمل بجد لضمان وصول طلبك في أفضل حالة وفي الوقت المحدد."
                </p>
                <p class="font-bold text-blue-600 mt-2">- فريق متجرك للدروب شيبنج</p>
            </div>
        </div>
        
        <!-- Floating Elements -->
        <div class="fixed top-10 left-10 text-4xl opacity-20 float-animation" style="animation-delay: 0.5s;">🎉</div>
        <div class="fixed top-20 right-10 text-3xl opacity-20 float-animation" style="animation-delay: 1s;">⭐</div>
        <div class="fixed bottom-20 left-20 text-3xl opacity-20 float-animation" style="animation-delay: 1.5s;">🎊</div>
        <div class="fixed bottom-10 right-20 text-4xl opacity-20 float-animation" style="animation-delay: 2s;">🚀</div>
    </div>
</body>
</html>
