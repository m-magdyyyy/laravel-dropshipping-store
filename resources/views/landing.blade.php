<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>فكره استور - أفضل المنتجات بأسعار لا تُقاوم</title>
    <meta name="description" content="اكتشف مجموعة مميزة من المنتجات عالية الجودة مع توصيل سريع وآمن لباب منزلك">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'cairo': ['Cairo', 'sans-serif'],
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
                        'bounce-slow': 'bounce 2s infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-20px)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
        }
        .hover-scale {
            transition: transform 0.3s ease;
        }
        .hover-scale:hover {
            transform: scale(1.05);
        }
        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        .product-card {
            transition: all 0.3s ease;
            transform: translateY(0);
        }
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gray-50 font-cairo">
    <!-- Success Message -->
    @if(session('success'))
    <div id="success-message" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300">
        <div class="flex items-center">
            <span class="text-xl ml-2">✅</span>
            <span>{{ session('success') }}</span>
            <button onclick="closeSuccessMessage()" class="mr-4 text-white hover:text-gray-200">
                <span class="text-xl">×</span>
            </button>
        </div>
    </div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
    <div id="error-message" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300">
        <div class="flex items-center">
            <span class="text-xl ml-2">❌</span>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
            <button onclick="closeErrorMessage()" class="mr-4 text-white hover:text-gray-200">
                <span class="text-xl">×</span>
            </button>
        </div>
    </div>
    @endif
    <!-- Hero Section -->
    <section class="gradient-bg text-white relative overflow-hidden min-h-screen flex items-center">
        <!-- Background Animation Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-10 left-10 w-20 h-20 bg-white opacity-10 rounded-full animate-bounce-slow"></div>
            <div class="absolute top-1/3 right-20 w-16 h-16 bg-yellow-300 opacity-20 rounded-full animate-float"></div>
            <div class="absolute bottom-20 left-1/4 w-12 h-12 bg-pink-300 opacity-15 rounded-full animate-pulse-slow"></div>
            <div class="absolute bottom-1/3 right-10 w-24 h-24 bg-blue-300 opacity-10 rounded-full animate-float"></div>
        </div>

        <div class="container mx-auto px-4 py-16 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="text-center lg:text-right">
                    <h1 class="text-5xl lg:text-7xl font-bold mb-6 text-shadow leading-tight">
                        أفضل المنتجات بأسعار 
                        <span class="text-yellow-300 animate-pulse">لا تُقاوم</span>
                    </h1>
                    <p class="text-xl lg:text-2xl mb-8 text-gray-100 leading-relaxed">
                        اكتشف مجموعة مميزة من المنتجات عالية الجودة مع توصيل سريع وآمن لباب منزلك
                    </p>
                    
                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="#products" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 px-8 rounded-full text-xl transition duration-300 shadow-lg hover-scale">
                            🛍️ تسوق الآن
                        </a>
                        <a href="#features" class="glass-effect border-2 border-white text-white font-bold py-4 px-8 rounded-full text-xl transition duration-300 hover:bg-white hover:text-gray-800">
                            ✨ اعرف المزيد
                        </a>
                    </div>

                    <!-- Trust Indicators -->
                    <div class="mt-12 flex flex-wrap justify-center lg:justify-start gap-6 text-sm">
                        <div class="flex items-center bg-white bg-opacity-20 px-4 py-2 rounded-full">
                            <span class="text-green-300 ml-2">✅</span>
                            <span>توصيل مجاني</span>
                        </div>
                        <div class="flex items-center bg-white bg-opacity-20 px-4 py-2 rounded-full">
                            <span class="text-green-300 ml-2">🚚</span>
                            <span>شحن سريع</span>
                        </div>
                        <div class="flex items-center bg-white bg-opacity-20 px-4 py-2 rounded-full">
                            <span class="text-green-300 ml-2">💯</span>
                            <span>ضمان الجودة</span>
                        </div>
                    </div>
                </div>

                <div class="text-center lg:text-left">
                    <div class="relative">
                        <!-- Main Hero Image/Icon -->
                        <div class="bg-white bg-opacity-20 rounded-3xl p-12 backdrop-filter backdrop-blur-lg border border-white border-opacity-30 animate-float">
                            <div class="text-8xl lg:text-9xl mb-6">🛍️</div>
                            <h3 class="text-2xl lg:text-3xl font-bold mb-4">توصيل مجاني</h3>
                            <p class="text-lg text-gray-100">لجميع المحافظات</p>
                        </div>
                        
                        <!-- Floating Elements -->
                        <div class="absolute -top-4 -right-4 bg-yellow-400 text-yellow-900 px-4 py-2 rounded-full font-bold text-sm animate-bounce">
                            خصم 50%
                        </div>
                        <div class="absolute -bottom-4 -left-4 bg-green-400 text-green-900 px-4 py-2 rounded-full font-bold text-sm animate-pulse">
                            توصيل سريع
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <div class="w-6 h-10 border-2 border-white rounded-full flex justify-center">
                <div class="w-1 h-3 bg-white rounded-full mt-2 animate-pulse"></div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-800 mb-6">لماذا تختارنا؟</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">نحن نقدم أفضل تجربة تسوق عبر الإنترنت مع ضمان الجودة والأسعار التنافسية</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 hover-scale">
                    <div class="text-6xl mb-6 animate-float">⚡</div>
                    <h3 class="text-2xl font-bold mb-4 text-blue-800">توصيل سريع</h3>
                    <p class="text-gray-600 text-lg leading-relaxed">توصيل خلال 1-3 أيام عمل لجميع المحافظات مع إمكانية التتبع المباشر للشحنة</p>
                </div>
                
                <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-green-50 to-green-100 hover-scale">
                    <div class="text-6xl mb-6 animate-float" style="animation-delay: 0.5s;">💯</div>
                    <h3 class="text-2xl font-bold mb-4 text-green-800">جودة مضمونة</h3>
                    <p class="text-gray-600 text-lg leading-relaxed">منتجات أصلية 100% بأعلى معايير الجودة مع ضمان الاستبدال والإرجاع</p>
                </div>
                
                {{-- <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-purple-50 to-purple-100 hover-scale">
                    <div class="text-6xl mb-6 animate-float" style="animation-delay: 1s;">🔒</div>
                    <h3 class="text-2xl font-bold mb-4 text-purple-800">دفع آمن</h3>
                    <p class="text-gray-600 text-lg leading-relaxed">الدفع عند الاستلام أو بالطرق الآمنة مع حماية كاملة لبياناتك</p>
                </div> --}}
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-800 mb-6">منتجاتنا المميزة</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">تشكيلة منتقاة بعناية من أفضل المنتجات بأسعار تنافسية وجودة عالية</p>
            </div>
            
            @if($products->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($products as $product)
                <div class="product-card bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ $product->image_url }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover"
                             loading="lazy"
                             onerror="this.src='https://via.placeholder.com/400x400?text=No+Image'">
                        
                        @if($product->discount_percentage > 0)
                        <div class="absolute top-4 right-4">
                            <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold animate-pulse">
                                خصم {{ $product->discount_percentage }}%
                            </span>
                        </div>
                        @endif
                        
                        <!-- Quick View Overlay -->
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <a href="{{ route('product.show', $product->slug) }}" 
                               class="bg-white text-gray-800 px-6 py-3 rounded-full font-bold hover:bg-gray-100 transition duration-300">
                                عرض التفاصيل
                            </a>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-3 text-gray-800 leading-tight">{{ $product->name }}</h3>
                        <p class="text-gray-600 mb-4 leading-relaxed">{{ Str::limit($product->description, 100) }}</p>
                        
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-2xl font-bold text-blue-600">{{ $product->formatted_price }}</span>
                            @if($product->original_price && $product->original_price > $product->price)
                                <span class="text-lg text-gray-500 line-through">{{ $product->formatted_original_price }}</span>
                            @endif
                        </div>
                        
                        <!-- Product Features Preview -->
                        @if($product->features)
                        <div class="mb-4">
                            @php
                                $features = explode("\n", $product->features);
                                $topFeatures = array_slice($features, 0, 2);
                            @endphp
                            @foreach($topFeatures as $feature)
                                @if(trim($feature))
                                <div class="flex items-center text-sm text-gray-600 mb-1">
                                    <span class="text-green-500 ml-2">✓</span>
                                    <span>{{ trim($feature) }}</span>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        @endif
                        
                        <a href="{{ route('product.show', $product->slug) }}" 
                           class="block w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-3 px-4 rounded-xl text-center transition duration-300 shadow-lg hover:shadow-xl">
                            🛒 اطلب الآن
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            
            @else
            <div class="text-center py-16">
                <div class="text-8xl mb-6 animate-bounce">📦</div>
                <h3 class="text-3xl font-bold text-gray-600 mb-4">قريباً منتجات جديدة!</h3>
                <p class="text-xl text-gray-500 mb-8">نعمل على إضافة منتجات مميزة لك</p>
                <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-full transition duration-300">
                    اشترك للحصول على التحديثات
                </a>
            </div>
            @endif
        </div>
    </section>


    <!-- CTA Section -->
    {{-- <section class="py-20 bg-gradient-to-r from-orange-500 to-red-500 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl lg:text-5xl font-bold mb-6 text-shadow">جاهز للطلب؟</h2>
            <p class="text-xl lg:text-2xl mb-8 max-w-3xl mx-auto">اختر منتجك المفضل واحصل على أفضل العروض مع توصيل مجاني لباب منزلك</p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="#order" class="bg-white text-orange-500 hover:bg-gray-100 font-bold py-4 px-8 rounded-full text-xl transition duration-300 shadow-lg hover-scale">
                    🛍️ اطلب الآن
                </a>
                <div class="flex items-center text-lg">
                    <span class="ml-2">📞</span>
                    <span>أو اتصل بنا: 01000000000</span>
                </div>
            </div>
        </div>
    </section> --}}

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    {{-- <h3 class="text-2xl font-bold mb-4">متجرك للدروب شيبنج</h3> --}}
                        <h3 class="text-2xl font-bold mb-4">فكره استور</h3>
                    <p class="text-gray-400 leading-relaxed">نحن نقدم أفضل المنتجات بأسعار تنافسية مع خدمة عملاء متميزة.</p>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold mb-4">روابط سريعة</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition duration-300">عن المتجر</a></li>
                        {{-- <li><a href="#" class="hover:text-white transition duration-300">سياسة الإرجاع</a></li> --}}
                        {{-- <li><a href="#" class="hover:text-white transition duration-300">الشحن والتوصيل</a></li> --}}
                        {{-- <li><a href="#" class="hover:text-white transition duration-300">تتبع الطلب</a></li> --}}
                    </ul>
                </div>
                
                {{-- <div>
                    <h4 class="text-lg font-bold mb-4">خدمة العملاء</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>📞 01000000000</li>
                        <li>📧 info@shop.com</li>
                        <li>🕒 من 9 ص إلى 6 م</li>
                        <li>📍 القاهرة، مصر</li>
                    </ul>
                </div> --}}
                
                <div>
                    <h4 class="text-lg font-bold mb-4">تابعنا</h4>
                    <div class="flex space-x-4 space-x-reverse">
                        {{-- <a href="#" class="bg-blue-600 hover:bg-blue-700 p-3 rounded-full transition duration-300">📘</a>
                        <a href="#" class="bg-pink-600 hover:bg-pink-700 p-3 rounded-full transition duration-300">📷</a> --}}
                        <a href="#" class="bg-green-600 hover:bg-green-700 p-3 rounded-full transition duration-300">📱</a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 فكره استور. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.addEventListener('DOMContentLoaded', () => {
            const animateElements = document.querySelectorAll('.product-card, .hover-scale');
            animateElements.forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(el);
            });
        });

        // Form validation and enhancement
        document.getElementById('phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) {
                value = value.slice(0, 11);
            }
            e.target.value = value;
        });

        // Success/Error message functions
        function closeSuccessMessage() {
            const message = document.getElementById('success-message');
            if (message) {
                message.style.transform = 'translateX(100%)';
                setTimeout(() => message.remove(), 300);
            }
        }

        function closeErrorMessage() {
            const message = document.getElementById('error-message');
            if (message) {
                message.style.transform = 'translateX(100%)';
                setTimeout(() => message.remove(), 300);
            }
        }

        // Show messages on page load
        document.addEventListener('DOMContentLoaded', () => {
            const successMessage = document.getElementById('success-message');
            const errorMessage = document.getElementById('error-message');
            
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.transform = 'translateX(0)';
                }, 100);
                // Auto hide after 5 seconds
                setTimeout(() => {
                    closeSuccessMessage();
                }, 5000);
            }
            
            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.transform = 'translateX(0)';
                }, 100);
                // Auto hide after 7 seconds
                setTimeout(() => {
                    closeErrorMessage();
                }, 7000);
            }
        });
    </script>
</body>
</html>
