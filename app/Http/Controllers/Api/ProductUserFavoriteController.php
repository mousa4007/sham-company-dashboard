<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductUserFavoriteController extends Controller
{
    public function toggleFavorite(Request $request)
    {
        $request->validate([
            'product_id' => 'required'
        ]);

        $request->user()->userProductFavorite()->toggle([$request->product_id]);

        return response()->json([
            'success' => true
        ]);
    }

    public function getFavorites(Request $request)
    {
        return $request->user()->userProductFavorite;
        // return $request->user()->fovorite;

    }

    public function isFavored(Request $request)
    {
        $request->validate([
            'product_id' => 'required'
        ]);

        $favorite_table_length = $request->user()->userProductFavorite->count();

        $arr = array();

        if ($favorite_table_length  > 0) {
            foreach ($request->user()->userProductFavorite as $value) {
                array_push($arr, $value->pivot->product_id);
            }
        } else {
            $data = false;
        }

        $data = in_array($request->product_id, $arr);

        return response()->json([
            'is_fovored' => $data
        ]);
    }
}
