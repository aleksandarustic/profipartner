<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all()->pluck('display_name', 'id');
        return view('dashboard')->with('roles',$roles);
    }
}
