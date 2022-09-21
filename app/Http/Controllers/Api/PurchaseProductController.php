<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Discount;
use App\Models\DiscountException;
use App\Models\Order;
use App\Models\Product;
use App\Models\Profit;
use App\Models\Sale;
use App\Models\TransferProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PurchaseProductController extends Controller
{

    public function userOrdersByMonth(Request $request)
    {
        return $request->user()->orders()->whereDate('created_at', '>=', Carbon::now()->subDays(30))->get();
    }
    public function userOrdersByWeek(Request $request)
    {
        return $request->user()->orders()->whereDate('created_at', '>=', Carbon::now()->subDays(7))->get();
    }
    public function userOrdersByDay(Request $request)
    {
        return $request->user()->orders()->whereDate('created_at', '>=', Carbon::now()->subDays(1))->get();
    }


    public function purchaseProduct(Request $request)
    {

        $this->validate($request, [
            'product_id' => 'required',
            'quantity' => 'required',
        ]);


        //get authenticated user
        $user = $request->user();

        //find the product from id
        $product = Product::find($request->product_id);

        //get product items count
        $productCount = $product->stockedProduct->where('selled', false)->count();

        //request quantity
        $quantity = $request->quantity;


        //response message :
        //low_qunatity
        //must_be_greater_than_0
        //credit_low
        //success
        if ($quantity < 1) {
            return 'must_be_greater_than_0';
        } elseif ($quantity > $productCount) {
            return 'low_qunatity';
        } elseif ($user->balance < $product->sell_price * $quantity) {
            return 'credit_low';
        } else {
            $orders = $product->stockedProduct->where('selled', false)->take($quantity);

            foreach ($orders as $order) {

                $order->update(['selled' => true]);

                // dd($order);

                Order::create([
                    'app_user_id' => $user->id,
                    'product_id' => $order->product_id,
                    'product' => $order->product_item,
                ]);

                Sale::create([
                    'product' => $order->product_item,
                    'product_id' => $order->product_id,
                ]);
                // $order->delete();
            }

            $user->update([
                'balance' => $user->balance - $product->sell_price * $quantity
            ]);


            // return $orders;

            $exception = Discount::find($user->discount)->exceptions;

            // return $exception->first()->price;
            $exceptions_ids = $exception->pluck('product_id')->toArray();


            if ($user->hasRole('agent')) {
                $agent = Agent::find($user->agent_id);
                if (in_array($order->product_id, $exceptions_ids)) {
                    Profit::create([
                        'app_user_id' => $agent->user->id,
                        'agent_id' => $user->agent_id,
                        'product_id' => $order->product_id,
                        'profit' => $product->price - $exception->first()->price
                    ]);
                } else {
                    Profit::create([
                        'app_user_id' => $agent->user->id,
                        'agent_id' => $user->agent_id,
                        'product_id' => $order->product_id,
                        'profit' => abs($product->price * Discount::find($user->discount)->percentage / 100)
                    ]);
                }
            }

            return 'success';
        }
    }

    public function updateUserBalance(Request $request)
    {
        $request->validate([
            'cost' => 'required'
        ]);

        $user = $request->user();

        $user->update([
            'balance' => $user->balance + $request->cost
        ]);

        return $user->balance;
    }

    public function createTransferProduct(Request $request)
    {
        $request->validate([
            'amount' => 'required',
            'address' => 'required',
            'product_id' => 'required',
        ]);

        if ($request->user()->balance >= $request->amount) {
            TransferProduct::create([
                'amount' => $request->amount,
                'address' => $request->address,
                'product_id' => $request->product_id,
                'app_user_id' => $request->user()->id,
            ]);
            return response()->json(true);
        } else if ($request->user()->balance <= $request->amount) {
            return response()->json('not_enough_balance');
        } else {
            return response()->json('error_occured');
        }
    }
}
