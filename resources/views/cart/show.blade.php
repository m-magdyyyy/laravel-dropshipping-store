<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>سلة التسوق - فكره استور</title>
    <meta name="description" content="سلة التسوق - راجع منتجاتك المختارة وأتمم عملية الشراء">
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
                        'fadeIn': 'fadeIn 0.5s ease-in-out',
                        'slideDown': 'slideDown 0.3s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        slideDown: {
                            '0%': { opacity: '0', transform: 'translateY(-10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
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
        .cart-item {
            transition: all 0.3s ease;
        }
        .cart-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }
        .quantity-btn {
            transition: all 0.2s ease;
        }
        .quantity-btn:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body class="bg-gray-50 font-cairo">
    <!-- Navigation -->
    <nav class="gradient-bg text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('landing') }}" class="text-2xl font-bold">فكره استور</a>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('landing') }}" class="hover:text-blue-200 transition-colors">
                        الرئيسية
                    </a>
                    <div class="relative">
                        <span class="bg-yellow-400 text-purple-800 px-2 py-1 rounded-full text-sm font-bold">
                            {{ $cartCount }} منتج
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Cart Content -->
    <div class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="text-center mb-8 animate-fadeIn">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">🛒 سلة التسوق</h1>
            <p class="text-gray-600 text-lg">راجع منتجاتك المختارة وأتمم عملية الشراء</p>
        </div>

        @if(empty($cart))
            <!-- Empty Cart -->
            <div class="text-center py-16 animate-fadeIn">
                <div class="text-8xl mb-6">🛒</div>
                <h2 class="text-3xl font-bold text-gray-600 mb-4">سلة التسوق فارغة</h2>
                <p class="text-gray-500 mb-8 text-lg">ابدأ بإضافة منتجات رائعة لسلة التسوق</p>
                <a href="{{ route('landing') }}" class="btn-primary text-white px-8 py-4 rounded-xl font-bold text-lg hover:shadow-lg">
                    تصفح المنتجات
                </a>
            </div>
        @else
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg p-6 animate-fadeIn">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <span class="text-purple-600 ml-3">📦</span>
                            المنتجات المختارة ({{ $cartCount }})
                        </h2>
                        
                        <div class="space-y-4">
                            @foreach($cart as $productId => $item)
                                <div class="cart-item border border-gray-200 rounded-xl p-4" data-product-id="{{ $productId }}">
                                    <div class="flex items-center space-x-4 space-x-reverse">
                                        <!-- Product Image -->
                                        <div class="flex-shrink-0">
                                            <img src="{{ $item['image_url'] }}" 
                                                 alt="{{ $item['name'] }}" 
                                                 class="w-20 h-20 object-cover rounded-lg">
                                        </div>
                                        
                                        <!-- Product Info -->
                                        <div class="flex-grow">
                                            <h3 class="text-lg font-bold text-gray-800">{{ $item['name'] }}</h3>
                                            <p class="text-purple-600 font-bold text-lg">
                                                {{ number_format($item['price'], 0) }} ج.م
                                            </p>
                                        </div>
                                        
                                        <!-- Quantity Controls -->
                                        <div class="flex items-center space-x-2 space-x-reverse">
                                            <button onclick="updateQuantity({{ $productId }}, {{ $item['quantity'] - 1 }})" 
                                                    class="quantity-btn bg-gray-200 hover:bg-gray-300 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center font-bold"
                                                    {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                                -
                                            </button>
                                            <span class="quantity-display-{{ $productId }} bg-purple-100 text-purple-800 px-3 py-1 rounded-lg font-bold min-w-[3rem] text-center">
                                                {{ $item['quantity'] }}
                                            </span>
                                            <button onclick="updateQuantity({{ $productId }}, {{ $item['quantity'] + 1 }})" 
                                                    class="quantity-btn bg-purple-600 hover:bg-purple-700 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold"
                                                    {{ $item['quantity'] >= 10 ? 'disabled' : '' }}>
                                                +
                                            </button>
                                        </div>
                                        
                                        <!-- Item Total -->
                                        <div class="text-center">
                                            <p class="text-sm text-gray-500">الإجمالي</p>
                                            <p class="item-total-{{ $productId }} text-lg font-bold text-green-600">
                                                {{ number_format($item['price'] * $item['quantity'], 0) }} ج.م
                                            </p>
                                        </div>
                                        
                                        <!-- Remove Button -->
                                        <button onclick="removeFromCart({{ $productId }})" 
                                                class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Clear Cart Button -->
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <button onclick="clearCart()" 
                                    class="text-red-600 hover:text-red-800 hover:bg-red-50 px-4 py-2 rounded-lg transition-colors font-bold">
                                🗑️ حذف جميع المنتجات
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-8 animate-fadeIn">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <span class="text-green-600 ml-3">💰</span>
                            ملخص الطلب
                        </h2>
                        
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">عدد المنتجات:</span>
                                <span class="cart-count font-bold text-lg">{{ $cartCount }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">المجموع الفرعي:</span>
                                <span class="cart-total font-bold text-lg text-green-600">{{ number_format($cartTotal, 0) }} ج.م</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">رسوم التوصيل:</span>
                                <span class="font-bold text-green-600">مجاناً 🚚</span>
                            </div>
                            <hr class="border-gray-200">
                            <div class="flex justify-between items-center text-xl">
                                <span class="font-bold text-gray-800">الإجمالي:</span>
                                <span class="cart-total font-bold text-green-600">{{ number_format($cartTotal, 0) }} ج.م</span>
                            </div>
                        </div>
                        
                        <!-- Checkout Button -->
                        <button onclick="showCheckoutModal()" 
                                class="w-full btn-primary text-white py-4 rounded-xl font-bold text-lg hover:shadow-lg transition-all">
                            إتمام الطلب 🛍️
                        </button>
                        
                        <!-- Continue Shopping -->
                        <a href="{{ route('landing') }}" 
                           class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-xl font-bold mt-4 transition-colors">
                            متابعة التسوق
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Unified Order Modal (borrowed from product page style) -->
    <div id="checkoutModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-end md:items-center justify-center px-3 sm:px-4 py-3 hidden">
        <div id="cartModalPanel" class="bg-white shadow-lg w-full max-w-[96vw] sm:max-w-md md:max-w-3xl lg:max-w-4xl rounded-t-2xl md:rounded-2xl overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-4 py-3 md:px-6 md:py-4 flex items-center justify-between border-b">
                <h3 class="text-lg font-bold text-gray-800">إتمام الطلب - السلة</h3>
                <button onclick="hideCheckoutModal()" class="text-gray-500 hover:text-gray-700 text-2xl leading-none">&times;</button>
            </div>
            <!-- Body -->
            <div class="p-4 md:p-6 overflow-y-auto space-y-6">
                <!-- Cart Summary inside modal -->
                <div class="bg-gray-50 rounded-xl p-4">
                    <h4 class="font-bold mb-3 text-gray-800 flex items-center"><span class="ml-2">🛒</span> ملخص المنتجات</h4>
                    <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                        @foreach($cart as $pid => $item)
                            <div class="flex justify-between items-center text-sm bg-white rounded p-2 border">
                                <div class="flex items-center gap-2">
                                    <img src="{{ $item['image_url'] }}" class="w-10 h-10 rounded object-cover" alt="{{ $item['name'] }}">
                                    <span class="font-medium text-gray-700">{{ $item['name'] }}</span>
                                </div>
                                <div class="text-left">
                                    <div class="text-purple-600 font-bold">{{ $item['quantity'] }} × {{ number_format($item['price'],0) }}</div>
                                    <div class="text-green-600 font-semibold">= {{ number_format($item['price'] * $item['quantity'],0) }} ج.م</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex justify-between items-center mt-4 pt-3 border-t font-bold text-lg">
                        <span class="text-gray-700">الإجمالي</span>
                        <span class="text-green-600">{{ number_format($cartTotal,0) }} ج.م</span>
                    </div>
                </div>
                <form id="checkoutForm" method="POST" action="{{ route('orders.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="cart_data" value='@json($cart)'>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 font-bold mb-1 text-sm">الاسم الكامل *</label>
                            <input name="customer_name" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm" />
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-1 text-sm">رقم الهاتف *</label>
                            <input name="phone" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-1 text-sm">العنوان التفصيلي *</label>
                        <textarea name="address" required rows="2" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm" placeholder="اكتب عنوانك بالكامل"></textarea>
                    </div>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 font-bold mb-1 text-sm">المحافظة</label>
                            <select name="governorate" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm">
                                <option value="">اختر المحافظة</option>
                                <option value="القاهرة">القاهرة</option>
                                <option value="الجيزة">الجيزة</option>
                                <option value="الإسكندرية">الإسكندرية</option>
                                <option value="الدقهلية">الدقهلية</option>
                                <option value="الشرقية">الشرقية</option>
                                <option value="القليوبية">القليوبية</option>
                                <option value="كفر الشيخ">كفر الشيخ</option>
                                <option value="الغربية">الغربية</option>
                                <option value="المنوفية">المنوفية</option>
                                <option value="البحيرة">البحيرة</option>
                                <option value="الإسماعيلية">الإسماعيلية</option>
                                <option value="بني سويف">بني سويف</option>
                                <option value="الفيوم">الفيوم</option>
                                <option value="المنيا">المنيا</option>
                                <option value="أسيوط">أسيوط</option>
                                <option value="سوهاج">سوهاج</option>
                                <option value="قنا">قنا</option>
                                <option value="الأقصر">الأقصر</option>
                                <option value="أسوان">أسوان</option>
                                <option value="البحر الأحمر">البحر الأحمر</option>
                                <option value="الوادي الجديد">الوادي الجديد</option>
                                <option value="مطروح">مطروح</option>
                                <option value="شمال سيناء">شمال سيناء</option>
                                <option value="جنوب سيناء">جنوب سيناء</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-1 text-sm">ملاحظات</label>
                            <textarea name="notes" rows="2" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm" placeholder="أي ملاحظات إضافية (اختياري)"></textarea>
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row gap-3 pt-2">
                        <button class="flex-1 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold py-3 rounded-lg shadow-md text-sm md:text-base">تأكيد الطلب ({{ number_format($cartTotal,0) }} ج.م)</button>
                        <button type="button" onclick="hideCheckoutModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3 rounded-lg text-sm md:text-base">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <div id="messageContainer" class="fixed top-4 right-4 z-50"></div>

    <script>
        // Update quantity
        function updateQuantity(productId, newQuantity) {
            if (newQuantity < 1 || newQuantity > 10) return;
            
        fetch('/cart/update', {
                method: 'POST',
                headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: newQuantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update quantity display
                    document.querySelector(`.quantity-display-${productId}`).textContent = newQuantity;
                    
                    // Update item total
                    document.querySelector(`.item-total-${productId}`).textContent = 
                        new Intl.NumberFormat('ar-EG').format(data.item_total) + ' ج.م';
                    
                    // Update cart totals
                    updateCartTotals(data.cart_count, data.cart_total);
                    
                    showMessage(data.message, 'success');
                } else {
                    showMessage('حدث خطأ في تحديث الكمية', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('حدث خطأ في تحديث الكمية', 'error');
            });
        }

        // Remove from cart
        function removeFromCart(productId) {
            if (!confirm('هل أنت متأكد من حذف هذا المنتج من السلة؟')) return;
            
        fetch('/cart/remove', {
                method: 'POST',
                headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove item from DOM
                    document.querySelector(`[data-product-id="${productId}"]`).remove();
                    
                    // Update cart totals
                    updateCartTotals(data.cart_count, data.cart_total);
                    
                    // If cart is empty, reload page
                    if (data.cart_count === 0) {
                        location.reload();
                    }
                    
                    showMessage(data.message, 'success');
                } else {
                    showMessage('حدث خطأ في حذف المنتج', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('حدث خطأ في حذف المنتج', 'error');
            });
        }

        // Clear cart
        function clearCart() {
            if (!confirm('هل أنت متأكد من حذف جميع المنتجات من السلة؟')) return;
            
        fetch('/cart/clear', {
                method: 'POST',
                headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    showMessage('حدث خطأ في حذف المنتجات', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('حدث خطأ في حذف المنتجات', 'error');
            });
        }

        // Update cart totals in UI
        function updateCartTotals(count, total) {
            document.querySelectorAll('.cart-count').forEach(el => {
                el.textContent = count;
            });
            document.querySelectorAll('.cart-total').forEach(el => {
                el.textContent = new Intl.NumberFormat('ar-EG').format(total) + ' ج.م';
            });
        }

        // Show/hide checkout modal
        function showCheckoutModal() {
            document.getElementById('checkoutModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function hideCheckoutModal() {
            document.getElementById('checkoutModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Show message
        function showMessage(message, type) {
            const container = document.getElementById('messageContainer');
            const messageDiv = document.createElement('div');
            messageDiv.className = `p-4 rounded-lg shadow-lg mb-4 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white animate-slideDown`;
            messageDiv.innerHTML = `
                <div class="flex items-center justify-between">
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-gray-200 mr-4">×</button>
                </div>
            `;
            container.appendChild(messageDiv);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (messageDiv.parentNode) {
                    messageDiv.remove();
                }
            }, 5000);
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideCheckoutModal();
            }
        });

        // Close modal on backdrop click
        document.getElementById('checkoutModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideCheckoutModal();
            }
        });
    </script>
</body>
</html>
