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
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet" />

  <style>
    * { box-sizing: border-box; }
    body { font-family: 'Cairo', sans-serif; background:#f9fafb; }
    img { max-width: 100%; height: auto; }

    .gallery-image { cursor: pointer; transition: transform .2s ease; }
    .gallery-image:hover { transform: scale(1.03); }
    .main-image { max-height: 500px; object-fit: cover; }

    .order-btn { position: relative; overflow: hidden; }
    .order-btn::before{
      content:''; position:absolute; inset:0; left:-100%;
      background:linear-gradient(90deg,transparent,rgba(255,255,255,.2),transparent);
      transition:left .5s;
    }
    .order-btn:hover::before{ left:100%; }

    /* ===== Modal Overlay ===== */
    #orderModal { transition: opacity .2s ease; }

    /* ===== Modal Panel (fix height + internal scroll) ===== */
    #modalPanel{
      /* أقصى ارتفاع ذكي للشاشات المختلفة */
      max-height: var(--panel-max, min(85vh, 85svh));
      width: 100%;
      overflow: hidden;         /* ممنوع يتسرّب سكرول خارج اللوح */
      display: flex;
      flex-direction: column;   /* علشان نقدر نثبّت الهيدر ونخلي الباقي ي-scroll */
      -webkit-overflow-scrolling: touch;
      border-top-left-radius: 1rem;   /* شكل Bottom-sheet */
      border-top-right-radius: 1rem;
    }
    
    /* للشاشات الصغيرة جداً والهواتف القصيرة - نستخدم مودال شاشة كاملة مصغّر */
    @media (max-width: 480px) {
      /* نجعل اللوح يغطي كامل الارتفاع المتاح لتفادي القص */
      #modalPanel {
        position: fixed !important;
        inset: 0 !important;
        width: 100vw !important;
        height: calc(100svh) !important;
        max-height: 100svh !important;
        margin: 0 !important;
        border-radius: 0 !important;
        display: flex !important;
        flex-direction: column !important;
      }

      /* نُظهر اللوح من الأعلى لتقليل المسافة الفارغة */
      #orderModal { align-items: stretch !important; justify-content: flex-start !important; }

      /* حشوات أصغر لزيادة المساحة المتاحة للفورم */
      .panel-header {
        padding: 0.5rem 0.75rem !important;
      }

      .panel-body {
        padding: 0.5rem 0.75rem 0.75rem 0.75rem !important;
        overflow-y: auto !important;
        -webkit-overflow-scrolling: touch !important;
      }

      /* تقليل المسافات في الفورم */
      .panel-body .space-y-3 > * + * { margin-top: 0.45rem !important; }
      .panel-body .space-y-4 > * + * { margin-top: 0.55rem !important; }

      /* تقليل حجم الحقول والنصوص لعرض أكبر قدر من الحقول */
      .panel-body input,
      .panel-body select,
      .panel-body textarea {
        padding: 0.45rem 0.6rem !important;
        font-size: 0.85rem !important;
      }

      .panel-body label { font-size: 0.72rem !important; margin-bottom: 0.2rem !important; }

      /* textarea أقل ارتفاعًا افتراضياً */
      .panel-body textarea { min-height: 48px !important; }

      /* تصغير الملخص (الصورة والسعر) لتفادي احتلاله لمساحة كبيرة */
      .panel-body .bg-blue-50 img { width: 2.6rem !important; height: 2.6rem !important; }
      .panel-body .bg-blue-50,
      .panel-body .bg-gray-50 { padding: 0.5rem !important; }

      /* تأكد أن العنصر الذي كان sticky لا يلتهم مساحة كبيرة على الشاشات الصغيرة */
      .md\:sticky { position: static !important; top: auto !important; }

      /* إذا كانت الشاشة قصيرة جداً، نُجري تعديل إضافي ليملأ المحتوى */
      @media (max-height: 560px) {
        #modalPanel { height: calc(100svh - 4px) !important; max-height: calc(100svh - 4px) !important; }
        .panel-body textarea { min-height: 40px !important; }
      }
    }
    .panel-header{
      position: sticky; top: 0;
      background: #fff;
      z-index: 10;
      border-bottom: 1px solid #eef2f7;
    }
    .panel-body{
      flex: 1 1 auto;
      overflow-y: auto;         /* السكرول الحقيقي هنا */
      padding-bottom: max(1rem, env(safe-area-inset-bottom, 0px));
      overscroll-behavior: contain;
    }

    /* ===== Mobile tweaks ===== */
    @media (max-width: 768px){
      .main-image{ max-height: 50vh; }
      .thumbnails-scroll{
        display:flex !important; gap:.5rem; overflow-x:auto; padding-bottom:.5rem;
        -webkit-overflow-scrolling:touch; scroll-snap-type:x mandatory;
      }
      .thumbnails-scroll img{
        flex:0 0 auto; height:4rem; width:4rem; object-fit:cover; scroll-snap-align:start;
      }
      .order-btn{ padding:.75rem 1rem; font-size:1rem; }
    }

    /* منع تكبير iOS عند التركيز على الحقول */
    @supports (-webkit-touch-callout: none){
      #modalPanel input, #modalPanel select, #modalPanel textarea{ font-size: 16px; }
    }

    /* Landscape منخفض الارتفاع */
    @media (orientation: landscape) and (max-height: 480px){
      #orderModal{ align-items: flex-start !important; }
      #modalPanel{
        margin-top: .5rem;
        max-height: calc(100vh - 20px) !important;
      }
      
      .panel-header {
        padding: 0.5rem 1rem !important;
      }
      
      .panel-body {
        padding: 0.5rem 1rem !important;
      }
      
      .panel-body .space-y-3 > * + *,
      .panel-body .space-y-4 > * + * {
        margin-top: 0.5rem !important;
      }
      
      .panel-body input,
      .panel-body select,
      .panel-body textarea {
        padding: 0.375rem 0.5rem !important;
        font-size: 0.8rem !important;
      }
      
      .panel-body textarea {
        min-height: 40px !important;
      }
    }

    /* للشاشات عالية جداً والقصيرة */
    @media (max-height: 600px) and (max-width: 480px) {
      #modalPanel {
        max-height: 95vh !important;
      }
      
      .panel-body .bg-blue-50,
      .panel-body .bg-gray-50 {
        padding: 0.5rem !important;
      }
      
      .panel-body .grid-cols-2 {
        gap: 0.5rem !important;
      }
      
      .panel-body .space-y-3 > * + * {
        margin-top: 0.375rem !important;
      }
    }
  </style>
