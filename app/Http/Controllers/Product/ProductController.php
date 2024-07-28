<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    

    public function testing() {
        $products = DB::table('ecommerce_products')->paginate(8);
        return view('webpages.users.jQuery', ['products' => $products]);
    }


    public function index() {
        $products = DB::table('ecommerce_products')->get();
        return view('webpages.admin.product.index', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        return view('webpages.admin.product.create');
    }

    /*** Store a newly created resource in storage. */
    public function store(Request $request) {

        // Define validation rules
        $rules = [
            'vendor_id' => 'required|integer|exists:ecommerce_vendors,id',
            'product_name' => 'required|string|max:255',
            'product_detail' => 'required|string',
            'sku' => 'required|string|unique:ecommerce_products,sku|max:255',
            'price' => 'required|numeric|between:0,99999999.99',
            'actual_price' => 'nullable|numeric|between:0,99999999.99',
            'offer_percentage' => 'nullable|string|max:255',
            'stock_quantity' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
            'category_id' => 'nullable|integer|exists:ecommerce_category,id',
            'brand' => 'nullable|string|max:255',
            'product_cover' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_1' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_2' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_3' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('product_cover')) {
            $product_cover = $request->file('product_cover');
            $destinationPath = public_path('products');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $fileName_cover = time() . '_' . $product_cover->getClientOriginalName();
            $product_cover->move($destinationPath, $fileName_cover);
            $relativePath_cover = 'products/' . $fileName_cover;
            $fullPath = $destinationPath . '/' . $fileName_cover;
            chmod($fullPath, 0644);
        }

        if ($request->hasFile('image_1')) {
            $image_1 = $request->file('image_1');
            $destinationPath = public_path('products');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $fileName_image_1 = time() . '_' . $image_1->getClientOriginalName();
            $image_1->move($destinationPath, $fileName_image_1);
            $relativePath_image_1 = 'products/' . $fileName_image_1;
            $fullPath = $destinationPath . '/' . $fileName_image_1;
            chmod($fullPath, 0644);
        }

        if ($request->hasFile('image_2')) {
            $image_2 = $request->file('image_2');
            $destinationPath = public_path('products');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $fileName_image_2 = time() . '_' . $image_2->getClientOriginalName();
            $image_2->move($destinationPath, $fileName_image_2);
            $relativePath_image_2 = 'products/' . $fileName_image_2;
            $fullPath = $destinationPath . '/' . $fileName_image_2;
            chmod($fullPath, 0644);
        }

        if ($request->hasFile('image_3')) {
            $image_3 = $request->file('image_3');
            $destinationPath = public_path('products');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $fileName_image_3 = time() . '_' . $image_3->getClientOriginalName();
            $image_3->move($destinationPath, $fileName_image_3);
            $relativePath_image_3 = 'products/' . $fileName_image_3;
            $fullPath = $destinationPath . '/' . $fileName_image_3;
            chmod($fullPath, 0644);
        }
            
        //Insert validated data into the ecommerce_products table
        $productId = DB::table('ecommerce_products')->insertGetId([
            'vendor_id' => 1,
            'product_name' => $request->input('product_name'),
            'product_cover'=> $relativePath_cover,
            'image_1'=> $relativePath_image_1 ?? '',
            'image_2'=> $relativePath_image_2 ?? '',
            'image_3'=> $relativePath_image_3 ?? '',
            'product_detail' => $request->input('product_detail'),
            'sku' => $request->input('sku'),
            'price' => $request->input('price'),
            'actual_price' => $request->input('actual_price'),
            'offer_percentage' => $request->input('offer_percentage'),
            'stock_quantity' => $request->input('stock_quantity'),
            'is_active' => $request->input('is_active'),
            'category_id' => $request->input('category_id'),
            'brand' => $request->input('brand'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
            
        return redirect()->route('admin.dashboard.index')->with('success', $request->input('product_name') . ' Product Added successfully!');
        //return response()->json(['message' => 'Images uploaded successfully!']);
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
    public function destroy(string $id){
    $filePaths = DB::table('ecommerce_products')->where('id',$id)->first();
    $cover = public_path($filePaths->product_cover);
    $image_1 = public_path($filePaths->image_1);
    $image_2 = public_path($filePaths->image_2);
    $image_3 = public_path($filePaths->image_3);
    if (File::exists($cover, $image_1,  $image_2, $image_3)) {
        File::delete($cover,$image_1, $image_2, $image_3);
    }
    DB::table('ecommerce_products')->where('id', $id)->delete();
    return redirect()->route('admin.dashboard.index')->with('success', 'Product deleted successfully!');
    }
}
