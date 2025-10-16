<?php

// معالجة جماعية لكل المنتجات الحالية لتوليد المصغّرات
use App\Models\Product;
use App\Jobs\OptimizeProductImages;

$products = Product::whereNotNull('image')->get();

echo "بدء معالجة " . $products->count() . " منتج...\n";

foreach ($products as $product) {
    if ($product->image && !$product->thumbnail_path) {
        OptimizeProductImages::dispatch($product->id);
        echo "تم إرسال مهمة تحسين للمنتج: " . $product->name . "\n";
    }
}

echo "تمت المعالجة بنجاح!\n";
