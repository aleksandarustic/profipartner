<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('created_at','desc')->paginate(3);
        return $users;
    }

    public function load(Request $request)
    {
        
        $totalRecords = User::count();
        $skip = $totalRecords < $request['perPage'] ? 0 : $request['perPage']*($request['page']-1);
        $users = User::orderBy($request['sort']['field'], $request['sort']['type'])
               ->take($request['perPage'])
               ->offset($skip)
               ->get();

        foreach($users as $key => $user){
            $plucked = $users[$key]->roles()->pluck('display_name','id');
            $users[$key]->selected_roles = array_keys($plucked->all());
            $users[$key]->roles_name = implode(",\n",array_values($plucked->all()));
        } 

        return ['rows' => $users,'totalRecords' => $totalRecords];
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
            'name' => 'required|string|max:150',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:7',
            'bio' => 'nullable|string|max:2500',
            'selected_roles' => 'present|array|required'
        ]);
        

        return User::create(
            ['name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'bio' => $request->bio
         ])->syncRoles($request->selected_roles);

    }

    public function profile(Request $request,$id){
        
        $user = User::findOrFail($id);


        $this->validate($request,[
            'name' => 'required|string|max:150',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|nullable|min:7',
            'bio' => 'nullable|string|max:2500',
            'photo' => 'sometimes|image|max:2048'
        ]);

        if(!empty($request->password)){
            $request->merge(['password' => Hash::make($request['password'])]);
        }
        else{
            $request->merge(['password' => $user->password]);
        }

        if($request->hasFile('photo')){

            // Delete old image
            if($user->photo != 'profile.png'){
                Storage::delete('public/profile_images/'. $user->photo);
            }

            $file_name_with_ext = $request->file('photo')->getClientOriginalName();
            $file_name = pathinfo($file_name_with_ext,PATHINFO_FILENAME);
            $extension = $request->file('photo')->getClientOriginalExtension();
            $file_name_to_store = $file_name . '_' . time() . '.'.$extension;

            $request->file('photo')->storeAs('public/profile_images',$file_name_to_store);

            $request->merge(array('photo' => $file_name_to_store));
        }


        $user->update($request->input());

        return $user;

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
        $user = User::findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string|max:150',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|min:7',
            'bio' => 'nullable|string|max:2500',
            'selected_roles' => 'present|array|required'
        ]);

        if(!empty($request->password)){
            $request->merge(['password' => Hash::make($request['password'])]);
        }
        $user->syncRoles($request->selected_roles);
        $user->update($request->all());

        return ['message' => 'Updated the user info'];        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->syncRoles([]);
        $user->syncPermissions([]);
        $user->delete();

        return ['message' => 'success'];
    }
}
