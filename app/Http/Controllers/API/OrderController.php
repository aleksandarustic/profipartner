<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Order;
use App\Customer;
use App\Reward;
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

    }

    public function load(Request $request)
    {
        
        $totalRecords = Order::count();
        $skip = $totalRecords < $request['perPage'] ? 0 : $request['perPage']*($request['page']-1);
        
        $orders = Order::with(array('customer'=>function($query){
            $query->select('id','username');
        },'reward' => function($query){
            $query->select('id','name');
        }))->orderBy($request['sort']['field'], $request['sort']['type'])
               ->take($request['perPage'])
               ->offset($skip)
               ->get();

        return ['rows' => $orders,'totalRecords' => $totalRecords];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'customer_id' => 'required|integer|exists:customers,id',
            'reward_id' => 'required|integer|exists:rewards,id',
        ]);

        $customer = Customer::findOrFail($request->customer_id);
        $reward = Reward::findOrFail($request->reward_id);

        if($customer->points >= $reward->points){
            $customer->points -= $reward->points;
            $customer->save();
        }
        else{
            $json = [
                'status' => 'error',
                'message' => "Customer does not have enough points",
            ];
    
            return response()->json($json, 403);

        }
        /*$this->validate($request, [
            'course_id' => 'required|unique:course_subject, course_id, NULL, NULL, subject_id, ' . $request['subject_id'],
            'subject_id' => 'required|unique:course_subject, subject_id, NULL, NULL, course_id, ' . $request['course_id'],
        ]);*/


        // $customer = Customer::where('provider_id',$request->provider_id)->where('provider',$request->provider)->first();
        // if(!$customer){}
        
        return Order::create(
            ['customer_id' => $request->customer_id,
            'reward_id' => $request->reward_id,
            'points' => $reward->points
         ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
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
        $this->validate($request,[
            'customer_id' => 'required|integer|exists:customers,id',
            'reward_id' => 'required|integer|exists:rewards,id',
        ]);

        $order = Order::findOrFail($id);
        $reward = Reward::findOrFail($request->reward_id);

        if($request->customer_id == $order->customer_id){

            $order->customer->points += $order->points;
            if($order->customer->points >= $reward->points){
                $order->customer->points -= $reward->points;
                $order->customer->save();
            }
            else{
                $json = [
                    'status' => 'error',
                    'message' => "Customer does not have enough points",
                ];
        
                return response()->json($json, 403);
            }
        }
        else{
            $order->customer->points += $order->points;
            $order->customer->save();

            $customer = Customer::findOrFail($request->customer_id);

            if($customer->points >= $reward->points){
                $customer->points -= $reward->points;
                $customer->save();
            }
            else{
                $json = [
                    'status' => 'error',
                    'message' => "Customer does not have enough points",
                ];
        
                return response()->json($json, 403);
            }

        }

        $order->customer_id = $request->customer_id;
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
