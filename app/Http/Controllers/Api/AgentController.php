<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\AppUser;
use App\Models\NotifcationsCount;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $agent = $request->user()->agent;
        return response()->json($agent);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Agent $agent)
    {
        return $agent;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->user()->hasRole('super-user')) {

            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:agents,email|unique:app_users,email',
                'password' => 'required|min:6',
                'phone' => 'required',
                'address' => 'required',
            ]);

            $agent = Agent::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'password' => Hash::make($request->password),
                'app_user_id' => $request->user()->id,
            ]);

            $appUser = AppUser::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'password' => Hash::make($request->password),
                'agent_id' => $agent->id
            ]);


            $appUser->syncRoles(['agent']);


            NotifcationsCount::create([
                'app_user_id'=>$appUser->id
            ]);

            return 'success';
        } else {
            return 'no_permission';
        }
    }


    public function addAgentPermission(Request $request)
    {
        if ($request->user()->hasRole('super-user')) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function edit(Agent $agent)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Agent $agent, AppUser $appUser)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|min:6',
            'balance' => 'required'
        ]);



        $agent->update([
            'name' => $request->name,
            'email' => $request->email,
            'balance' => $request->balance,
            'password' => Hash::make($request->password),
            'app_user_id' => $request->user()->id,
        ]);

        $appUser->update([
            'name' => $request->name,
            'email' => $request->email,
            'balance' => $request->balance,
            'password' => Hash::make($request->password),
        ]);



        return response()->json(['success' => true, 'agent' => $agent]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Agent $agent)
    {
        $agent->delete();

        return response()->json(['success' => true, 'message' => 'deleted successfully']);
    }
}
