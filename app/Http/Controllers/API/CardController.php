<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Card;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cards = Card::with(array('customer'=>function($query){
            $query->select('id','username');
        }))->orderBy('created_at','desc')->get();

        return $cards;
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
            'note' => 'nullable|string|max:2500',
            'image' => 'required|image|max:2048|dimensions:min_width=100,min_height=100'
        ]);

        if($request->hasFile('image')){

            $file_name_with_ext = $request->file('image')->getClientOriginalName();
            $file_name = pathinfo($file_name_with_ext,PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $file_name_to_store = $file_name . '_' . time() . '.'.$extension;

            $request->file('image')->storeAs('public/card_images',$file_name_to_store);

            $request->merge(array('image' => $file_name_to_store));
        }

        return Card::create(
            ['note' => $request->note,
            'customer_id' => $request->customer_id,
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
        $card = Card::findOrFail($id);

        $this->validate($request,[
            'points' => 'required|integer',
        ]);
        $card->customer->points -= $card->points;
        $card->customer->points += $request->points;
        $card->points = $request->points;
        $card->status = 1;
        $card->save();
        $card->customer->save();

        return ['message' => 'Points has been updated'];     

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $card = Card::findOrFail($id);
        $card->customer->points -= $card->points;
        $card->customer->save();
        $card->delete();

        return ['message' => 'Card has been deleted'];
    }
}
