<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>سلة التسوق - فكره استور</title>
  <meta name="description" content="سلة التسوق - راجع منتجاتك المختارة وأتمم عملية الشراء" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&family=Reem+Kufi:wght@600;700&display=swap" rel="stylesheet" />

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            cairo: ["Cairo", "sans-serif"],
          },
          colors: {
            // Primary Palette (Trust & Neutrality)
            white: "#FFFFFF",
            lightgray: "#F5F5F5",
            lightgray2: "#E5E5E5",
            charcoal: "#2D2D2D",
            charcoalText: "#333333",
            // Accents
            brandBlue: "#2563EB", // Tailwind blue-600
            brandIndigo: "#4F46E5", // indigo-600 alternative
            brandGreen: "#10B981",
            brandOrange: "#F97316",
            // Supporting
            softBeige: "#FAFAF5",
            mutedNavy: "#1E3A8A",
          },
          boxShadow: {
            soft: "0 8px 25px rgba(0,0,0,0.06)",
            lift: "0 12px 30px rgba(37,99,235,0.15)",
          },
          animation: {
            fadeIn: "fadeIn .5s ease-out both",
            fadeInUp: "fadeInUp .5s ease-out both",
            slideDown: "slideDown .3s ease-out both",
            scaleIn: "scaleIn .35s ease-out both",
            pulseSoft: "pulseSoft 1.8s ease-in-out infinite",
            shimmer: "shimmer 2s linear infinite",
            modalIn: "modalIn .35s ease-out both",
            ripple: "ripple 0.6s ease-out",
          },
          keyframes: {
            fadeIn: {
              "0%": { opacity: 0 },
              "100%": { opacity: 1 },
            },
            fadeInUp: {
              "0%": { opacity: 0, transform: "translateY(16px)" },
              "100%": { opacity: 1, transform: "translateY(0)" },
            },
            slideDown: {
              "0%": { opacity: 0, transform: "translateY(-8px)" },
              "100%": { opacity: 1, transform: "translateY(0)" },
            },
            scaleIn: {
              "0%": { opacity: 0, transform: "scale(.96)" },
              "100%": { opacity: 1, transform: "scale(1)" },
            },
            pulseSoft: {
              "0%,100%": { transform: "translateY(0)" },
              "50%": { transform: "translateY(-2px)" },
            },
            shimmer: {
              "0%": { backgroundPosition: "-100% 0" },
              "100%": { backgroundPosition: "200% 0" },
            },
            modalIn: {
              "0%": { opacity: 0, transform: "translateY(12px) scale(.98)" },
              "100%": { opacity: 1, transform: "translateY(0) scale(1)" },
            },
            ripple: {
              "0%": { boxShadow: "0 0 0 0 rgba(37,99,235,0.45)" },
              "100%": { boxShadow: "0 0 0 16px rgba(37,99,235,0)" },
            },
          },
        },
      },
    };
  </script>

  <style>
  body { font-family: "Cairo", sans-serif; }
  .font-brand-kufi { font-family: 'Reem Kufi', 'Cairo', sans-serif; font-weight:700; letter-spacing:.5px; }
  .text-logo-shadow { text-shadow: 0 0 0 currentColor, 0 .5px 0 currentColor; }
    .gradient-bg { /* Header background using muted navy -> blue gradient */
      background: linear-gradient(135deg, #1E3A8A 0%, #2563EB 100%);
    }
    .btn-primary {
      background: linear-gradient(135deg, #2563EB 0%, #4F46E5 100%);
      transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
    }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(37,99,235,.25); filter: brightness(1.02); }
    .btn-success { background: #10B981; transition: transform .2s ease, box-shadow .2s ease; }
    .btn-success:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(16,185,129,.25); }
    .btn-ghost { transition: background-color .2s ease, transform .2s ease; }
    .btn-ghost:hover { background-color: #E5E5E5; transform: translateY(-1px); }
    .cart-item { transition: transform .25s ease, box-shadow .25s ease; }
    .cart-item:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(0,0,0,.06); }
    .badge-pill { background: #FAFAF5; color: #1E3A8A; }
    /* Shimmer helper */
    .shimmer-bg { background: linear-gradient(90deg, rgba(255,255,255,.0) 0%, rgba(255,255,255,.35) 50%, rgba(255,255,255,.0) 100%); background-size: 200% 100%; }
    /* Respect user motion preference */
    @media (prefers-reduced-motion: reduce) {
      * { animation: none !important; transition: none !important; }
    }
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
<body class="bg-lightgray font-cairo text-charcoalText">
  <!-- Navigation -->
  <nav class="gradient-bg text-white shadow-lg">
    <div class="container mx-auto px-4 py-4 relative">
      <div class="flex justify-between items-center">
        <!-- Logo & Toggle -->
        <div class="flex items-center gap-4">
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
          <button id="navToggle" class="sm:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white/10 hover:bg-white/15 transition absolute left-4 top-1/2 -translate-y-1/2 sm:static z-50" aria-label="فتح/إغلاق القائمة">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
          </button>
        </div>
        <!-- Nav Links -->
        <div id="navMenu" class="hidden sm:flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6 w-full sm:w-auto mt-4 sm:mt-0 text-sm sm:text-base">
          <a href="{{ route('landing') }}" class="hover:text-blue-200 transition-colors">الرئيسية</a>
          <div class="relative">
            <span class="badge-pill px-3 py-1 rounded-full text-sm font-bold shadow-soft animate-fadeInUp block text-center">
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
    <div class="text-center mb-8 animate-fadeInUp">
  <h1 class="text-3xl sm:text-4xl font-extrabold text-charcoalText mb-3">🛒 سلة التسوق</h1>
      <p class="text-gray-600 text-lg">راجع منتجاتك المختارة وأتمم عملية الشراء</p>
    </div>

    @if(empty($cart))
      <!-- Empty Cart -->
      <div class="text-center py-16 animate-fadeInUp">
        <div class="text-8xl mb-6 animate-pulseSoft">🛒</div>
        <h2 class="text-3xl font-bold text-gray-700 mb-4">سلة التسوق فارغة</h2>
        <p class="text-gray-500 mb-8 text-lg">ابدأ بإضافة منتجات رائعة لسلة التسوق</p>
        <a href="{{ route('landing') }}" class="btn-primary inline-flex items-center gap-2 text-white px-8 py-4 rounded-xl font-bold text-lg shadow-soft">
          <span class="relative inline-block">تصفح المنتجات</span>
        </a>
      </div>
    @else
  <div class="grid lg:grid-cols-3 gap-6 lg:gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
          <div class="bg-white rounded-2xl shadow-soft p-4 sm:p-6 animate-scaleIn">
            <h2 class="text-2xl font-bold text-charcoalText mb-6 flex items-center">
              <span class="text-brandBlue ml-3">📦</span>
              المنتجات المختارة ({{ $cartCount }})
            </h2>

            <div class="space-y-4">
              @foreach($cart as $productId => $item)
                <div class="cart-item border border-lightgray2 bg-softBeige/40 rounded-xl p-4" data-product-id="{{ $productId }}">
                  <div class="flex flex-wrap items-start sm:items-center gap-4">
                    <!-- Product Image -->
                    <div class="relative">
                      <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-lg ring-1 ring-lightgray2" />
                      <span class="absolute -top-2 -right-2 text-[10px] bg-brandBlue text-white px-2 py-0.5 rounded-full shadow-soft animate-fadeInUp">{{ $item['quantity'] }}</span>
                    </div>

                    <!-- Product Info -->
                    <div class="min-w-[150px] flex-1">
                      <h3 class="text-base sm:text-lg font-bold text-charcoalText leading-tight overflow-hidden max-h-[3.2rem]">{{ $item['name'] }}</h3>
                      <p class="text-brandBlue font-extrabold text-base sm:text-lg mt-1">{{ number_format($item['price'], 0) }} ج.م</p>
                    </div>

                    <!-- Item Total (mobile first) -->
                    <div class="text-right sm:text-center order-4 sm:order-none w-auto sm:w-20">
                      <p class="text-xs sm:text-sm text-gray-500">الإجمالي</p>
                      <p class="item-total-{{ $productId }} text-base sm:text-lg font-extrabold text-brandGreen">{{ number_format($item['price'] * $item['quantity'], 0) }} ج.م</p>
                    </div>

                    <!-- Quantity Controls -->
                    <div class="flex items-center gap-2 w-full sm:w-auto sm:justify-center">
                      <button onclick="changeQuantity({{ $productId }}, -1)"
                        class="quantity-btn btn-minus bg-lightgray2 hover:bg-lightgray text-charcoal w-9 h-9 rounded-full flex items-center justify-center font-bold disabled:opacity-40 disabled:cursor-not-allowed"
                        {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>-
                      </button>
                      <span class="quantity-display-{{ $productId }} bg-white ring-1 ring-lightgray2 text-charcoalText px-3 py-1 rounded-lg font-bold min-w-[3rem] text-center">{{ $item['quantity'] }}</span>
                      <button onclick="changeQuantity({{ $productId }}, 1)"
                        class="quantity-btn btn-plus bg-brandBlue hover:brightness-110 text-white w-9 h-9 rounded-full flex items-center justify-center font-bold disabled:opacity-40 disabled:cursor-not-allowed"
                        {{ $item['quantity'] >= 10 ? 'disabled' : '' }}>+
                      </button>
                    </div>

                    <!-- Remove Button -->
                    <div class="ml-auto">
                      <button onclick="removeFromCart({{ $productId }})" class="text-brandOrange/90 hover:text-brandOrange hover:bg-brandOrange/10 p-2 rounded-lg transition-colors" aria-label="حذف المنتج">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                          <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>

            <!-- Clear Cart Button -->
            <div class="mt-6 pt-4 border-t border-lightgray2">
              <button onclick="clearCart()" class="btn-ghost text-brandOrange font-bold px-4 py-2 rounded-lg">
                🗑️ حذف جميع المنتجات
              </button>
            </div>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-2xl shadow-soft p-6 lg:sticky top-8 animate-scaleIn">
            <h2 class="text-2xl font-bold text-charcoalText mb-6 flex items-center">
              <span class="text-brandGreen ml-3">💰</span>
              ملخص الطلب
            </h2>

            <div class="space-y-4 mb-6">
              <div class="flex justify-between items-center">
                <span class="text-gray-600">عدد المنتجات:</span>
                <span class="cart-count font-bold text-lg">{{ $cartCount }}</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-gray-600">المجموع الفرعي:</span>
                <span class="cart-total font-extrabold text-lg text-brandGreen">{{ number_format($cartTotal, 0) }} ج.م</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-gray-600">رسوم التوصيل:</span>
                <span class="font-bold text-brandGreen">مجاناً 🚚</span>
              </div>
              <hr class="border-lightgray2" />
              <div class="flex justify-between items-center text-xl">
                <span class="font-extrabold text-charcoalText">الإجمالي:</span>
                <span class="cart-total font-extrabold text-brandGreen">{{ number_format($cartTotal, 0) }} ج.م</span>
              </div>
            </div>

            <!-- Checkout Button -->
            <button onclick="showCheckoutModal()"
              class="group w-full btn-primary text-white py-4 rounded-xl font-bold text-lg shadow-soft relative overflow-hidden">
              <span class="relative z-10">إتمام الطلب 🛍️</span>
              <span class="absolute inset-0 shimmer-bg opacity-0 group-hover:opacity-100 animate-shimmer rounded-xl"></span>
            </button>

            <!-- Continue Shopping -->
            <a href="{{ route('landing') }}"
              class="block w-full text-center bg-lightgray2 hover:bg-lightgray text-charcoal py-3 rounded-xl font-bold mt-4 transition-colors">
              متابعة التسوق
            </a>
          </div>
        </div>
      </div>
    @endif
  </div>

  <!-- Unified Order Modal -->
  <div id="checkoutModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-end md:items-center justify-center px-3 sm:px-4 py-3 hidden">
    <div id="cartModalPanel" class="bg-white shadow-soft w-full max-w-[96vw] sm:max-w-md md:max-w-3xl lg:max-w-4xl rounded-t-2xl md:rounded-2xl overflow-hidden flex flex-col animate-modalIn">
      <!-- Header -->
      <div class="px-4 py-3 md:px-6 md:py-4 flex items-center justify-between border-b">
        <h3 class="text-lg font-bold text-charcoalText">إتمام الطلب - السلة</h3>
        <button onclick="hideCheckoutModal()" class="text-gray-500 hover:text-charcoalText text-2xl leading-none">&times;</button>
      </div>
      <!-- Body -->
      <div class="p-4 md:p-6 overflow-y-auto space-y-6 max-h-[80vh]">
        <!-- Cart Summary inside modal -->
        <div class="bg-softBeige rounded-xl p-4 ring-1 ring-lightgray2">
          <h4 class="font-bold mb-3 text-charcoalText flex items-center"><span class="ml-2">🛒</span> ملخص المنتجات</h4>
          <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
            @foreach($cart as $pid => $item)
              <div class="flex justify-between items-center text-sm bg-white rounded p-2 border border-lightgray2">
                <div class="flex items-center gap-2">
                  <img src="{{ $item['image_url'] }}" class="w-10 h-10 rounded object-cover ring-1 ring-lightgray2" alt="{{ $item['name'] }}" />
                  <span class="font-medium text-charcoalText">{{ $item['name'] }}</span>
                </div>
                <div class="text-left">
                  <div class="text-brandIndigo font-bold">{{ $item['quantity'] }} × {{ number_format($item['price'],0) }}</div>
                  <div class="text-brandGreen font-extrabold">= {{ number_format($item['price'] * $item['quantity'],0) }} ج.م</div>
                </div>
              </div>
            @endforeach
          </div>
          <div class="flex justify-between items-center mt-4 pt-3 border-t border-lightgray2 font-bold text-lg">
            <span class="text-charcoalText">الإجمالي</span>
            <span class="text-brandGreen">{{ number_format($cartTotal,0) }} ج.م</span>
          </div>
        </div>

        <form id="checkoutForm" method="POST" action="{{ route('orders.store') }}" class="space-y-4">
          @csrf
          <input type="hidden" name="cart_data" value='@json($cart)' />
          <div class="grid md:grid-cols-2 gap-4">
            <div>
              <label class="block text-charcoalText font-bold mb-1 text-sm">الاسم الكامل *</label>
              <input name="customer_name" required class="w-full border border-lightgray2 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brandBlue focus:border-transparent text-sm" />
            </div>
            <div>
              <label class="block text-charcoalText font-bold mb-1 text-sm">رقم الهاتف *</label>
              <input name="phone" required class="w-full border border-lightgray2 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brandBlue focus:border-transparent text-sm" />
            </div>
          </div>
          <div>
            <label class="block text-charcoalText font-bold mb-1 text-sm">العنوان التفصيلي *</label>
            <textarea name="address" required rows="2" class="w-full border border-lightgray2 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brandBlue focus:border-transparent text-sm" placeholder="اكتب عنوانك بالكامل"></textarea>
          </div>
          <div class="grid md:grid-cols-2 gap-4">
            <div>
              <label class="block text-charcoalText font-bold mb-1 text-sm">المحافظة *</label>
              <select name="governorate" required class="w-full border border-lightgray2 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brandBlue focus:border-transparent text-sm">
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
              <label class="block text-charcoalText font-bold mb-1 text-sm">ملاحظات</label>
              <textarea name="notes" rows="2" class="w-full border border-lightgray2 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brandBlue focus:border-transparent text-sm" placeholder="أي ملاحظات إضافية (اختياري)"></textarea>
            </div>
          </div>
          <div class="flex flex-col md:flex-row gap-3 pt-2">
            <button class="flex-1 btn-success hover:brightness-105 text-white font-bold py-3 rounded-lg shadow-soft text-sm md:text-base relative overflow-hidden group">
              <span class="relative z-10">تأكيد الطلب ({{ number_format($cartTotal,0) }} ج.م)</span>
              <span class="absolute inset-0 shimmer-bg opacity-0 group-hover:opacity-100 animate-shimmer rounded-lg"></span>
            </button>
            <button type="button" onclick="hideCheckoutModal()" class="flex-1 bg-lightgray2 hover:bg-lightgray text-charcoal font-bold py-3 rounded-lg text-sm md:text-base">
              إلغاء
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Toasts Container -->
  <div id="messageContainer" class="fixed top-4 right-4 z-[60]"></div>

  <script>
    // Mobile nav toggle
    document.getElementById('navToggle')?.addEventListener('click', () => {
      const menu = document.getElementById('navMenu');
      if (!menu) return;
      const isHidden = menu.classList.contains('hidden');
      // Toggle visibility
      menu.classList.toggle('hidden');

      // Mobile dropdown styling (only apply on small screens)
      if (window.innerWidth < 640) {
        if (isHidden) {
          // Now opening
            menu.classList.add(
              'absolute','left-0','right-0','top-full','bg-white','text-charcoalText','shadow-lg','rounded-b-xl','px-4','py-3','z-40','border-t','border-lightgray2'
            );
            // Force flex layout when shown
            menu.classList.remove('flex-col');
            menu.classList.add('flex');
        } else {
            // Closing: cleanup
            menu.classList.remove(
              'absolute','left-0','right-0','top-full','bg-white','text-charcoalText','shadow-lg','rounded-b-xl','px-4','py-3','z-40','border-t','border-lightgray2','flex'
            );
            // Restore original hidden mobile base (flex-col for future desktop) if not already
            if (!menu.classList.contains('flex-col')) menu.classList.add('flex-col');
        }
      }
    });

    // Button ripple feedback
    document.addEventListener('click', (e) => {
      const t = e.target.closest('button, a');
      if (!t) return;
      t.classList.add('animate-ripple');
      setTimeout(() => t.classList.remove('animate-ripple'), 600);
    });

    // Helper: change quantity relative (+1 / -1)
    function changeQuantity(productId, delta) {
      const display = document.querySelector(`.quantity-display-${productId}`);
      if (!display) return;
      const current = parseInt(display.textContent.trim(), 10) || 0;
      const target = current + delta;
      updateQuantity(productId, target);
    }

    // Update quantity (absolute value)
    function updateQuantity(productId, newQuantity) {
      if (newQuantity < 1 || newQuantity > 10) return;
      // Prevent rapid double clicks
      const container = document.querySelector(`[data-product-id="${productId}"]`);
      const minusBtn = container?.querySelector('.btn-minus');
      const plusBtn = container?.querySelector('.btn-plus');
      [minusBtn, plusBtn].forEach(b => b && (b.disabled = true));
      fetch('/cart/update', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ product_id: productId, quantity: newQuantity })
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          document.querySelector(`.quantity-display-${productId}`).textContent = newQuantity;
          document.querySelector(`.item-total-${productId}`).textContent = new Intl.NumberFormat('ar-EG').format(data.item_total) + ' ج.م';
          updateCartTotals(data.cart_count, data.cart_total);
          // Re-enable buttons & adjust state
          if (minusBtn) minusBtn.disabled = newQuantity <= 1;
          if (plusBtn) plusBtn.disabled = newQuantity >= 10;
          showMessage(data.message, 'success');
        } else {
          showMessage('حدث خطأ في تحديث الكمية', 'error');
          if (minusBtn) minusBtn.disabled = (parseInt(document.querySelector(`.quantity-display-${productId}`).textContent,10) <= 1);
          if (plusBtn) plusBtn.disabled = (parseInt(document.querySelector(`.quantity-display-${productId}`).textContent,10) >= 10);
        }
      })
      .catch(() => {
        showMessage('حدث خطأ في تحديث الكمية', 'error');
        if (minusBtn) minusBtn.disabled = false;
        if (plusBtn) plusBtn.disabled = false;
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
        body: JSON.stringify({ product_id: productId })
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          const row = document.querySelector(`[data-product-id="${productId}"]`);
          if (row) { row.classList.add('opacity-0', 'translate-y-1', 'transition'); setTimeout(() => row.remove(), 180); }
          updateCartTotals(data.cart_count, data.cart_total);
          if (data.cart_count === 0) location.reload();
          showMessage(data.message, 'success');
        } else {
          showMessage('حدث خطأ في حذف المنتج', 'error');
        }
      })
      .catch(() => showMessage('حدث خطأ في حذف المنتج', 'error'));
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
      .then(r => r.json())
      .then(data => { if (data.success) location.reload(); else showMessage('حدث خطأ في حذف المنتجات', 'error'); })
      .catch(() => showMessage('حدث خطأ في حذف المنتجات', 'error'));
    }

    // Update cart totals in UI
    function updateCartTotals(count, total) {
      document.querySelectorAll('.cart-count').forEach(el => el.textContent = count);
      document.querySelectorAll('.cart-total').forEach(el => el.textContent = new Intl.NumberFormat('ar-EG').format(total) + ' ج.م');
    }

    // Show/hide checkout modal
    function showCheckoutModal() {
      const m = document.getElementById('checkoutModal');
      m.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }
    function hideCheckoutModal() {
      const m = document.getElementById('checkoutModal');
      m.classList.add('hidden');
      document.body.style.overflow = 'auto';
    }

    // Toasts
    function showMessage(message, type) {
      const container = document.getElementById('messageContainer');
      const wrapper = document.createElement('div');
      wrapper.className = `p-4 rounded-lg shadow-soft mb-3 text-white animate-slideDown ${type === 'success' ? 'bg-brandGreen' : 'bg-brandOrange'}`;
      wrapper.innerHTML = `
        <div class="flex items-center justify-between gap-4">
          <span>${message}</span>
          <button class="text-white/90 hover:text-white" aria-label="إغلاق" onclick="this.closest('div').remove()">×</button>
        </div>`;
      container.appendChild(wrapper);
      setTimeout(() => wrapper && wrapper.remove(), 5000);
    }

    // Keyboard & backdrop close
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') hideCheckoutModal(); });
    document.getElementById('checkoutModal').addEventListener('click', (e) => { if (e.target === e.currentTarget) hideCheckoutModal(); });
  </script>
</body>
</html>