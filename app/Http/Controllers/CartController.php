<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        // Add product to cart using model
        Cart::add($request->product_id, $request->quantity);
        
        if ($this->shouldReturnJson($request)) {
            return response()->json([
                'success' => true,
                'message' => 'تم إضافة المنتج للسلة بنجاح',
                'cart_count' => Cart::count(),
                'cart_total' => Cart::total()
            ]);
        }

        return redirect()->back()->with('success', 'تم إضافة المنتج للسلة بنجاح');
    }
    
    public function show()
    {
        $cart = Cart::get();
        $cartTotal = Cart::total();
        $cartCount = Cart::count();
        
        return view('cart.show', compact('cart', 'cartTotal', 'cartCount'));
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:10'
        ]);
        
        $updatedItem = Cart::updateQuantity($request->product_id, $request->quantity);
        
    if ($updatedItem && $this->shouldReturnJson($request)) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الكمية بنجاح',
                'cart_count' => Cart::count(),
                'cart_total' => Cart::total(),
                'item_total' => $updatedItem['price'] * $updatedItem['quantity']
            ]);
        }
        
        return redirect()->back()->with('success', 'تم تحديث الكمية بنجاح');
    }
    
    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);
        
        Cart::remove($request->product_id);
        
    if ($this->shouldReturnJson($request)) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف المنتج من السلة',
                'cart_count' => Cart::count(),
                'cart_total' => Cart::total()
            ]);
        }
        
        return redirect()->back()->with('success', 'تم حذف المنتج من السلة');
    }
    
    public function clear()
    {
        Cart::clear();
        
    if ($this->shouldReturnJson(request())) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف جميع المنتجات من السلة',
                'cart_count' => 0,
                'cart_total' => 0
            ]);
        }
        
        return redirect()->back()->with('success', 'تم حذف جميع المنتجات من السلة');
    }
    
    public function count()
    {
        return response()->json([
            'cart_count' => Cart::count()
        ]);
    }

    /**
     * Determine if we should return JSON for this request.
     */
    private function shouldReturnJson(Request $request): bool
    {
        return $request->ajax()
            || $request->wantsJson()
            || $request->expectsJson()
            || $request->isJson()
            || str_contains($request->header('Accept'), 'application/json');
    }
}
