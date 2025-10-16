<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function removeGalleryImage(Request $request, Product $product)
    {
        $index = $request->json('index');
        
        if (!is_numeric($index) || $index < 0) {
            return response()->json(['error' => 'Invalid index'], 400);
        }
        
        $gallery = $product->gallery ?? [];
        
        if (!isset($gallery[$index])) {
            return response()->json(['error' => 'Image not found'], 404);
        }
        
        $imagePath = $gallery[$index];
        
        // حذف الملف من القرص إذا كان محلي
        if (is_string($imagePath) && !str_starts_with($imagePath, 'http')) {
            Storage::disk('public')->delete(ltrim($imagePath, '/'));
        }
        
        // إزالة الصورة من المصفوفة
        array_splice($gallery, $index, 1);
        
        // تحديث المنتج
        $product->update(['gallery' => array_values($gallery)]);
        
        return response()->json(['success' => true]);
    }
}
