<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function categories(Request $request)
    {
        if($request->user()->status == 'active'){

        $category = DB::table('categories')->where('status','active')->orderBy('arrangement','asc')->get();

        return $category;
    }
    }

    public function products(Request $request)
    {
        $products = Category::find($request->id)->products;
        return $products;
    }

    public function userOrders(Request $request)
    {
        $request->validate([
            'app_user_id' => 'required'
        ]);
        $user_orders = AppUser::find($request->id)->orders;
    }

    public function productCount(Request $request)
    {
        $request->validate([
            'product_id' => 'required'
        ]);

        $productCount = Product::find($request->product_id)->stockedProduct->count();

        return $productCount;
    }
}
