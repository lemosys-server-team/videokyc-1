<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;




class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $activeusers=User::with('roles')->whereHas('roles', function($query){
            $query->where('id','!=' ,config('constants.ROLE_TYPE_SUPERADMIN_ID'));
        })->where('is_active',true)->count(); 
        $inactiveusers=User::with('roles')->whereHas('roles', function($query){
            $query->where('id','!=' ,config('constants.ROLE_TYPE_SUPERADMIN_ID'));
        })->where('is_active',false)->count(); 
      return view('admin.dashboard.dashboard',compact('activeusers','inactiveusers'));
    }
    
}
