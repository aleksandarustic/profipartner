<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
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
            'provider_id' => 'required|integer',
        ]);

        $user = User::where('provider_id',$request->provider_id)->where('provider',$request->provider)->first();

        if($user){
            return ['message' => 'success'];
        }
        else{

            $this->validate($request,[
                'username' => 'required|string|max:150',
                'email' => 'required|string|email|max:150|unique:users'
            ]);

            if(!empty($request->input('password'))){
               $password = Hash::make($request->input('password'));
            }
            else{
               $password = null;
            }

             User::create([
                'name' =>$request->input('username'),
                'username' =>$request->input('username'),
                'email' => $request->input('email'),
                'provider' => $request->input('provider'),
                'provider_id' => $request->input('provider_id'),
                'photo' => 'profile.png',
                'password' => $password
            ])->attachRole('user');

            return ['message' => 'success'];
        }

    }

   
}
