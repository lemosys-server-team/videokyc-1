<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Notifications\OTPVerification;
use Illuminate\Support\Facades\Crypt;
use App\User;
use App\State;
use App\City;
use App\Holiday;
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
    public function create(){
        $state = State::where(['is_active'=>TRUE])->pluck('title', 'id');
        $city=City::where(['is_active'=>TRUE])->pluck('title', 'id');
        $holidays=Holiday::where(['is_active'=>TRUE])->pluck('date')->toArray();
        $holiday='';
        if(!empty($holidays)){
            $date=implode(' ', $holidays);
            $holiday= json_encode(str_replace(' ', ',', $date));
        }
        return view('admin.users.customer_create', compact('state','city','holiday'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $rules = [
            'name'           => 'required', 
            'email'          => 'required|email|unique:'.with(new User)->getTable().',email',
            'mobile_number'  => 'required|unique:'.with(new User)->getTable().',mobile_number',
            'state_id'       => 'required',
            'city_id'        => 'required',
            'address1'       =>'required',
            'address2'       =>'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $request->all();
            
            $code = rand(100000,999999);
            $data['password'] = Hash::make($code);
            $user = User::create($data);

            if ($user)
                $user->notify(
                    new OTPVerification($code,$request->mobile_number)
                );
            //assign user roles
            $user->assignRole(config('constants.ROLE_TYPE_USER_ID'));
            $request->session()->flash('success',__('global.messages.add'));
            return redirect()->route('admin.customers.index');
        }else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $user = User::findOrFail($id);
        $state = State::where(['is_active'=>TRUE])->pluck('title', 'id');
        $city=City::where(['is_active'=>TRUE])->pluck('title', 'id');
        $holidays=Holiday::where(['is_active'=>TRUE])->pluck('date')->toArray();
        $holiday='';
        if(!empty($holidays)){
            $date=implode(' ', $holidays);
            $holiday= json_encode(str_replace(' ', ',', $date));
        }
        return view('admin.users.customer_edit',compact('user','state','city','holiday'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $user = User::findOrFail($id);

        $rules = [
            'name'           => 'required', 
            'email'          => 'required|email|unique:'.with(new User)->getTable().',email,'.$user->getKey(),
            'mobile_number'  => 'required|unique:'.with(new User)->getTable().',mobile_number,'.$user->getKey(),
            'state_id'       => 'required',
            'city_id'        => 'required',
            'address1'       => 'required',
            'address2'       => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $request->all();
            $user->update($data);
            $request->session()->flash('success',__('global.messages.update'));
            return redirect()->route('admin.customers.index');
        }else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCustomers(Request $request){
          $role_id = $request->input('role_id');
       
          $users=User::with(['roles','state','city'])
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
            ->addColumn('state', function($user){
                return isset($user->state->title)?$user->state->title:'';
            })
            ->addColumn('city', function($user){
                return isset($user->city->title)?$user->city->title:'';
            })
            ->addColumn('action', function ($user) {
                return
                    // Delete
                '<a href="'.route('admin.customers.edit',[$user->id]).'" class="btn btn-primary btn-circle btn-sm"><i class="fas fa-edit"></i></a> '.
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
}
