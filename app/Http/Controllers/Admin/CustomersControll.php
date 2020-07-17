<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Time;
use App\Schedule;
use Validator;
use Auth;
use DataTables;
use Config;
use Form;
use DB;


class CustomersControll extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $roles = Role::where('id', '!=', config('constants.ROLE_TYPE_SUPERADMIN_ID'))->pluck('name', 'id');
        return view('admin/users/customers',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCustomers(Request $request){
          $role_id = $request->input('role_id');
       
          $users=User::with('roles')
          ->whereHas('roles', function($query){
            $query->where('id','=' ,config('constants.ROLE_TYPE_USER_ID'));
          })->select([\DB::raw(with(new User)->getTable().'.*')])->groupBy('id');
        

        if(intval($role_id) > 0)
            $users->whereHas('roles', function($query) use ($role_id) {
                $query->where('id', $role_id);
            });

        return DataTables::of($users)
       
            ->editColumn('created_at', function($user){
                return date(config('constants.DATE_FORMAT'), strtotime($user->created_at));
            })            
            ->addColumn('user_name', function($user){
                return $user->name;
            })
            ->addColumn('mobile_number', function($user){
                return $user->mobile_number;
            })
            ->editColumn('email', function ($user) {
              return $user->email;
            })
            ->filterColumn('mobile_number', function ($query, $keyword) {
                $keyword = strtolower($keyword);
                 $query->whereRaw("mobile_number like ?", ["%$keyword%"]);
            })
            ->filterColumn('user_name', function ($query, $keyword) {
                $keyword = strtolower($keyword);
                 $query->whereRaw('users.name like ?', ["%$keyword%"]);
            })
            ->addColumn('action', function ($user) {
                return
                    // Delete
                      Form::open(array(
                                  'style' => 'display: inline-block;',
                                  'method' => 'DELETE',
                                   'onsubmit'=>"return confirm('Do you really want to delete?')",
                                  'route' => ['admin.users.destroy', $user->id])).
                      ' <button type="submit" class="btn btn-danger btn-circle btn-sm"><i class="fas fa-trash"></i></button>'.
                      Form::close();
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
