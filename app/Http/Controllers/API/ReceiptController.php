<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Receipt;

class ReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $receipts = Receipt::with(array('user'=>function($query){
            $query->select('id','name');
        }))->orderBy('created_at','decs')->get();

        return $receipts;
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
            'user_id' => 'required|integer',
            'desc' => 'nullable|string|max:2500',
            'image' => 'required|image|max:2048'
        ]);

        if($request->hasFile('image')){

            $file_name_with_ext = $request->file('image')->getClientOriginalName();
            $file_name = pathinfo($file_name_with_ext,PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $file_name_to_store = $file_name . '_' . time() . '.'.$extension;

            $request->file('image')->storeAs('public/receipt_images',$file_name_to_store);

            $request->merge(array('image' => $file_name_to_store));
        }

        return Receipt::create(
            ['desc' => $request->desc,
            'user_id' => $request->user_id,
            'image' => $request->input('image')
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
