<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>فكره استور - أفضل المنتجات بأسعار لا تُقاوم</title>
    <meta name="description" content="اكتشف مجموعة مميزة من المنتجات عالية الجودة مع توصيل سريع وآمن لباب منزلك">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&family=Reem+Kufi:wght@600;700&display=swap" rel="stylesheet">

    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              'cairo': ['Cairo', 'sans-serif'],
            },
            colors: {
              brand: {
                white: '#FFFFFF',
                light: '#F5F5F5',
                light2: '#E5E5E5',
                dark: '#333333',
                darker: '#2D2D2D',
                blue: '#2563EB',
                navy: '#1E3A8A',
                green: '#10B981',
                orange: '#F97316',
                beige: '#FAFAF5'
              }
            },
            animation: {
              'float': 'float 6s ease-in-out infinite',
              'pulse-slow': 'pulse 3s ease-in-out infinite',
              'bounce-slow': 'bounce 2s infinite',
              'fade-in-up': 'fade-in-up 700ms ease both'
            },
            keyframes: {
              float: {
                '0%, 100%': { transform: 'translateY(0px)' },
                '50%': { transform: 'translateY(-20px)' },
              },
              'fade-in-up': {
                '0%': { opacity: 0, transform: 'translateY(24px)' },
                '100%': { opacity: 1, transform: 'translateY(0)' }
              }
            },
            boxShadow: {
              'soft': '0 10px 30px rgba(0,0,0,0.06)'
            },
            borderRadius: {
              '2xl': '1.25rem'
            }
          }
        }
      }
    </script>

    <style>
      body { font-family: 'Cairo', sans-serif; }
      /* Brand Kufi style (approximation of modern customized kufi) */
      .font-brand-kufi { font-family: 'Reem Kufi', 'Cairo', sans-serif; font-weight:700; letter-spacing:.5px; }
      .text-logo-shadow { text-shadow: 0 0 0 currentColor, 0 .5px 0 currentColor; }
      .glass-effect { backdrop-filter: blur(10px); background: rgba(255,255,255,0.08); }
      .hover-scale { transition: transform .3s ease; }
      .hover-scale:hover { transform: scale(1.04); }
      .product-card { transition: transform .3s ease, box-shadow .3s ease; transform: translateY(0); }
      .product-card:hover { transform: translateY(-8px); box-shadow: 0 16px 36px rgba(0,0,0,.08); }
      .tw-cursor { display:inline-block; width:1ch; animation: blink 1s steps(1,end) infinite; }
      @keyframes blink { 0%,49% {opacity:1} 50%,100% {opacity:0} }
      @media (prefers-reduced-motion: reduce) { .tw-cursor { animation: none } }
    </style>
    <!-- TikTok Pixel Base Code -->
    <script>
    !function (w, d, t) {
      w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(
    var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js",o=n&&n.partner;ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=r,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};n=document.createElement("script")
    ;n.type="text/javascript",n.async=!0,n.src=r+"?sdkid="+e+"&lib="+t;e=document.getElementsByTagName("script")[0];e.parentNode.insertBefore(n,e)};

      ttq.load('D33EPL3C77U1Q4B3F7HG');
      ttq.page();
    }(window, document, 'ttq');
    </script>
  <!-- Google Site Verification -->
  <meta name="google-site-verification" content="JdThZmvAVdqI96t_f_RCCCPa7V8QgYQhSG2-FdmkHWg" />