</head>
<body>
  <!-- Navigation -->
  <nav class="bg-white shadow-md">
    <div class="container mx-auto px-4 py-4">
      <div class="flex justify-between items-center">
  <a href="{{ route('landing') }}" class="text-xl md:text-2xl font-bold text-blue-600">فكره استور</a>
        <a href="{{ route('landing') }}" class="text-blue-600 hover:text-blue-800 text-sm md:text-base">العودة للصفحة الرئيسية</a>
      </div>
    </div>
  </nav>

  <!-- Product Details -->
  <section class="py-6 md:py-12">
    <div class="container mx-auto px-4">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
        <!-- Product Images -->
        <div>
          <div class="mb-4">
            <img id="mainImage" src="{{ $product->image_url }}" alt="{{ $product->name }}"
                 class="w-full main-image rounded-lg shadow-lg"
                 loading="lazy"
                 onerror="this.src='https://via.placeholder.com/600x400?text=No+Image'">
          </div>

          @if($product->gallery && count($product->gallery) > 0)
          <div class="thumbnails-scroll grid grid-cols-4 gap-2 md:grid-cols-4 md:gap-2">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                 class="gallery-image w-full h-16 md:h-20 object-cover rounded border-2 border-blue-500"
                 onclick="changeMainImage('{{ $product->image_url }}', this)"
                 onerror="this.style.display='none'">
            @foreach($product->gallery as $image)
              @php $imageUrl = str_starts_with($image, 'http') ? $image : '/storage/' . ltrim($image, '/'); @endphp
              <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                   class="gallery-image w-full h-16 md:h-20 object-cover rounded border-2 border-gray-300 hover:border-blue-500"
                   onclick="changeMainImage('{{ $imageUrl }}', this)"
                   onerror="this.style.display='none'">
            @endforeach
          </div>
          @endif
        </div>

        <!-- Product Info -->
        <div>
          <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">{{ $product->name }}</h1>

          <div class="mb-6">
            <div class="flex flex-wrap items-center gap-2 md:gap-4 mb-2">
              <span class="text-2xl md:text-3xl font-bold text-blue-600">{{ $product->formatted_price }}</span>
              @if($product->original_price && $product->original_price > $product->price)
                <span class="text-lg md:text-xl text-gray-500 line-through">{{ $product->formatted_original_price }}</span>
                <span class="bg-red-500 text-white px-2 py-1 rounded text-xs md:text-sm font-bold">خصم {{ $product->discount_percentage }}%</span>
              @endif
            </div>
            <p class="text-green-600 font-semibold text-sm md:text-base">✅ متوفر - توصيل مجاني</p>
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
                  <span class="text-green-500 mr-2">✓</span>
                  <span class="text-gray-700">{{ trim($feature) }}</span>
                </div>
                @endif
              @endforeach
            </div>
          </div>
          @endif

          <!-- Order Button -->
          <div class="bg-white rounded-lg shadow-lg p-4 md:p-6 border-2 border-blue-500">
            <button onclick="openOrderModal()"
              class="order-btn w-full bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-bold py-3 md:py-4 px-4 md:px-6 rounded-lg text-lg md:text-xl transition duration-300 transform hover:scale-105 shadow-lg">
              🛒 اطلب الآن
            </button>
            <div class="mt-3 md:mt-4 text-center">
              <p class="text-gray-600 text-sm md:text-base">✅ توصيل مجاني لجميع المحافظات</p>
              <p class="text-gray-600 text-sm md:text-base">🚚 شحن خلال1-3 أيام عمل</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Trust Badges -->
  <section class="py-8 bg-blue-50">
    <div class="container mx-auto px-4">
      <div class="grid md:grid-cols-3 gap-6 text-center">
        <div class="flex flex-col items-center">
          <div class="text-4xl mb-2">🚚</div>
          <h3 class="font-bold">توصيل سريع</h3>
          <p class="text-gray-600">خلال1-3 أيام عمل</p>
        </div>
        <div class="flex flex-col items-center">
          <div class="text-4xl mb-2">💯</div>
          <h3 class="font-bold">ضمان الجودة</h3>
          <p class="text-gray-600">منتجات أصلية 100%</p>
        </div>
        <div class="flex flex-col items-center">
          <div class="text-4xl mb-2">🔒</div>
          <h3 class="font-bold">دفع آمن</h3>
          <p class="text-gray-600">الدفع عند الاستلام</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-gray-800 text-white py-8">
    <div class="container mx-auto px-4 text-center">
      <p>&copy; 2025 متجرك للدروب شيبنج. جميع الحقوق محفوظة.</p>
      {{-- <p class="mt-2">للاستفسارات: 01000000000</p> --}}
    </div>
  </footer>

  <!-- Order Modal -->
  <div id="orderModal"
       class="fixed inset-0 bg-black/40 backdrop-blur-[1px] z-50
              flex items-end md:items-center justify-center px-3 sm:px-4 py-3"
       style="display:none;">
    <div id="modalPanel"
         class="bg-white shadow-lg w-full max-w-[96vw] sm:max-w-md md:max-w-2xl lg:max-w-4xl p-0 md:p-0">
      <!-- Header (sticky) -->
      <div class="panel-header px-4 py-3 md:px-6 md:py-4 flex items-center justify-between">
        <h3 class="text-base md:text-lg font-bold text-gray-800">تفاصيل الطلب</h3>
        <button onclick="closeOrderModal()" class="text-gray-500 hover:text-gray-700 text-2xl leading-none">&times;</button>
      </div>

      <!-- Body (scrollable) -->
      <div class="panel-body px-4 pb-4 md:px-6 md:pb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
          <!-- Summary -->
          <div class="space-y-4 md:sticky md:top-4">
            <div class="bg-blue-50 rounded-md p-3 text-sm">
              <div class="flex items-center gap-4">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded-lg" loading="lazy">
                <div>
                  <h4 class="font-bold text-gray-800">{{ $product->name }}</h4>
                  <p class="text-blue-600 font-bold">{{ $product->formatted_price }}</p>
                </div>
              </div>
            </div>

            <div class="bg-gray-50 rounded-md p-3 text-sm">
              <div class="flex justify-between items-center mb-2"><span class="text-gray-600">السعر:</span><span class="font-bold">{{ $product->formatted_price }}</span></div>
              <div class="flex justify-between items-center mb-2"><span class="text-gray-600">الكمية:</span><span id="modalQuantityDisplay" class="font-bold">1</span></div>
              <div class="flex justify-between items-center border-t pt-2"><span class="text-lg font-bold">الإجمالي:</span><span id="totalPrice" class="text-xl font-bold text-blue-600">{{ $product->formatted_price }}</span></div>
            </div>
          </div>

          <!-- Form -->
          <div>
            <form id="productOrderForm" class="space-y-3" novalidate>
              @csrf
              <input type="hidden" name="product_id" value="{{ $product->id }}">

              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label for="quantity" class="block text-xs font-medium text-gray-700 mb-1">الكمية</label>
                  <select id="quantity" name="quantity"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @for($i = 1; $i <= 10; $i++)
                      <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                  </select>
                </div>
                <div>
                  <label for="phone" class="block text-xs font-medium text-gray-700 mb-1">رقم الهاتف</label>
                  <input type="tel" id="phone" name="phone" required autocomplete="tel"
                         class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                         placeholder="01xxxxxxxxx" inputmode="numeric" pattern="01[0-9]{9}">
                </div>
              </div>

              <div>
                <label for="customer_name" class="block text-xs font-medium text-gray-700 mb-1">الاسم كاملاً</label>
                <input type="text" id="customer_name" name="customer_name" required autocomplete="name"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="أدخل اسمك كاملاً">
              </div>

              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label for="governorate" class="block text-xs font-medium text-gray-700 mb-1">المحافظة</label>
                  <select id="governorate" name="governorate" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                  <input id="apartment" name="apartment" type="text" autocomplete="address-line2"
                         class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                         placeholder="مثال: الدور 3، شقة 8">
                </div>
              </div>

              <div>
                <label for="address" class="block text-xs font-medium text-gray-700 mb-1">العنوان التفصيلي</label>
                <textarea id="address" name="address" required rows="2" autocomplete="street-address"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="المدينة، الحي، الشارع، رقم العقار"></textarea>
              </div>

              <div class="flex gap-2 pt-1">
                <button type="button" onclick="closeOrderModal()"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-3 rounded-md transition duration-200 text-sm">
                  إلغاء
                </button>
                <button type="submit" id="productSubmitBtn"
                        class="flex-1 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-semibold py-2 px-3 rounded-md transition duration-200 text-sm">
                  تأكيد الطلب
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
      </div><!-- /panel-body -->
    </div><!-- /modalPanel -->
  </div><!-- /orderModal -->

  <script>
    const productPrice = {{ $product->price }};

    /* نحسب أقصى ارتفاع واقعي للمودال ونخزنه كمتغير CSS */
    function setPanelMaxHeight(){
      const panel = document.getElementById('modalPanel');
      if(!panel) return;
      
      // الحصول على الارتفاع الفعلي للشاشة
      const viewportHeight = window.visualViewport?.height || window.innerHeight;
      const screenWidth = window.innerWidth;
      
      let maxHeight;
      
      // للشاشات الصغيرة (الموبايل)
      if (screenWidth <= 480) {
        maxHeight = Math.min(viewportHeight * 0.92, viewportHeight - 30);
      }
      // للشاشات المتوسطة
      else if (screenWidth <= 768) {
        maxHeight = Math.min(viewportHeight * 0.85, viewportHeight - 60);
      }
      // للشاشات الكبيرة
      else {
        maxHeight = Math.min(viewportHeight * 0.8, viewportHeight - 80);
      }
      
      panel.style.setProperty('--panel-max', maxHeight + 'px');
    }

    function openOrderModal(){
      const modal = document.getElementById('orderModal');
      modal.style.display='flex';
      document.body.style.overflow='hidden';
      updateTotalPrice();
      setPanelMaxHeight();
      if (window.matchMedia('(max-width: 768px)').matches){
        setTimeout(()=>{ const p=document.getElementById('phone'); if(p) p.focus(); }, 120);
      }
    }

    function closeOrderModal(){
      const modal = document.getElementById('orderModal');
      modal.style.display='none';
      document.body.style.overflow='auto';
      const form=document.getElementById('productOrderForm');
      form.reset(); document.getElementById('quantity').value='1';
      updateTotalPrice();
      document.getElementById('productSuccessMessage').classList.add('hidden');
      document.getElementById('productErrorMessage').classList.add('hidden');
    }

    // غلق عند الضغط خارج اللوح
    document.getElementById('orderModal').addEventListener('click',function(e){
      if (e.target === this) closeOrderModal();
    });
    // غلق بـ ESC
    document.addEventListener('keydown',function(e){
      if(e.key==='Escape') closeOrderModal();
    });

    function updateTotalPrice(){
      const q = parseInt(document.getElementById('quantity').value)||1;
      const total = productPrice*q;
      document.getElementById('totalPrice').textContent =
        new Intl.NumberFormat('ar-EG').format(total) + ' ج.م';
      document.getElementById('modalQuantityDisplay').textContent = q;
    }
    document.getElementById('quantity').addEventListener('change', updateTotalPrice);

    function changeMainImage(src, el){
      const main=document.getElementById('mainImage'); main.src=src;
      document.querySelectorAll('.gallery-image').forEach(img=>{
        img.classList.remove('border-blue-500'); img.classList.add('border-gray-300');
      });
      if(el){ el.classList.remove('border-gray-300'); el.classList.add('border-blue-500'); }
    }

    // رقم الهاتف: أرقام فقط وبحد أقصى 11
    document.getElementById('phone').addEventListener('input', function(e){
      let v=e.target.value.replace(/\D/g,''); if(v.length>11) v=v.slice(0,11); e.target.value=v;
    });

    // إرسال الطلب
    document.getElementById('productOrderForm').addEventListener('submit', function(e){
      e.preventDefault();
      const btn=document.getElementById('productSubmitBtn');
      const ok=document.getElementById('productSuccessMessage');
      const err=document.getElementById('productErrorMessage');

      ok.classList.add('hidden'); err.classList.add('hidden');
      btn.disabled=true; btn.textContent='جاري الإرسال...';

      const formData=new FormData(this);
      fetch('{{ route("orders.store") }}',{
        method:'POST', body:formData,
        headers:{
          'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept':'application/json','X-Requested-With':'XMLHttpRequest'
        }
      })
      .then(r=>{
        if(!r.ok){
          return r.text().then(t=>{ try{const d=JSON.parse(t);throw new Error(d.message||'حدث خطأ في الخادم');}
            catch(_){throw new Error('حدث خطأ في الخادم: '+r.status);} });
        }
        return r.json();
      })
      .then(d=>{
        if(d.success){
          ok.classList.remove('hidden');
          setTimeout(()=>{ window.location.href='{{ route("thanks") }}'; },2000);
        } else { throw new Error(d.message||'حدث خطأ غير متوقع'); }
      })
      .catch(e=>{
        console.error(e); err.classList.remove('hidden');
        document.getElementById('productErrorText').textContent=e.message;
      })
      .finally(()=>{ btn.disabled=false; btn.textContent='تأكيد الطلب'; });
    });

    // نحدث الارتفاع الديناميكي عند الفتح/تغيير الأبعاد/فتح الكيبورد
    if (window.visualViewport){
      visualViewport.addEventListener('resize', setPanelMaxHeight);
    }
    window.addEventListener('resize', setPanelMaxHeight);
    window.addEventListener('orientationchange', ()=>setTimeout(setPanelMaxHeight, 120));
  </script>
</body>
</html>
