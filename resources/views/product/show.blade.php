<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  
  <!-- Primary Meta Tags -->
  <title>{{ $product->meta_title ?: $product->name }} | فكرة ستور</title>
  <meta name="title" content="{{ $product->meta_title ?: $product->name }} | فكرة ستور">
  <meta name="description" content="{{ $product->meta_description ?: Str::limit($product->description, 155) }}" />
  <meta name="keywords" content="{{ $product->name }}, ملابس نسائية, أزياء محتشمة, فكرة ستور, شحن مجاني مصر">
  <meta name="robots" content="index, follow">
  
  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="product">
  <meta property="og:url" content="{{ route('product.show', $product->slug) }}">
  <meta property="og:title" content="{{ $product->name }} | فكرة ستور">
  <meta property="og:description" content="{{ Str::limit($product->description, 200) }}">
  <meta property="og:image" content="{{ $product->image_url }}">
  <meta property="og:locale" content="ar_EG">
  <meta property="product:price:amount" content="{{ $product->price }}">
  <meta property="product:price:currency" content="EGP">
  
  <!-- Twitter -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:url" content="{{ route('product.show', $product->slug) }}">
  <meta name="twitter:title" content="{{ $product->name }} | فكرة ستور">
  <meta name="twitter:description" content="{{ Str::limit($product->description, 200) }}">
  <meta name="twitter:image" content="{{ $product->image_url }}">
  
  <!-- Canonical URL -->
  <link rel="canonical" href="{{ route('product.show', $product->slug) }}">
  
  <!-- Schema.org Product Structured Data (no Blade directives inside JSON) -->
  @php
    $imagesArray = [];
    $imagesArray[] = $product->image_url;
    if(!empty($product->gallery)){
      foreach($product->gallery as $img){
        $imagesArray[] = str_starts_with($img, 'http') ? $img : asset('storage/' . ltrim($img, '/'));
      }
    }
    $schemaData = [
      '@context' => 'https://schema.org/',
      '@type' => 'Product',
      'name' => $product->name,
      'image' => $imagesArray,
      'description' => $product->description,
      'sku' => (string)$product->id,
      'category' => 'Women\'s Fashion',
      'brand' => [
        '@type' => 'Brand',
        'name' => 'Fekra Store',
        'url' => url('/')
      ],
      'manufacturer' => [
        '@type' => 'Organization',
        'name' => 'Fekra Store'
      ],
      'aggregateRating' => [
        '@type' => 'AggregateRating',
        'ratingValue' => '4.5',
        'reviewCount' => '15',
        'bestRating' => '5',
        'worstRating' => '1'
      ],
      'offers' => [
        '@type' => 'Offer',
        'url' => route('product.show', $product->slug),
        'priceCurrency' => 'EGP',
        'price' => (string)$product->price,
        'priceValidUntil' => now()->addMonths(3)->format('Y-m-d'),
        'availability' => 'https://schema.org/InStock',
        'itemCondition' => 'https://schema.org/NewCondition'
      ],
      'aggregateRating' => [
        '@type' => 'AggregateRating',
        'ratingValue' => number_format((($product->id * 37) % 11 + 40) / 10, 1),
        'reviewCount' => (($product->id * 37) % 14) + 2,
      ],
    ];
    
    // Breadcrumb Schema
    $breadcrumbSchema = [
      '@context' => 'https://schema.org/',
      '@type' => 'BreadcrumbList',
      'itemListElement' => [
        [
          '@type' => 'ListItem',
          'position' => 1,
          'name' => 'الرئيسية',
          'item' => url('/')
        ],
        [
          '@type' => 'ListItem',
          'position' => 2,
          'name' => 'المنتجات',
          'item' => url('/#products')
        ],
        [
          '@type' => 'ListItem',
          'position' => 3,
          'name' => $product->name,
          'item' => url()->current()
        ]
      ]
    ];
  @endphp
  <script type="application/ld+json">{!! json_encode($schemaData, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
  <script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet" />

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
              mint: '#A8DADC',
            },
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
    body { 
      font-family: 'Inter', system-ui, sans-serif; 
      background: #FFF8F3;
      color: #2D3142;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }
    img { max-width: 100%; height: auto; }

    /* ===== Modern Navbar ===== */
    .navbar { 
      position: fixed; 
      top: 0; 
      inset-inline: 0; 
      z-index: 50; 
      transition: all .3s ease;
      backdrop-filter: blur(20px) saturate(180%);
      background: rgba(255,255,255,0.75);
      border-bottom: 1px solid rgba(255,255,255,0.3);
    }
    .nav-solid { 
      background: linear-gradient(135deg, #FF6B9D 0%, #C9184A 100%); 
      color: #fff; 
      box-shadow: 0 4px 20px rgba(255,107,157,0.3);
    }
    .nav-transparent { background: rgba(255,255,255,0.75); color: #2D3142; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
    .navbar a { color: inherit; }
    .nav-solid .cart-btn { background: rgba(255,255,255,.15); color: #fff; }
    .nav-solid .cart-btn:hover { background: rgba(255,255,255,.25); box-shadow: 0 0 30px rgba(255,255,255,0.3); }
    .nav-transparent .cart-btn { background: linear-gradient(135deg, #FF6B9D 0%, #C9184A 100%); color:#fff; border: none; }
    .nav-transparent .cart-btn:hover { box-shadow: 0 4px 20px rgba(255,107,157,0.4); transform: translateY(-2px); }

    .gallery-image { cursor: pointer; transition: transform .2s ease; }
    .gallery-image:hover { transform: scale(1.03); }
    .main-image { max-height: 500px; object-fit: cover; }
    
    /* Portrait style for better product display */
    .main-image-portrait { 
      width: 100%;
      height: auto;
      max-height: 600px;
      object-fit: contain;
      aspect-ratio: 3/4;
      background: #f8f9fa;
      transition: opacity 0.3s ease;
    }
    
    /* Loading skeleton animations */
    .skeleton {
      background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
      background-size: 200% 100%;
      animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
      0% { background-position: 200% 0; }
      100% { background-position: -200% 0; }
    }
    
    .image-loading {
      opacity: 0.7;
      filter: blur(1px);
    }
    
    .image-loaded {
      opacity: 1;
      filter: none;
    }
    
    /* Micro animations */
    .pulse-gentle {
      animation: pulseGentle 2s ease-in-out infinite;
    }
    
    @keyframes pulseGentle {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.8; }
    }
    
    /* Enhanced button animations */
    .btn-enhanced {
      position: relative;
      overflow: hidden;
      transform: translateZ(0);
    }
    
    .btn-enhanced:before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }
    
    .btn-enhanced:hover:before {
      left: 100%;
    }
    
    /* Thumbnail gallery improvements */
    .thumbnail-wrapper {
      transition: all 0.3s ease;
      cursor: pointer;
    }
    
    .thumbnail-wrapper:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .thumbnails-scroll {
      scrollbar-width: none; /* Firefox */
      -ms-overflow-style: none; /* IE and Edge */
    }
    
    .thumbnails-scroll::-webkit-scrollbar {
      display: none; /* Chrome, Safari, Opera */
    }

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

    @media (max-width: 768px){ 
      .main-image-portrait{ max-height: 50vh; aspect-ratio: 3/4; } 
      .thumbnails-scroll{ display:flex !important; gap:.75rem; overflow-x:auto; padding-bottom:.5rem; -webkit-overflow-scrolling:touch; scroll-snap-type:x mandatory; } 
      .thumbnail-wrapper{ flex:0 0 auto; width: 4rem; scroll-snap-align:start; } 
      .order-btn{ padding:.75rem 1rem; font-size:1rem; } 
    }

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
    <!-- Google Analytics 4 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=GA_TRACKING_ID"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'GA_TRACKING_ID', {
        page_title: document.title,
        page_location: window.location.href
      });
      
      // Enhanced E-commerce tracking
      gtag('config', 'GA_TRACKING_ID', {
        custom_map: {'custom_parameter_1': 'product_id'}
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
</head>
<body class="bg-brand-cream pt-16 md:pt-20">
  <!-- Modern Navigation -->
  <nav id="siteNav" class="navbar nav-solid">
    <div class="container mx-auto px-6 lg:px-12 py-2 md:py-3">
      <div class="flex justify-between items-center">
        <!-- Logo + Brand -->
        <a href="{{ route('landing') }}" class="flex items-center gap-3 group hover:opacity-95 transition-all">
          <div class="w-14 h-14 rounded-full p-2 shadow-xl backdrop-blur-sm border-2 border-purple-600/40 flex items-center justify-center group-hover:shadow-2xl transition-all duration-300" style="background: linear-gradient(135deg, #4c1d54 0%, #6b2c7a 100%);">
            <img src="{{ asset('images/fekra-new-logo.png') }}" alt="Fekra Store" class="w-10 h-10 object-contain drop-shadow-sm group-hover:scale-110 transition-transform duration-300"/>
          </div>
          <span class="text-xl lg:text-2xl font-bold font-display text-white drop-shadow-lg group-hover:scale-105 transition">
            Fekra Store
          </span>
        </a>

        <!-- Links -->
        <div class="flex items-center gap-3 md:gap-4">
          <a href="{{ route('landing') }}" class="hover:opacity-80 text-sm md:text-base font-medium transition-all">Home</a>
          <a href="{{ route('cart.show') }}" class="cart-btn relative p-2.5 rounded-full transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <span id="cart-badge" class="absolute -top-1 -right-1 bg-brand-gold text-white text-xs font-bold px-1.5 py-0.5 rounded-full min-w-[1rem] text-center hidden shadow-md">0</span>
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Breadcrumbs -->
  <nav class="bg-white border-b border-gray-100" aria-label="Breadcrumb">
    <div class="container mx-auto px-4 py-3 text-left">
      <ol class="flex items-center gap-3 text-sm text-gray-600 justify-start overflow-x-auto whitespace-nowrap" dir="ltr">
        <!-- Home -->
        <li class="shrink-0">
          <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white shadow-sm hover:shadow-md text-gray-700 hover:text-brand-rose transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path d="M10.707 1.293a1 1 0 00-1.414 0l-8 8A1 1 0 002 11h1v7a1 1 0 001 1h4a1 1 0 001-1v-4h2v4a1 1 0 001 1h4a1 1 0 001-1v-7h1a1 1 0 00.707-1.707l-8-8z"/>
            </svg>
            <span>الرئيسية</span>
          </a>
        </li>
        <!-- Separator -->
        <li class="text-gray-300 shrink-0" aria-hidden="true">
          <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
        </li>
        <!-- Products -->
        <li class="shrink-0">
          <a href="{{ url('/#products') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white shadow-sm hover:shadow-md text-gray-700 hover:text-brand-rose transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M3 3h7v7H3V3zm0 11h7v7H3v-7zm11-11h7v7h-7V3zm0 11h7v7h-7v-7z"/>
            </svg>
            <span>المنتجات</span>
          </a>
        </li>
        <!-- Separator -->
        <li class="text-gray-300 shrink-0" aria-hidden="true">
          <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
        </li>
        <!-- Current -->
        <li class="shrink-0">
          <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-brand-rose/10 text-brand-rose font-medium">
            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path d="M17.707 9.293l-7-7A1 1 0 009 2v3H6a3 3 0 00-3 3v6a4 4 0 004 4h6a3 3 0 003-3v-3h3a1 1 0 00.707-1.707zM7 8h5v2H7V8z"/>
            </svg>
            <span>{{ $product->name }}</span>
          </span>
        </li>
      </ol>
    </div>
  </nav>

  <!-- Product Details -->
  <section class="py-6 md:py-12">
    <div class="container mx-auto px-4 max-w-7xl">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-start">
        <!-- Product Images -->
        <div class="animate-fadeInUp">
          <div class="mb-4 bg-white rounded-lg shadow-soft overflow-hidden">
            <picture>
              @php
                $imgUrl = $product->image_url;
                $webpUrl = null;
                if (!str_starts_with($imgUrl, 'http')) {
                    $relative = ltrim($imgUrl, '/');
                    $fullPath = public_path($relative);
                    if (preg_match('/\.(jpg|jpeg|png)$/i', $fullPath)) {
                        $webpFs = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $fullPath);
                        if ($webpFs && file_exists($webpFs)) {
                            $webpUrl = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $imgUrl);
                        }
                    } elseif (preg_match('/\.webp$/i', $fullPath) && file_exists($fullPath)) {
                        $webpUrl = $imgUrl;
                    }
                } else {
                    if (preg_match('/\.webp($|\?)/i', $imgUrl)) {
                        $webpUrl = $imgUrl;
                    }
                }
              @endphp
              @if($webpUrl)
                <source srcset="{{ $webpUrl }}" type="image/webp">
              @endif
              <img id="mainImage" src="{{ $imgUrl }}" alt="{{ $product->name }} - صورة المنتج الرئيسية" class="w-full main-image-portrait rounded-lg img-pop" loading="eager" decoding="async" onerror="this.src='https://via.placeholder.com/600x400?text=No+Image'">
            </picture>
          </div>

          @if($product->gallery && count($product->gallery) > 0)
          <div class="thumbnails-scroll grid grid-cols-4 gap-2 md:grid-cols-4 md:gap-3">
            <div class="thumbnail-wrapper bg-white rounded-lg shadow-sm overflow-hidden border-2 border-brand-rose">
              <picture>
                @php 
                  $thumbImg = $product->image_url; 
                  $webpUrl = null; 
                  if (!str_starts_with($thumbImg, 'http')) {
                      $relative = ltrim($thumbImg, '/');
                      $fullPath = public_path($relative);
                      if (preg_match('/\.(jpg|jpeg|png)$/i', $fullPath)) {
                          $webpFs = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $fullPath);
                          if ($webpFs && file_exists($webpFs)) {
                              $webpUrl = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $thumbImg);
                          }
                      } elseif (preg_match('/\.webp$/i', $fullPath) && file_exists($fullPath)) {
                          $webpUrl = $thumbImg;
                      }
                  } else {
                      if (preg_match('/\.webp($|\?)/i', $thumbImg)) {
                          $webpUrl = $thumbImg;
                      }
                  }
                @endphp
                @if($webpUrl)
                  <source srcset="{{ $webpUrl }}" type="image/webp">
                @endif
                <img src="{{ $thumbImg }}" alt="{{ $product->name }} - صورة مصغرة 1" class="gallery-image w-full aspect-[3/4] object-contain bg-gray-50" onclick="changeMainImage('{{ $thumbImg }}', this)" loading="lazy" decoding="async" onerror="this.style.display='none'">
              </picture>
            </div>
            @foreach($product->gallery as $index => $image)
              @php $imageUrl = str_starts_with($image, 'http') ? $image : '/storage/' . ltrim($image, '/'); @endphp
              <div class="thumbnail-wrapper bg-white rounded-lg shadow-sm overflow-hidden border-2 border-gray-200 hover:border-brand-rose transition-colors">
                <picture>
                  @php 
                    $thumb2 = $imageUrl; 
                    $webpUrl = null; 
                    if (!str_starts_with($thumb2, 'http')) {
                        $relative = ltrim($thumb2, '/');
                        $fullPath = public_path($relative);
                        if (preg_match('/\.(jpg|jpeg|png)$/i', $fullPath)) {
                            $webpFs = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $fullPath);
                            if ($webpFs && file_exists($webpFs)) {
                                $webpUrl = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $thumb2);
                            }
                        } elseif (preg_match('/\.webp$/i', $fullPath) && file_exists($fullPath)) {
                            $webpUrl = $thumb2;
                        }
                    } else {
                        if (preg_match('/\.webp($|\?)/i', $thumb2)) {
                            $webpUrl = $thumb2;
                        }
                    }
                  @endphp
                  @if($webpUrl)
                    <source srcset="{{ $webpUrl }}" type="image/webp">
                  @endif
                  <img src="{{ $thumb2 }}" alt="{{ $product->name }} - صورة مصغرة {{ $index + 2 }}" class="gallery-image w-full aspect-[3/4] object-contain bg-gray-50" onclick="changeMainImage('{{ $thumb2 }}', this)" loading="lazy" decoding="async" onerror="this.style.display='none'">
                </picture>
              </div>
            @endforeach
          </div>
          @endif
        </div>

        <!-- Product Info -->
        <div class="animate-fadeInUp">
          <h1 class="text-3xl md:text-4xl font-display font-bold text-brand-charcoal mb-4">{{ $product->name }}</h1>
          <!-- Consistent star rating based on product ID (4.0-5.0) -->
          @php
            // Use product ID to generate consistent rating for each product
            $seed = $product->id * 37; // Multiply by prime to spread values
            $randomRating = (($seed % 11) + 40) / 10; // Generates 4.0-5.0
            $fullStars = floor($randomRating);
            $hasHalfStar = ($randomRating - $fullStars) >= 0.5;
            $reviewCount = (($seed % 14) + 2); // Generates 2-15 reviews
          @endphp
          <div class="flex items-center gap-2 mb-4">
            <span class="flex text-yellow-400 text-xl">
              @for($i=0; $i<$fullStars; $i++)
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20" class="w-5 h-5 inline"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.97c.3.921-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.197-1.539-1.118l1.287-3.97a1 1 0 00-.364-1.118L2.174 9.397c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.97z"/></svg>
              @endfor
              @if($hasHalfStar)
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20" class="w-5 h-5 inline"><defs><linearGradient id="half"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="#e5e7eb"/></linearGradient></defs><path fill="url(#half)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.97c.3.921-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.197-1.539-1.118l1.287-3.97a1 1 0 00-.364-1.118L2.174 9.397c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.97z"/></svg>
              @endif
              @for($i=0; $i<(5-$fullStars-($hasHalfStar?1:0)); $i++)
                <svg xmlns="http://www.w3.org/2000/svg" fill="#e5e7eb" viewBox="0 0 20 20" class="w-5 h-5 inline"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.97c.3.921-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.197-1.539-1.118l1.287-3.97a1 1 0 00-.364-1.118L2.174 9.397c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.97z"/></svg>
              @endfor
            </span>
            <span class="text-sm text-gray-600">{{ $randomRating }} ({{ $reviewCount }} مراجعات)</span>
          </div>

          <div class="mb-8">
            <div class="flex flex-wrap items-center gap-3 md:gap-4 mb-4">
              <span class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-brand-rose to-brand-rose-dark bg-clip-text text-transparent">{{ $product->formatted_price }}</span>
              @if($product->original_price && $product->original_price > $product->price)
                <span class="text-xl md:text-2xl text-brand-slate/60 line-through">{{ $product->formatted_original_price }}</span>
                <span class="bg-gradient-to-r from-brand-rose to-brand-rose-dark text-white px-3 py-1.5 rounded-full text-sm md:text-base font-bold shadow-lg">-{{ $product->discount_percentage }}%</span>
              @endif
            </div>

          </div>

          <div class="mb-8 bg-white p-6 rounded-3xl shadow-soft">
            <h3 class="text-xl font-bold mb-4 text-brand-charcoal flex items-center gap-2">
              <span class="text-2xl">📝</span> Description
            </h3>
            <p class="text-brand-slate leading-relaxed">{{ $product->description }}</p>
          </div>

          @if($product->features)
          <div class="mb-8 bg-gradient-to-br from-white to-brand-cream p-6 rounded-3xl border border-brand-beige">
            <h3 class="text-xl font-bold mb-4 text-brand-charcoal flex items-center gap-2">
              <span class="text-2xl">✨</span> Key Features
            </h3>
            <div class="space-y-3">
              @foreach(explode("\n", $product->features) as $feature)
                @if(trim($feature))
                <div class="flex items-start gap-3">
                  <span class="text-brand-rose text-xl mt-0.5">✓</span>
                  <span class="text-brand-slate flex-1">{{ trim($feature) }}</span>
                </div>
                @endif
              @endforeach
            </div>
          </div>
          @endif

          <!-- Modern Order Card -->
          <div class="bg-gradient-to-br from-white to-brand-cream rounded-3xl shadow-card p-6 md:p-8 border border-brand-beige animate-scaleIn">
            <!-- Quantity Selector -->
            <div class="mb-6">
              <label class="block text-brand-charcoal font-bold mb-3 text-lg">Quantity:</label>
              <div class="flex items-center justify-center gap-4">
                <button onclick="decreaseQuantity()" class="bg-brand-beige hover:bg-brand-rose hover:text-white text-brand-charcoal font-bold py-3 px-5 rounded-full text-lg transition-all shadow-soft">−</button>
                <span id="qtyBadge" class="bg-white border-2 border-brand-rose px-6 py-3 rounded-2xl font-bold text-xl min-w-[3.5rem] text-center text-brand-charcoal shadow-soft">1</span>
                <button onclick="increaseQuantity()" class="bg-gradient-to-r from-brand-rose to-brand-rose-dark text-white font-bold py-3 px-5 rounded-full text-lg shadow-soft hover:shadow-glow transition-all">+</button>
              </div>
            </div>
            <!-- Action Buttons -->
            <div class="space-y-4">
              <button onclick="openOrderModal()" class="btn w-full bg-gradient-to-r from-brand-rose to-brand-rose-dark text-white font-bold py-4 md:py-5 px-6 rounded-2xl text-lg md:text-xl shadow-lg hover:shadow-glow transition-all group">
                <span class="relative z-10 flex items-center justify-center gap-2">
                  <span>Order Now</span>
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                  </svg>
                </span>
                <span class="absolute inset-0 shimmer-bg opacity-0 group-hover:opacity-100 animate-shimmer rounded-2xl"></span>
              </button>
              <button onclick="handleAddToCart(); addToCart({{ $product->id }})" class="btn w-full bg-gradient-to-r from-brand-mint to-emerald-400 hover:from-emerald-500 hover:to-emerald-600 text-white font-bold py-4 md:py-5 px-6 rounded-2xl text-lg md:text-xl shadow-lg transition-all group">
                <span class="relative z-10 flex items-center justify-center gap-2">
                  <span>Add to Cart</span>
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                  </svg>
                </span>
                <span class="absolute inset-0 shimmer-bg opacity-0 group-hover:opacity-100 animate-shimmer rounded-2xl"></span>
              </button>
            </div>
            <div class="mt-6 space-y-2 text-center bg-white/50 p-4 rounded-2xl">
              <p class="text-brand-slate text-sm md:text-base flex items-center justify-center gap-2">
                <span class="text-brand-mint text-lg">✓</span> Free Shipping Nationwide
              </p>
              <p class="text-brand-slate text-sm md:text-base flex items-center justify-center gap-2">
                <span class="text-brand-rose text-lg">🚚</span> Delivery in 1-3 Business Days
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Modern Trust Badges -->
  <section class="py-12 md:py-16 bg-gradient-to-b from-white to-brand-cream">
    <div class="container mx-auto px-6 lg:px-12">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Secure Payment -->
        <div class="text-center p-8 rounded-3xl bg-white border border-brand-beige hover:shadow-card transition-all duration-500 animate-fadeInUp">
          <div class="mb-6 flex justify-center">
            <div class="w-20 h-20 bg-gradient-to-br from-brand-rose to-brand-rose-dark rounded-2xl flex items-center justify-center shadow-soft">
              <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
              </svg>
            </div>
          </div>
          <h3 class="text-2xl font-bold text-brand-charcoal mb-3">Secure Payment</h3>
          <p class="text-brand-slate leading-relaxed">Cash on delivery or secure online payment with complete data protection</p>
        </div>

        <!-- Premium Quality -->
        <div class="text-center p-8 rounded-3xl bg-white border border-brand-beige hover:shadow-card transition-all duration-500 animate-fadeInUp" style="animation-delay: 0.1s">
          <div class="mb-6 flex justify-center">
            <div class="w-20 h-20 bg-gradient-to-br from-brand-gold to-brand-gold-light rounded-2xl flex items-center justify-center shadow-soft">
              <span class="text-white font-extrabold text-3xl">100%</span>
            </div>
          </div>
          <h3 class="text-2xl font-bold text-brand-charcoal mb-3">Premium Quality</h3>
          <p class="text-brand-slate leading-relaxed">100% authentic products with highest quality standards and return guarantee</p>
        </div>

        <!-- Fast Delivery -->
        <div class="text-center p-8 rounded-3xl bg-white border border-brand-beige hover:shadow-card transition-all duration-500 animate-fadeInUp" style="animation-delay: 0.2s">
          <div class="mb-6 flex justify-center">
            <div class="w-20 h-20 bg-gradient-to-br from-brand-mint to-emerald-500 rounded-2xl flex items-center justify-center shadow-soft">
              <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
              </svg>
            </div>
          </div>
          <h3 class="text-2xl font-bold text-brand-charcoal mb-3">Free Shipping</h3>
          <p class="text-brand-slate leading-relaxed">Delivery within 1-3 business days with real-time package tracking</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Modern Footer -->
  <footer class="relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-brand-charcoal via-brand-slate to-brand-charcoal"></div>
    <div class="relative z-10 container mx-auto px-6 lg:px-12 py-12 text-center">
      <div class="mb-6">
        <h3 class="font-display text-3xl font-bold bg-gradient-to-r from-brand-rose-light to-brand-gold bg-clip-text text-transparent mb-2">Fekra Store</h3>
        <p class="text-gray-300 text-sm">Modern Women's Fashion</p>
      </div>
      
      <div class="flex justify-center gap-6 mb-8">
        <a href="https://www.tiktok.com/@fekra__store" target="_blank" class="w-12 h-12 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center hover:bg-brand-rose hover:scale-110 transition-all duration-300 group" aria-label="TikTok">
          <svg class="w-6 h-6 text-white group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
          </svg>
        </a>
      </div>
      
      <p class="text-gray-400 text-sm">&copy; 2025 Fekra Store. All rights reserved.</p>
    </div>
  </footer>

  <!-- Modern Order Modal -->
  <div id="orderModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center px-4 py-6">
    <div id="modalPanel" class="bg-white rounded-3xl shadow-card w-full max-w-4xl max-h-[90vh] overflow-hidden">
      <!-- Header -->
      <div class="px-8 py-6 border-b border-brand-beige bg-gradient-to-r from-brand-cream to-white">
        <div class="flex items-center justify-between">
          <h3 class="font-display text-2xl font-bold text-brand-charcoal">Complete Your Order</h3>
          <button onclick="closeOrderModal()" class="w-10 h-10 rounded-full bg-brand-beige hover:bg-brand-rose hover:text-white transition-all duration-300 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
      </div>

      <!-- Body (scrollable) -->
      <div class="overflow-y-auto max-h-[calc(90vh-140px)] px-8 py-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <!-- Order Summary -->
          <div class="space-y-4">
            <div class="p-6 rounded-2xl bg-gradient-to-br from-brand-rose/10 to-brand-gold/10 border border-brand-beige">
              <div class="flex items-center gap-4">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-20 h-20 object-cover rounded-xl shadow-soft" loading="lazy">
                <div class="flex-1">
                  <h4 class="font-bold text-brand-charcoal text-lg mb-1">{{ $product->name }}</h4>
                  <p class="text-xl font-extrabold bg-gradient-to-r from-brand-rose to-brand-rose-dark bg-clip-text text-transparent">{{ $product->formatted_price }}</p>
                </div>
              </div>
            </div>

            <div class="p-6 rounded-2xl bg-white border border-brand-beige space-y-3">
              <div class="flex justify-between items-center text-brand-slate"><span>Price:</span><span class="font-bold text-brand-charcoal">{{ $product->formatted_price }}</span></div>
              <div class="flex justify-between items-center text-brand-slate"><span>Quantity:</span><span id="modalQuantityDisplay" class="font-bold text-brand-charcoal">1</span></div>
              <div class="flex justify-between items-center pt-3 border-t border-brand-beige">
                <span class="text-xl font-bold text-brand-charcoal">Total:</span>
                <span id="totalPrice" class="text-2xl font-extrabold bg-gradient-to-r from-brand-rose to-brand-gold bg-clip-text text-transparent">{{ $product->formatted_price }}</span>
              </div>
            </div>
          </div>

          <!-- Order Form -->
          <div>
            <form id="productOrderForm" class="space-y-4" novalidate>
              @csrf
              <input type="hidden" name="product_id" value="{{ $product->id }}">

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label for="modalQuantity" class="block text-sm font-medium text-brand-charcoal mb-2">Quantity</label>
                  <select id="modalQuantity" name="quantity" class="w-full px-4 py-3 border border-brand-beige rounded-xl focus:ring-2 focus:ring-brand-rose focus:border-brand-rose transition-all">
                    @for($i = 1; $i <= 10; $i++)
                      <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                  </select>
                </div>
                <div>
                  <label for="phone" class="block text-sm font-medium text-brand-charcoal mb-2">Phone Number</label>
                  <input type="tel" id="phone" name="customer_phone" required autocomplete="tel" class="w-full px-4 py-3 border border-brand-beige rounded-xl focus:ring-2 focus:ring-brand-rose focus:border-brand-rose transition-all" placeholder="01xxxxxxxxx" inputmode="numeric" pattern="01[0-9]{9}">
                </div>
              </div>

              <div>
                <label for="customer_name" class="block text-sm font-medium text-brand-charcoal mb-2">Full Name</label>
                <input type="text" id="customer_name" name="customer_name" required autocomplete="name" class="w-full px-4 py-3 border border-brand-beige rounded-xl focus:ring-2 focus:ring-brand-rose focus:border-brand-rose transition-all" placeholder="Enter your full name">
              </div>

              <div class="grid grid-cols-1 gap-4">
                <div>
                  <label for="governorate" class="block text-sm font-medium text-brand-charcoal mb-2">Governorate</label>
                  <select id="governorate" name="governorate" required class="w-full px-4 py-3 border border-brand-beige rounded-xl focus:ring-2 focus:ring-brand-rose focus:border-brand-rose transition-all">
                    <option value="">Select Governorate</option>
                    <option value="Cairo">Cairo</option>
                    <option value="Giza">Giza</option>
                    <option value="Alexandria">Alexandria</option>
                    <option value="Dakahlia">Dakahlia</option>
                    <option value="Beheira">Beheira</option>
                    <option value="Monufia">Monufia</option>
                    <option value="Gharbia">Gharbia</option>
                    <option value="Kafr El Sheikh">Kafr El Sheikh</option>
                    <option value="Sharqia">Sharqia</option>
                    <option value="Qalyubia">Qalyubia</option>
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
                    <option value="Port Said">Port Said</option>
                    <option value="Damietta">Damietta</option>
                    <option value="Ismailia">Ismailia</option>
                    <option value="Suez">Suez</option>
                  </select>
                </div>
              </div>

              <div>
                <label for="address" class="block text-sm font-medium text-brand-charcoal mb-2">Detailed Address</label>
                <textarea id="address" name="address" required rows="3" autocomplete="street-address" class="w-full px-4 py-3 border border-brand-beige rounded-xl focus:ring-2 focus:ring-brand-rose focus:border-brand-rose transition-all" placeholder="City, District, Street, Building No."></textarea>
              </div>

              <div class="flex gap-4 pt-2">
                <button type="button" onclick="closeOrderModal()" class="flex-1 bg-brand-beige hover:bg-gray-300 text-brand-charcoal font-semibold py-3 px-6 rounded-xl transition-all duration-300">Cancel</button>
                <button type="submit" id="productSubmitBtn" class="flex-1 bg-gradient-to-r from-brand-rose to-brand-rose-dark hover:shadow-card text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 btn-enhanced">
                  <span class="btn-text">تأكيد الطلب</span>
                  <span class="btn-loading hidden">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    جاري المعالجة...
                  </span>
                </button>
              </div>

              <div id="productSuccessMessage" class="hidden mt-4 p-5 bg-emerald-50 border-2 border-emerald-400 text-emerald-700 rounded-2xl">
                <h3 class="font-bold mb-2 flex items-center gap-2">
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                  Order Placed Successfully!
                </h3>
                <p>We'll contact you within 24 hours to confirm your order.</p>
              </div>

              <div id="productErrorMessage" class="hidden mt-4 p-5 bg-red-50 border-2 border-red-400 text-red-700 rounded-2xl">
                <h3 class="font-bold mb-2 flex items-center gap-2">
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                  Error Occurred!
                </h3>
                <p id="productErrorText">Please try again.</p>
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
        } else { toast('Error adding product to cart', 'error'); }
      })
      .catch(() => toast('Error adding product to cart', 'error'));
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
      modal.classList.add('flex');
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
      modal.classList.remove('flex');
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

      // Update thumbnail borders
      document.querySelectorAll('.thumbnail-wrapper').forEach(wrapper=>{
        wrapper.classList.remove('border-brand-rose');
        wrapper.classList.add('border-gray-200');
      });
      if(el && el.closest('.thumbnail-wrapper')){ 
        const wrapper = el.closest('.thumbnail-wrapper');
        wrapper.classList.remove('border-gray-200'); 
        wrapper.classList.add('border-brand-rose'); 
      }
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
      console.log('Form submitted');
      const btn=document.getElementById('productSubmitBtn');
      const ok=document.getElementById('productSuccessMessage');
      const err=document.getElementById('productErrorMessage');
      ok.classList.add('hidden'); err.classList.add('hidden');
      btn.disabled=true; 
      const btnText = btn.querySelector('span') || btn;
      btnText.textContent='Processing...';
      const formData=new FormData(this);
      console.log('Form data:', Object.fromEntries(formData));
      console.log('Sending request to:', '{{ route("orders.store") }}');
      fetch('{{ route("orders.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(r => {
        console.log('Response received:', r.status, r.statusText);
        return r;
      })
      .then(async r => {
        console.log('Response status:', r.status);
        let data = null;
        try { data = await r.clone().json(); console.log('Response data:', data); } catch(e) { console.error('JSON parse error:', e); }
        if (!r.ok) {
          if (data && data.errors) {
            const flat = Object.values(data.errors).flat().join(' | ');
            console.error('Validation errors:', data.errors);
            throw new Error(flat || data.message || 'Validation failed');
          }
            const text = data?.message || 'Server error ('+r.status+')';
            throw new Error(text);
        }
        return data;
      })
      .then(d => {
        console.log('Success data:', d);
        if (d && d.success) {
          ok.classList.remove('hidden');
          console.log('Redirecting to thanks page...');
          setTimeout(() => { window.location.href='{{ route("thanks") }}'; }, 1400);
        } else {
          throw new Error(d?.message || 'An unexpected error occurred');
        }
      })
      .catch(e => {
        console.error('Error:', e);
        err.classList.remove('hidden');
        const errorTextEl = document.getElementById('productErrorText');
        if(errorTextEl) errorTextEl.textContent = e.message;
      })
      .finally(()=>{
        btn.disabled=false;
        const btnText = btn.querySelector('span') || btn;
        btnText.textContent='Confirm Order';
      });
  });

  // Use debounced listeners only
  window.addEventListener('resize', schedulePanelResize, { passive:true });
  window.addEventListener('orientationchange', ()=>setTimeout(setPanelMaxHeight, 140), { passive:true });
  
  // Image zoom functionality
  function initImageZoom() {
    const mainImage = document.getElementById('mainImage');
    if (!mainImage) return;
    
    mainImage.style.cursor = 'zoom-in';
    mainImage.addEventListener('click', function() {
      // Create zoom overlay
      const overlay = document.createElement('div');
      overlay.className = 'fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4 cursor-zoom-out';
      overlay.style.animation = 'fadeIn 0.3s ease-out';
      
      // Create zoomed image
      const zoomedImg = document.createElement('img');
      zoomedImg.src = this.src;
      zoomedImg.alt = this.alt;
      zoomedImg.className = 'max-w-full max-h-full object-contain';
      zoomedImg.style.animation = 'scaleIn 0.3s ease-out';
      
      overlay.appendChild(zoomedImg);
      document.body.appendChild(overlay);
      
      // Close on click or escape
      overlay.addEventListener('click', function() {
        overlay.style.animation = 'fadeOut 0.2s ease-out';
        setTimeout(() => document.body.removeChild(overlay), 200);
      });
      
      document.addEventListener('keydown', function closeOnEscape(e) {
        if (e.key === 'Escape') {
          overlay.style.animation = 'fadeOut 0.2s ease-out';
          setTimeout(() => document.body.removeChild(overlay), 200);
          document.removeEventListener('keydown', closeOnEscape);
        }
      });
    });
  }
  
  // Initialize zoom on page load
  initImageZoom();
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
