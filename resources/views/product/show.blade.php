<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $product->meta_title ?: $product->name }} - متجرك للدروب شيبنج</title>
    
    @if($product->meta_description)
    <meta name="description" content="{{ $product->meta_description }}">
    @endif
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
        .gallery-image {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .gallery-image:hover {
            transform: scale(1.05);
        }
        .main-image {
            max-height: 500px;
            object-fit: cover;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('landing') }}" class="text-2xl font-bold text-blue-600">
                    متجرك للدروب شيبنج
                </a>
                <a href="{{ route('landing') }}" class="text-blue-600 hover:text-blue-800">
                    العودة للصفحة الرئيسية
                </a>
            </div>
        </div>
    </nav>

    <!-- Product Details -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Product Images -->
                <div>
                    <div class="mb-4">
                        <img id="mainImage" src="{{ $product->image_url }}" 
                             alt="{{ $product->name }}" 
                             class="w-full main-image rounded-lg shadow-lg"
                             onerror="this.src='https://via.placeholder.com/600x400?text=No+Image'">
                    </div>
                    
                    @if($product->gallery && count($product->gallery) > 0)
                    <div class="grid grid-cols-4 gap-2">
                        <!-- Main image thumbnail -->
                        <img src="{{ $product->image_url }}" 
                             alt="{{ $product->name }}" 
                             class="gallery-image w-full h-20 object-cover rounded border-2 border-blue-500"
                             onclick="changeMainImage(this.src)"
                             onerror="this.src='https://via.placeholder.com/100x100?text=No+Image'">
                        
                        @foreach($product->gallery as $image)
                        <img src="{{ str_starts_with($image, 'http') ? $image : asset('storage/' . $image) }}" 
                             alt="{{ $product->name }}" 
                             class="gallery-image w-full h-20 object-cover rounded border-2 border-gray-300 hover:border-blue-500"
                             onclick="changeMainImage(this.src)">
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $product->name }}</h1>
                    
                    <div class="mb-6">
                        <div class="flex items-center gap-4 mb-2">
                            <span class="text-3xl font-bold text-blue-600">{{ $product->formatted_price }}</span>
                            @if($product->original_price && $product->original_price > $product->price)
                                <span class="text-xl text-gray-500 line-through">{{ $product->formatted_original_price }}</span>
                                <span class="bg-red-500 text-white px-2 py-1 rounded text-sm font-bold">
                                    خصم {{ $product->discount_percentage }}%
                                </span>
                            @endif
                        </div>
                        <p class="text-green-600 font-semibold">✅ متوفر - توصيل مجاني</p>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-bold mb-2">الوصف:</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                    </div>

                    @if($product->features)
                    <div class="mb-6">
                        <h3 class="text-lg font-bold mb-2">المميزات:</h3>
                        <div class="space-y-2">
                            @foreach(explode("\n", $product->features) as $feature)
                                @if(trim($feature))
                                <div class="flex items-center">
                                    <span class="text-green-500 mr-2">✓</span>
                                    <span class="text-gray-700">{{ trim($feature) }}</span>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Order Form -->
                    <div class="bg-white rounded-lg shadow-lg p-6 border-2 border-blue-500">
                        <h3 class="text-xl font-bold mb-4 text-center">اطلب الآن</h3>
                        
                        <form id="productOrderForm" class="space-y-4">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            <div>
                                <label for="quantity" class="block text-sm font-bold text-gray-700 mb-2">الكمية</label>
                                <select id="quantity" name="quantity" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label for="customer_name" class="block text-sm font-bold text-gray-700 mb-2">الاسم كاملاً</label>
                                <input type="text" id="customer_name" name="customer_name" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="أدخل اسمك كاملاً">
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-bold text-gray-700 mb-2">رقم الهاتف</label>
                                <input type="tel" id="phone" name="phone" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="01xxxxxxxxx">
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-bold text-gray-700 mb-2">العنوان بالتفصيل</label>
                                <textarea id="address" name="address" required rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="أدخل عنوانك بالتفصيل (المحافظة، المدينة، الشارع، رقم البناء)"></textarea>
                            </div>

                            <div class="border-t pt-4">
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-lg font-semibold">الإجمالي:</span>
                                    <span id="totalPrice" class="text-2xl font-bold text-blue-600">{{ $product->formatted_price }}</span>
                                </div>
                            </div>

                            <button type="submit" id="productSubmitBtn"
                                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 px-6 rounded-lg text-xl transition duration-300">
                                تأكيد الطلب
                            </button>
                        </form>

                        <!-- Success Message -->
                        <div id="productSuccessMessage" class="hidden mt-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                            <h3 class="font-bold">تم تسجيل طلبك بنجاح!</h3>
                            <p>سيتم التواصل معك خلال 24 ساعة لتأكيد الطلب.</p>
                        </div>

                        <!-- Error Message -->
                        <div id="productErrorMessage" class="hidden mt-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            <h3 class="font-bold">حدث خطأ!</h3>
                            <p id="productErrorText">يرجى المحاولة مرة أخرى.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Badges -->
    <section class="py-8 bg-blue-50">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-6 text-center">
                <div class="flex flex-col items-center">
                    <div class="text-4xl mb-2">🚚</div>
                    <h3 class="font-bold">توصيل سريع</h3>
                    <p class="text-gray-600">خلال 2-3 أيام عمل</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="text-4xl mb-2">💯</div>
                    <h3 class="font-bold">ضمان الجودة</h3>
                    <p class="text-gray-600">منتجات أصلية 100%</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="text-4xl mb-2">🔒</div>
                    <h3 class="font-bold">دفع آمن</h3>
                    <p class="text-gray-600">الدفع عند الاستلام</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; 2025 متجرك للدروب شيبنج. جميع الحقوق محفوظة.</p>
            <p class="mt-2">للاستفسارات: 01000000000</p>
        </div>
    </footer>

    <script>
        const productPrice = {{ $product->price }};
        
        // Update total price when quantity changes
        document.getElementById('quantity').addEventListener('change', function() {
            const quantity = parseInt(this.value);
            const total = productPrice * quantity;
            document.getElementById('totalPrice').textContent = new Intl.NumberFormat('ar-EG').format(total) + ' ج.م';
        });

        // Gallery image switching
        function changeMainImage(src) {
            document.getElementById('mainImage').src = src;
            
            // Update active thumbnail
            document.querySelectorAll('.gallery-image').forEach(img => {
                img.classList.remove('border-blue-500');
                img.classList.add('border-gray-300');
            });
            
            event.target.classList.remove('border-gray-300');
            event.target.classList.add('border-blue-500');
        }

        // Form submission
        document.getElementById('productOrderForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('productSubmitBtn');
            const successMessage = document.getElementById('productSuccessMessage');
            const errorMessage = document.getElementById('productErrorMessage');
            
            // Reset messages
            successMessage.classList.add('hidden');
            errorMessage.classList.add('hidden');
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.textContent = 'جاري الإرسال...';
            
            const formData = new FormData(this);
            
            fetch('{{ route("orders.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    successMessage.classList.remove('hidden');
                    document.getElementById('productOrderForm').reset();
                    document.getElementById('quantity').value = '1';
                    document.getElementById('totalPrice').textContent = '{{ $product->formatted_price }}';
                } else {
                    throw new Error(data.message || 'حدث خطأ غير متوقع');
                }
            })
            .catch(error => {
                errorMessage.classList.remove('hidden');
                document.getElementById('productErrorText').textContent = error.message;
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'تأكيد الطلب';
            });
        });
    </script>
</body>
</html>
