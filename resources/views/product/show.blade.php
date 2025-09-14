<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>{{ $product->meta_title ?: $product->name }} - فكره استور</title>

  @if($product->meta_description)
  <meta name="description" content="{{ $product->meta_description }}" />
  @endif

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&family=Reem+Kufi:wght@600;700&display=swap" rel="stylesheet" />

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { cairo: ["Cairo", "sans-serif"] },
          colors: {
            white: "#FFFFFF",
            lightgray: "#F5F5F5",
            lightgray2: "#E5E5E5",
            charcoal: "#2D2D2D",
            charcoalText: "#333333",
            brandBlue: "#2563EB",
            brandIndigo: "#4F46E5",
            brandGreen: "#10B981",
            brandOrange: "#F97316",
            softBeige: "#FAFAF5",
            mutedNavy: "#1E3A8A",
          },
          boxShadow: {
            soft: "0 8px 25px rgba(0,0,0,0.06)",
            lift: "0 12px 30px rgba(37,99,235,0.15)",
          },
          animation: {
            fadeIn: "fadeIn .45s ease-out both",
            fadeInUp: "fadeInUp .5s ease-out both",
            scaleIn: "scaleIn .35s ease-out both",
            shimmer: "shimmer 2s linear infinite",
            modalIn: "modalIn .35s ease-out both",
            pulseSoft: "pulseSoft 1.8s ease-in-out infinite",
            slideDown: "slideDown .3s ease-out both",
            ripple: "ripple .6s ease-out",
          },
          keyframes: {
            fadeIn: { "0%": { opacity: 0 }, "100%": { opacity: 1 } },
            fadeInUp: { "0%": { opacity: 0, transform: "translateY(14px)" }, "100%": { opacity: 1, transform: "translateY(0)" } },
            scaleIn: { "0%": { opacity: 0, transform: "scale(.96)" }, "100%": { opacity: 1, transform: "scale(1)" } },
            shimmer: { "0%": { backgroundPosition: "-100% 0" }, "100%": { backgroundPosition: "200% 0" } },
            modalIn: { "0%": { opacity: 0, transform: "translateY(10px) scale(.98)" }, "100%": { opacity: 1, transform: "translateY(0) scale(1)" } },
            pulseSoft: { "0%,100%": { transform: "translateY(0)" }, "50%": { transform: "translateY(-2px)" } },
            slideDown: { "0%": { opacity: 0, transform: "translateY(-8px)" }, "100%": { opacity: 1, transform: "translateY(0)" } },
            ripple: { "0%": { boxShadow: "0 0 0 0 rgba(37,99,235,.45)" }, "100%": { boxShadow: "0 0 0 16px rgba(37,99,235,0)" } },
          },
        },
      },
    };
  </script>

  <style>
  * { box-sizing: border-box; }
    body { font-family: 'Cairo', sans-serif; background:#f9fafb; color:#333333; }
  .font-brand-kufi { font-family: 'Reem Kufi', 'Cairo', sans-serif; font-weight:700; letter-spacing:.5px; }
  .text-logo-shadow { text-shadow: 0 0 0 currentColor, 0 .5px 0 currentColor; }
    img { max-width: 100%; height: auto; }

    /* ===== Navbar (with logo) states ===== */
    .navbar { position: fixed; top: 0; inset-inline: 0; z-index: 50; transition: background-color .25s ease, box-shadow .25s ease, color .25s ease; }
    .nav-solid { background: linear-gradient(135deg, #1E3A8A 0%, #2563EB 100%); color: #fff; box-shadow: 0 6px 18px rgba(0,0,0,.08); }
    .nav-transparent { background: transparent; color: #333; box-shadow: none; }
    .navbar a { color: inherit; }
    .nav-solid .cart-btn { background: rgba(255,255,255,.12); color: #fff; }
    .nav-solid .cart-btn:hover { background: rgba(255,255,255,.22); }
    .nav-transparent .cart-btn { background:#fff; color:#333; border:1px solid #E5E5E5; }
    .nav-transparent .cart-btn:hover { background:#F5F5F5; }

    .gallery-image { cursor: pointer; transition: transform .2s ease; }
    .gallery-image:hover { transform: scale(1.03); }
    .main-image { max-height: 500px; object-fit: cover; }

    .order-btn, .btn { position: relative; overflow: hidden; }
    .btn .shimmer-bg, .order-btn .shimmer-bg { background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,.25) 50%, rgba(255,255,255,0) 100%); background-size: 200% 100%; }

    /* ===== Modal Overlay ===== */
  #orderModal { transition: opacity .2s ease; }
  /* Stabilize modal to prevent repaint jitter */
  #modalPanel { will-change: transform; transform: translateZ(0); }

    /* ===== Modal Panel (fix height + internal scroll) ===== */
    #modalPanel{
      max-height: var(--panel-max, min(85vh, 85svh));
      width: 100%;
      overflow: hidden;
      display: flex; flex-direction: column;
      -webkit-overflow-scrolling: touch;
      border-top-left-radius: 1rem; border-top-right-radius: 1rem;
    }

    @media (max-width: 480px) {
      #modalPanel { position: fixed !important; inset: 0 !important; width: 100vw !important; height: 100svh !important; max-height: 100svh !important; margin: 0 !important; border-radius: 0 !important; display: flex !important; flex-direction: column !important; }
      #orderModal { align-items: stretch !important; justify-content: flex-start !important; }
      .panel-header { padding: .5rem .75rem !important; }
      .panel-body { padding: .5rem .75rem .75rem .75rem !important; overflow-y: auto !important; -webkit-overflow-scrolling: touch !important; }
      .panel-body .space-y-3 > * + * { margin-top: .45rem !important; }
      .panel-body .space-y-4 > * + * { margin-top: .55rem !important; }
      .panel-body input, .panel-body select, .panel-body textarea { padding: .45rem .6rem !important; font-size: .85rem !important; }
      .panel-body label { font-size: .72rem !important; margin-bottom: .2rem !important; }
      .panel-body textarea { min-height: 48px !important; }
      .panel-body .bg-blue-50 img { width: 2.6rem !important; height: 2.6rem !important; }
      .panel-body .bg-blue-50, .panel-body .bg-gray-50 { padding: .5rem !important; }
      .md\:sticky { position: static !important; top: auto !important; }
      @media (max-height: 560px) { #modalPanel { height: calc(100svh - 4px) !important; max-height: calc(100svh - 4px) !important; } .panel-body textarea { min-height: 40px !important; } }
    }
    .panel-header{ position: sticky; top: 0; background: #fff; z-index: 10; border-bottom: 1px solid #eef2f7; }
  /* Make scrollbar space always reserved to avoid width reflow that may cause pointer jitter */
  .panel-body{ flex: 1 1 auto; overflow-y: scroll; padding-bottom: max(1rem, env(safe-area-inset-bottom, 0px)); overscroll-behavior: contain; scrollbar-gutter: stable both-edges; }
  /* Force stable cursor inside modal form to avoid flicker between pointer/text */
  #modalPanel { cursor: default; }
  #modalPanel form * { cursor: text; }
  #modalPanel button, #modalPanel [type=button], #modalPanel [type=submit] { cursor: pointer; }
  /* Avoid any accidental overlay capturing pointer */
  #modalPanel .shimmer-bg { pointer-events: none; }

    @media (max-width: 768px){ .main-image{ max-height: 50vh; } .thumbnails-scroll{ display:flex !important; gap:.5rem; overflow-x:auto; padding-bottom:.5rem; -webkit-overflow-scrolling:touch; scroll-snap-type:x mandatory; } .thumbnails-scroll img{ flex:0 0 auto; height:4rem; width:4rem; object-fit:cover; scroll-snap-align:start; } .order-btn{ padding:.75rem 1rem; font-size:1rem; } }

    @supports (-webkit-touch-callout: none){ #modalPanel input, #modalPanel select, #modalPanel textarea{ font-size: 16px; } }

    @media (orientation: landscape) and (max-height: 480px){ #orderModal{ align-items: flex-start !important; } #modalPanel{ margin-top: .5rem; max-height: calc(100vh - 20px) !important; } .panel-header { padding: .5rem 1rem !important; } .panel-body { padding: .5rem 1rem !important; } .panel-body .space-y-3 > * + *, .panel-body .space-y-4 > * + * { margin-top: .5rem !important; } .panel-body input, .panel-body select, .panel-body textarea { padding: .375rem .5rem !important; font-size: .8rem !important; } .panel-body textarea { min-height: 40px !important; } }

    @media (max-height: 600px) and (max-width: 480px) { #modalPanel { max-height: 95vh !important; } .panel-body .bg-blue-50, .panel-body .bg-gray-50 { padding: .5rem !important; } .panel-body .grid-cols-2 { gap: .5rem !important; } .panel-body .space-y-3 > * + * { margin-top: .375rem !important; } }

    .btn-primary { background: linear-gradient(135deg, #2563EB 0%, #4F46E5 100%); transition: transform .2s ease, box-shadow .2s ease, filter .2s ease; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(37,99,235,.25); filter: brightness(1.02); }
    .btn-success { background:#10B981; transition: transform .2s ease, box-shadow .2s ease; }
    .btn-success:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(16,185,129,.25); }
    .btn-ghost { transition: background-color .2s ease, transform .2s ease; }
    .btn-ghost:hover { background-color:#E5E5E5; transform: translateY(-1px); }

    /* ==== Subtle hover & scroll-in helpers ==== */
    .animate-on-scroll { opacity: 0; transform: translateY(12px); transition: opacity .45s ease, transform .45s ease; }
    .animate-on-scroll.in-view { opacity: 1; transform: translateY(0); }
    .card-hover { transition: transform .2s ease, box-shadow .2s ease; }
    .card-hover:hover { transform: translateY(-2px); box-shadow: 0 14px 32px rgba(0,0,0,.08); }
    .img-pop { transition: transform .25s ease; }
    .img-pop:hover { transform: scale(1.02); }
    .badge-bounce { animation: pulseSoft 1.8s ease-in-out infinite; }
    .main-image.fade-swap { animation: fadeIn .45s ease-out both; }

    @media (prefers-reduced-motion: reduce) { * { animation: none !important; transition: none !important; } }
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
</head>
<body class="bg-lightgray pt-16 md:pt-20">
  <!-- Navigation -->
  <nav id="siteNav" class="navbar nav-solid">
    <div class="container mx-auto px-4 py-3 md:py-4">
      <div class="flex justify-between items-center">
        <!-- Logo + Brand -->
        <a href="{{ route('landing') }}" class="flex items-center gap-2 md:gap-3 font-extrabold tracking-tight hover:opacity-95">
      <img src="{{ asset('images/fekra-logo.png') }}" alt="فكره استور" class="h-12 w-auto drop-shadow-sm group-hover:scale-105 transition"/>
              <span class="text-2xl md:text-3xl font-extrabold font-brand-kufi text-logo-shadow leading-none text-brand-navy group-hover:text-brand-blue transition">فكره استور</span>
        </a>

        <!-- Links -->
        <div class="flex items-center gap-3 md:gap-4">
          <a href="{{ route('landing') }}" class="hover:opacity-80 text-sm md:text-base">الرئيسية</a>
          <a href="{{ route('cart.show') }}" class="cart-btn relative p-2 md:p-3 rounded-full transition">
            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5-6M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
            </svg>
            <span id="cart-badge" class="absolute -top-0.5 -right-0.5 bg-brandOrange text-white text-[10px] font-bold px-1 py-[1px] rounded-full min-w-[1rem] text-center hidden">0</span>
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Product Details -->
  <section class="py-6 md:py-12">
    <div class="container mx-auto px-4">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
        <!-- Product Images -->
        <div class="animate-fadeInUp">
          <div class="mb-4">
            <img id="mainImage" src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full main-image rounded-lg shadow-soft img-pop" loading="lazy" onerror="this.src='https://via.placeholder.com/600x400?text=No+Image'">
          </div>

          @if($product->gallery && count($product->gallery) > 0)
          <div class="thumbnails-scroll grid grid-cols-4 gap-2 md:grid-cols-4 md:gap-2">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="gallery-image w-full h-16 md:h-20 object-cover rounded border-2 border-brandBlue" onclick="changeMainImage('{{ $product->image_url }}', this)" onerror="this.style.display='none'">
            @foreach($product->gallery as $image)
              @php $imageUrl = str_starts_with($image, 'http') ? $image : '/storage/' . ltrim($image, '/'); @endphp
              <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="gallery-image w-full h-16 md:h-20 object-cover rounded border-2 border-lightgray2 hover:border-brandBlue" onclick="changeMainImage('{{ $imageUrl }}', this)" onerror="this.style.display='none'">
            @endforeach
          </div>
          @endif
        </div>

        <!-- Product Info -->
        <div class="animate-fadeInUp">
          <h1 class="text-2xl md:text-3xl font-extrabold text-charcoalText mb-4">{{ $product->name }}</h1>

          <div class="mb-6">
            <div class="flex flex-wrap items-center gap-2 md:gap-4 mb-2">
              <span class="text-2xl md:text-3xl font-extrabold text-brandBlue">{{ $product->formatted_price }}</span>
              @if($product->original_price && $product->original_price > $product->price)
                <span class="text-lg md:text-xl text-gray-500 line-through">{{ $product->formatted_original_price }}</span>
                <span class="bg-brandOrange text-white px-2 py-1 rounded text-xs md:text-sm font-bold">خصم {{ $product->discount_percentage }}%</span>
              @endif
            </div>
            <p class="text-brandGreen font-semibold text-sm md:text-base">✅ متوفر - توصيل مجاني</p>
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
                  <span class="text-brandGreen mr-2">✓</span>
                  <span class="text-gray-700">{{ trim($feature) }}</span>
                </div>
                @endif
              @endforeach
            </div>
          </div>
          @endif

          <!-- Order Card -->
          <div class="bg-white rounded-xl shadow-soft p-4 md:p-6 ring-1 ring-lightgray2 animate-scaleIn card-hover">
            <!-- Quantity Selector (smaller) -->
            <div class="mb-4">
              <label class="block text-gray-700 font-bold mb-2">الكمية:</label>
              <div class="flex items-center justify-center gap-2">
                <button onclick="decreaseQuantity()" class="btn-ghost bg-lightgray2 hover:bg-lightgray text-charcoal font-bold py-1.5 px-3 rounded-md text-sm">−</button>
                <span id="qtyBadge" class="bg-white ring-1 ring-lightgray2 px-3 py-1.5 rounded font-bold text-base min-w-[2.5rem] text-center">1</span>
                <button onclick="increaseQuantity()" class="btn-primary text-white font-bold py-1.5 px-3 rounded-md text-sm">+</button>
              </div>
            </div>
            <!-- Action Buttons -->
            <div class="space-y-3">
              <button onclick="handleAddToCart(); addToCart({{ $product->id }})" class="btn w-full btn-success hover:brightness-105 text-white font-bold py-3 md:py-4 px-4 md:px-6 rounded-lg text-lg md:text-xl shadow-soft group">
                <span class="relative z-10">🛒 أضف للسلة</span>
                <span class="absolute inset-0 shimmer-bg opacity-0 group-hover:opacity-100 animate-shimmer rounded-lg"></span>
              </button>
              <button onclick="openOrderModal()" class="btn w-full btn-primary text-white font-bold py-3 md:py-4 px-4 md:px-6 rounded-lg text-lg md:text-xl shadow-soft group">
                <span class="relative z-10">اطلب الآن</span>
                <span class="absolute inset-0 shimmer-bg opacity-0 group-hover:opacity-100 animate-shimmer rounded-lg"></span>
              </button>
            </div>
            <div class="mt-3 md:mt-4 text-center">
              <p class="text-gray-600 text-sm md:text-base">✅ توصيل مجاني لجميع المحافظات</p>
              <p class="text-gray-600 text-sm md:text-base">🚚 شحن خلال 1-3 أيام عمل</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Trust Badges -->

  <!-- Footer -->
  <footer class="bg-charcoal text-white py-8">
    <div class="container mx-auto px-4 text-center">
      <p>&copy; 2025 فكرة. جميع الحقوق محفوظة.</p>
    </div>
  </footer>

  <!-- Order Modal -->
  <div id="orderModal" class="fixed inset-0 bg-black/40 backdrop-blur-[1px] z-50 flex items-end md:items-center justify-center px-3 sm:px-4 py-3 hidden">
  <div id="modalPanel" class="bg-white shadow-lift w-full max-w-[96vw] sm:max-w-md md:max-w-2xl lg:max-w-4xl p-0 md:p-0">
      <!-- Header (sticky) -->
      <div class="panel-header px-4 py-3 md:px-6 md:py-4 flex items-center justify-between">
        <h3 class="text-base md:text-lg font-bold text-charcoalText">تفاصيل الطلب</h3>
        <button onclick="closeOrderModal()" class="text-gray-500 hover:text-charcoalText text-2xl leading-none">&times;</button>
      </div>

      <!-- Body (scrollable) -->
      <div class="panel-body px-4 pb-4 md:px-6 md:pb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
          <!-- Summary -->
          <div class="space-y-4 md:sticky md:top-4">
            <div class="bg-blue-50 rounded-md p-3 text-sm ring-1 ring-lightgray2">
              <div class="flex items-center gap-4">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded-lg ring-1 ring-lightgray2" loading="lazy">
                <div>
                  <h4 class="font-bold text-charcoalText">{{ $product->name }}</h4>
                  <p class="text-brandBlue font-extrabold">{{ $product->formatted_price }}</p>
                </div>
              </div>
            </div>

            <div class="bg-gray-50 rounded-md p-3 text-sm ring-1 ring-lightgray2">
              <div class="flex justify-between items-center mb-2"><span class="text-gray-600">السعر:</span><span class="font-bold">{{ $product->formatted_price }}</span></div>
              <div class="flex justify-between items-center mb-2"><span class="text-gray-600">الكمية:</span><span id="modalQuantityDisplay" class="font-bold">1</span></div>
              <div class="flex justify-between items-center border-t pt-2"><span class="text-lg font-extrabold">الإجمالي:</span><span id="totalPrice" class="text-xl font-extrabold text-brandBlue">{{ $product->formatted_price }}</span></div>
            </div>
          </div>

          <!-- Form -->
          <div>
            <form id="productOrderForm" class="space-y-3" novalidate>
              @csrf
              <input type="hidden" name="product_id" value="{{ $product->id }}">

              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label for="modalQuantity" class="block text-xs font-medium text-gray-700 mb-1">الكمية</label>
                  <select id="modalQuantity" name="quantity" class="w-full px-3 py-2 border border-lightgray2 rounded-md focus:ring-2 focus:ring-brandBlue focus:border-brandBlue">
                    @for($i = 1; $i <= 10; $i++)
                      <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                  </select>
                </div>
                <div>
                  <label for="phone" class="block text-xs font-medium text-gray-700 mb-1">رقم الهاتف</label>
                  <input type="tel" id="phone" name="phone" required autocomplete="tel" class="w-full px-3 py-2 border border-lightgray2 rounded-md focus:ring-2 focus:ring-brandBlue focus:border-brandBlue" placeholder="01xxxxxxxxx" inputmode="numeric" pattern="01[0-9]{9}">
                </div>
              </div>

              <div>
                <label for="customer_name" class="block text-xs font-medium text-gray-700 mb-1">الاسم كاملاً</label>
                <input type="text" id="customer_name" name="customer_name" required autocomplete="name" class="w-full px-3 py-2 border border-lightgray2 rounded-md focus:ring-2 focus:ring-brandBlue focus:border-brandBlue" placeholder="أدخل اسمك كاملاً">
              </div>

              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label for="governorate" class="block text-xs font-medium text-gray-700 mb-1">المحافظة</label>
                  <select id="governorate" name="governorate" required class="w-full px-3 py-2 border border-lightgray2 rounded-md focus:ring-2 focus:ring-brandBlue focus:border-brandBlue">
                    <option value="">اختر المحافظة</option>
                    <option value="القاهرة">القاهرة</option>
                    <option value="الجيزة">الجيزة</option>
                    <option value="الإسكندرية">الإسكندرية</option>
                    <option value="الدقهلية">الدقهلية</option>
                    <option value="البحيرة">البحيرة</option>
                    <option value="المنوفية">المنوفية</option>
                    <option value="الغربية">الغربية</option>
                    <option value="كفر الشيخ">كفر الشيخ</option>
                    <option value="الشرقية">الشرقية</option>
                    <option value="القليوبية">القليوبية</option>
                    <option value="بنى سويف">بنى سويف</option>
                    <option value="الفيوم">الفيوم</option>
                    <option value="المنيا">المنيا</option>
                    <option value="أسيوط">أسيوط</option>
                    <option value="سوهاج">سوهاج</option>
                    <option value="قنا">قنا</option>
                    <option value="الأقصر">الأقصر</option>
                    <option value="أسوان">أسوان</option>
                    <option value="البحر الأحمر">البحر الأحمر</option>
                    <option value="الوادى الجديد">الوادى الجديد</option>
                    <option value="مطروح">مطروح</option>
                    <option value="شمال سيناء">شمال سيناء</option>
                    <option value="جنوب سيناء">جنوب سيناء</option>
                    <option value="بورسعيد">بورسعيد</option>
                    <option value="دمياط">دمياط</option>
                    <option value="الإسماعيلية">الإسماعيلية</option>
                    <option value="السويس">السويس</option>
                  </select>
                </div>
                <div>
                  <label for="apartment" class="block text-xs font-medium text-gray-700 mb-1">الدور/الشقة (اختياري)</label>
                  <input id="apartment" name="apartment" type="text" autocomplete="address-line2" class="w-full px-3 py-2 border border-lightgray2 rounded-md focus:ring-2 focus:ring-brandBlue focus:border-brandBlue" placeholder="مثال: الدور 3، شقة 8">
                </div>
              </div>

              <div>
                <label for="address" class="block text-xs font-medium text-gray-700 mb-1">العنوان التفصيلي</label>
                <textarea id="address" name="address" required rows="2" autocomplete="street-address" class="w-full px-3 py-2 border border-lightgray2 rounded-md focus:ring-2 focus:ring-brandBlue focus:border-brandBlue" placeholder="المدينة، الحي، الشارع، رقم العقار"></textarea>
              </div>

              <div class="flex gap-2 pt-1">
                <button type="button" onclick="closeOrderModal()" class="flex-1 bg-lightgray2 hover:bg-lightgray text-charcoal font-semibold py-2 px-3 rounded-md transition duration-200 text-sm">إلغاء</button>
                <button type="submit" id="productSubmitBtn" class="flex-1 btn-primary text-white font-semibold py-2 px-3 rounded-md transition duration-200 text-sm group">
                  <span class="relative z-10">تأكيد الطلب</span>
                  <span class="absolute inset-0 shimmer-bg opacity-0 group-hover:opacity-100 animate-shimmer rounded-md"></span>
                </button>
              </div>

              <div id="productSuccessMessage" class="hidden mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                <h3 class="font-bold mb-2">✅ تم تسجيل طلبك بنجاح!</h3>
                <p>سيتم التواصل معك خلال 24 ساعة لتأكيد الطلب.</p>
              </div>

              <div id="productErrorMessage" class="hidden mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <h3 class="font-bold mb-2">❌ حدث خطأ!</h3>
                <p id="productErrorText">يرجى المحاولة مرة أخرى.</p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    const productPrice = {{ $product->price }};

    // Navbar solid <-> transparent on scroll
    const siteNav = document.getElementById('siteNav');
    function setNavState(){
      const scrolled = window.scrollY > 16;
      if (scrolled){
        siteNav.classList.remove('nav-solid');
        siteNav.classList.add('nav-transparent');
      } else {
        siteNav.classList.add('nav-solid');
        siteNav.classList.remove('nav-transparent');
      }
    }
    window.addEventListener('scroll', setNavState, { passive: true });
    document.addEventListener('DOMContentLoaded', setNavState);

    // Button ripple feedback
    document.addEventListener('click', (e) => {
      const t = e.target.closest('button, a');
      if (!t) return;
      t.classList.add('animate-ripple');
      setTimeout(() => t.classList.remove('animate-ripple'), 600);
    });

    // ===== Small counter in card (avoid ID conflicts) =====
    let currentQuantity = 1;
    function renderQtyBadge(){ const el = document.getElementById('qtyBadge'); if(el) el.textContent = currentQuantity; }
    function increaseQuantity(){ if (currentQuantity < 10) { currentQuantity++; renderQtyBadge(); } }
    function decreaseQuantity(){ if (currentQuantity > 1) { currentQuantity--; renderQtyBadge(); } }

    // Cart functionality
    function addToCart(productId) {
      fetch('/cart/add', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ product_id: productId, quantity: currentQuantity })
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          updateCartCount();
          toast(data.message, 'success');
          const cartIcon = document.querySelector('#cart-badge').parentElement;
          cartIcon.classList.add('animate-pulseSoft');
          setTimeout(() => cartIcon.classList.remove('animate-pulseSoft'), 700);
        } else { toast('حدث خطأ في إضافة المنتج للسلة', 'error'); }
      })
      .catch(() => toast('حدث خطأ في إضافة المنتج للسلة', 'error'));
    }

    function updateCartCount() {
      fetch('/cart/count')
      .then(r => r.json())
      .then(data => {
        const cartBadge = document.getElementById('cart-badge');
        if (data.cart_count > 0) { cartBadge.textContent = data.cart_count; cartBadge.classList.remove('hidden'); }
        else { cartBadge.classList.add('hidden'); }
      })
      .catch(() => {});
    }

    // Toast helper
    function toast(message, type) {
      const container = document.createElement('div');
      container.className = 'fixed top-4 right-4 z-50';
      const msg = document.createElement('div');
      msg.className = `p-4 rounded-lg shadow-soft text-white transform translate-x-full transition-transform duration-300 animate-slideDown ${type === 'success' ? 'bg-brandGreen' : 'bg-brandOrange'}`;
      msg.innerHTML = `<div class="flex items-center justify-between gap-4"><span>${message}</span><button class="text-white/90 hover:text-white" onclick="this.closest('div').remove()">×</button></div>`;
      container.appendChild(msg); document.body.appendChild(container);
      setTimeout(() => { msg.style.transform = 'translateX(0)'; }, 50);
      setTimeout(() => { msg.style.transform = 'translateX(100%)'; setTimeout(() => container.remove(), 280); }, 3000);
    }

    document.addEventListener('DOMContentLoaded', () => {
      updateCartCount();
      renderQtyBadge();
      startAutoRotate();
      setupScrollIn();
    });

    /* Dynamic modal height */
    function setPanelMaxHeight(){
      const panel = document.getElementById('modalPanel'); if(!panel) return;
      // Use innerHeight instead of visualViewport (visualViewport can fire rapid resize events on pointer move in some environments)
      const h = window.innerHeight;
      const w = window.innerWidth;
      let ratio = .8;
      if (w <= 480) ratio = .92; else if (w <= 768) ratio = .85;
      const maxHeight = Math.round(h * ratio);
      panel.style.setProperty('--panel-max', maxHeight + 'px');
    }
    // Debounced wrapper to avoid layout thrash
    let _panelResizeTimer=null; function schedulePanelResize(){ clearTimeout(_panelResizeTimer); _panelResizeTimer=setTimeout(setPanelMaxHeight, 90); }

    function openOrderModal(){
      const modal = document.getElementById('orderModal');
      modal.classList.remove('hidden');
      document.body.style.overflow='hidden';
      // Fully stop auto-rotate to avoid DOM class toggles influencing cursor hit-testing
      stopAutoRotate();
      syncModalQuantityWithBadge();
      updateTotalPrice(); setPanelMaxHeight();
      if (window.matchMedia('(max-width: 768px)').matches){ setTimeout(()=>{ const p=document.getElementById('phone'); if(p) p.focus(); }, 120); }
    }

    function closeOrderModal(){
      const modal = document.getElementById('orderModal');
      modal.classList.add('hidden');
      document.body.style.overflow='auto';
      const form=document.getElementById('productOrderForm'); form.reset();
      document.getElementById('modalQuantity').value='1';
      updateTotalPrice(); document.getElementById('productSuccessMessage').classList.add('hidden'); document.getElementById('productErrorMessage').classList.add('hidden');
      // Restart gallery auto-rotate after modal closes
      startAutoRotate();
    }

    const orderModalEl = document.getElementById('orderModal');
    if(orderModalEl){
      orderModalEl.addEventListener('click',function(e){ if (e.target === this) closeOrderModal(); });
    }
    document.addEventListener('keydown',function(e){ if(e.key==='Escape') closeOrderModal(); });

    // ===== Modal quantity / total =====
    function updateTotalPrice(){
      const q = parseInt(document.getElementById('modalQuantity').value)||1;
      const total = productPrice*q;
      document.getElementById('totalPrice').textContent = new Intl.NumberFormat('ar-EG').format(total) + ' ج.م';
      document.getElementById('modalQuantityDisplay').textContent = q;
    }
  const modalQuantityEl = document.getElementById('modalQuantity');
  if(modalQuantityEl){ modalQuantityEl.addEventListener('change', updateTotalPrice); }

    function syncModalQuantityWithBadge(){
      const sel = document.getElementById('modalQuantity');
      if (sel) { sel.value = String(currentQuantity); }
      updateTotalPrice();
    }

    function changeMainImage(src, el){
      const main=document.getElementById('mainImage');
      if (!main) return;
      main.classList.remove('fade-swap');
      void main.offsetWidth; // reflow to restart animation
      main.src=src;
      main.classList.add('fade-swap');

      document.querySelectorAll('.gallery-image').forEach(img=>{
        img.classList.remove('border-brandBlue');
        img.classList.add('border-lightgray2');
      });
      if(el){ el.classList.remove('border-lightgray2'); el.classList.add('border-brandBlue'); }
    }

    // ====== Auto-rotate gallery (carousel-lite) ======
    let autoTimer = null;
    let autoIdx = 0;
    let autoPaused = false;

    function getGallerySources(){
      const thumbs = Array.from(document.querySelectorAll('.thumbnails-scroll img'));
      const srcs = thumbs.map(t => t.getAttribute('src')).filter(Boolean);
      const mainSrc = document.getElementById('mainImage')?.getAttribute('src');
      if (mainSrc && !srcs.includes(mainSrc)) srcs.unshift(mainSrc);
      return srcs;
    }

    function startAutoRotate(){
      const sources = getGallerySources();
      if (sources.length <= 1) return;
      if (autoTimer) clearInterval(autoTimer);
      autoTimer = setInterval(() => {
        if (autoPaused) return;
        const srcs = getGallerySources();
        if (srcs.length <= 1) return;
        autoIdx = (autoIdx + 1) % srcs.length;
        const thumbs = Array.from(document.querySelectorAll('.thumbnails-scroll img'));
        const match = thumbs.find(t => t.getAttribute('src') === srcs[autoIdx]);
        changeMainImage(srcs[autoIdx], match);
      }, 3500);
    }

    function stopAutoRotate(){ if (autoTimer){ clearInterval(autoTimer); autoTimer = null; } }

    // Pause on hover / focus
    const mainImgEl = document.getElementById('mainImage');
    if (mainImgEl){
      mainImgEl.addEventListener('mouseenter', () => autoPaused = true);
      mainImgEl.addEventListener('mouseleave', () => autoPaused = false);
      mainImgEl.addEventListener('focusin', () => autoPaused = true);
      mainImgEl.addEventListener('focusout', () => autoPaused = false);
    }
    // Clicking a thumbnail sets the index accordingly
    document.addEventListener('click', (e) => {
      const t = e.target.closest('.thumbnails-scroll img');
      if (!t) return;
      const srcs = getGallerySources();
      const i = srcs.indexOf(t.getAttribute('src'));
      if (i >= 0) autoIdx = i;
    });
    // Pause when tab hidden
    document.addEventListener('visibilitychange', () => { autoPaused = document.hidden; });

    // Stop autoplay when modal opens/close
    const orderModal = document.getElementById('orderModal');
    const modalObserver = new MutationObserver(() => {
      const isHidden = orderModal.classList.contains('hidden');
      autoPaused = !isHidden;
    });
    if (orderModal) modalObserver.observe(orderModal, { attributes: true, attributeFilter: ['class'] });

    // ===== Scroll-in animations for sections =====
    function setupScrollIn(){
      const els = document.querySelectorAll('.animate-on-scroll');
      if ('IntersectionObserver' in window){
        const io = new IntersectionObserver((entries)=>{
          entries.forEach(e=>{
            if (e.isIntersecting){ e.target.classList.add('in-view'); io.unobserve(e.target); }
          });
        }, { threshold: .1 });
        els.forEach(el=>io.observe(el));
      } else {
        // Fallback
        els.forEach(el=>el.classList.add('in-view'));
      }
    }

    // رقم الهاتف: أرقام فقط وبحد أقصى 11 (تحقق من وجود العنصر)
    const phoneEl = document.getElementById('phone');
    if (phoneEl) {
      phoneEl.addEventListener('input', function(e){
        let v=e.target.value.replace(/\D/g,'');
        if(v.length>11) v=v.slice(0,11);
        e.target.value=v;
      });
    }

    // إرسال الطلب
  const productOrderFormEl = document.getElementById('productOrderForm');
  if(productOrderFormEl) productOrderFormEl.addEventListener('submit', function(e){
      e.preventDefault();
      const btn=document.getElementById('productSubmitBtn');
      const ok=document.getElementById('productSuccessMessage');
      const err=document.getElementById('productErrorMessage');
      ok.classList.add('hidden'); err.classList.add('hidden');
      btn.disabled=true; btn.querySelector('span').textContent='جاري الإرسال...';
      const formData=new FormData(this);
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
            throw new Error(flat || data.message || 'فشل التحقق');
          }
            const text = data?.message || 'حدث خطأ في الخادم ('+r.status+')';
            throw new Error(text);
        }
        return data;
      })
      .then(d => {
        if (d && d.success) {
          ok.classList.remove('hidden');
          setTimeout(() => { window.location.href='{{ route("thanks") }}'; }, 1400);
        } else {
          throw new Error(d?.message || 'حدث خطأ غير متوقع');
        }
      })
      .catch(e => {
        err.classList.remove('hidden');
        document.getElementById('productErrorText').textContent = e.message;
      })
      .finally(()=>{
        btn.disabled=false;
        btn.querySelector('span').textContent='تأكيد الطلب';
      });
  });

  // Use debounced listeners only
  window.addEventListener('resize', schedulePanelResize, { passive:true });
  window.addEventListener('orientationchange', ()=>setTimeout(setPanelMaxHeight, 140), { passive:true });
  </script>
  
  <!-- TikTok Pixel Events: ViewContent + AddToCart -->
  <script>
    (function(){
      // Guard against missing TTQ
      function tiktokTrack(eventName, payload){
        if (window.ttq && typeof window.ttq.track === 'function') {
          window.ttq.track(eventName, payload);
        }
      }

      // Product data from Blade (safe JSON for strings)
      const productData = {
        id: {{ $product->id }},
        name: @json($product->name),
        price: {{ (float) $product->price }}
      };

      // Fire ViewContent on page load
      tiktokTrack('ViewContent', {
        content_id: String(productData.id),
        content_type: 'product',
        content_name: productData.name,
        price: productData.price,
        currency: 'EGP'
      });

      // Expose AddToCart handler (quantity fixed to 1 per requirements)
      window.handleAddToCart = function(){
        tiktokTrack('AddToCart', {
          content_id: String(productData.id),
          content_type: 'product',
          content_name: productData.name,
          price: productData.price,
          currency: 'EGP',
          quantity: 1
        });
      };
    })();
  </script>
</body>
</html>
