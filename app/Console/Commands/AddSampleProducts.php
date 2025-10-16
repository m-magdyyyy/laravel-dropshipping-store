<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class AddSampleProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:sample';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add sample products for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $products = [
            [
                'name' => 'ساعة ذكية عالية الجودة',
                'slug' => 'smart-watch-premium',
                'description' => 'ساعة ذكية متطورة مع شاشة AMOLED عالية الدقة، مقاومة للماء، ومتابعة شاملة للصحة واللياقة البدنية. تدعم المكالمات والرسائل وأكثر من 100 نشاط رياضي.',
                'features' => "شاشة AMOLED بحجم 1.43 بوصة\nمقاومة للماء بمعيار IP68\nبطارية تدوم حتى 14 يوم\nمتابعة معدل ضربات القلب\nGPS مدمج\nمكالمات بلوتوث\nأكثر من 100 وضع رياضي",
                'price' => 899.00,
                'original_price' => 1299.00,
                'is_active' => true,
                'sort_order' => 1,
                'meta_title' => 'ساعة ذكية عالية الجودة - أفضل الأسعار',
                'meta_description' => 'احصل على أفضل ساعة ذكية بميزات متطورة وسعر مميز مع توصيل مجاني'
            ],
            [
                'name' => 'سماعات لاسلكية احترافية',
                'slug' => 'wireless-headphones-pro',
                'description' => 'سماعات لاسلكية عالية الجودة مع تقنية إلغاء الضوضاء النشطة وجودة صوت استثنائية. مثالية للموسيقى والمكالمات والألعاب.',
                'features' => "تقنية إلغاء الضوضاء النشطة\nجودة صوت Hi-Fi\nبطارية 30 ساعة\nشحن سريع\nمقاومة للعرق\nاتصال بلوتوث 5.3\nمايكروفون مدمج للمكالمات",
                'price' => 599.00,
                'original_price' => 899.00,
                'is_active' => true,
                'sort_order' => 2,
                'meta_title' => 'سماعات لاسلكية احترافية - جودة صوت فائقة',
                'meta_description' => 'سماعات لاسلكية عالية الجودة مع إلغاء الضوضاء وبطارية طويلة المدى'
            ],
            [
                'name' => 'حامل هاتف مغناطيسي للسيارة',
                'slug' => 'magnetic-car-phone-holder',
                'description' => 'حامل هاتف مغناطيسي قوي ومتين للسيارة، يثبت على فتحة التكييف. يدعم جميع أحجام الهواتف الذكية مع دوران 360 درجة.',
                'features' => "مغناطيس قوي نيوديميوم\nتثبيت آمن على فتحة التكييف\nدوران 360 درجة\nيدعم جميع أحجام الهواتف\nتصميم أنيق ومتين\nسهولة التركيب والاستخدام",
                'price' => 149.00,
                'original_price' => 249.00,
                'is_active' => true,
                'sort_order' => 3,
                'meta_title' => 'حامل هاتف مغناطيسي للسيارة - قوي ومتين',
                'meta_description' => 'حامل هاتف مغناطيسي عملي للسيارة مع تثبيت آمن ودوران كامل'
            ],
            [
                'name' => 'شاحن لاسلكي سريع',
                'slug' => 'fast-wireless-charger',
                'description' => 'شاحن لاسلكي سريع بقوة 15 واط، متوافق مع جميع الهواتف التي تدعم الشحن اللاسلكي. تصميم أنيق مع إضاءة LED.',
                'features' => "شحن سريع 15 واط\nمتوافق مع آيفون وأندرويد\nحماية من السخونة الزائدة\nإضاءة LED للحالة\nتصميم نحيف وأنيق\nحماية من الشحن الزائد",
                'price' => 299.00,
                'original_price' => 449.00,
                'is_active' => true,
                'sort_order' => 4,
                'meta_title' => 'شاحن لاسلكي سريع 15 واط - آمن وعملي',
                'meta_description' => 'شاحن لاسلكي سريع وآمن لجميع الهواتف الذكية مع حماية متقدمة'
            ]
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        $this->info('Sample products created successfully!');
    }
}
