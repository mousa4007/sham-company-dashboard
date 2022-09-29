<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProfitsController extends Controller
{
    //Super User Profits start
    public function superUserProfitsInDay(Request $request)
    {
        return $request->user()->profits()
        ->where('created_at','>=',Carbon::now()->subDay())->where('agent_id',null)->sum('profit');
    }

    public function superUserProfitsInWeek(Request $request)
    {
        return $request->user()->profits()
        ->where('created_at','>=',Carbon::now()->subWeek())->sum('profit');
    }

    public function superUserProfitsInMonth(Request $request)
    {
        return $request->user()->profits()
        ->where('created_at','>=',Carbon::now()->subMonth())->sum('profit');
    }
    //Super User Profits end

    // Agents Profits start
    public function profitsFromAgentInDay(Request $request)
    {
        $request->validate([
            'agent_id'=>'required|integer'
        ]);

        return Profit::where('agent_id',$request->agent_id)
        ->where('created_at','>=',Carbon::now()->subDay())->sum('profit');
    }

    public function profitsFromAgentInWeek(Request $request)
    {
        $request->validate([
            'agent_id'=>'required|integer'
        ]);

        return Profit::where('agent_id',$request->agent_id)
        ->where('created_at','>=',Carbon::now()->subWeek())->sum('profit');
    }

    public function profitsFromAgentInMonth(Request $request)
    {
        $request->validate([
            'agent_id'=>'required|integer'
        ]);

        return Profit::where('agent_id',$request->agent_id)
        ->where('created_at','>=',Carbon::now()->subMonth())->sum('profit');
    }
    // Agents Profits start


    public function withdrawProfits(Request $request)
    {
        $request->validate([
            'profit' => 'required | numeric'
        ]);

        // dd( $request->user()->whithdrawn_profits);

        if($request->profit <= ($request->user()->total_profits - $request->user()->whithdrawn_profits)){
            $request->user()->update([
                'whithdrawn_profits' => $request->user()->whithdrawn_profits + $request->profit ,
                'balance' =>   $request->user()->balance + $request->profit,
            ]);

            return 'success';
        }else{
            return 'profit_illegal';
        }
    }


}
