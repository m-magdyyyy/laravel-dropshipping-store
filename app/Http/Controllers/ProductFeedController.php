<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductFeedController extends Controller
{
    /**
     * Generate Google Shopping XML feed
     * 
     * @return \Illuminate\Http\Response
     */
    public function generate()
    {
        // Fetch all active products
        $products = Product::where('is_active', true)->get();
        
        // Generate XML content from blade view
        $xml = view('feeds.google', compact('products'))->render();
        
        // Return response with XML content type
        return response($xml, 200)
            ->header('Content-Type', 'text/xml; charset=utf-8');
    }
}
