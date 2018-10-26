<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Customer;
use Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer = Auth::user();
        return ['customer' => $customer];
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
            'provider' => 'required|string',
            'provider_id' => 'required|string',
        ]);

        $customer = Customer::where('provider_id',$request->provider_id)->where('provider',$request->provider)->first();

        if($customer){
            return ['api_token' => $customer->api_token];
        }
        else{

            $this->validate($request,[
                'username' => 'required|string|max:150',
                'email' => 'required|string|email|max:150'
            ]);

            $customer = Customer::create([
                'username' =>$request->input('username'),
                'email' => $request->input('email'),
                'provider' => $request->input('provider'),
                'provider_id' => $request->input('provider_id'),
            ]);

            return ['api_token' => $customer->api_token];
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $customer = Auth::user();

        $this->validate($request,[
            'username' => 'nullable|string|max:100',
            'email' => 'nullable|string|email|max:100',
            'provider' => 'nullable|string|unique:customers,provider,'.$customer->id.',id,provider_id,'.$request['provider_id'],
            'provider_id' => 'nullable|string|unique:customers,provider_id,'.$customer->id.',id,provider,'.$request['provider'],
        ]);

        $customer->update($request->except(['points']));

        return ['message' => 'Updated the customer info'];     
    }


}
