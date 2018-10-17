<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Customer;

class CustomerController extends Controller
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


    public function load(Request $request)
    {
        
        $totalRecords = Customer::count();
        $skip = $totalRecords < $request['perPage'] ? 0 : $request['perPage']*($request['page']-1);
        $customers = Customer::orderBy($request['sort']['field'], $request['sort']['type'])
               ->take($request['perPage'])
               ->offset($skip)
               ->get();

        return ['rows' => $customers,'totalRecords' => $totalRecords];
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
            'username' => 'required|string|max:100',
            'email' => 'required|string|email|max:100',
            'provider' => 'required|string|unique:customers,provider,NULL,NULL,provider_id,'.$request['provider_id'],
            'provider_id' => 'required|string|unique:customers,provider_id,NULL,NULL,provider,'.$request['provider'],
        ]);

        /*$this->validate($request, [
            'course_id' => 'required|unique:course_subject, course_id, NULL, NULL, subject_id, ' . $request['subject_id'],
            'subject_id' => 'required|unique:course_subject, subject_id, NULL, NULL, course_id, ' . $request['course_id'],
        ]);*/


        // $customer = Customer::where('provider_id',$request->provider_id)->where('provider',$request->provider)->first();
        // if(!$customer){}
        
        return Customer::create(
            ['username' => $request->username,
            'email' => $request->email,
            'provider' => $request->provider,
            'provider_id' => $request->provider_id,
         ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $this->validate($request,[
            'username' => 'required|string|max:100',
            'email' => 'required|string|email|max:100',
            'provider' => 'required|string|unique:customers,provider,'.$customer->id.',id,provider_id,'.$request['provider_id'],
            'provider_id' => 'required|string|unique:customers,provider_id,'.$customer->id.',id,provider,'.$request['provider'],
        ]);

        $customer->update($request->all());

        return ['message' => 'Updated the customer info'];     
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return ['message' => 'success'];
    }
}
