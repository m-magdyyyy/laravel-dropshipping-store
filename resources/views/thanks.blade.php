<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شكراً لك - تم تسجيل طلبك بنجاح</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
        .bounce-in {
            animation: bounceIn 0.8s ease-out;
        }
        @keyframes bounceIn {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
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
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center py-8">
    <div class="container mx-auto px-4 text-center">
        <div class="bounce-in bg-white rounded-2xl shadow-lg border p-8 max-w-lg mx-auto">
            <!-- Success Icon -->
            <div class="text-5xl mb-4">✅</div>
            
            <!-- Thank You Message -->
            <h1 class="text-2xl font-bold text-gray-800 mb-3">
                شكراً لك!
            </h1>
            
            <h2 class="text-lg font-semibold text-blue-600 mb-6">
                تم تسجيل طلبك بنجاح 🎉
            </h2>
            
            <!-- Order Details -->
            <div class="bg-gray-50 rounded-xl p-4 mb-6 text-sm">
                <div class="space-y-2 text-right">
                    @if($isCartOrder && !empty($orders))
                        <!-- Cart Orders -->
                        <div class="flex justify-between border-b pb-2 mb-3">
                            <span class="text-blue-600 font-bold">طلب متعدد المنتجات</span>
                            <span class="font-bold text-gray-700">نوع الطلب:</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ $orders[0]->customer_name }}</span>
                            <span class="font-bold text-gray-700">اسم العميل:</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ $orders[0]->phone }}</span>
                            <span class="font-bold text-gray-700">رقم الهاتف:</span>
                        </div>
                        
                        <!-- Products Summary -->
                        <div class="mt-4 pt-3 border-t">
                            <h4 class="font-bold text-gray-800 mb-3 text-center">المنتجات المطلوبة:</h4>
                            <div class="space-y-2">
                                @foreach($orders as $order)
                                <div class="flex justify-between items-center bg-white p-2 rounded">
                                    <span class="text-gray-600 text-xs">
                                        {{ $order->quantity }} × {{ number_format($order->product->price, 0) }} ج.م
                                    </span>
                                    <span class="font-medium text-gray-800">{{ $order->product->name }}</span>
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
                            <div class="flex justify-between items-center mt-3 pt-2 border-t font-bold">
                                <span class="text-green-600">
                                    {{ number_format($cartTotal, 0) }} ج.م
                                </span>
                                <span class="text-gray-700">الإجمالي:</span>
                            </div>
                        </div>
                        
                    @elseif(session('order'))
                        <!-- Single Product Order -->
                        @php $order = session('order'); @endphp
                        <div class="flex justify-between">
                            <span class="text-blue-600 font-bold">#{{ $order->id }}</span>
                            <span class="font-bold text-gray-700">رقم الطلب:</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ $order->customer_name }}</span>
                            <span class="font-bold text-gray-700">اسم العميل:</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ $order->phone }}</span>
                            <span class="font-bold text-gray-700">رقم الهاتف:</span>
                        </div>
                        @if($order->product)
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ $order->product->name }}</span>
                            <span class="font-bold text-gray-700">المنتج:</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-green-600 font-bold">{{ $order->formatted_total_price }}</span>
                            <span class="font-bold text-gray-700">السعر:</span>
                        </div>
                        @endif
                    @endif
                </div>
            </div>
            
            <!-- What's Next -->
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-3">ماذا بعد؟</h3>
                <div class="space-y-2 text-gray-700 text-sm">
                    <div class="flex items-center justify-center">
                        <span class="bg-blue-100 text-blue-600 w-6 h-6 rounded-full flex items-center justify-center font-bold text-xs ml-2">1</span>
                        <span>سيتم التواصل معك خلال 24 ساعة لتأكيد الطلب</span>
                    </div>
                   
                    <div class="flex items-center justify-center">
                        <span class="bg-orange-100 text-orange-600 w-6 h-6 rounded-full flex items-center justify-center font-bold text-xs ml-2">3</span>
                        <span>الدفع عند الاستلام</span>
                    </div>
                </div>
            </div>
            
            <!-- Contact Info -->
            {{-- <div class="bg-blue-50 rounded-xl p-4 mb-6 text-sm">
                <h4 class="font-bold text-gray-800 mb-2">للاستفسار أو المتابعة:</h4>
                <div class="space-y-1 text-gray-700">
                    <div>📞 الهاتف: 01000000000</div>
                    <div>📧 البريد الإلكتروني: info@shop.com</div>
                </div>
            </div> --}}
            
            <!-- Action Buttons -->
            <div class="flex flex-col gap-3 justify-center">
                <a href="{{ route('landing') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-300 shadow text-sm">
                    🏠 العودة للصفحة الرئيسية
                </a>
                <a href="{{ route('landing') }}#products" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg transition duration-300 shadow text-sm">
                    🛍️ تسوق المزيد
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
                $cartTotalForPixel = 0;
                $ids = [];
                foreach ((array) $orders as $o) {
                    if (isset($o->product)) {
                        $cartTotalForPixel += ((float) $o->product->price) * ((int) $o->quantity);
                    }
                    if (isset($o->id)) { $ids[] = $o->id; }
                }
                $idsStr = implode(',', $ids);
            @endphp
            tiktokTrack('CompletePayment', {
                value: {{ $cartTotalForPixel ?: 0 }},
                currency: 'EGP',
                content_id: @json($idsStr ?: 'cart')
            });
        @elseif(session('order'))
            @php $order = session('order'); @endphp
            tiktokTrack('CompletePayment', {
                value: {{ (float) ($order->total_price ?? ($order->product->price ?? 0) * ($order->quantity ?? 1)) }},
                currency: 'EGP',
                content_id: String({{ (int) $order->id }})
            });
        @endif
    })();
    </script>
</html>
</html>
