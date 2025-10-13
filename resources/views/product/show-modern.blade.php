<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>{{ $product->meta_title ?: $product->name }} - Fekra Store</title>

  @if($product->meta_description)
  <meta name="description" content="{{ $product->meta_description }}" />
  @endif

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet" />

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            'sans': ['Cairo', 'system-ui', 'sans-serif'],
            'display': ['Tajawal', 'serif'],
          },
          colors: {
            brand: {
              rose: '#FF6B9D',
              'rose-light': '#FFB8D2',
              'rose-dark': '#C9184A',
              cream: '#FFF8F3',
              beige: '#F5E6D3',
              charcoal: '#2D3142',
              slate: '#4F5D75',
              gold: '#D4AF37',
              'gold-light': '#F4E4C1',
              mint: '#A8DADC',
            },
          },
        },
      },
    };
  </script>

  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { 
      font-family: 'Cairo', system-ui, sans-serif; 
      background: linear-gradient(135deg, #FFF8F3 0%, #FFFFFF 100%);
      color: #2D3142;
      -webkit-font-smoothing: antialiased;
    }

    /* Modern Glassmorphism Navbar */
    .navbar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 50;
      background: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(20px);
      border-bottom: 1px solid rgba(255, 107, 157, 0.1);
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
    }

    /* Product Image Gallery */
    .image-gallery-container {
      position: sticky;
      top: 100px;
    }

    .main-product-image {
      aspect-ratio: 1;
      border-radius: 24px;
      overflow: hidden;
      background: linear-gradient(135deg, #F5E6D3 0%, #FFFFFF 100%);
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
    }

    .main-product-image:hover {
      transform: scale(1.02);
    }

    .thumbnail {
      aspect-ratio: 1;
      border-radius: 12px;
      overflow: hidden;
      cursor: pointer;
      transition: all 0.3s ease;
      border: 2px solid transparent;
    }

    .thumbnail:hover {
      transform: scale(1.05);
      border-color: #FF6B9D;
    }

    .thumbnail.active {
      border-color: #FF6B9D;
      box-shadow: 0 0 0 4px rgba(255, 107, 157, 0.2);
    }

    /* Modern Buttons */
    .btn-primary {
      background: linear-gradient(135deg, #FF6B9D 0%, #C9184A 100%);
      color: white;
      padding: 18px 40px;
      border-radius: 16px;
      font-weight: 700;
      font-size: 18px;
      border: none;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 10px 30px rgba(255, 107, 157, 0.3);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 15px 40px rgba(255, 107, 157, 0.4);
    }

    .btn-secondary {
      background: white;
      color: #FF6B9D;
      padding: 18px 40px;
      border-radius: 16px;
      font-weight: 700;
      font-size: 18px;
      border: 2px solid #FF6B9D;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .btn-secondary:hover {
      background: #FF6B9D;
      color: white;
      transform: translateY(-2px);
    }

    /* Price Tag */
    .price-tag {
      background: linear-gradient(135deg, #FF6B9D 0%, #C9184A 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-size: 48px;
      font-weight: 800;
    }

    /* Features Card */
    .feature-card {
      background: white;
      border-radius: 20px;
      padding: 24px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
      border: 1px solid rgba(255, 107, 157, 0.1);
    }

    .feature-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 15px 50px rgba(255, 107, 157, 0.15);
    }

    /* Badge */
    .badge {
      background: linear-gradient(135deg, #FF6B9D 0%, #C9184A 100%);
      color: white;
      padding: 8px 20px;
      border-radius: 50px;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      box-shadow: 0 6px 20px rgba(255, 107, 157, 0.3);
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.05); }
    }

    /* Animations */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .fade-in {
      animation: fadeIn 0.6s ease-out;
    }

    .fade-in-delay-1 {
      animation: fadeIn 0.6s ease-out 0.1s both;
    }

    .fade-in-delay-2 {
      animation: fadeIn 0.6s ease-out 0.2s both;
    }

    .fade-in-delay-3 {
      animation: fadeIn 0.6s ease-out 0.3s both;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
      .price-tag {
        font-size: 36px;
      }
      
      .btn-primary, .btn-secondary {
        padding: 14px 28px;
        font-size: 16px;
      }

      .image-gallery-container {
        position: relative;
        top: 0;
      }
    }
  </style>
</head>
<body class="pt-24">
  
  <!-- Modern Navbar -->
  <nav class="navbar">
    <div class="container mx-auto px-6 py-4">
      <div class="flex justify-between items-center">
        <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
          <div class="w-12 h-12 rounded-full p-2 shadow-lg backdrop-blur-sm border-2 border-purple-600/40 flex items-center justify-center transition-all duration-300" style="background: linear-gradient(135deg, #4c1d54 0%, #6b2c7a 100%);">
            <img src="{{ asset('images/fekra-new-logo.png') }}" alt="Fekra Store" class="w-8 h-8 object-contain transition group-hover:scale-110"/>
          </div>
          <span class="text-xl font-bold font-display bg-gradient-to-r from-brand-rose to-brand-rose-dark bg-clip-text text-transparent">
            Fekra Store
          </span>
        </a>

        <div class="flex items-center gap-4">
          <a href="{{ route('landing') }}" class="text-brand-charcoal hover:text-brand-rose transition font-semibold">
            الرئيسية
          </a>
          <a href="{{ route('cart.show') }}" class="relative p-3 bg-gradient-to-r from-brand-rose to-brand-rose-dark text-white rounded-full hover:shadow-lg transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <span id="cart-badge" class="absolute -top-1 -left-1 bg-brand-gold text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-md hidden">0</span>
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Product Section -->
  <section class="py-12">
    <div class="container mx-auto px-6">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        
        <!-- Image Gallery -->
        <div class="image-gallery-container fade-in">
          <div class="main-product-image mb-6">
            <img id="mainImage" src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover" loading="lazy" onerror="this.src='https://via.placeholder.com/800?text=No+Image'">
          </div>

          @if($product->gallery && count($product->gallery) > 0)
          <div class="grid grid-cols-4 gap-4">
            <div class="thumbnail active" onclick="changeMainImage('{{ $product->image_url }}', this)">
              <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover" onerror="this.style.display='none'">
            </div>
            @foreach($product->gallery as $image)
              @php $imageUrl = str_starts_with($image, 'http') ? $image : '/storage/' . ltrim($image, '/'); @endphp
              <div class="thumbnail" onclick="changeMainImage('{{ $imageUrl }}', this)">
                <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="w-full h-full object-cover" onerror="this.style.display='none'">
              </div>
            @endforeach
          </div>
          @endif
        </div>

        <!-- Product Info -->
        <div class="fade-in-delay-1">
          <!-- Product Title -->
          <h1 class="text-4xl lg:text-5xl font-bold text-brand-charcoal mb-4 font-display">
            {{ $product->name }}
          </h1>

          <!-- Rating -->
          @php
            $seed = $product->id * 37;
            $randomRating = (($seed % 11) + 40) / 10;
            $fullStars = floor($randomRating);
            $hasHalfStar = ($randomRating - $fullStars) >= 0.5;
            $reviewCount = (($seed % 14) + 2);
          @endphp
          <div class="flex items-center gap-3 mb-6">
            <div class="flex text-yellow-400">
              @for($i=0; $i<$fullStars; $i++)
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20" class="w-6 h-6">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.97c.3.921-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.197-1.539-1.118l1.287-3.97a1 1 0 00-.364-1.118L2.174 9.397c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.97z"/>
                </svg>
              @endfor
            </div>
            <span class="text-lg font-semibold text-brand-slate">{{ $randomRating }}</span>
            <span class="text-brand-slate">({{ $reviewCount }} مراجعات)</span>
          </div>

          <!-- Price -->
          <div class="flex items-center gap-4 mb-8">
            <span class="price-tag">{{ $product->formatted_price }}</span>
            @if($product->original_price && $product->original_price > $product->price)
              <span class="text-2xl text-gray-400 line-through">{{ $product->formatted_original_price }}</span>
              <span class="badge">-{{ $product->discount_percentage }}%</span>
            @endif
          </div>

          <!-- Urgency Badges -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="feature-card text-center">
              <div class="text-4xl mb-2">🚚</div>
              <h4 class="font-bold text-brand-charcoal mb-1">شحن مجاني</h4>
              <p class="text-sm text-brand-slate">عرض لفترة محدودة</p>
            </div>
            <div class="feature-card text-center">
              <div class="text-4xl mb-2">⚡</div>
              <h4 class="font-bold text-brand-charcoal mb-1">{{ $product->stock ?? 7 }} فقط متبقي</h4>
              <p class="text-sm text-brand-slate">اطلب الآن</p>
            </div>
            <div class="feature-card text-center">
              <div class="text-4xl mb-2">✓</div>
              <h4 class="font-bold text-brand-charcoal mb-1">متوفر الآن</h4>
              <p class="text-sm text-brand-slate">توصيل سريع</p>
            </div>
          </div>

          <!-- Description -->
          <div class="feature-card mb-6">
            <h3 class="text-2xl font-bold mb-4 text-brand-charcoal flex items-center gap-2">
              <span class="text-3xl">📝</span>
              الوصف
            </h3>
            <p class="text-brand-slate text-lg leading-relaxed">{{ $product->description }}</p>
          </div>

          <!-- Features -->
          @if($product->features)
          <div class="feature-card mb-8">
            <h3 class="text-2xl font-bold mb-4 text-brand-charcoal flex items-center gap-2">
              <span class="text-3xl">✨</span>
              المميزات الرئيسية
            </h3>
            <ul class="space-y-3">
              @foreach($product->features as $feature)
              <li class="flex items-start gap-3">
                <svg class="w-6 h-6 text-brand-rose flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="text-brand-slate text-lg">{{ $feature }}</span>
              </li>
              @endforeach
            </ul>
          </div>
          @endif

          <!-- Action Buttons -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <button onclick="openOrderModal()" class="btn-primary w-full">
              <span>اطلب الآن</span>
            </button>
            <button onclick="addToCart({{ $product->id }})" class="btn-secondary w-full">
              <span>أضف للسلة</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Order Modal -->
  <div id="orderModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4" onclick="if(event.target===this) closeOrderModal()">
    <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-8 animate-fadeIn">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-brand-charcoal font-display">إتمام الطلب</h2>
        <button onclick="closeOrderModal()" class="text-brand-slate hover:text-brand-rose transition">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <!-- Product Summary -->
      <div class="bg-gradient-to-r from-brand-cream to-white p-6 rounded-2xl mb-6 border border-brand-beige">
        <div class="flex items-center gap-4">
          <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-24 h-24 object-cover rounded-xl">
          <div class="flex-1">
            <h3 class="font-bold text-xl text-brand-charcoal mb-2">{{ $product->name }}</h3>
            <p class="text-3xl font-bold bg-gradient-to-r from-brand-rose to-brand-rose-dark bg-clip-text text-transparent">
              {{ $product->formatted_price }}
            </p>
          </div>
        </div>
      </div>

      <!-- Order Form -->
      <form id="productOrderForm" class="space-y-6">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="hidden" name="quantity" value="1">

        <div>
          <label class="block text-brand-charcoal font-semibold mb-2">الاسم الكامل *</label>
          <input type="text" name="customer_name" required class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-brand-rose focus:outline-none transition">
        </div>

        <div>
          <label class="block text-brand-charcoal font-semibold mb-2">رقم الهاتف *</label>
          <input type="tel" name="customer_phone" id="phoneInput" required class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-brand-rose focus:outline-none transition" placeholder="01xxxxxxxxx">
        </div>

        <div>
          <label class="block text-brand-charcoal font-semibold mb-2">المحافظة *</label>
          <select name="governorate" required class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-brand-rose focus:outline-none transition">
            <option value="">اختر المحافظة</option>
            <option value="Cairo">القاهرة</option>
            <option value="Giza">الجيزة</option>
            <option value="Alexandria">الإسكندرية</option>
            <option value="Dakahlia">الدقهلية</option>
            <option value="Red Sea">البحر الأحمر</option>
            <option value="Beheira">البحيرة</option>
            <option value="Fayoum">الفيوم</option>
            <option value="Gharbiya">الغربية</option>
            <option value="Ismailia">الإسماعيلية</option>
            <option value="Menofia">المنوفية</option>
            <option value="Minya">المنيا</option>
            <option value="Qaliubiya">القليوبية</option>
            <option value="New Valley">الوادي الجديد</option>
            <option value="Suez">السويس</option>
            <option value="Aswan">أسوان</option>
            <option value="Assiut">أسيوط</option>
            <option value="Beni Suef">بني سويف</option>
            <option value="Port Said">بورسعيد</option>
            <option value="Damietta">دمياط</option>
            <option value="Sharkia">الشرقية</option>
            <option value="South Sinai">جنوب سيناء</option>
            <option value="Kafr Al sheikh">كفر الشيخ</option>
            <option value="Matrouh">مطروح</option>
            <option value="Luxor">الأقصر</option>
            <option value="Qena">قنا</option>
            <option value="North Sinai">شمال سيناء</option>
            <option value="Sohag">سوهاج</option>
          </select>
        </div>

        <div>
          <label class="block text-brand-charcoal font-semibold mb-2">العنوان بالتفصيل *</label>
          <textarea name="address" required rows="3" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-brand-rose focus:outline-none transition" placeholder="اكتب عنوانك بالتفصيل..."></textarea>
        </div>

        <div>
          <label class="block text-brand-charcoal font-semibold mb-2">ملاحظات إضافية (اختياري)</label>
          <textarea name="notes" rows="2" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-brand-rose focus:outline-none transition" placeholder="أي ملاحظات إضافية..."></textarea>
        </div>

        <!-- Messages -->
        <div id="productSuccessMessage" class="hidden bg-green-50 border-2 border-green-500 text-green-700 px-6 py-4 rounded-xl font-semibold">
          ✅ تم إرسال طلبك بنجاح! جاري التحويل...
        </div>
        <div id="productErrorMessage" class="hidden bg-red-50 border-2 border-red-500 text-red-700 px-6 py-4 rounded-xl font-semibold">
          <span id="productErrorText">حدث خطأ. يرجى المحاولة مرة أخرى.</span>
        </div>

        <button type="submit" id="productSubmitBtn" class="btn-primary w-full">
          <span>تأكيد الطلب</span>
        </button>
      </form>
    </div>
  </div>

  <script>
    // Change Main Image
    function changeMainImage(url, element) {
      const mainImg = document.getElementById('mainImage');
      mainImg.style.opacity = '0';
      setTimeout(() => {
        mainImg.src = url;
        mainImg.style.opacity = '1';
      }, 150);
      
      document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
      if(element) element.closest('.thumbnail').classList.add('active');
    }

    // Modal Functions
    function openOrderModal() {
      document.getElementById('orderModal').classList.remove('hidden');
      document.getElementById('orderModal').classList.add('flex');
      document.body.style.overflow = 'hidden';
    }

    function closeOrderModal() {
      document.getElementById('orderModal').classList.add('hidden');
      document.getElementById('orderModal').classList.remove('flex');
      document.body.style.overflow = '';
    }

    // Phone Input Validation
    const phoneEl = document.getElementById('phoneInput');
    if(phoneEl) {
      phoneEl.addEventListener('input', function(e){
        let v = e.target.value.replace(/\D/g,'');
        if(v.length > 11) v = v.slice(0,11);
        e.target.value = v;
      });
    }

    // Form Submit
    const form = document.getElementById('productOrderForm');
    if(form) {
      form.addEventListener('submit', function(e){
        e.preventDefault();
        const btn = document.getElementById('productSubmitBtn');
        const success = document.getElementById('productSuccessMessage');
        const error = document.getElementById('productErrorMessage');
        
        success.classList.add('hidden');
        error.classList.add('hidden');
        btn.disabled = true;
        btn.querySelector('span').textContent = 'جاري الإرسال...';
        
        const formData = new FormData(this);
        fetch('{{ route("orders.store") }}', {
          method: 'POST',
          body: formData,
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        })
        .then(async r => {
          let data = null;
          try { data = await r.clone().json(); } catch(_) {}
          if (!r.ok) {
            if (data && data.errors) {
              const flat = Object.values(data.errors).flat().join(' | ');
              throw new Error(flat || data.message || 'فشل التحقق من البيانات');
            }
            throw new Error(data?.message || 'خطأ في الخادم ('+r.status+')');
          }
          return data;
        })
        .then(d => {
          if (d && d.success) {
            success.classList.remove('hidden');
            setTimeout(() => { window.location.href='{{ route("thanks") }}'; }, 1400);
          } else {
            throw new Error(d?.message || 'حدث خطأ غير متوقع');
          }
        })
        .catch(e => {
          error.classList.remove('hidden');
          document.getElementById('productErrorText').textContent = e.message;
        })
        .finally(() => {
          btn.disabled = false;
          btn.querySelector('span').textContent = 'تأكيد الطلب';
        });
      });
    }

    // Add to Cart
    function addToCart(productId) {
      fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json'
        },
        body: JSON.stringify({ product_id: productId, quantity: 1 })
      })
      .then(r => r.json())
      .then(data => {
        if(data.success) {
          const badge = document.getElementById('cart-badge');
          badge.textContent = data.cart_count;
          badge.classList.remove('hidden');
          alert('تم إضافة المنتج إلى السلة!');
        }
      })
      .catch(e => console.error(e));
    }
  </script>
</body>
</html>
