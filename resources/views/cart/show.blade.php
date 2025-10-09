<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Shopping Cart - Fekra Store</title>
  <meta name="description" content="Review your selected products and complete your purchase" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet" />

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ["Inter", "sans-serif"],
            display: ["Playfair Display", "serif"],
          },
          colors: {
            // Modern Women's Fashion Palette
            'brand-rose': '#FF6B9D',
            'brand-rose-light': '#FFB8D2',
            'brand-rose-dark': '#C9184A',
            'brand-gold': '#D4AF37',
            'brand-gold-light': '#F4E4C1',
            'brand-charcoal': '#2D3142',
            'brand-slate': '#4F5D75',
            'brand-cream': '#FFF8F3',
            'brand-beige': '#F5E6D3',
            'brand-mint': '#A8DADC',
            'brand-lavender': '#E5D9F2',
            'brand-peach': '#FFD6BA',
          },
          boxShadow: {
            soft: "0 10px 30px rgba(201, 24, 74, 0.08)",
            card: "0 20px 60px rgba(201, 24, 74, 0.12)",
          },
          animation: {
            fadeIn: "fadeIn .6s ease-out both",
            fadeInUp: "fadeInUp .6s ease-out both",
            slideDown: "slideDown .4s ease-out both",
            scaleIn: "scaleIn .4s ease-out both",
            pulseSoft: "pulseSoft 2s ease-in-out infinite",
            shimmer: "shimmer 2.5s linear infinite",
          },
          keyframes: {
            fadeIn: {
              "0%": { opacity: 0 },
              "100%": { opacity: 1 },
            },
            fadeInUp: {
              "0%": { opacity: 0, transform: "translateY(20px)" },
              "100%": { opacity: 1, transform: "translateY(0)" },
            },
            slideDown: {
              "0%": { opacity: 0, transform: "translateY(-10px)" },
              "100%": { opacity: 1, transform: "translateY(0)" },
            },
            scaleIn: {
              "0%": { opacity: 0, transform: "scale(.95)" },
              "100%": { opacity: 1, transform: "scale(1)" },
            },
            pulseSoft: {
              "0%,100%": { transform: "scale(1)" },
              "50%": { transform: "scale(1.02)" },
            },
            shimmer: {
              "0%": { backgroundPosition: "-100% 0" },
              "100%": { backgroundPosition: "200% 0" },
            },
          },
        },
      },
    };
  </script>

  <style>
    body { 
      font-family: "Inter", sans-serif; 
      background: linear-gradient(to bottom, #FFF8F3, #FFFFFF);
    }
    .font-display { font-family: 'Playfair Display', serif; }
    .gradient-bg {
      background: linear-gradient(135deg, #FF6B9D 0%, #C9184A 100%);
    }
    .btn-primary {
      background: linear-gradient(135deg, #FF6B9D 0%, #C9184A 100%);
      transition: transform .3s ease, box-shadow .3s ease;
    }
    .btn-primary:hover { 
      transform: translateY(-2px); 
      box-shadow: 0 15px 40px rgba(201, 24, 74, 0.3); 
    }
    .btn-success { 
      background: linear-gradient(135deg, #10B981 0%, #059669 100%); 
      transition: transform .3s ease, box-shadow .3s ease; 
    }
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
<body class="min-h-screen">
  <!-- Modern Navigation -->
  <nav class="gradient-bg text-white shadow-card sticky top-0 z-40 backdrop-blur-md">
    <div class="container mx-auto px-6 lg:px-12 py-5">
      <div class="flex justify-between items-center">
        <!-- Logo -->
        <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
          @if(file_exists(public_path('images/fekra-logo.png')))
            <img src="{{ asset('images/fekra-logo.png') }}" alt="Fekra Store" class="h-10 w-auto drop-shadow-md group-hover:scale-105 transition-transform duration-300"/>
          @endif
          <span class="font-display text-2xl md:text-3xl font-bold tracking-tight">Fekra Store</span>
        </a>

        <!-- Nav Links -->
        <div class="flex items-center gap-6">
          <a href="{{ route('landing') }}" class="hover:text-brand-rose-light transition-colors duration-300 font-medium">Home</a>
          <div class="relative">
            <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-bold shadow-soft">
              {{ $cartCount }} {{ $cartCount === 1 ? 'Item' : 'Items' }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <!-- Cart Content -->
  <div class="container mx-auto px-6 lg:px-12 py-12">
    <!-- Page Header -->
    <div class="text-center mb-12 animate-fadeInUp">
      <h1 class="font-display text-5xl md:text-6xl font-bold text-brand-charcoal mb-4">
        Shopping Cart
      </h1>
      <p class="text-brand-slate text-lg">Review your selected items and complete your purchase</p>
    </div>

    @if(empty($cart))
      <!-- Enhanced Empty Cart State -->
      <div class="text-center py-20 animate-fadeInUp">
        <div class="relative mb-8">
          <div class="text-9xl animate-pulseSoft opacity-20">🛒</div>
          <div class="absolute inset-0 flex items-center justify-center">
            <div class="w-24 h-24 bg-gradient-to-br from-brand-rose-light to-brand-rose rounded-full flex items-center justify-center animate-scaleIn" style="animation-delay: 0.3s;">
              <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
              </svg>
            </div>
          </div>
        </div>
        <h2 class="font-display text-4xl font-bold text-brand-charcoal mb-4 animate-fadeInUp" style="animation-delay: 0.2s;">سلتك فارغة</h2>
        <p class="text-brand-slate mb-10 text-xl animate-fadeInUp" style="animation-delay: 0.4s;">ابدأ بإضافة منتجات رائعة إلى سلتك</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fadeInUp" style="animation-delay: 0.6s;">
          <a href="{{ route('landing') }}" class="btn-primary btn-enhanced inline-flex items-center gap-3 text-white px-10 py-5 rounded-2xl font-bold text-lg shadow-card hover:shadow-soft transition-all duration-300 transform hover:scale-105">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>تصفح المنتجات</span>
          </a>
          <div class="text-sm text-brand-slate flex items-center gap-2">
            <span class="w-2 h-2 bg-brand-gold rounded-full animate-pulse"></span>
            <span>شحن مجاني للطلبات</span>
          </div>
        </div>
      </div>
    @else
      <div class="grid lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
          <div class="bg-white rounded-3xl shadow-card p-6 md:p-8 animate-scaleIn border border-brand-beige">
            <h2 class="font-display text-3xl font-bold text-brand-charcoal mb-8 flex items-center gap-3">
              <span class="text-brand-rose">📦</span>
              Your Items ({{ $cartCount }})
            </h2>

            <div class="space-y-6">
              @foreach($cart as $productId => $item)
                <div class="cart-item border border-brand-beige bg-gradient-to-br from-white to-brand-cream/30 rounded-2xl p-6 hover:shadow-card transition-all duration-300" data-product-id="{{ $productId }}">
                  <div class="flex flex-wrap items-center gap-6">
                    <!-- Product Image -->
                    <div class="relative">
                      <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="w-24 h-24 object-cover rounded-xl shadow-soft" />
                      <span class="absolute -top-2 -right-2 text-xs bg-gradient-to-r from-brand-rose to-brand-rose-dark text-white px-3 py-1 rounded-full shadow-soft font-bold">{{ $item['quantity'] }}</span>
                    </div>

                    <!-- Product Info -->
                    <div class="flex-1 min-w-[200px]">
                      <h3 class="text-xl font-bold text-brand-charcoal leading-tight mb-2">{{ $item['name'] }}</h3>
                      <p class="text-brandBlue font-extrabold text-base sm:text-lg mt-1">{{ number_format($item['price'], 0) }} ج.م</p>
                    </div>

                    <!-- Item Total -->
                    <div class="text-right">
                      <p class="text-sm text-brand-slate mb-1">Total</p>
                      <p class="item-total-{{ $productId }} text-2xl font-bold bg-gradient-to-r from-brand-rose to-brand-gold bg-clip-text text-transparent">{{ number_format($item['price'] * $item['quantity'], 0) }} EGP</p>
                    </div>

                    <!-- Quantity Controls -->
                    <div class="flex items-center gap-3">
                      <button onclick="changeQuantity({{ $productId }}, -1)"
                        class="quantity-btn btn-minus bg-brand-beige hover:bg-brand-gold hover:text-white text-brand-charcoal w-10 h-10 rounded-full flex items-center justify-center font-bold disabled:opacity-40 disabled:cursor-not-allowed transition-all duration-300"
                        {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>-
                      </button>
                      <span class="quantity-display-{{ $productId }} bg-white border-2 border-brand-beige text-brand-charcoal px-4 py-2 rounded-xl font-bold min-w-[4rem] text-center text-lg">{{ $item['quantity'] }}</span>
                      <button onclick="changeQuantity({{ $productId }}, 1)"
                        class="quantity-btn btn-plus bg-gradient-to-r from-brand-rose to-brand-rose-dark hover:shadow-soft text-white w-10 h-10 rounded-full flex items-center justify-center font-bold disabled:opacity-40 disabled:cursor-not-allowed transition-all duration-300"
                        {{ $item['quantity'] >= 10 ? 'disabled' : '' }}>+
                      </button>
                    </div>

                    <!-- Remove Button -->
                    <div>
                      <button onclick="removeFromCart({{ $productId }})" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-3 rounded-xl transition-all duration-300" aria-label="Remove item">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>

            <!-- Clear Cart Button -->
            <div class="mt-8 pt-6 border-t border-brand-beige">
              <button onclick="clearCart()" class="text-red-500 hover:text-red-700 hover:bg-red-50 font-bold px-6 py-3 rounded-xl transition-all duration-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                Clear All Items
              </button>
            </div>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-3xl shadow-card p-8 lg:sticky top-24 animate-scaleIn border border-brand-beige">
            <h2 class="font-display text-3xl font-bold text-brand-charcoal mb-8 flex items-center gap-3">
              <span class="text-brand-gold">💰</span>
              Order Summary
            </h2>

            <div class="space-y-5 mb-8">
              <div class="flex justify-between items-center text-brand-slate">
                <span class="font-medium">Items:</span>
                <span class="cart-count font-bold text-xl text-brand-charcoal">{{ $cartCount }}</span>
              </div>
              <div class="flex justify-between items-center text-brand-slate">
                <span class="font-medium">Subtotal:</span>
                <span class="cart-total font-bold text-xl text-brand-charcoal">{{ number_format($cartTotal, 0) }} EGP</span>
              </div>
              <div class="flex justify-between items-center text-brand-slate">
                <span class="font-medium">Shipping:</span>
                <span class="font-bold text-emerald-600 flex items-center gap-1">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                  FREE
                </span>
              </div>
              <hr class="border-brand-beige" />
              <div class="flex justify-between items-center pt-2">
                <span class="font-display text-2xl font-bold text-brand-charcoal">Total:</span>
                <span class="cart-total font-display text-3xl font-bold bg-gradient-to-r from-brand-rose to-brand-gold bg-clip-text text-transparent">{{ number_format($cartTotal, 0) }} EGP</span>
              </div>
            </div>

            <!-- Checkout Button -->
            <button onclick="showCheckoutModal()"
              class="w-full btn-primary text-white py-5 rounded-2xl font-bold text-lg shadow-card hover:shadow-soft transition-all duration-300 transform hover:scale-105 mb-4">
              Complete Order 🛍️
            </button>

            <!-- Continue Shopping -->
            <a href="{{ route('landing') }}"
              class="block w-full text-center bg-brand-beige hover:bg-brand-gold hover:text-white text-brand-charcoal py-4 rounded-2xl font-bold transition-all duration-300">
              Continue Shopping
            </a>
          </div>
        </div>
      </div>
    @endif
  </div>

  <!-- Modern Checkout Modal -->
  <div id="checkoutModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 items-center justify-center px-4 hidden" style="display: none;">
    <div id="cartModalPanel" class="bg-white rounded-3xl shadow-card w-full max-w-4xl max-h-[90vh] overflow-hidden animate-scaleIn">
      <!-- Header -->
      <div class="px-8 py-6 border-b border-brand-beige bg-gradient-to-r from-brand-cream to-white">
        <div class="flex items-center justify-between">
          <h3 class="font-display text-3xl font-bold text-brand-charcoal">Complete Your Order</h3>
          <button onclick="hideCheckoutModal()" class="w-10 h-10 rounded-full bg-brand-beige hover:bg-brand-rose hover:text-white transition-all duration-300 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
      </div>

      <!-- Body -->
      <div class="p-8 overflow-y-auto max-h-[calc(90vh-140px)] space-y-6">
        <!-- Cart Summary -->
        <div class="bg-gradient-to-br from-brand-rose/10 to-brand-gold/10 rounded-2xl p-6 border border-brand-beige">
          <h4 class="font-bold mb-4 text-brand-charcoal text-xl flex items-center gap-2">
            <span>🛒</span> Order Summary
          </h4>
          <div class="space-y-3 max-h-64 overflow-y-auto">
            @foreach($cart as $pid => $item)
              <div class="flex justify-between items-center bg-white rounded-xl p-4 border border-brand-beige shadow-soft">
                <div class="flex items-center gap-3">
                  <img src="{{ $item['image_url'] }}" class="w-14 h-14 rounded-lg object-cover shadow-soft" alt="{{ $item['name'] }}" />
                  <span class="font-medium text-brand-charcoal">{{ $item['name'] }}</span>
                </div>
                <div class="text-right">
                  <div class="text-brand-slate font-semibold">{{ $item['quantity'] }} × {{ number_format($item['price'],0) }} EGP</div>
                  <div class="font-bold bg-gradient-to-r from-brand-rose to-brand-gold bg-clip-text text-transparent">{{ number_format($item['price'] * $item['quantity'],0) }} EGP</div>
                </div>
              </div>
            @endforeach
          </div>
          <div class="flex justify-between items-center mt-6 pt-4 border-t border-brand-beige">
            <span class="font-display text-2xl font-bold text-brand-charcoal">Total</span>
            <span class="font-display text-3xl font-bold bg-gradient-to-r from-brand-rose to-brand-gold bg-clip-text text-transparent">{{ number_format($cartTotal,0) }} EGP</span>
          </div>
        </div>

        <!-- Order Form -->
        <form id="checkoutForm" method="POST" action="{{ route('orders.store') }}" class="space-y-5">
          @csrf
          <input type="hidden" name="cart_data" value='@json($cart)' />
          
          <div class="grid md:grid-cols-2 gap-5">
            <div>
              <label class="block text-brand-charcoal font-semibold mb-2">Full Name *</label>
              <input name="customer_name" required class="w-full border-2 border-brand-beige rounded-xl px-4 py-3 focus:ring-2 focus:ring-brand-rose focus:border-brand-rose transition-all" placeholder="Enter your full name" />
            </div>
            <div>
              <label class="block text-brand-charcoal font-semibold mb-2">Phone Number *</label>
              <input name="phone" required class="w-full border-2 border-brand-beige rounded-xl px-4 py-3 focus:ring-2 focus:ring-brand-rose focus:border-brand-rose transition-all" placeholder="01xxxxxxxxx" />
            </div>
          </div>

          <div>
            <label class="block text-brand-charcoal font-semibold mb-2">Detailed Address *</label>
            <textarea name="address" required rows="3" class="w-full border-2 border-brand-beige rounded-xl px-4 py-3 focus:ring-2 focus:ring-brand-rose focus:border-brand-rose transition-all" placeholder="City, District, Street, Building No."></textarea>
          </div>

          <div class="grid md:grid-cols-2 gap-5">
            <div>
              <label class="block text-brand-charcoal font-semibold mb-2">Governorate *</label>
              <select name="governorate" required class="w-full border-2 border-brand-beige rounded-xl px-4 py-3 focus:ring-2 focus:ring-brand-rose focus:border-brand-rose transition-all">
                <option value="">Select Governorate</option>
                <option value="Cairo">Cairo</option>
                <option value="Giza">Giza</option>
                <option value="Alexandria">Alexandria</option>
                <option value="Dakahlia">Dakahlia</option>
                <option value="Sharqia">Sharqia</option>
                <option value="Qalyubia">Qalyubia</option>
                <option value="Kafr El Sheikh">Kafr El Sheikh</option>
                <option value="Gharbia">Gharbia</option>
                <option value="Monufia">Monufia</option>
                <option value="Beheira">Beheira</option>
                <option value="Ismailia">Ismailia</option>
                <option value="Beni Suef">Beni Suef</option>
                <option value="Fayoum">Fayoum</option>
                <option value="Minya">Minya</option>
                <option value="Asyut">Asyut</option>
                <option value="Sohag">Sohag</option>
                <option value="Qena">Qena</option>
                <option value="Luxor">Luxor</option>
                <option value="Aswan">Aswan</option>
                <option value="Red Sea">Red Sea</option>
                <option value="New Valley">New Valley</option>
                <option value="Matrouh">Matrouh</option>
                <option value="North Sinai">North Sinai</option>
                <option value="South Sinai">South Sinai</option>
              </select>
            </div>
            <div>
              <label class="block text-brand-charcoal font-semibold mb-2">Notes (Optional)</label>
              <textarea name="notes" rows="3" class="w-full border-2 border-brand-beige rounded-xl px-4 py-3 focus:ring-2 focus:ring-brand-rose focus:border-brand-rose transition-all" placeholder="Any additional notes"></textarea>
            </div>
          </div>

          <div class="flex flex-col md:flex-row gap-4 pt-4">
            <button type="submit" class="flex-1 btn-success text-white font-bold py-4 rounded-2xl shadow-card hover:shadow-soft transition-all duration-300 transform hover:scale-105">
              Confirm Order ({{ number_format($cartTotal,0) }} EGP)
            </button>
            <button type="button" onclick="hideCheckoutModal()" class="flex-1 bg-brand-beige hover:bg-gray-300 text-brand-charcoal font-bold py-4 rounded-2xl transition-all duration-300">
              Cancel
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
      if (!confirm('Are you sure you want to remove this item from your cart?')) return;
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
          showMessage('Error removing item', 'error');
        }
      })
      .catch(() => showMessage('Error removing item', 'error'));
    }

    // Clear cart
    function clearCart() {
      if (!confirm('Are you sure you want to clear all items from your cart?')) return;
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
      .then(data => { if (data.success) location.reload(); else showMessage('Error clearing cart', 'error'); })
      .catch(() => showMessage('Error clearing cart', 'error'));
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
      m.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }
    function hideCheckoutModal() {
      const m = document.getElementById('checkoutModal');
      m.classList.add('hidden');
      m.style.display = 'none';
      document.body.style.overflow = 'auto';
    }

    // Toasts
    function showMessage(message, type) {
      const container = document.getElementById('messageContainer');
      const wrapper = document.createElement('div');
      wrapper.className = `p-4 rounded-2xl shadow-card mb-3 text-white animate-slideDown ${type === 'success' ? 'bg-emerald-500' : 'bg-red-500'}`;
      wrapper.innerHTML = `
        <div class="flex items-center justify-between gap-4">
          <span class="font-medium">${message}</span>
          <button class="text-white/90 hover:text-white font-bold text-xl" aria-label="Close" onclick="this.closest('div').remove()">×</button>
        </div>`;
      container.appendChild(wrapper);
      setTimeout(() => wrapper && wrapper.remove(), 5000);
    }

    // Keyboard & backdrop close
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') hideCheckoutModal(); });
    document.getElementById('checkoutModal').addEventListener('click', (e) => { if (e.target === e.currentTarget) hideCheckoutModal(); });
  </script>

  <!-- Modern Footer -->
  <footer class="relative overflow-hidden mt-20">
    <div class="absolute inset-0 bg-gradient-to-br from-brand-charcoal via-brand-slate to-brand-charcoal"></div>
    <div class="relative z-10 container mx-auto px-6 lg:px-12 py-12 text-center">
      <div class="mb-6">
        <h3 class="font-display text-3xl font-bold bg-gradient-to-r from-brand-rose-light to-brand-gold bg-clip-text text-transparent mb-2">Fekra Store</h3>
        <p class="text-gray-300 text-sm">Modern Women's Fashion</p>
      </div>
      
      <div class="flex justify-center gap-6 mb-8">
        <a href="#" class="w-10 h-10 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center hover:bg-brand-rose hover:scale-110 transition-all duration-300">
          <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
        </a>
        <a href="#" class="w-10 h-10 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center hover:bg-brand-rose hover:scale-110 transition-all duration-300">
          <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
        </a>
      </div>
      
      <p class="text-gray-400 text-sm">&copy; 2025 Fekra Store. All rights reserved.</p>
    </div>
  </footer>
</body>
</html>