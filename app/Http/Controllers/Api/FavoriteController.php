<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function addToFavorite(Request $request)
    {
        $request->validate([
            'app_user_id' => 'required',
            'favorite_id' => 'required'
        ]);

        Favorite::create($request->all());

        return response()->json([
            'sucess' => true,
        ]);
    }

    public function getFavorite(Request $request)
    {

        $favorite = $request->user()->fovorite->pluck('favorite_id');

        $products = Product::whereIn('id', $favorite)->get();

        return $products;
    }


    public function toggleFavorite(Request $request)
    {

    }
}
