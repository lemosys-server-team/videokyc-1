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

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){  
        $roles = Role::where('id', '!=', config('constants.ROLE_TYPE_SUPERADMIN_ID'))->pluck('name', 'id');
        return view('admin/users/index',compact('roles'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUsers(Request $request){
          $role_id = $request->input('role_id');
       
          $users=User::with('roles')
          ->whereHas('roles', function($query){
            $query->where('id',config('constants.ROLE_TYPE_SALES_ID'));
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
            ->editColumn('role', function($user){               
              return ucwords($user->roles[0]['name']);
            })
            ->filterColumn('mobile_number', function ($query, $keyword) {
                $keyword = strtolower($keyword);
                 $query->whereRaw("mobile_number like ?", ["%$keyword%"]);
            })
            ->filterColumn('user_name', function ($query, $keyword) {
                $keyword = strtolower($keyword);
                 $query->whereRaw('users.name like ?', ["%$keyword%"]);
            })

            ->editColumn('is_active', function ($user) {
                if($user->is_active == TRUE )
                {
                    return "<a href='".route('admin.users.status',$user->id)."'><span class='badge badge-success'>Active</span></a>";
                }else{
                    return "<a href='".route('admin.users.status',$user->id)."'><span class='badge badge-danger'>Inactive</span></a>";
                }
            })
            ->addColumn('action', function ($user) {
                return
                    // edit
                    '<a href="'.route('admin.users.edit',[$user->id]).'" class="btn btn-primary btn-circle btn-sm"><i class="fas fa-edit"></i></a> '.
                    // Delete
                      Form::open(array(
                                  'style' => 'display: inline-block;',
                                  'method' => 'DELETE',
                                   'onsubmit'=>"return confirm('Do you really want to delete?')",
                                  'route' => ['admin.users.destroy', $user->id])).
                      ' <button type="submit" class="btn btn-danger btn-circle btn-sm"><i class="fas fa-trash"></i></button>'.
                      Form::close();
            })
            ->rawColumns(['is_active','action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $roles = Role::where('id', '!=', config('constants.ROLE_TYPE_SUPERADMIN_ID'))->get()->pluck('name', 'id')->map(function($value, $key){
            return ucwords($value);
        });
        $times = Time::where('is_active',true)->pluck('title', 'id')->map(function($value, $key){
          return ucwords($value);
        });
        return view('admin.users.create', compact('roles','times'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $rules = [
            'role_id'           => 'required', 
            'time_id'           => 'required', 
            'employee_id'       => 'required', 
            'name'              => 'required', 
            'email'             => 'required',
            'password'          => 'required',
            'mobile_number'     => 'required|unique:'.with(new User)->getTable().',mobile_number',
            'profile_picture'   => 'image',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $request->all();
            $data['password'] = Hash::make($data['password']);
            if ($request->hasFile('profile_picture')){
                $file = $request->file('profile_picture');
                $customimagename  = time() . '.' . $file->getClientOriginalExtension();
                $destinationPath = config('constants.USERS_UPLOADS_PATH');
                $file->storeAs($destinationPath, $customimagename);
                $data['profile_picture'] = $customimagename;
            }
            $user = User::create($data);
            $user_id=$user->id;
            // assign user roles
            if (!empty($request->role_id)) {
                $user->assignRole($request->role_id);
            }
            
            $request->session()->flash('success',__('global.messages.add'));
            return redirect()->route('admin.users.index');
        }else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        return redirect()->route('admin.users.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $user = User::findOrFail($id);
        $roles = Role::where('id', '!=', config('constants.ROLE_TYPE_SUPERADMIN_ID'))->get()->pluck('name', 'id')->map(function($value, $key){
            return ucwords($value);
        });
        $times = Time::where('is_active',true)->pluck('title', 'id')->map(function($value, $key){
          return ucwords($value);
        });
        return view('admin.users.edit',compact('user','roles','times'));
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
           /* 'role_id'           => 'required', */
            'time_id'           => 'required', 
            'name'              => 'required', 
            'employee_id'       => 'required',  
            'email'             => 'required',
            'mobile_number'     => 'required|unique:'.with(new User)->getTable().',mobile_number,'.$user->getKey(),
            'profile_picture'   => 'image'
        ];
       
        if (isset($request->reset_password) && $request->reset_password==TRUE) {
            $rules['password'] = 'required|confirmed';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $request->all();

            if (isset($request->reset_password) && $request->reset_password==TRUE) {
                $data['password'] = Hash::make($request->password);
            }else{
                unset($data['password']);
            }
            if ($request->hasFile('profile_picture')){
                $file = $request->file('profile_picture');
                $customimagename  = time() . '.' . $file->getClientOriginalExtension();
                $destinationPath = config('constants.USERS_UPLOADS_PATH');
                $file->storeAs($destinationPath, $customimagename);

                if ($user->profile_picture!='' && \Storage::exists(config('constants.USERS_UPLOADS_PATH').$user->profile_picture)) {
                    \Storage::delete(config('constants.USERS_UPLOADS_PATH').$user->profile_picture);
                }
                $data['profile_picture'] = $customimagename;
            } 
            $user->update($data);

            // Update user roles
            /*if (!empty($request->role_id)) {
                $user->syncRoles($request->role_id);
            }*/

            $request->session()->flash('success',__('global.messages.update'));
            return redirect()->route('admin.users.index');
        }else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Change status the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function status($id){
      $user = User::findOrFail($id);
      if(isset($user->is_active) && $user->is_active==FALSE){
          $user->update(['is_active'=>TRUE]);
          session()->flash('success',__('global.messages.activate'));
      }else{
          $user->update(['is_active'=>FALSE]);
          session()->flash('danger',__('global.messages.deactivate'));
      }
      return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
      $user = User::with('roles')->whereId($id)->first();
      if(isset($user)){
        if($user['roles'][0]['id']==config('constants.ROLE_TYPE_SALES_ID')){
            $schedules = Schedule::where('sale_id',$id)->get();
            foreach ($schedules as $key => $schedule) {
                $ss01 = isset($schedule->ss01)?$schedule->ss01:'';
                if(isset($ss01) && $ss01!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$ss01)) {
                    \Storage::delete(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$ss01);
                }
                $ss02 = isset($schedule->ss02)?$schedule->ss02:'';
                if (isset($ss02) && $ss02!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$ss02)) {
                    \Storage::delete(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$ss02);
                }
                $ss03 = isset($schedule->ss03)?$schedule->ss03:'';
                if (isset($ss03) && $ss03!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$ss03)) {
                    \Storage::delete(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$ss03);
                }
                $schedule->delete();
            }
        }else{
            $schedules = Schedule::where('user_id',$id)->get();
            foreach ($schedules as $key => $schedule) {
                    $image_adhar = isset($schedule->image_adhar)?$schedule->image_adhar:'';
                    if (isset($image_adhar) && $image_adhar!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_USER').$image_adhar)) {
                        \Storage::delete(config('constants.SCHEDULE_UPLOAD_PATH_USER').$image_adhar);
                    }

                    $image_pen = isset($schedule->image_pen)?$schedule->image_pen:'';
                    if (isset($image_pen) && $image_pen!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_USER').$image_pen)) {
                        \Storage::delete(config('constants.SCHEDULE_UPLOAD_PATH_USER').$image_pen);
                    }
                    
                    $image_photo = isset($schedule->image_photo)?$schedule->image_photo:'';
                    if (isset($image_photo) && $image_photo!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_USER').$image_photo)) {
                        \Storage::delete(config('constants.SCHEDULE_UPLOAD_PATH_USER').$image_photo);
                    }
                    $schedule->delete();
            }
        }
        $user->forceDelete();
    }
    session()->flash('danger',__('global.messages.delete'));
    return redirect()->back();
    }

}