</head>
<body class="bg-brand-light font-cairo text-brand-darker opacity-0 transition-opacity duration-700">

  <!-- Success Message -->
  @if(session('success'))
  <div id="success-message" class="fixed top-4 right-4 bg-brand-green text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300">
    <div class="flex items-center">
      <span class="text-xl ml-2">✅</span>
      <span>{{ session('success') }}</span>
      <button onclick="closeSuccessMessage()" class="mr-4 text-white/90 hover:text-white">×</button>
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
      <button onclick="closeErrorMessage()" class="mr-4 text-white/90 hover:text-white">×</button>
    </div>
  </div>
  @endif

  <!-- Navigation Bar -->
  <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 bg-white/85 backdrop-blur-md border-b border-brand-light2 shadow-sm transition-all duration-300">
    <div class="container mx-auto px-4">
      <div class="flex justify-between items-center py-3">
        <!-- Logo -->
        <div class="flex items-center gap-3">
          @if(file_exists(public_path('images/fekra-logo.png')))
            <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
              <img src="{{ asset('images/fekra-logo.png') }}" alt="فكره استور" class="h-12 w-auto drop-shadow-sm group-hover:scale-105 transition"/>
              <span class="text-2xl md:text-3xl font-extrabold font-brand-kufi text-logo-shadow leading-none text-brand-navy group-hover:text-brand-blue transition">فكره استور</span>
            </a>
          @else
            <a href="{{ route('landing') }}" class="text-xl font-extrabold text-brand-navy">فكره استور</a>
          @endif
        </div>

        <!-- Links -->
        <div class="hidden md:flex items-center gap-8">
          <a href="#products" class="text-brand-darker hover:text-brand-blue font-semibold transition">المنتجات</a>
          <a href="#features" class="text-brand-darker hover:text-brand-blue font-semibold transition">المميزات</a>
          <a href="#testimonials" class="text-brand-darker hover:text-brand-blue font-semibold transition">آراء العملاء</a>
        </div>

        <!-- Cart -->
        <div class="flex items-center gap-4">
          <a href="{{ route('cart.show') }}" class="relative bg-brand-blue hover:bg-blue-700 text-white p-3 rounded-full transition shadow-soft">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5-6M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
            </svg>
            <span id="cart-badge" class="absolute -top-2 -right-2 bg-brand-orange text-white text-xs font-bold px-2 py-0.5 rounded-full min-w-[1.5rem] text-center hidden">0</span>
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="relative overflow-hidden min-h-screen flex items-center pt-24 text-white" style="background: linear-gradient(135deg, #1E3A8A 0%, #2563EB 100%);">
    <!-- Background floating shapes -->
    <div class="absolute inset-0 opacity-20 pointer-events-none">
      <div class="absolute top-10 left-10 w-20 h-20 bg-white/20 rounded-full animate-bounce-slow"></div>
      <div class="absolute top-1/3 right-20 w-16 h-16 bg-white/30 rounded-full animate-float"></div>
      <div class="absolute bottom-20 left-1/4 w-12 h-12 bg-white/20 rounded-full animate-pulse-slow"></div>
      <div class="absolute bottom-1/3 right-10 w-24 h-24 bg-white/10 rounded-full animate-float"></div>
    </div>

    <div class="container mx-auto px-4 py-16 relative z-10">
      <div class="grid lg:grid-cols-2 gap-12 items-center">
        <div class="text-center lg:text-right animate-fade-in-up">
          <h1 class="text-5xl lg:text-7xl font-extrabold leading-tight mb-6">
            أفضل المنتجات بأسعار 
            <span id="typewriter" class="text-brand-orange"></span><span class="tw-cursor">|</span>
          </h1>
          <p class="text-xl lg:text-2xl mb-10 text-white/90">
            اكتشف مجموعة مميزة من المنتجات عالية الجودة مع توصيل سريع وآمن لباب منزلك
          </p>

          <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
            <a href="#products" class="bg-brand-blue hover:bg-blue-700 text-white font-bold py-4 px-8 rounded-full text-lg transition shadow-soft hover:shadow-lg">
              🛍️ تسوق الآن
            </a>
            <a href="#features" class="glass-effect border border-white/30 text-white font-bold py-4 px-8 rounded-full text-lg transition hover:bg-white hover:text-brand-darker">
              ✨ اعرف المزيد
            </a>
          </div>

          <!-- Trust Indicators -->
          <div class="mt-12 flex flex-wrap justify-center lg:justify-start gap-3 text-sm">
            <div class="flex items-center bg-white/10 px-4 py-2 rounded-full">
              <span class="ml-2">✅</span><span>توصيل مجاني</span>
            </div>
            <div class="flex items-center bg-white/10 px-4 py-2 rounded-full">
              <span class="ml-2">🚚</span><span>شحن سريع</span>
            </div>
            <div class="flex items-center bg-white/10 px-4 py-2 rounded-full">
              <span class="ml-2">💯</span><span>ضمان الجودة</span>
            </div>
          </div>
        </div>

        <div class="text-center lg:text-left">
          <div class="relative animate-fade-in-up" style="animation-delay:120ms">
            <figure class="group bg-white/12 rounded-3xl p-4 sm:p-6 lg:p-8 backdrop-blur-xl border border-white/20 animate-float shadow-soft overflow-hidden max-w-xl mx-auto">
              <img
                src="https://www.heropay.eu/blog-images/553938487/vendre-shopify-hero.webp"
                alt="واجهة متجر إلكتروني احترافية تعرض منتجات مميزة"
                loading="lazy"
                class="w-full h-auto rounded-2xl object-cover ring-1 ring-white/30 shadow-xl group-hover:scale-[1.02] transition duration-500"/>
              <figcaption class="sr-only">صورة عرض توضيحي لمتجر إلكتروني</figcaption>
              <!-- Sub badges -->
              <div class="absolute top-2 left-2 flex gap-2 opacity-0 group-hover:opacity-100 transition">
                <span class="bg-brand-blue/80 text-white text-xs font-bold px-2 py-1 rounded-full shadow">متجر آمن</span>
                <span class="bg-brand-green/80 text-white text-xs font-bold px-2 py-1 rounded-full shadow">دفع موثوق</span>
              </div>
            </figure>
            <div class="absolute -top-4 -right-4 bg-brand-orange text-white px-4 py-2 rounded-full font-bold text-sm animate-bounce shadow-soft">
              خصم 50%
            </div>
            <div class="absolute -bottom-4 -left-4 bg-brand-green text-white px-4 py-2 rounded-full font-bold text-sm animate-pulse shadow-soft">
            توصيل مجاني 
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
      <div class="w-6 h-10 border-2 border-white/80 rounded-full flex justify-center">
        <div class="w-1 h-3 bg-white rounded-full mt-2 animate-pulse"></div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section id="features" class="py-20 bg-brand-beige">
    <div class="container mx-auto px-4">
      <div class="text-center mb-14">
        <h2 class="text-4xl lg:text-5xl font-extrabold text-brand-darker mb-4">لماذا تختارنا؟</h2>
        <p class="text-lg text-brand-dark/80 max-w-3xl mx-auto">نحن نقدم أفضل تجربة تسوق عبر الإنترنت مع ضمان الجودة والأسعار التنافسية</p>
      </div>

      <div class="grid md:grid-cols-3 gap-6">
        <div class="text-center p-8 rounded-2xl bg-white hover-scale shadow-soft animate-fade-in-up">
          <div class="text-6xl mb-4 animate-float">⚡</div>
          <h3 class="text-2xl font-bold mb-2 text-brand-navy">توصيل مجاني </h3>
          <p class="text-brand-darker/80 leading-relaxed">توصيل خلال 1-3 أيام عمل لجميع المحافظات مع إمكانية التتبع المباشر للشحنة</p>
        </div>
        <div class="text-center p-8 rounded-2xl bg-white hover-scale shadow-soft animate-fade-in-up" style="animation-delay:120ms">
          <div class="text-6xl mb-4 animate-float">💯</div>
          <h3 class="text-2xl font-bold mb-2 text-brand-navy">جودة مضمونة</h3>
          <p class="text-brand-darker/80 leading-relaxed">منتجات أصلية 100% بأعلى معايير الجودة مع ضمان الاستبدال والإرجاع</p>
        </div>
        <div class="text-center p-8 rounded-2xl bg-white hover-scale shadow-soft animate-fade-in-up" style="animation-delay:240ms">
          <div class="text-6xl mb-4 animate-float">🔒</div>
          <h3 class="text-2xl font-bold mb-2 text-brand-navy">الدفع عند الاستلام

          </h3>
          <p class="text-brand-darker/80 leading-relaxed">الدفع عند الاستلام أو بالطرق الآمنة مع حماية كاملة لبياناتك</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Products Section -->
  <section id="products" class="py-20 bg-brand-light">
    <div class="container mx-auto px-4">
      <div class="text-center mb-14">
        <h2 class="text-4xl lg:text-5xl font-extrabold text-brand-darker mb-4">منتجاتنا المميزة</h2>
        <p class="text-lg text-brand-dark/80 max-w-3xl mx-auto">تشكيلة منتقاة بعناية من أفضل المنتجات بأسعار تنافسية وجودة عالية</p>
      </div>

      @if($products->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          @foreach($products as $product)
          <div class="product-card bg-white rounded-2xl shadow-soft overflow-hidden animate-fade-in-up">
            <div class="relative h-64 overflow-hidden">
              <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover" loading="lazy" onerror="this.src='https://via.placeholder.com/400x400?text=No+Image'">

              @if($product->discount_percentage > 0)
              <div class="absolute top-4 right-4">
                <span class="bg-brand-orange text-white px-3 py-1 rounded-full text-sm font-bold animate-pulse">
                  خصم {{ $product->discount_percentage }}%
                </span>
              </div>
              @endif

              <!-- Quick View Overlay -->
              <div class="absolute inset-0 bg-black/50 opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                <a href="{{ route('product.show', $product->slug) }}" class="bg-white text-brand-darker px-6 py-3 rounded-full font-bold hover:bg-brand-light transition">
                  عرض التفاصيل
                </a>
              </div>
            </div>

            <div class="p-6">
              <h3 class="text-xl font-extrabold mb-2 text-brand-darker leading-tight">{{ $product->name }}</h3>
              <!-- Consistent star rating based on product ID (4.0-5.0) -->
              @php
                $seed = $product->id * 37; // Use product ID for consistency
                $randomRating = (($seed % 11) + 40) / 10; // Generates 4.0-5.0
                $fullStars = floor($randomRating);
                $hasHalfStar = ($randomRating - $fullStars) >= 0.5;
              @endphp
              <div class="flex items-center gap-2 mb-3">
                <span class="flex text-yellow-400 text-lg">
                  @for($i=0; $i<$fullStars; $i++)
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20" class="w-4 h-4 inline"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.97c.3.921-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.197-1.539-1.118l1.287-3.97a1 1 0 00-.364-1.118L2.174 9.397c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.97z"/></svg>
                  @endfor
                  @if($hasHalfStar)
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20" class="w-4 h-4 inline"><defs><linearGradient id="half-{{ $product->id }}"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="#e5e7eb"/></linearGradient></defs><path fill="url(#half-{{ $product->id }})" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.97c.3.921-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.197-1.539-1.118l1.287-3.97a1 1 0 00-.364-1.118L2.174 9.397c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.97z"/></svg>
                  @endif
                  @for($i=0; $i<(5-$fullStars-($hasHalfStar?1:0)); $i++)
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#e5e7eb" viewBox="0 0 20 20" class="w-4 h-4 inline"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.97c.3.921-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.197-1.539-1.118l1.287-3.97a1 1 0 00-.364-1.118L2.174 9.397c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.97z"/></svg>
                  @endfor
                </span>
                <span class="text-xs text-gray-600">{{ $randomRating }}</span>
              </div>
              <p class="text-brand-darker/70 mb-4 leading-relaxed">{{ Str::limit($product->description, 100) }}</p>

              <div class="flex justify-between items-center mb-4">
                <span class="text-2xl font-extrabold text-brand-blue">{{ $product->formatted_price }}</span>
                @if($product->original_price && $product->original_price > $product->price)
                  <span class="text-lg text-brand-darker/50 line-through">{{ $product->formatted_original_price }}</span>
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
                    <div class="flex items-center text-sm text-brand-darker/80 mb-1">
                      <span class="text-brand-green ml-2">✓</span>
                      <span>{{ trim($feature) }}</span>
                    </div>
                  @endif
                @endforeach
              </div>
              @endif

              <div class="flex gap-2">
                <!-- Add to Cart Button (Green) -->
                <button onclick="addToCart({{ $product->id }})" class="flex-1 bg-brand-green hover:bg-emerald-600 text-white font-bold py-3 px-4 rounded-xl transition shadow-soft hover:shadow-lg">
                  🛒 أضف للسلة
                </button>
                <!-- Buy Now Button (Blue) -->
                <a href="{{ route('product.show', $product->slug) }}" class="flex-1 bg-brand-blue hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl text-center transition shadow-soft hover:shadow-lg">
                  اطلب الآن
                </a>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      @else
        <div class="text-center py-16">
          <div class="text-8xl mb-6 animate-bounce">📦</div>
          <h3 class="text-3xl font-extrabold text-brand-darker mb-3">قريباً منتجات جديدة!</h3>
          <p class="text-lg text-brand-darker/70 mb-8">نعمل على إضافة منتجات مميزة لك</p>
          <a href="#" class="bg-brand-blue hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-full transition">اشترك للحصول على التحديثات</a>
        </div>
      @endif
    </div>
  </section>

  <!-- Testimonials Section -->
  <section id="testimonials" class="py-20 bg-brand-beige">
    <div class="container mx-auto px-4">
      <div class="text-center mb-14">
        <h2 class="text-4xl lg:text-5xl font-extrabold text-brand-darker mb-4">آراء عملاؤنا</h2>
        <p class="text-lg text-brand-dark/80 max-w-3xl mx-auto">ثقة العملاء أهم حاجة — بعض المراجعات الحقيقية من عملاء اشتروا واستلموا منتجاتهم</p>
      </div>

      <div class="grid md:grid-cols-3 gap-6">
        <!-- Card 1 -->
        <div class="bg-white rounded-2xl p-8 shadow-soft hover-scale">
          <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-full bg-brand-light2 flex items-center justify-center text-brand-navy font-bold">م</div>
            <div>
              <p class="font-bold text-brand-darker leading-tight">محمد. أ</p>
              <p class="text-sm text-brand-darker/60">القاهرة</p>
            </div>
          </div>
          <div class="flex items-center text-xl mb-3" aria-label="5 من 5">⭐️⭐️⭐️⭐️⭐️</div>
          <p class="text-brand-darker/80 leading-relaxed">تجربة ممتازة! المنتج وصل بسرعة وبجودة عالية جدًا. التعامل محترم وخدمة العملاء سريعة في الرد.</p>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-2xl p-8 shadow-soft hover-scale">
          <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-full bg-brand-light2 flex items-center justify-center text-brand-navy font-bold">ن</div>
            <div>
              <p class="font-bold text-brand-darker leading-tight">نادين. س</p>
              <p class="text-sm text-brand-darker/60">الإسكندرية</p>
            </div>
          </div>
          <div class="flex items-center text-xl mb-3" aria-label="4 من 5">⭐️⭐️⭐️⭐️☆</div>
          <p class="text-brand-darker/80 leading-relaxed">الأسعار حلوة جدًا مقارنة بالسوق، والتغليف كان ممتاز. أكيد هكرر الطلب.</p>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-2xl p-8 shadow-soft hover-scale">
          <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-full bg-brand-light2 flex items-center justify-center text-brand-navy font-bold">أ</div>
            <div>
              <p class="font-bold text-brand-darker leading-tight">أحمد. ك</p>
              <p class="text-sm text-brand-darker/60">طنطا</p>
            </div>
          </div>
          <div class="flex items-center text-xl mb-3" aria-label="5 من 5">⭐️⭐️⭐️⭐️⭐️</div>
          <p class="text-brand-darker/80 leading-relaxed">طلبت مرتين لحد دلوقتي وكل مرة الالتزام في المواعيد والجودة عاليين جدًا. شكراً ليكم.</p>
        </div>
      </div>

      <div class="text-center mt-12">
        <a href="#products" class="inline-block bg-white text-brand-darker font-bold py-3 px-8 rounded-full shadow-soft hover:shadow-lg transition border border-brand-light2">
          اكتشف المنتجات الآن
        </a>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-brand-darker text-brand-light pt-14 pb-10">
    <div class="container mx-auto px-4">
      <div class="grid md:grid-cols-4 gap-10">
        <div class="md:col-span-2 animate-fade-in-up">
          @if(file_exists(public_path('images/fekra-logo.png')))
            <div class="flex items-center gap-3 mb-4">
              <img src="{{ asset('images/fekra-logo.png') }}" alt="فكره استور" class="h-16 w-auto"/>
              <h3 class="text-3xl md:text-4xl font-extrabold font-brand-kufi text-logo-shadow leading-tight">فكره استور</h3>
            </div>
          @else
            <h3 class="text-2xl font-extrabold mb-4">فكره استور</h3>
          @endif
          <p class="text-brand-light/80 leading-relaxed">نقدم منتجات مختارة بعناية بأسعار تنافسية وخدمة عملاء سريعة. رؤيتنا هي تجربة تسوق بسيطة، آمنة، وموثوقة.</p>
        </div>

        <div class="animate-fade-in-up" style="animation-delay:120ms">
          <h4 class="text-lg font-bold mb-4 text-white">روابط سريعة</h4>
          <ul class="space-y-2 text-brand-light/80">
            <li><a href="#products" class="hover:text-white transition">المنتجات</a></li>
            <li><a href="#features" class="hover:text-white transition">المميزات</a></li>
            <li><a href="#testimonials" class="hover:text-white transition">آراء العملاء</a></li>
            <li><a href="{{ route('cart.show') }}" class="hover:text-white transition">السلة</a></li>
          </ul>
        </div>

        <div class="animate-fade-in-up" style="animation-delay:240ms">
          <h4 class="text-lg font-bold mb-4 text-white">تواصل معنا</h4>
          <ul class="space-y-2 text-brand-light/80">
            
            <li>📍 القاهرة، مصر</li>
            <li class="flex gap-2 pt-2">
              <a href="https://www.tiktok.com/@fekra__store?_t=ZS-8zbjerbuBxY&_r=1" class="bg-brand-green hover:bg-emerald-600 p-2 rounded-full" aria-label="تيك توك">📱</a>
              
            </li>
          </ul>
        </div>
      </div>

      <div class="border-t border-white/10 mt-10 pt-6 flex flex-col md:flex-row items-center justify-between text-brand-light/70 gap-3">
        <p>&copy; 2025 فكره استور. جميع الحقوق محفوظة.</p>
        <div class="flex items-center gap-3 text-sm">
          <span class="bg-white/10 px-3 py-1 rounded-full">سياسة الخصوصية</span>
          <span class="bg-white/10 px-3 py-1 rounded-full">الشروط والأحكام</span>
        </div>
      </div>
    </div>
  </footer>

  <script>
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        if (this.getAttribute('href') === '#') return;
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      });
    });

    // Intersection animations (progressive reveal)
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate-fade-in-up');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

    window.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.product-card, .hover-scale').forEach(el => observer.observe(el));
      updateCartCount();

      // Slide-in messages
      const successMessage = document.getElementById('success-message');
      const errorMessage = document.getElementById('error-message');
      if (successMessage) setTimeout(() => successMessage.style.transform = 'translateX(0)', 100);
      if (errorMessage) setTimeout(() => errorMessage.style.transform = 'translateX(0)', 100);
      if (successMessage) setTimeout(closeSuccessMessage, 5000);
      if (errorMessage) setTimeout(closeErrorMessage, 7000);
    });

    // Phone input guard (optional if exists)
    const phoneEl = document.getElementById('phone');
    if (phoneEl) {
      phoneEl.addEventListener('input', (e) => {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 11) value = value.slice(0, 11);
        e.target.value = value;
      });
    }

    // Success/Error message close helpers
    function closeSuccessMessage() {
      const el = document.getElementById('success-message');
      if (el) { el.style.transform = 'translateX(100%)'; setTimeout(() => el.remove(), 300); }
    }
    function closeErrorMessage() {
      const el = document.getElementById('error-message');
      if (el) { el.style.transform = 'translateX(100%)'; setTimeout(() => el.remove(), 300); }
    }

    // Cart API helpers
    function addToCart(productId, quantity = 1) {
      fetch('/cart/add', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ product_id: productId, quantity })
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          updateCartCount();
          showCartMessage(data.message, 'success');
          const cartIcon = document.querySelector('#cart-badge').parentElement;
          cartIcon.classList.add('animate-bounce');
          setTimeout(() => cartIcon.classList.remove('animate-bounce'), 600);
        } else {
          showCartMessage('حدث خطأ في إضافة المنتج للسلة', 'error');
        }
      })
      .catch(() => showCartMessage('حدث خطأ في إضافة المنتج للسلة', 'error'));
    }

    function updateCartCount() {
      fetch('/cart/count')
        .then(r => r.json())
        .then(data => {
          const badge = document.getElementById('cart-badge');
          if (!badge) return;
          if (data.cart_count > 0) {
            badge.textContent = data.cart_count;
            badge.classList.remove('hidden');
          } else {
            badge.classList.add('hidden');
          }
        })
        .catch(() => {});
    }

    function showCartMessage(message, type) {
      const container = document.createElement('div');
      container.className = 'fixed top-24 right-4 z-50';
      const msg = document.createElement('div');
      msg.className = `p-4 rounded-lg shadow-lg text-white transform translate-x-full transition-transform duration-300 ${type === 'success' ? 'bg-brand-green' : 'bg-red-500'}`;
      msg.innerHTML = `<div class="flex items-center"><span class="text-xl ml-2">${type === 'success' ? '✅' : '❌'}</span><span>${message}</span><button class="mr-4 text-white/90 hover:text-white" onclick="this.closest('.fixed').remove()">×</button></div>`;
      container.appendChild(msg);
      document.body.appendChild(container);
      setTimeout(() => { msg.style.transform = 'translateX(0)'; }, 100);
      setTimeout(() => { msg.style.transform = 'translateX(100%)'; setTimeout(() => container.remove(), 300); }, 3000);
    }

    // Navbar scroll effect (transparent on scroll down)
    window.addEventListener('scroll', () => {
      const navbar = document.getElementById('navbar');
      if (window.scrollY > 10) {
        // اجعله شفاف عند النزول
        navbar.classList.add('bg-transparent', 'backdrop-blur-0', 'border-transparent');
        navbar.classList.remove('bg-white/85', 'backdrop-blur-md', 'border-brand-light2', 'shadow-sm');
      } else {
        // يرجع للوضع الأصلي أعلى الصفحة
        navbar.classList.add('bg-white/85', 'backdrop-blur-md', 'border-brand-light2', 'shadow-sm');
        navbar.classList.remove('bg-transparent', 'backdrop-blur-0', 'border-transparent');
      }
    });
  </script>
  
  <!-- Page & Hero Typewriter Animations -->
  <script>
    // Ensure page fade-in
    document.addEventListener('DOMContentLoaded', () => {
      document.body.classList.remove('opacity-0');
    });

    // Typewriter effect for hero headline
    (function(){
      const el = () => document.getElementById('typewriter');
      const phrases = ['لا تُقاوم', 'بتوصيل سريع', 'وجودة مضمونة'];
      const typeDelay = 90;
      const holdDelay = 1200;
      const eraseDelay = 50;
      let i = 0, j = 0, typing = true;

      function tick(){
        const node = el();
        if (!node) return;
        const current = phrases[i];
        if (typing) {
          node.textContent = current.slice(0, j+1);
          j++;
          if (j === current.length) { typing = false; setTimeout(tick, holdDelay); return; }
          setTimeout(tick, typeDelay);
        } else {
          node.textContent = current.slice(0, j-1);
          j--;
          if (j === 0) { typing = true; i = (i + 1) % phrases.length; }
          setTimeout(tick, eraseDelay);
        }
      }
      document.addEventListener('DOMContentLoaded', () => setTimeout(tick, 400));
    })();
  </script>
</body>
</html>
