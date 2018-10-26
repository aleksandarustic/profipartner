<?php

namespace App\Http\Controllers\Mobile;

use App\Customer;
use App\Http\Controllers\Controller;
use App\Order;
use App\Reward;
use Auth;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer = Auth::user();
        return ['orders' => $customer->orders];
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Auth::user();
        $order = Order::findOrFail($id);

        if ($order->customer_id != $customer->id) {
            $json = [
                'status' => 'error',
                'message' => "Order does not belong to you",
            ];
            return response()->json($json, 403);
        }

        return ['order' => $order];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $customer = Auth::user();

        $this->validate($request, [
            'reward_id' => 'required|integer|exists:rewards,id',
        ]);

        $reward = Reward::findOrFail($request->reward_id);

        if ($customer->points >= $reward->points) {
            $customer->points -= $reward->points;
            $customer->save();
        } else {
            $json = [
                'status' => 'error',
                'message' => "Customer does not have enough points",
            ];
            return response()->json($json, 403);

        }

        Order::create(
            ['customer_id' => $customer->id,
                'reward_id' => $request->reward_id,
                'points' => $reward->points,
            ]);

        return ['message' => 'Reward has been ordered'];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $customer = Auth::user();

        $this->validate($request, [
            'reward_id' => 'required|integer|exists:rewards,id',
        ]);

        $order = Order::findOrFail($id);

        $reward = Reward::findOrFail($request->reward_id);

        if ($order->customer_id != $customer->id) {
            $json = [
                'status' => 'error',
                'message' => "Order does not belong to you",
            ];
            return response()->json($json, 403);
        }

        $order->customer->points += $order->points;
        if ($order->customer->points >= $reward->points) {
            $order->customer->points -= $reward->points;
            $order->customer->save();
        } else {
            $json = [
                'status' => 'error',
                'message' => "Customer does not have enough points",
            ];

            return response()->json($json, 403);
        }

        $order->reward_id = $request->reward_id;
        $order->points = $reward->points;
        $order->save();

        return ['message' => 'success'];

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
