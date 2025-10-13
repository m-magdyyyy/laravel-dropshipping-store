<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Primary Meta Tags -->
  <title>Fekra Store - Modern Women's Fashion | Modest & Practical Wear</title>
  <meta name="title" content="Fekra Store - Modern Women's Fashion | Modest & Practical Wear">
  <meta name="description" content="Shop the latest modest & modern women's fashion: trendy hijab outfits, coordinated casual sets, premium quality, fast delivery, cash on delivery.">
  <meta name="keywords" content="women fashion, modest outfits, hijab set, casual wear, women's clothing Egypt, coordinated sets, modern hijab, modest fashion, trendy outfits, women's wear, fashion store, Cairo fashion, online shopping Egypt, cash on delivery">
    <meta name="author" content="Fekra Store">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Arabic">
    <meta name="revisit-after" content="7 days">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
  <meta property="og:title" content="Fekra Store - Modern Women's Fashion | Modest & Practical Wear">
  <meta property="og:description" content="Trendy modest hijab outfits, quality casual wear, stylish coordinated sets. Fast delivery & cash on delivery available.">
    <meta property="og:image" content="{{ file_exists(public_path('images/fekra-new-logo.png')) ? asset('images/fekra-new-logo.png') : asset('images/fekra-logo.png') }}">
    <meta property="og:locale" content="ar_EG">
    <meta property="og:site_name" content="Fekra Store">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url('/') }}">
  <meta name="twitter:title" content="Fekra Store - Modern Women's Fashion | Modest & Practical Wear">
  <meta name="twitter:description" content="Trendy modest hijab outfits, coordinated sets & premium quality.">
    <meta name="twitter:image" content="{{ file_exists(public_path('images/fekra-new-logo.png')) ? asset('images/fekra-new-logo.png') : asset('images/fekra-logo.png') }}">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url('/') }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ file_exists(public_path('images/fekra-new-logo.png')) ? asset('images/fekra-new-logo.png') : asset('images/fekra-logo.png') }}">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">

    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              'sans': ['Inter', 'system-ui', 'sans-serif'],
              'display': ['Playfair Display', 'serif'],
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
                lavender: '#E5D9F2',
                mint: '#A8DADC',
                peach: '#FFD6BA',
              }
            },
            animation: {
              'float': 'float 6s ease-in-out infinite',
              'pulse-slow': 'pulse 3s ease-in-out infinite',
              'bounce-slow': 'bounce 2s infinite',
              'fade-in': 'fadeIn 0.6s ease-out forwards',
              'fade-in-up': 'fadeInUp 0.7s ease-out forwards',
              'slide-in-right': 'slideInRight 0.8s ease-out forwards',
              'scale-in': 'scaleIn 0.5s ease-out forwards',
            },
            keyframes: {
              float: {
                '0%, 100%': { transform: 'translateY(0px)' },
                '50%': { transform: 'translateY(-20px)' },
              },
              fadeIn: {
                '0%': { opacity: '0' },
                '100%': { opacity: '1' }
              },
              fadeInUp: {
                '0%': { opacity: '0', transform: 'translateY(30px)' },
                '100%': { opacity: '1', transform: 'translateY(0)' }
              },
              slideInRight: {
                '0%': { opacity: '0', transform: 'translateX(50px)' },
                '100%': { opacity: '1', transform: 'translateX(0)' }
              },
              scaleIn: {
                '0%': { opacity: '0', transform: 'scale(0.9)' },
                '100%': { opacity: '1', transform: 'scale(1)' }
              }
            },
            boxShadow: {
              'soft': '0 10px 40px rgba(0,0,0,0.08)',
              'glow': '0 0 30px rgba(255,107,157,0.3)',
              'card': '0 4px 20px rgba(0,0,0,0.06)',
            },
          }
        }
      }
    </script>

    <style>
      * { margin: 0; padding: 0; box-sizing: border-box; }
      body { 
        font-family: 'Inter', system-ui, sans-serif; 
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
      }
      
      /* Modern Gradient Background */
      .gradient-bg {
        background: linear-gradient(135deg, #FF6B9D 0%, #C9184A 50%, #8B1E3F 100%);
        position: relative;
      }
      
      .gradient-bg::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(255,107,157,0.2) 0%, transparent 50%);
        pointer-events: none;
      }
      
      /* Glass Morphism */
      .glass-effect { 
        backdrop-filter: blur(20px) saturate(180%);
        background: rgba(255,255,255,0.75);
        border: 1px solid rgba(255,255,255,0.3);
      }
      
      .glass-dark {
        backdrop-filter: blur(20px) saturate(180%);
        background: rgba(255,107,157,0.1);
        border: 1px solid rgba(255,255,255,0.2);
      }
      
      /* Modern Card Hover */
      .product-card { 
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        transform: translateY(0);
      }
      .product-card:hover { 
        transform: translateY(-12px) scale(1.02);
        box-shadow: 0 20px 60px rgba(255,107,157,0.25);
      }
      
      /* Smooth Hover Effects */
      .hover-scale { 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      }
      .hover-scale:hover { 
        transform: scale(1.05);
      }
      
      /* Modern Button */
      .btn-primary {
        background: linear-gradient(135deg, #FF6B9D 0%, #C9184A 100%);
        box-shadow: 0 4px 15px rgba(255,107,157,0.4);
        transition: all 0.3s ease;
      }
      .btn-primary:hover {
        box-shadow: 0 6px 25px rgba(255,107,157,0.6);
        transform: translateY(-2px);
      }
      
      /* Typewriter Cursor */
      .tw-cursor { 
        display:inline-block; 
        width:1ch; 
        animation: blink 1s steps(1,end) infinite; 
      }
      @keyframes blink { 
        0%,49% {opacity:1} 
        50%,100% {opacity:0} 
      }
      
      /* Scroll Animations */
      .scroll-reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease-out;
      }
      .scroll-reveal.active {
        opacity: 1;
        transform: translateY(0);
      }
      
      @media (prefers-reduced-motion: reduce) { 
        *, *::before, *::after { 
          animation-duration: 0.01ms !important;
          animation-iteration-count: 1 !important;
          transition-duration: 0.01ms !important;
        }
      }
    </style>
    
    <!-- Google Analytics 4 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=GA_TRACKING_ID"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'GA_TRACKING_ID', {
        page_title: 'Fekra Store - الصفحة الرئيسية',
        page_location: window.location.href
      });
    </script>

    <!-- Facebook Pixel (Meta Pixel) -->
    <script>
      !function(f,b,e,v,n,t,s)
      {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};
      if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
      n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t,s)}(window, document,'script',
      'https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', 'YOUR_PIXEL_ID');
      fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id=YOUR_PIXEL_ID&ev=PageView&noscript=1"
    /></noscript>
    
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
  
  <!-- Schema.org Structured Data -->
  @php
    $orgSchema = [
      '@context' => 'https://schema.org',
      '@type' => 'Organization',
      'name' => 'Fekra Store',
      'alternateName' => 'فكرة ستور',
      'url' => url('/'),
      'logo' => file_exists(public_path('images/fekra-new-logo.png')) ? asset('images/fekra-new-logo.png') : asset('images/fekra-logo.png'),
      'description' => 'متجر فكرة للأزياء النسائية العصرية والمحتشمة - أزياء راقية وجودة عالية مع التوصيل السريع',
      'contactPoint' => [
        '@type' => 'ContactPoint',
        'telephone' => '+201201297965',
        'contactType' => 'customer service',
        'availableLanguage' => ['Arabic', 'English']
      ],
      'address' => [
        '@type' => 'PostalAddress',
        'addressLocality' => 'Cairo',
        'addressCountry' => 'EG'
      ],
      // Social profiles
      'sameAs' => [
        'https://wa.me/201201297965',
        'https://www.facebook.com/fekrastore',
        'https://www.instagram.com/fekrastore'
      ],
    ];

    $webSiteSchema = [
      '@context' => 'https://schema.org',
      '@type' => 'WebSite',
      'name' => 'Fekra Store',
      'url' => url('/'),
      'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => url('/').'?search={search_term_string}',
        'query-input' => 'required name=search_term_string'
      ]
    ];

    $itemListSchema = null;
    if($products->count() > 0){
      $elements = [];
      foreach($products as $idx => $p){
        $elements[] = [
          '@type' => 'ListItem',
          'position' => $idx + 1,
          'item' => [
            '@type' => 'Product',
            'name' => $p->name,
            'image' => $p->image_url,
            'description' => $p->description,
            'offers' => [
              '@type' => 'Offer',
              'price' => (string)$p->price,
              'priceCurrency' => 'EGP',
              'availability' => 'https://schema.org/InStock',
              'url' => route('product.show', $p->slug)
            ]
          ]
        ];
      }
      $itemListSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'itemListElement' => $elements,
      ];
    }
  @endphp
  <script type="application/ld+json">{!! json_encode($orgSchema, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
  @if($itemListSchema)
    <script type="application/ld+json">{!! json_encode($itemListSchema, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
  @endif
</head>
<body class="bg-brand-light font-cairo text-brand-darker opacity-0 transition-opacity duration-700">

  <!-- Floating WhatsApp Button -->
  <a href="https://wa.me/201201297965" target="_blank" class="fixed bottom-6 left-6 z-50 bg-green-500 hover:bg-green-600 text-white w-16 h-16 rounded-full flex items-center justify-center shadow-2xl hover:shadow-glow transition-all hover:scale-110 group" aria-label="Contact us on WhatsApp">
    <svg class="w-8 h-8 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
    </svg>
    <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full animate-ping"></span>
    <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full"></span>
  </a>

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

  <!-- Modern Navigation Bar -->
  <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 glass-effect shadow-sm transition-all duration-300">
    <div class="container mx-auto px-6 lg:px-12">
      <div class="flex justify-between items-center py-3">
        <!-- Logo -->
        <div class="flex items-center gap-3">
          <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
            <div class="w-14 h-14 rounded-full p-2 shadow-xl backdrop-blur-sm border-2 border-purple-600/40 flex items-center justify-center group-hover:shadow-2xl transition-all duration-300" style="background: linear-gradient(135deg, #4c1d54 0%, #6b2c7a 100%);">
              <img src="{{ asset('images/fekra-new-logo.png') }}" alt="Fekra Store" class="w-10 h-10 object-contain drop-shadow-sm group-hover:scale-110 transition-transform duration-300"/>
            </div>
            <span class="text-xl lg:text-2xl font-bold font-display text-white drop-shadow-lg group-hover:scale-105 transition">
              Fekra Store
            </span>
          </a>
        </div>

        <!-- Links -->
        <div class="hidden md:flex items-center gap-8">
          <a href="#products" class="text-brand-charcoal hover:text-brand-rose font-medium transition-colors">Shop</a>
          <a href="#collections" class="text-brand-charcoal hover:text-brand-rose font-medium transition-colors">Collections</a>
          <a href="#features" class="text-brand-charcoal hover:text-brand-rose font-medium transition-colors">Features</a>
          <a href="#testimonials" class="text-brand-charcoal hover:text-brand-rose font-medium transition-colors">Reviews</a>
        </div>

        <!-- Cart & Actions -->
        <div class="flex items-center gap-3">
          <a href="{{ route('cart.show') }}" class="relative group">
            <div class="bg-gradient-to-r from-brand-rose to-brand-rose-dark p-2.5 rounded-full transition-all group-hover:shadow-glow">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
              </svg>
            </div>
            <span id="cart-badge" class="absolute -top-1 -right-1 bg-brand-gold text-white text-xs font-bold px-1.5 py-0.5 rounded-full min-w-[1rem] text-center hidden shadow-md">0</span>
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Modern Hero Section -->
  <section class="relative overflow-hidden min-h-screen flex items-center pt-24 gradient-bg">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 opacity-10 pointer-events-none">
      <div class="absolute top-20 left-10 w-32 h-32 bg-white rounded-full blur-3xl animate-float"></div>
      <div class="absolute top-1/3 right-20 w-40 h-40 bg-brand-gold rounded-full blur-3xl animate-pulse-slow"></div>
      <div class="absolute bottom-20 left-1/4 w-24 h-24 bg-white rounded-full blur-2xl animate-bounce-slow"></div>
      <div class="absolute bottom-1/3 right-16 w-36 h-36 bg-brand-rose-light rounded-full blur-3xl animate-float" style="animation-delay: 1s"></div>
    </div>

    <div class="container mx-auto px-6 lg:px-12 py-20 relative z-10">
      <div class="grid lg:grid-cols-2 gap-12 items-center max-w-7xl mx-auto">
        <!-- Hero Content -->
        <div class="text-center lg:text-left space-y-8">
          <div class="inline-block glass-dark px-8 py-3 rounded-full text-white text-base font-medium animate-fade-in shadow-lg">
            ✨ New Collection Available
          </div>
          
          <h1 class="text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-display font-bold leading-tight text-white animate-fade-in-up">
            Modest Fashion,
            <span class="block mt-3 bg-gradient-to-r from-brand-gold-light via-brand-gold to-brand-gold-light bg-clip-text text-transparent">
              Modern Style
            </span>
          </h1>
          
          <p class="text-lg md:text-xl lg:text-2xl text-white/95 max-w-2xl mx-auto lg:mx-0 leading-relaxed animate-fade-in-up font-light" style="animation-delay: 0.1s">
            Trendy hijab outfits & casual wear for modern women. Comfortable sets, stylish coordinates, and colorful designs.
          </p>

          <!-- CTA Buttons -->
          <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start items-center animate-fade-in-up pt-2" style="animation-delay: 0.2s">
            <a href="#products" class="btn-primary text-white font-bold py-4 px-10 rounded-full text-lg inline-flex items-center justify-center gap-3 hover-scale shadow-2xl">
              <span>Shop Now</span>
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
              </svg>
            </a>
            <a href="#collections" class="glass-dark text-white font-bold py-4 px-10 rounded-full text-lg hover:bg-white/30 transition-all inline-flex items-center justify-center gap-3 shadow-xl">
              <span>Explore Collections</span>
            </a>
          </div>


        </div>

        <!-- Hero Image (fixed visibility & local source with fallback) -->
        <div class="relative animate-fade-in-up" style="animation-delay:0.4s">
          <div class="relative rounded-3xl overflow-hidden shadow-2xl group">
            <picture>
              {{-- Use existing hero image path; optional webp if added later --}}
              @if(file_exists(public_path('images/hero/fashion-model.webp')))
                <source srcset="{{ asset('images/hero/fashion-model.webp') }}" type="image/webp">
              @endif
              @php $heroImg = file_exists(public_path('images/hero/fashion-model.jpg')) ? 'images/hero/fashion-model.jpg' : (file_exists(public_path('images/hero/fashion-model.png')) ? 'images/hero/fashion-model.png' : null); @endphp
              <img 
                src="{{ $heroImg ? asset($heroImg) : 'https://images.unsplash.com/photo-1520970014086-2208d157c9e2?auto=format&fit=crop&w=900&q=70' }}" 
                alt="Modest Fashion Hero" 
                class="w-full h-full object-cover aspect-[2/3] md:aspect-[3/4] lg:aspect-[4/5]" 
                onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1520970014086-2208d157c9e2?auto=format&fit=crop&w=900&q=70';">
            </picture>
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>

            <!-- Floating Badges -->
            <div class="absolute top-4 left-4 glass-dark px-5 py-3 rounded-2xl shadow-xl backdrop-blur-md">
              <div class="text-white text-sm leading-tight"><span class="block text-xl font-bold">500+</span>Customers</div>
            </div>
            <div class="absolute top-4 right-4 glass-dark px-5 py-3 rounded-2xl shadow-xl backdrop-blur-md">
              <div class="text-white text-sm leading-tight"><span class="block text-xl font-bold">⭐ 4.9</span>Rating</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Products Section -->
  <section id="products" class="py-24 bg-gradient-to-b from-white to-brand-cream">
    <div class="container mx-auto px-6 lg:px-12">
      <div class="text-center mb-16 scroll-reveal">
        <div class="inline-block bg-brand-rose/10 px-6 py-2 rounded-full text-brand-rose font-semibold mb-4">
          المنتجات المميزة
        </div>
        <h2 class="text-4xl lg:text-6xl font-display font-bold text-brand-charcoal mb-4">
          الأكثر رواجاً الآن
        </h2>
        <p class="text-lg text-brand-slate max-w-2xl mx-auto">
          مجموعة مختارة بعناية من أفضل المنتجات العصرية بأسعار تنافسية. 
          <a href="#products" class="text-brand-rose hover:underline font-medium">اكتشف المزيد</a>
        </p>
      </div>

      @if($products->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          @foreach($products as $product)
          <div class="group relative overflow-hidden rounded-3xl shadow-card hover:shadow-glow transition-all duration-500 scroll-reveal" data-product-id="{{ $product->id }}">
            <!-- Background Image with Overlay -->
            <div class="relative aspect-[3/4] overflow-hidden bg-white">
              @php
                $allImages = [];
                // Add main image first
                if ($product->image_url) {
                    $allImages[] = $product->image_url;
                }
                // Add gallery images if available
                if ($product->gallery && is_array($product->gallery)) {
                    foreach ($product->gallery as $galleryImg) {
                        if (!empty($galleryImg)) {
                            // Check if it's a full URL or storage path
                            $imgUrl = str_starts_with($galleryImg, 'http') ? $galleryImg : '/storage/' . ltrim($galleryImg, '/');
                            if (!in_array($imgUrl, $allImages)) {
                                $allImages[] = $imgUrl;
                            }
                        }
                    }
                }
                $hasMultipleImages = count($allImages) > 1;
              @endphp
              
              <!-- Image Carousel Container -->
              <div class="product-carousel relative w-full h-full" data-carousel-id="carousel-{{ $product->id }}">
                @foreach($allImages as $index => $imageUrl)
                  <picture class="carousel-image absolute inset-0 w-full h-full transition-opacity duration-500 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}" data-index="{{ $index }}">
                    @php
                      $webpUrl = null;
                      $imgUrl = $imageUrl;
                      // If it's a local path (starts with /) check if a matching .webp exists
                      if (!str_starts_with($imgUrl, 'http')) {
                          $relative = ltrim($imgUrl, '/'); // e.g. storage/products/img.jpg
                          $fullPath = public_path($relative);
                          if (preg_match('/\.(jpg|jpeg|png)$/i', $fullPath)) {
                              $webpFs = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $fullPath);
                              if ($webpFs && file_exists($webpFs)) {
                                  $webpUrl = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $imgUrl);
                              }
                          } elseif (preg_match('/\.webp$/i', $fullPath) && file_exists($fullPath)) {
                              $webpUrl = $imgUrl; // already webp
                          }
                      } else {
                          // Remote URL: only use if it's already webp
                          if (preg_match('/\.webp($|\?)/i', $imgUrl)) {
                              $webpUrl = $imgUrl;
                          }
                      }
                    @endphp
                    @if($webpUrl)
                      <source srcset="{{ $webpUrl }}" type="image/webp">
                    @endif
                    <img 
                      src="{{ $imgUrl }}" 
                      alt="{{ $product->name }} - صورة المنتج {{ $index + 1 }}" 
                      class="w-full h-full object-contain" 
                      loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                      decoding="async"
                      onerror="this.src='https://via.placeholder.com/400x600?text=No+Image'">
                  </picture>
                @endforeach
              </div>
              
              <!-- Dark overlay for text readability -->
              <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-black/20 group-hover:from-black/90 transition-all duration-300 pointer-events-none"></div>

              <!-- Carousel Navigation Dots -->
              @if($hasMultipleImages)
              <div class="absolute top-4 left-1/2 -translate-x-1/2 flex gap-2 z-20">
                @foreach($allImages as $index => $imageUrl)
                  <button 
                    onclick="goToSlide('carousel-{{ $product->id }}', {{ $index }})"
                    class="carousel-dot w-2 h-2 rounded-full transition-all {{ $index === 0 ? 'bg-white w-6' : 'bg-white/50' }}"
                    data-carousel-id="carousel-{{ $product->id }}"
                    data-index="{{ $index }}"
                    aria-label="Go to image {{ $index + 1 }}">
                  </button>
                @endforeach
              </div>
              @endif

              <!-- Discount Badge -->
              @if($product->discount_percentage > 0)
              <div class="absolute top-6 right-6 z-20">
                <span class="bg-gradient-to-r from-brand-rose to-brand-rose-dark text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg">
                  -{{ $product->discount_percentage }}%
                </span>
              </div>
              @endif

              <!-- Content Overlay -->
              <div class="absolute inset-0 p-6 flex flex-col justify-end z-10">
                <!-- Rating -->
                @php
                  $seed = $product->id * 37;
                  $randomRating = (($seed % 11) + 40) / 10;
                  $fullStars = floor($randomRating);
                  $hasHalfStar = ($randomRating - $fullStars) >= 0.5;
                @endphp
                <div class="flex items-center gap-2 mb-3">
                  <span class="flex text-yellow-400">
                    @for($i=0; $i<$fullStars; $i++)
                      <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20" class="w-4 h-4"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.97c.3.921-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.197-1.539-1.118l1.287-3.97a1 1 0 00-.364-1.118L2.174 9.397c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.97z"/></svg>
                    @endfor
                    @if($hasHalfStar)
                      <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20" class="w-4 h-4"><defs><linearGradient id="half-{{ $product->id }}"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="#666"/></linearGradient></defs><path fill="url(#half-{{ $product->id }})" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.97c.3.921-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.197-1.539-1.118l1.287-3.97a1 1 0 00-.364-1.118L2.174 9.397c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.97z"/></svg>
                    @endif
                  </span>
                  <span class="text-sm text-white/90 font-semibold">{{ $randomRating }}</span>
                </div>

                <!-- Price -->
                <div class="flex items-baseline gap-3 mb-4">
                  <span class="text-3xl font-bold text-white">{{ $product->formatted_price }}</span>
                  @if($product->original_price && $product->original_price > $product->price)
                    <span class="text-lg text-white/60 line-through">{{ $product->formatted_original_price }}</span>
                  @endif
                </div>

                <!-- Action Buttons - Always Visible -->
                <div class="flex gap-3 transition-all duration-300">
                  <button onclick="addToCart({{ $product->id }})" class="flex-1 bg-white/90 hover:bg-white text-brand-charcoal font-bold py-3 px-4 rounded-xl transition-all flex items-center justify-center gap-2 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Add to Cart</span>
                  </button>
                  <a href="{{ route('product.show', $product->slug) }}" class="flex-1 btn-primary text-white font-bold py-3 px-4 rounded-xl text-center flex items-center justify-center gap-2 shadow-lg">
                    <span>Buy Now</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      @else
        <div class="text-center py-20 scroll-reveal">
          <div class="text-8xl mb-6 animate-bounce">✨</div>
          <h3 class="text-4xl font-display font-bold text-brand-charcoal mb-4">New Arrivals Coming Soon!</h3>
          <p class="text-lg text-brand-slate mb-8">We're working on adding amazing products just for you</p>
          <a href="#" class="btn-primary text-white font-semibold py-4 px-8 rounded-full inline-flex items-center gap-2">
            <span>Subscribe for Updates</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
          </a>
        </div>
      @endif
    </div>
  </section>

  <!-- Testimonials Section -->
  <section id="testimonials" class="py-24 bg-white">
    <div class="container mx-auto px-6 lg:px-12">
      <div class="text-center mb-16 scroll-reveal">
        <div class="inline-block bg-brand-rose/10 px-6 py-2 rounded-full text-brand-rose font-semibold mb-4">
          Customer Reviews
        </div>
        <h2 class="text-4xl lg:text-6xl font-display font-bold text-brand-charcoal mb-4">
          What Our Customers Say
        </h2>
        <p class="text-lg text-brand-slate max-w-2xl mx-auto">
          Real reviews from our amazing customers who love shopping with us
        </p>
      </div>

      @php
        // Centralized testimonials data with variable ratings
        $reviews = [
          [
            'initial' => 'S',
            'name' => 'Sarah A.',
            'city' => 'Cairo',
            'avatar_gradient' => 'from-brand-rose to-brand-rose-dark',
            'rating' => 5,
            'text' => 'Absolutely love my purchase! The quality is amazing and delivery was super fast. Will definitely order again!',
          ],
          [
            'initial' => 'N',
            'name' => 'Nora M.',
            'city' => 'Alexandria',
            'avatar_gradient' => 'from-brand-gold to-brand-gold-light',
            'rating' => 4,
            'text' => 'Great prices and excellent customer service. The packaging was beautiful too. Highly recommended!',
          ],
          [
            'initial' => 'L',
            'name' => 'Layla K.',
            'city' => 'Giza',
            'avatar_gradient' => 'from-brand-mint to-brand-charcoal',
            'rating' => 4,
            'text' => "I've ordered twice and both times exceeded my expectations. Quality and service are top-notch!",
          ],
        ];
      @endphp

      <div class="grid md:grid-cols-3 gap-8">
        @foreach ($reviews as $index => $review)
          <div class="bg-gradient-to-br from-brand-cream to-white rounded-3xl p-8 border border-brand-beige hover:shadow-card transition-all duration-500 scroll-reveal" @if($index) style="animation-delay: {{ number_format($index * 0.1, 1) }}s" @endif>
            <div class="flex items-center gap-4 mb-6">
              <div class="w-16 h-16 rounded-full bg-gradient-to-br {{ $review['avatar_gradient'] }} flex items-center justify-center text-white font-bold text-xl shadow-soft">
                {{ $review['initial'] }}
              </div>
              <div>
                <p class="font-bold text-brand-charcoal text-lg">{{ $review['name'] }}</p>
                <p class="text-sm text-brand-slate">{{ $review['city'] }}</p>
              </div>
            </div>
            <div class="flex items-center gap-1 text-xl mb-4">
              @for ($i = 1; $i <= 5; $i++)
                <span class="{{ $i <= $review['rating'] ? 'text-yellow-400' : 'text-gray-300' }}">⭐</span>
              @endfor
            </div>
            <p class="text-brand-slate leading-relaxed">"{{ $review['text'] }}"</p>
          </div>
        @endforeach
      </div>

      <div class="text-center mt-12 scroll-reveal">
        <a href="#products" class="btn-primary text-white font-semibold py-4 px-10 rounded-full inline-flex items-center gap-2">
          <span>Start Shopping</span>
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
          </svg>
        </a>
      </div>
    </div>
  </section>

  <!-- Modern Footer -->
  <footer class="bg-gradient-to-br from-brand-charcoal via-brand-slate to-brand-charcoal text-white pt-20 pb-10">
    <div class="container mx-auto px-6 lg:px-12">
      <div class="grid md:grid-cols-4 gap-12 mb-12">
        <!-- Brand Column -->
        <div class="md:col-span-2">
          <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-full p-3 backdrop-blur-sm border-2 border-purple-600/30 flex items-center justify-center" style="background: linear-gradient(135deg, #4c1d54 0%, #6b2c7a 100%);">
              <img src="{{ asset('images/fekra-new-logo.png') }}" alt="Fekra Store" class="w-12 h-12 object-contain drop-shadow-lg"/>
            </div>
            <h3 class="text-3xl lg:text-4xl font-bold font-display bg-gradient-to-r from-brand-rose-light to-brand-gold-light bg-clip-text text-transparent">
              Fekra Store
            </h3>
          </div>
          <p class="text-white/70 leading-relaxed mb-6 max-w-md">
            Your destination for modern women's fashion. Quality products, competitive prices, and exceptional service.
          </p>
          <div class="flex gap-3">
            <a href="https://www.tiktok.com/@fekra__store" class="w-12 h-12 bg-white/10 hover:bg-brand-rose rounded-full flex items-center justify-center transition-all hover:scale-110" aria-label="TikTok">
              <span class="text-xl">📱</span>
            </a>
            <a href="#" class="w-12 h-12 bg-white/10 hover:bg-brand-rose rounded-full flex items-center justify-center transition-all hover:scale-110" aria-label="Instagram">
              <span class="text-xl">📷</span>
            </a>
            <a href="#" class="w-12 h-12 bg-white/10 hover:bg-brand-rose rounded-full flex items-center justify-center transition-all hover:scale-110" aria-label="Facebook">
              <span class="text-xl">👥</span>
            </a>
          </div>
        </div>

        <!-- Quick Links -->
        <div>
          <h4 class="text-lg font-bold mb-6 text-white">Quick Links</h4>
          <ul class="space-y-3 text-white/70">
            <li><a href="#products" class="hover:text-brand-rose transition-colors hover:translate-x-1 inline-block">Shop</a></li>
            <li><a href="#collections" class="hover:text-brand-rose transition-colors hover:translate-x-1 inline-block">Collections</a></li>
            <li><a href="#features" class="hover:text-brand-rose transition-colors hover:translate-x-1 inline-block">Features</a></li>
            <li><a href="#testimonials" class="hover:text-brand-rose transition-colors hover:translate-x-1 inline-block">Reviews</a></li>
            <li><a href="{{ route('cart.show') }}" class="hover:text-brand-rose transition-colors hover:translate-x-1 inline-block">Cart</a></li>
          </ul>
        </div>

        <!-- Contact -->
        <div>
          <h4 class="text-lg font-bold mb-6 text-white">Contact Us</h4>
          <ul class="space-y-3 text-white/70">
            <li class="flex items-center gap-2">
              <span class="text-brand-rose">📍</span>
              <span>Cairo, Egypt</span>
            </li>
            <li class="flex items-center gap-2">
              <span class="text-brand-rose">📞</span>
              <a href="tel:01201297965" class="hover:text-brand-rose transition-colors">01201297965</a>
            </li>
            <li class="flex items-center gap-2">
              <span class="text-brand-rose">💬</span>
              <a href="https://wa.me/201201297965" target="_blank" class="hover:text-brand-rose transition-colors">WhatsApp</a>
            </li>
            <li class="flex items-center gap-2">
              <span class="text-brand-rose">🚚</span>
              <span>Fast Delivery</span>
            </li>
            <li class="flex items-center gap-2">
              <span class="text-brand-rose">🔒</span>
              <span>Secure Payment</span>
            </li>
          </ul>
        </div>
      </div>

      <!-- Bottom Bar -->
      <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
        <p class="text-white/60 text-sm">
          &copy; 2025 <span class="text-brand-rose font-semibold">Fekra Store</span>. All rights reserved.
        </p>
        <div class="flex items-center gap-4 text-sm">
          <a href="#" class="text-white/60 hover:text-brand-rose transition-colors">Privacy Policy</a>
          <span class="text-white/30">•</span>
          <a href="#" class="text-white/60 hover:text-brand-rose transition-colors">Terms & Conditions</a>
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

    // Product Image Carousel
    const carouselState = {};
    
    function goToSlide(carouselId, index) {
      const carousel = document.querySelector(`[data-carousel-id="${carouselId}"]`);
      if (!carousel) return;
      
      const images = carousel.querySelectorAll('.carousel-image');
      const dots = document.querySelectorAll(`[data-carousel-id="${carouselId}"].carousel-dot`);
      
      // Hide all images
      images.forEach(img => img.classList.remove('opacity-100'));
      images.forEach(img => img.classList.add('opacity-0'));
      
      // Show selected image
      if (images[index]) {
        images[index].classList.remove('opacity-0');
        images[index].classList.add('opacity-100');
      }
      
      // Update dots
      dots.forEach(dot => {
        dot.classList.remove('w-6', 'bg-white');
        dot.classList.add('w-2', 'bg-white/50');
      });
      if (dots[index]) {
        dots[index].classList.remove('w-2', 'bg-white/50');
        dots[index].classList.add('w-6', 'bg-white');
      }
      
      // Update state
      carouselState[carouselId] = index;
    }
    
    function nextSlide(carouselId) {
      const carousel = document.querySelector(`[data-carousel-id="${carouselId}"]`);
      if (!carousel) return;
      
      const images = carousel.querySelectorAll('.carousel-image');
      const currentIndex = carouselState[carouselId] || 0;
      const nextIndex = (currentIndex + 1) % images.length;
      goToSlide(carouselId, nextIndex);
    }
    
    // Auto-rotate carousels
    function initCarousels() {
      document.querySelectorAll('.product-carousel').forEach(carousel => {
        const carouselId = carousel.getAttribute('data-carousel-id');
        const images = carousel.querySelectorAll('.carousel-image');
        
        if (images.length > 1) {
          carouselState[carouselId] = 0;
          
          // Auto-rotate every 3 seconds
          setInterval(() => {
            nextSlide(carouselId);
          }, 3000);
        }
      });
    }

    // Modern Navbar Scroll Effect
    let lastScroll = 0;
    window.addEventListener('scroll', () => {
      const navbar = document.getElementById('navbar');
      const currentScroll = window.scrollY;
      
      if (currentScroll > 100) {
        navbar.classList.add('shadow-lg');
      } else {
        navbar.classList.remove('shadow-lg');
      }
      
      lastScroll = currentScroll;
    });

    // Scroll Reveal Animation
    const scrollObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('active');
          scrollObserver.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    });

    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.scroll-reveal').forEach(el => {
        scrollObserver.observe(el);
      });
      
      // Initialize carousels
      initCarousels();
    });
  </script>
  
  <!-- Page Fade-in & Smooth Animations -->
  <script>
    // Ensure smooth page load
    window.addEventListener('load', () => {
      document.body.style.opacity = '1';
    });

    // Modern fade-in on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', () => {
      setTimeout(() => {
        document.body.classList.remove('opacity-0');
      }, 100);
    });
  </script>
</body>
</html>
