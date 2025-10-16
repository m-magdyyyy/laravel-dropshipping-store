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
        // Check if it's a cart order or single product order
        $isCartOrder = $request->has('cart_data');
        
        if ($isCartOrder) {
            return $this->storeCartOrder($request);
        } else {
            return $this->storeSingleProductOrder($request);
        }
    }
    
    private function storeSingleProductOrder(Request $request)
    {
        // Validation rules - accept customer_name which the front-end form sends
        $rules = [
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'address' => 'required|string|max:1000',
            'governorate' => 'required|string|max:100', // made required
            'product_id' => 'nullable|exists:products,id',
            'notes' => 'nullable|string|max:1000',
            'quantity' => 'integer|min:1|max:10',
        ];

        $messages = [
            'customer_name.required' => 'الاسم مطلوب',
            'customer_phone.required' => 'رقم الهاتف مطلوب',
            'address.required' => 'العنوان مطلوب',
            'governorate.required' => 'المحافظة مطلوبة',
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
        
        // Convert customer_phone to phone for database
        if (isset($validated['customer_phone'])) {
            $validated['phone'] = $validated['customer_phone'];
            unset($validated['customer_phone']);
        }

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
    
    private function storeCartOrder(Request $request)
    {
        // Validation rules for cart order
        $rules = [
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:1000',
            'governorate' => 'required|string|max:100', // made required
            'notes' => 'nullable|string|max:1000',
            'cart_data' => 'required|string',
        ];

        $messages = [
            'customer_name.required' => 'الاسم مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',
            'address.required' => 'العنوان مطلوب',
            'governorate.required' => 'المحافظة مطلوبة',
            'cart_data.required' => 'بيانات السلة مطلوبة',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        
        // Decode cart data
        $cartData = json_decode($validated['cart_data'], true);
        
        if (empty($cartData)) {
            return redirect()->back()->withErrors(['cart_data' => 'السلة فارغة'])->withInput();
        }

        $orders = [];
        
        // Create separate order for each product in cart
        foreach ($cartData as $productId => $item) {
            $orderData = [
                'customer_name' => $validated['customer_name'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'governorate' => $validated['governorate'],
                'notes' => $validated['notes'],
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'status' => Order::STATUS_PENDING,
            ];
            
            $order = Order::create($orderData);
            $orders[] = $order->load('product');
        }

        // Clear cart after successful order
        session()->forget('cart');
        
        // Store orders in session for thanks page
        session(['orders' => $orders, 'is_cart_order' => true]);

        return redirect()->route('thanks');
    }

    public function thanks()
    {
        // Check if there's an order in session (single product or cart orders)
        if (!session('order') && !session('orders')) {
            return redirect()->route('landing');
        }

        $isCartOrder = session('is_cart_order', false);
        $order = session('order');
        $orders = session('orders', []);

        return view('thanks', compact('order', 'orders', 'isCartOrder'));
    }
}
