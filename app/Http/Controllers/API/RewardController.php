<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;


class RewardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rewards = Reward::orderBy('created_at', 'desc')->get();

        return $rewards;
    }


    public function get_select_options(){
        $rewards = Reward::all()->pluck('name', 'id');
        return $rewards;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'nullable|string|max:2500',
            'points' => 'required|integer',
            'image' => 'required|image|max:2048|dimensions:min_width=100,min_height=100',
        ]);

        $file_name_with_ext = $request->file('image')->getClientOriginalName();
        $file_name = pathinfo($file_name_with_ext,PATHINFO_FILENAME);
        $extension = $request->file('image')->getClientOriginalExtension();
        $file_name_to_store = $file_name . '_' . time() . '.'.$extension;

        $request->file('image')->storeAs('public/reward_images',$file_name_to_store);
        $request->merge(array('image' => $file_name_to_store));


       return Reward::create(
            ['name' => $request->name,
            'description' => $request->description,
            'image' => $request->input('image'),
            'points' => $request->points,
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

        $reward = Reward::findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string',
            'description' => 'nullable|string|max:2500',
            'points' => 'required|integer',
            'image' => 'image|nullable|max:2048|dimensions:min_width=100,min_height=100'
        ]);

        // Hadle file upload
        if($request->hasFile('image')){
            // Delete old image
            Storage::delete('public/reward_images/'. $reward->image);

            $file_name_with_ext = $request->file('image')->getClientOriginalName();
            $file_name = pathinfo($file_name_with_ext,PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $file_name_to_store = $file_name . '_' . time() . '.'.$extension;

            $request->file('image')->storeAs('public/reward_images',$file_name_to_store);

            $request->merge(array('image' => $file_name_to_store));
        }
        else{
            $request->merge(array('image' => $reward->image));
        }

        $reward->update($request->input());

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
        $reward = Reward::findOrFail($id);
        Storage::delete('public/reward_images/'. $reward->image);
        $reward->delete();

        return ['message' => 'success'];
    }
}
