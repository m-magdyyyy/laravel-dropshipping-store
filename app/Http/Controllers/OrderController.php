<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)
                          ->orderBy('sort_order')
                          ->orderBy('created_at', 'desc')
                          ->take(6)
                          ->get();
        
        return view('landing', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:1000',
            'governorate' => 'required|string|max:100',
            'product_id' => 'nullable|exists:products,id',
            'notes' => 'nullable|string|max:1000',
            'quantity' => 'integer|min:1|max:10',
        ], [
            'name.required' => 'الاسم مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',
            'address.required' => 'العنوان مطلوب',
            'governorate.required' => 'المحافظة مطلوبة',
            'product_id.exists' => 'المنتج المحدد غير موجود',
        ]);

        // Set default values
        $validated['quantity'] = $validated['quantity'] ?? 1;
        $validated['customer_name'] = $validated['name'];
        unset($validated['name']);

        $order = Order::create($validated);

        // Store order in session for thanks page
        session(['order' => $order->load('product')]);

        return redirect()->route('thanks');
    }

    public function thanks()
    {
        // Check if there's an order in session
        if (!session('order')) {
            return redirect()->route('landing');
        }

        return view('thanks');
    }
}
