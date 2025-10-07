<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - Order Confirmed | Fekra Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ["Inter", "sans-serif"],
            display: ["Playfair Display", "serif"],
          },
          colors: {
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
          },
        },
      },
    };
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #FFF8F3 0%, #FFB8D2 100%);
        }
        .bounce-in {
            animation: bounceIn 1s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }
        @keyframes bounceIn {
            0% { transform: scale(0.5) rotate(-5deg); opacity: 0; }
            50% { transform: scale(1.05) rotate(2deg); }
            100% { transform: scale(1) rotate(0); opacity: 1; }
        }
        .success-icon {
            animation: successPulse 2s ease-in-out infinite;
        }
        @keyframes successPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
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
<body class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="container mx-auto text-center">
        <div class="bounce-in bg-white rounded-3xl shadow-card border-2 border-brand-beige p-10 md:p-12 max-w-2xl mx-auto">
            <!-- Success Icon -->
            <div class="success-icon text-8xl mb-6">
                <div class="inline-block p-6 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 text-white shadow-card">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Thank You Message -->
            <h1 class="font-display text-5xl md:text-6xl font-bold text-brand-charcoal mb-4">
                Thank You!
            </h1>
            
            <h2 class="text-2xl font-semibold bg-gradient-to-r from-brand-rose to-brand-gold bg-clip-text text-transparent mb-8">
                Your order has been placed successfully 🎉
            </h2>
            
            <!-- Order Details -->
            <div class="bg-gradient-to-br from-brand-cream to-brand-beige rounded-2xl p-6 mb-8 border-2 border-brand-beige">
                <div class="space-y-4">
                    @if($isCartOrder && !empty($orders))
                        <!-- Cart Orders -->
                        <div class="flex justify-between items-center border-b-2 border-brand-beige pb-4 mb-4">
                            <span class="font-bold text-brand-charcoal">Order Type:</span>
                            <span class="bg-gradient-to-r from-brand-rose to-brand-gold bg-clip-text text-transparent font-bold">Multiple Products</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-brand-charcoal">Customer Name:</span>
                            <span class="text-brand-slate">{{ $orders[0]->customer_name }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-brand-charcoal">Phone:</span>
                            <span class="text-brand-slate">{{ $orders[0]->phone }}</span>
                        </div>
                        
                        <!-- Products Summary -->
                        <div class="mt-6 pt-4 border-t-2 border-brand-beige">
                            <h4 class="font-display text-2xl font-bold text-brand-charcoal mb-4 text-center">Your Items</h4>
                            <div class="space-y-3">
                                @foreach($orders as $order)
                                <div class="flex justify-between items-center bg-white p-4 rounded-xl shadow-soft">
                                    <span class="font-medium text-brand-charcoal">{{ $order->product->name }}</span>
                                    <span class="text-brand-slate font-semibold">
                                        {{ $order->quantity }} × {{ number_format($order->product->price, 0) }} EGP
                                    </span>
                                </div>
                                @endforeach
                            </div>
                            @php
                                $cartTotal = 0;
                                foreach ($orders as $o) {
                                    if (isset($o->product)) {
                                        $cartTotal += ($o->product->price * $o->quantity);
                                    }
                                }
                            @endphp
                            <div class="flex justify-between items-center mt-6 pt-4 border-t-2 border-brand-beige">
                                <span class="font-display text-2xl font-bold text-brand-charcoal">Total:</span>
                                <span class="font-display text-3xl font-bold bg-gradient-to-r from-brand-rose to-brand-gold bg-clip-text text-transparent">
                                    {{ number_format($cartTotal, 0) }} EGP
                                </span>
                            </div>
                        </div>
                        
                    @elseif(session('order'))
                        <!-- Single Product Order -->
                        @php $order = session('order'); @endphp
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-brand-charcoal">Order Number:</span>
                            <span class="bg-gradient-to-r from-brand-rose to-brand-gold bg-clip-text text-transparent font-bold text-xl">#{{ $order->id }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-brand-charcoal">Customer Name:</span>
                            <span class="text-brand-slate">{{ $order->customer_name }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-brand-charcoal">Phone:</span>
                            <span class="text-brand-slate">{{ $order->phone }}</span>
                        </div>
                        @if($order->product)
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-brand-charcoal">Product:</span>
                            <span class="text-brand-slate">{{ $order->product->name }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t-2 border-brand-beige">
                            <span class="font-display text-2xl font-bold text-brand-charcoal">Total:</span>
                            <span class="font-display text-3xl font-bold bg-gradient-to-r from-brand-rose to-brand-gold bg-clip-text text-transparent">{{ $order->formatted_total_price }}</span>
                        </div>
                        @endif
                    @endif
                </div>
            </div>
            
            <!-- What's Next -->
            <div class="mb-8">
                <h3 class="font-display text-3xl font-bold text-brand-charcoal mb-6">What's Next?</h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-4 p-4 bg-white rounded-xl shadow-soft">
                        <span class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-brand-rose to-brand-rose-dark text-white rounded-full flex items-center justify-center font-bold text-xl shadow-soft">1</span>
                        <div class="flex-1 text-left pt-2">
                            <p class="text-brand-slate font-medium">We'll contact you within 24 hours to confirm your order</p>
                        </div>
                    </div>
                   
                    <div class="flex items-start gap-4 p-4 bg-white rounded-xl shadow-soft">
                        <span class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-brand-gold to-amber-600 text-white rounded-full flex items-center justify-center font-bold text-xl shadow-soft">2</span>
                        <div class="flex-1 text-left pt-2">
                            <p class="text-brand-slate font-medium">Cash on delivery - pay when you receive your order</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-4 bg-white rounded-xl shadow-soft">
                        <span class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-full flex items-center justify-center font-bold text-xl shadow-soft">3</span>
                        <div class="flex-1 text-left pt-2">
                            <p class="text-brand-slate font-medium">Free shipping - delivery within 1-3 business days</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('landing') }}" 
                   class="flex-1 bg-gradient-to-r from-brand-rose to-brand-rose-dark hover:shadow-card text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-soft flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Back to Home
                </a>
                <a href="{{ route('landing') }}#products" 
                   class="flex-1 bg-brand-gold hover:bg-amber-500 hover:shadow-card text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-soft flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Shop More
                </a>
            </div>
        </div>
    </div>
</body>
<!-- TikTok Pixel: CompletePayment (Purchase) -->
<script>
    (function(){
        function tiktokTrack(eventName, payload){
            if (window.ttq && typeof window.ttq.track === 'function') {
                window.ttq.track(eventName, payload);
            }
        }

        // Two flows in this view: multi-order cart vs single order in session
        @if(isset($isCartOrder) && $isCartOrder && !empty($orders))
            @php
                $cartTotalForPixel = 0.0;
                $ids = [];
                foreach ((array) $orders as $o) {
                    $price = isset($o->product) ? (float) $o->product->price : 0.0;
                    $qty = (int) ($o->quantity ?? 1);
                    $cartTotalForPixel += $price * $qty;
                    if (isset($o->id)) { $ids[] = (int) $o->id; }
                }
                $idsStr = implode(',', $ids);
            @endphp
            tiktokTrack('CompletePayment', {
                value: {{ $cartTotalForPixel }},
                currency: 'EGP',
                content_id: @json($idsStr ?: 'cart')
            });
        @elseif(session('order'))
            @php 
                $order = session('order');
                // Ensure a numeric value even if relation not loaded
                $purchaseValue = (float)($order->total_price ?? ((($order->product->price ?? 0) * ($order->quantity ?? 1))));
            @endphp
            tiktokTrack('CompletePayment', {
                value: {{ $purchaseValue }},
                currency: 'EGP',
                content_id: String({{ (int) $order->id }})
            });
        @endif
    })();
    </script>
</html>
