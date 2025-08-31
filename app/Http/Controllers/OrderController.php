<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        // Validation rules - accept customer_name which the front-end form sends
        $rules = [
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:1000',
            'governorate' => 'nullable|string|max:100',
            'product_id' => 'nullable|exists:products,id',
            'notes' => 'nullable|string|max:1000',
            'quantity' => 'integer|min:1|max:10',
        ];

        $messages = [
            'customer_name.required' => 'الاسم مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',
            'address.required' => 'العنوان مطلوب',
            'product_id.exists' => 'المنتج المحدد غير موجود',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // If request expects JSON (AJAX), return JSON validation errors
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed',
                ], 422);
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // Set default values
        $validated['quantity'] = $validated['quantity'] ?? 1;

        // Ensure database column uses 'customer_name'
        $order = Order::create($validated);

        // Store order in session for thanks page
        session(['order' => $order->load('product')]);

        // Return JSON for AJAX clients, otherwise redirect
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'message' => 'تم تسجيل الطلب بنجاح',
            ]);
        }

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
