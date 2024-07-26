<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{


    public function homepage() {
        $products = DB::table('ecommerce_products')->paginate(8);
        return view('webpages.users.homepage', ['products' => $products]);
    }


}