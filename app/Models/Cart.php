<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Cart extends Model
{
    // This model handles cart operations using sessions
    // No database table needed for cart functionality
    
    public static function add($productId, $quantity = 1)
    {
        $product = Product::findOrFail($productId);
        $cart = Session::get('cart', []);
        
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
            $cart[$productId]['quantity'] = min($cart[$productId]['quantity'], 10);
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image_url' => $product->image_url ?: $product->image,
                'quantity' => $quantity,
                'slug' => $product->slug
            ];
        }
        
        Session::put('cart', $cart);
        return $cart[$productId];
    }
    
    public static function updateQuantity($productId, $quantity)
    {
        $cart = Session::get('cart', []);
        
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = max(1, min($quantity, 10));
            Session::put('cart', $cart);
            return $cart[$productId];
        }
        
        return null;
    }
    
    public static function remove($productId)
    {
        $cart = Session::get('cart', []);
        
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);
            return true;
        }
        
        return false;
    }
    
    public static function clear()
    {
        Session::forget('cart');
    }
    
    public static function get()
    {
        return Session::get('cart', []);
    }
    
    public static function count()
    {
        $cart = Session::get('cart', []);
        return array_sum(array_column($cart, 'quantity'));
    }
    
    public static function total()
    {
        $cart = Session::get('cart', []);
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return $total;
    }
    
    public static function formattedTotal()
    {
        return number_format(self::total(), 0) . ' ج.م';
    }
}
