<?php

namespace App\Http\Controllers\Mobile;

use App\Card;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Auth;


class CardController extends Controller
{

    public function index()
    {
        $customer = Auth::user();
        return ['cards' => $customer->cards];
    }

 
    public function store(Request $request)
    {
        $customer = Auth::user();

        $this->validate($request, [
            'note' => 'nullable|string|max:2500',
            'image' => 'required|image|max:2048|dimensions:min_width=100,min_height=100',
        ]);

        $file_name_with_ext = $request->file('image')->getClientOriginalName();
        $file_name = pathinfo($file_name_with_ext, PATHINFO_FILENAME);
        $extension = $request->file('image')->getClientOriginalExtension();
        $file_name_to_store = $file_name . '_' . time() . '.' . $extension;

        $request->file('image')->storeAs('public/card_images', $file_name_to_store);

        $request->merge(array('image' => $file_name_to_store));

        return Card::create(
            ['note' => $request->note,
                'customer_id' => $customer->id,
                'image' => $request->input('image'),
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
        $customer_id = Auth::user()->id;
        $card = Card::find($id);

        if(!$card){
            $json = [
                'status' => 'error',
                'message' => "Card not exist",
            ];
    
            return response()->json($json, 403);

        }

        if($card->customer_id != $customer_id){
            $json = [
                'status' => 'error',
                'message' => "Card does not belong to you",
            ];

            return response()->json($json, 403);

        }

        return $card;

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
        $customer = Auth::user();
        $card = Card::findOrFail($id);

        if($card->status == 1){
            $json = [
                'status' => 'error',
                'message' => "Card have already been validated",
            ];

            return response()->json($json, 403);
        }
        if($card->customer_id != $customer->id){
            $json = [
                'status' => 'error',
                'message' => "Card does not belong to you",
            ];
            return response()->json($json, 403);
        }

        $this->validate($request, [
            'note' => 'nullable|string|max:2500',
            'image' => 'nullable|image|max:2048|dimensions:min_width=100,min_height=100',
        ]);        

        // Hadle file upload
        if($request->hasFile('image')){
            // Delete old image
            Storage::delete('public/card_images/'. $card->image);

            $file_name_with_ext = $request->file('image')->getClientOriginalName();
            $file_name = pathinfo($file_name_with_ext,PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $file_name_to_store = $file_name . '_' . time() . '.'.$extension;

            $request->file('image')->storeAs('public/card_images',$file_name_to_store);

            $request->merge(array('image' => $file_name_to_store));
        }
        else{
            $request->merge(array('image' => $card->image));
        }

        $card->update($request->input());


        return ['message' => 'success'];

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Auth::user();
        $card = Card::findOrFail($id);
        if($card->customer_id != $customer->id){
            $json = [
                'status' => 'error',
                'message' => "Card does not belong to you",
            ];
            return response()->json($json, 403);
        }
        Storage::delete('public/card_images/'. $card->image);

        $card->delete();

        return ['message' => 'Card has been deleted'];
    }
}
