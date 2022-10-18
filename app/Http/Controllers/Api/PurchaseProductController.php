<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\AppUser;
use App\Models\Discount;
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
        $productCount = $product->stockedProduct->where('selled', false)->where('status','active')->count();

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
            $orders = $product->stockedProduct->where('selled', false)->where('status','active')->take($quantity);

            foreach ($orders as $order) {

                $order->update(['selled' => true]);

                Sale::create([
                    'product' => $order->product_item,
                    'product_id' => $order->product_id,
                    'price' => $product->sell_price
                ]);

                if ($user->hasRole('super-user') || $user->hasRole('user')) {
                    if ($user->discount != null) {
                        if (count(Discount::find($user->discount)->exceptions) > 0) {

                            $exception = Discount::find($user->discount)->exceptions;

                            $exceptions_ids = $exception->pluck('product_id')->toArray();

                            if (in_array($order->product_id, $exceptions_ids)) {

                            $profit = $product->sell_price - $exception->where('product_id',$product->id)->first()->price;

                            $this->createOrderAndProfit($user,$order,$product,$profit);

                            }else{
                                $profit = abs($product->sell_price * Discount::find($user->discount)->percentage / 100);

                                $this->createOrderAndProfit($user,$order,$product,$profit);
                            }
                        } else {
                            $profit = abs($product->sell_price * Discount::find($user->discount)->percentage / 100);
                            // dd($profit);
                            $this->createOrderAndProfit($user,$order,$product,$profit);
                        }
                    } else {
                        Order::create([
                            'app_user_id' => $user->id,
                            'product_id' => $order->product_id,
                            'product_name' => $product->name,
                            'product' => $order->product_item,
                            'price' => $product->sell_price,
                            'is_returned' => true,
                            'profit' => 0,
                        ]);
                    }

                    $user->update([
                        'balance' => $user->balance - $product->sell_price,
                        'outgoingBalance' => $user->outgoingBalance + $product->sell_price
                    ]);
                }


                if ($user->hasRole('agent')) {


                    $agent = Agent::find($user->agent_id);

                    // dd($agent->user);
                    if ($agent->user->discount != null) {
                    //    dd(count(Discount::find($agent->user->discount)->exceptions));
                        if (count(Discount::find($agent->user->discount)->exceptions) > 0) {

                            $exception = Discount::find($agent->user->discount)->exceptions;

                            // return $exception->first()->price;
                            $exceptions_ids = $exception->pluck('product_id')->toArray();

                            if (in_array($order->product_id, $exceptions_ids)) {

                            $profit = $product->sell_price - $exception->where('product_id',$product->id)->first()->price;


                                $ord = Order::create([
                                    'app_user_id' => AppUser::where('agent_id', $agent->id)->first()->id,
                                    'product_id' => $order->product_id,
                                    'product_name' => $product->name,
                                    'product' => $order->product_item,
                                    'price' => $product->sell_price,
                                    'is_returned' => true,
                                    'profit' => $profit,
                                ]);

                                Profit::create([
                                    'order_id' => $ord->id,
                                    'app_user_id' => $agent->user->id,
                                    'agent_id' => $user->agent_id,
                                    'product_id' => $order->product_id,
                                    'profit' => $profit,
                                    'message' => $product->sell_price - $exception->first()->price . '$ مربح من شراء وكيل منتج ' . $product->name
                                ]);
                                $agent->user->update(['total_profits' =>  $agent->user->total_profits + $profit]);
                            }else{

                            $profit = abs($product->sell_price * Discount::find($agent->user->discount)->percentage / 100);

                            $ord = Order::create([
                                'app_user_id' => AppUser::where('agent_id', $agent->id)->first()->id,
                                'product_id' => $order->product_id,
                                'product_name' => $product->name,
                                'product' => $order->product_item,
                                'price' => $product->sell_price,
                                'is_returned' => true,
                                'profit' => $profit,
                            ]);

                            Profit::create([
                                'order_id' => $ord->id,
                                'app_user_id' => $agent->user->id,
                                'agent_id' => $user->agent_id,
                                'product_id' => $order->product_id,
                                'profit' => $profit,
                                'message' => abs($product->sell_price * Discount::find($agent->user->discount)->percentage / 100) . '$ مربح من شراء وكيل منتج ' . $product->name
                            ]);
                            $agent->user->update(['total_profits' => $agent->user->total_profits + $profit]);
                            }
                        } else {

                            $profit = abs($product->sell_price * Discount::find($agent->user->discount)->percentage / 100);

                            $ord = Order::create([
                                'app_user_id' => AppUser::where('agent_id', $agent->id)->first()->id,
                                'product_id' => $order->product_id,
                                'product_name' => $product->name,
                                'product' => $order->product_item,
                                'price' => $product->sell_price,
                                'is_returned' => true,
                                'profit' => $profit,
                            ]);

                            Profit::create([
                                'order_id' => $ord->id,
                                'app_user_id' => $agent->user->id,
                                'agent_id' => $user->agent_id,
                                'product_id' => $order->product_id,
                                'profit' => $profit,
                                'message' => abs($product->sell_price * Discount::find($agent->user->discount)->percentage / 100) . '$ مربح من شراء وكيل منتج ' . $product->name
                            ]);
                            $agent->user->update(['total_profits' => $agent->user->total_profits + $profit]);
                        }
                    } else {
                        //
                        Order::create([
                            'app_user_id' => AppUser::where('agent_id', $agent->id)->first()->id,
                            'product_id' => $order->product_id,
                            'product_name' => $product->name,
                            'product' => $order->product_item,
                            'price' => $product->sell_price,
                            'is_returned' => true,
                            'profit' => 0,
                        ]);
                    }


                    $user->update([
                        'balance' => $user->balance - $product->sell_price,
                        'outgoingBalance' => $user->outgoingBalance + $product->sell_price
                    ]);

                    Agent::find($user->agent_id)->update([
                        'balance' => $agent->balance - $product->sell_price,
                    ]);
                }
            }
            return 'success';
        }
    }

    public function createOrderAndProfit($user,$order,$product,$profit)
    {

        $ord = Order::create([
            'app_user_id' => $user->id,
            'product_id' => $order->product_id,
            'product_name' => $product->name,
            'product' => $order->product_item,
            'price' => $product->sell_price,
            'is_returned' => true,
            'profit' => $profit,
        ]);

        Profit::create([
            'order_id' => $ord->id,
            'app_user_id' => $user->id,
            'agent_id' => null,
            'product_id' => $order->product_id,
            'profit' => $profit,
            'message' => $profit . '$ مربح من شراء منتج ' . $product->name
        ]);

        $user->update(['total_profits' => $user->total_profits + $profit]);
    }



    public function updateUserBalance(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'product_item' => 'required'
        ]);

        $user = $request->user();
        $product = Product::find($request->product_id);

        Sale::create([
            'product' => $product->name,
            'product_id' => $request->product_id,
            'price' => $product->sell_price
        ]);


        if ($user->hasRole('super-user') || $user->hasRole('user')) {

            if ($user->discount != null) {

                if (count(Discount::find($user->discount)->exceptions) > 0) {

                    $exception = Discount::find($user->discount)->exceptions;

                    // return $exception->first()->price;
                    $exceptions_ids = $exception->pluck('product_id')->toArray();

                    if (in_array($product->id, $exceptions_ids)) {
                        // $profit = $product->sell_price - $exception->first()->price;
                        $profit = $product->sell_price - $exception->where('product_id',$product->id)->first()->price;



                    $ord = Order::create([
                        'app_user_id' => $user->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product' => $request->product_item,
                        'price' => $product->sell_price,
                        'is_returned' => false,
                        'profit' => $profit,
                    ]);


                    Profit::create([
                        'order_id' => $ord->id,
                        'app_user_id' => $user->id,
                        'agent_id' => null,
                        'product_id' => $product->id,
                        'profit' => $profit,
                        'message' => $profit . '$ مربح من شراء منتج ' . $product->name
                    ]);

                    $user->update(['total_profits' => $user->total_profits + $profit]);
                    }else{
                        $profit = abs($product->sell_price * Discount::find($user->discount)->percentage / 100);

                    $ord = Order::create([
                        'app_user_id' => $user->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product' => $request->product_item,
                        'price' => $product->sell_price,
                        'is_returned' => true,
                        'profit' => $profit,
                    ]);

                    Profit::create([
                        'order_id' => $ord->id,
                        'app_user_id' => $user->id,
                        'agent_id' => null,
                        'product_id' => $product->id,
                        'profit' => $profit,
                        'message' => $profit . '$ مربح من شراء منتج ' . $product->name
                    ]);

                    $user->update(['total_profits' => $user->total_profits + $profit]);
                }


                }
                 else {

                    $profit = abs($product->sell_price * Discount::find($user->discount)->percentage / 100);

                    $ord = Order::create([
                        'app_user_id' => $user->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product' => $request->product_item,
                        'price' => $product->sell_price,
                        'is_returned' => false,
                        'profit' => $profit,
                    ]);



                    Profit::create([
                        'order_id' => $ord->id,
                        'app_user_id' => $user->id,
                        'agent_id' => null,
                        'product_id' => $product->id,
                        'profit' => $profit,
                        'message' => $profit . '$ مربح من شراء منتج ' . $product->name
                    ]);

                    $user->update(['total_profits' => $user->total_profits + $profit]);
                }
            } else {

               $order =  Order::create([
                    'app_user_id' => $user->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product' => $request->product_item,
                    'price' => $product->sell_price,
                    'profit' => 0,
                ]);
            }

            $user->update([
                'balance' => $user->balance - $product->sell_price,
                'outgoingBalance' => $user->outgoingBalance + $product->sell_price
            ]);
        }

        if ($user->hasRole('agent')) {

            $agent = Agent::find($user->agent_id);

            if ($agent->user->discount != null) {

                if (count(Discount::find($agent->user->discount)->exceptions) > 0) {

                    $exception = Discount::find($agent->user->discount)->exceptions;

                    dd($exception);

                    $exceptions_ids = $exception->pluck('product_id')->toArray();

                    if (in_array($product->id, $exceptions_ids)) {
                        // $profit = $product->sell_price - $exception->first()->price;
            dd('exception');

                        $profit = $product->sell_price - $exception->where('product_id',$product->id)->first()->price;

                        $ord = Order::create([
                            'app_user_id' => $user->id,
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'product' => $request->product_item,
                            'price' => $product->sell_price,
                            'is_returned' => false,
                            'profit' => $profit,
                        ]);

                        Profit::create([
                            'order_id' => $ord->id,
                            'app_user_id' => $agent->user->id,
                            'agent_id' => $user->agent_id,
                            'product_id' => $product->id,
                            'profit' => $profit,
                            'message' => $product->sell_price - $exception->first()->price . '$ مربح من شراء وكيل منتج ' . $product->name
                        ]);

                        $agent->user->update(['total_profits' =>  $agent->user->total_profits + $profit]);
                    }
                } else {

            dd('no exeption');

                    $profit = abs($product->sell_price * Discount::find($agent->user->discount)->percentage / 100);

                    $ord = Order::create([
                        'app_user_id' => $user->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product' => $request->product_item,
                        'price' => $product->sell_price,
                        'is_returned' => false,
                        'profit' => $profit,
                    ]);

                    Profit::create([
                        'order_id' => $ord->id,
                        'app_user_id' => $agent->user->id,
                        'agent_id' => $user->agent_id,
                        'product_id' => $product->id,
                        'profit' => $profit,
                        'message' => abs($product->sell_price * Discount::find($agent->user->discount)->percentage / 100) . '$ مربح من شراء وكيل منتج ' . $product->name
                    ]);

                    $agent->user->update(['total_profits' => $agent->user->total_profits + $profit]);
                }
            } else {
            dd('no discount');

                Order::create([
                    'app_user_id' => $user->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product' => $request->product_item,
                    'price' => $product->sell_price,
                    'is_returned' => false,
                    'profit' => 0,
                ]);
            }

            dd('i jump every thing');
            $user->update([
                'balance' => $user->balance - $product->sell_price,
                'outgoingBalance' => $user->outgoingBalance + $product->sell_price
            ]);

            $agent->update([
                'balance' => $agent->balance - $product->sell_price
            ]);
        }
        return 'success';
    }

    public function createTransferProduct(Request $request)
    {
        $request->validate([
            'amount' => 'required',
            'address' => 'required',
            'product_id' => 'required',
        ]);

        $sell_price = Product::find($request->product_id)->sell_price;

        if ($request->user()->balance >= $request->amount) {

            $order = Order::create([
                'app_user_id' => $request->user()->id,
                'product_id' => $request->product_id,
                'product_name' => Product::find($request->product_id)->name,
                'product' => $request->address,
                'price' => $sell_price,
                'is_returned' => false,
                'profit' => 0,
                'transfer_status' => 'ignored'
            ]);


            TransferProduct::create([
                'order_id' => $order->id,
                'amount' => $request->amount,
                'address' => $request->address,
                'product_id' => $request->product_id,
                'app_user_id' => $request->user()->id,
            ]);

            $request->user()->update([
                'balance' => $request->user()->balance - Product::find($request->product_id)->sell_price,
                'outgoingBalance' => $request->user()->outgoingBalance + Product::find($request->product_id)->sell_price,
            ]);

            if ($request->user()->hasRole('agent')) {
                $agent = Agent::find($request->user()->agent_id);

                $agent->update([
                    'balance' => $agent->balance - $sell_price
                ]);
            }

            return 'success';
        } else if ($request->user()->balance <= $request->amount) {
            return 'not_enough_balance';
        } else {
            return 'error_occured';
        }
    }
}
