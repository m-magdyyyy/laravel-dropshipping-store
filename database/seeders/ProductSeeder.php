<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'أسكين شات للبشرة',
                'slug' => 'askinchat',
                'description' => 'منتج رائع للعناية بالبشرة يحتوي على مكونات طبيعية مفيدة للبشرة ويساعد على ترطيبها ونعومتها.',
                'features' => "✓ مكونات طبيعية 100%\n✓ مناسب لجميع أنواع البشرة\n✓ ترطيب عميق للبشرة\n✓ تحسين ملمس البشرة\n✓ حماية من العوامل الخارجية",
                'price' => 600.00,
                'original_price' => 800.00,
                'image_url' => 'https://via.placeholder.com/400x400/4F46E5/FFFFFF?text=Askin+Chat',
                'is_active' => true,
                'sort_order' => 1,
                'meta_title' => 'أسكين شات للبشرة - أفضل منتج للعناية بالبشرة',
                'meta_description' => 'اشتري أسكين شات للبشرة بأفضل سعر. منتج طبيعي 100% للعناية بالبشرة وترطيبها.'
            ],
            [
                'name' => 'كريم مرطب للوجه',
                'slug' => 'face-moisturizer',
                'description' => 'كريم مرطب فعال للوجه يحتوي على فيتامين E وزيوت طبيعية لترطيب البشرة الجافة.',
                'features' => "✓ يحتوي على فيتامين E\n✓ زيوت طبيعية مغذية\n✓ امتصاص سريع\n✓ مناسب للاستخدام اليومي\n✓ خالٍ من البارابين",
                'price' => 450.00,
                'original_price' => 600.00,
                'image_url' => 'https://via.placeholder.com/400x400/10B981/FFFFFF?text=Face+Cream',
                'is_active' => true,
                'sort_order' => 2,
                'meta_title' => 'كريم مرطب للوجه - ترطيب فائق للبشرة',
                'meta_description' => 'كريم مرطب للوجه بفيتامين E وزيوت طبيعية. احصل على بشرة ناعمة ومرطبة.'
            ],
            [
                'name' => 'سيروم فيتامين سي',
                'slug' => 'vitamin-c-serum',
                'description' => 'سيروم فيتامين سي المطور للحصول على بشرة مشرقة وصحية مع حماية من التصبغات.',
                'features' => "✓ فيتامين سي عالي التركيز\n✓ يحارب التصبغات\n✓ يعزز إشراق البشرة\n✓ مضاد للأكسدة\n✓ يحفز تجديد الخلايا",
                'price' => 750.00,
                'original_price' => 950.00,
                'image_url' => 'https://via.placeholder.com/400x400/F59E0B/FFFFFF?text=Vitamin+C',
                'is_active' => true,
                'sort_order' => 3,
                'meta_title' => 'سيروم فيتامين سي - إشراق وحيوية للبشرة',
                'meta_description' => 'سيروم فيتامين سي لبشرة مشرقة وصحية. يحارب التصبغات ويعزز تجديد الخلايا.'
            ]
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}
