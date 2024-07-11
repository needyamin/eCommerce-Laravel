<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $products = DB::table('ecommerce_products')->get();
        return view('webpages.admin.product.index', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('webpages.admin.product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Define validation rules
        $validatedData = $request->validate([
            'vendor_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'detail' => 'required|string',
            'sku' => 'required|string|unique:ecommerce_products,sku|max:255',
            'price' => 'required|numeric',
            'actual_price' => 'nullable|numeric',
            'offer_percentage' => 'nullable|numeric|min:0|max:100',
            'stock_quantity' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
            'category_id' => 'required|integer',
            'brand' => 'nullable|string|max:255',
             // Additional validation for product images
            'filename' => 'required|string|max:255',
            'mime_type' => 'nullable|string|max:255',
            'size' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        // Sanitize the slug 
        //$slug = Str::slug($request->input('slug_url'));

         // Insert validated data into the database using DB query
         DB::table('ecommerce_products')->insertGetId([
         'vendor_id' => $validatedData['vendor_id'],
         'name' => $validatedData['name'],
         'detail' => $validatedData['detail'],
         'sku' => $validatedData['sku'],
         'price' => $validatedData['price'],
         'actual_price' => $validatedData['actual_price'],
         'offer_percentage' => $validatedData['offer_percentage'],
         'stock_quantity' => $validatedData['stock_quantity'],
         'is_active' => $validatedData['is_active'],
         'category_id' => $validatedData['category_id'],
         'brand' => $validatedData['brand'],
         'created_at' => now(),
         'updated_at' => now(),
        ]);

        // Insert validated data into the product_images table using DB query
        DB::table('ecommerce_product_images')->insert([
            'product_id' => $productId,
            'filename' => $validatedData['filename'],
            'mime_type' => $validatedData['mime_type'],
            'size' => $validatedData['size'],
            'description' => $validatedData['description'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Redirect or show a success message
        return redirect()->route('admin.dashboard.index')->with('success', 'Product Added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::table('ecommerce_products')->where('id', $id)->delete();
        return redirect()->route('admin.dashboard.index')->with('success', 'Product deleted successfully!');
    }
}
