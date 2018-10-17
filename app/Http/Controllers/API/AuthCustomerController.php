<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Customer;
use Illuminate\Support\Facades\Hash;


class AuthCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validate($request,[
            'provider' => 'required|string',
            'provider_id' => 'required|string',
        ]);

        $customer = Customer::where('provider_id',$request->provider_id)->where('provider',$request->provider)->first();

        if($customer){
            return ['customer_id' => $customer->id];
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

            return ['customer_id' => $customer->id];
        }

    }

   
}
