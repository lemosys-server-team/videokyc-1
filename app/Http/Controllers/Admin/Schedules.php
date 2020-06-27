<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Schedule;
use Validator;
use Auth;
use DataTables;
use Config;
use Form;
use DB;

class Schedules extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){  
    	$sales=User::with('roles')
          ->whereHas('roles', function($query){
            $query->where('id',config('constants.ROLE_TYPE_SALES_ID'));
          })
          ->where('is_active',true)
          ->pluck('name','id');
        return view('admin/schedule/index',compact('sales'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSchedules(Request $request){
        
        $schedules=Schedule::with('user','sale')->select([\DB::raw(with(new Schedule)->getTable().'.*')])->groupBy('id');

        $sale_id = intval($request->input('sales_id'));
        if($sale_id > 0) 
            $schedules->where('sale_id', $sale_id); 
           
        $status = $request->input('status');
        if($status !='') 
            $schedules->where('status', $status); 

        $date = $request->input('date');
        if($date!=''){
           $date = date('Y-m-d', strtotime($date));
           $schedules->whereDate('datetime', '=', $date);
        }
        
        return DataTables::of($schedules)
       
            ->editColumn('datetime', function($schedule){
                return date(config('constants.DATE_FORMAT'), strtotime($schedule->datetime));
            })            
            ->editColumn('status', function ($schedule) {
               return ucwords($schedule->status);
            })
            ->addColumn('user_email', function($schedule){
                return isset($schedule->user->email)?$schedule->sale->email:'';
            })
            ->addColumn('user_mobile', function($schedule){
                return isset($schedule->user->mobile_number)?$schedule->sale->mobile_number:'';
            })
            ->addColumn('time', function($schedule){
                 return date(config('constants.TIME_FORMAT'), strtotime($schedule->datetime));
            })
            ->filterColumn('user_email', function ($query, $keyword) {
                $keyword = strtolower($keyword);
                  $query->whereHas('user', function($query) use ($keyword){
                  	 $query->whereRaw("email like ?", ["%$keyword%"]);
		        });
            })
            ->filterColumn('user_mobile', function ($query, $keyword) {
                $keyword = strtolower($keyword);
                  $query->whereHas('user', function($query) use ($keyword){
                  	 $query->whereRaw("mobile_number like ?", ["%$keyword%"]);
		        });
            })
            ->filterColumn('time', function ($query, $keyword) {
                $keyword = strtolower($keyword);
                $query->whereRaw("LOWER(DATE_FORMAT(datetime,'".config('constants.MYSQL_STORE_TIME_FORMAT')."')) like ?", ["%$keyword%"]);
            })
            ->addColumn('action', function ($schedule) {
                return
                    // edit
                    '<a href="'.route('admin.schedules.show',[$schedule->id]).'" class="btn btn-success btn-circle btn-sm"><i class="fa fa-eye"></i></a> <a href="'.route('admin.schedules.edit',[$schedule->id]).'" class="btn btn-primary btn-circle btn-sm"><i class="fas fa-edit"></i></a> '.
                    // Delete
                      Form::open(array(
                                  'style' => 'display: inline-block;',
                                  'method' => 'DELETE',
                                   'onsubmit'=>"return confirm('Do you really want to delete?')",
                                  'route' => ['admin.schedules.destroy', $schedule->id])).
                      ' <button type="submit" class="btn btn-danger btn-circle btn-sm"><i class="fas fa-trash"></i></button>'.
                      Form::close();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $users=User::with('roles')
          ->whereHas('roles', function($query){
            $query->where('id',config('constants.ROLE_TYPE_USER_ID'));
          })
          ->where('is_active',true)
          ->pluck('name','id');

        $sales=User::with('roles')
          ->whereHas('roles', function($query){
            $query->where('id',config('constants.ROLE_TYPE_SALES_ID'));
          })
          ->where('is_active',true)
          ->pluck('name','id');
        return view('admin.schedule.form', compact('users','sales'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $rules = [
            'sale_id'  => 'required', 
            'user_id'  => 'required', 
            'date'     => 'required', 
            'time'     => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->passes()) {
            
            $data = $request->all();
            $date=isset($request->date)?$request->date:date(config('constants.MYSQL_STORE_DATE_FORMAT'));
            $date=date(config('constants.MYSQL_STORE_DATE_FORMAT'),strtotime($date));
            $time=isset($request->time)?$request->time:date("H:i:00");
            $data['datetime']=$date." ".$time;  
            $data['status']=config('constants.PENDING');
            Schedule::create($data);
            $request->session()->flash('success',__('global.messages.add'));
            return redirect()->route('admin.schedules.index');
        }else{
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($schedule_id){
    	$schedule = Schedule::with('user','sale')->where('id',$schedule_id)->first();
    	return view('admin.schedule.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($schedule_id){
    	$schedule = Schedule::findOrFail($schedule_id);
        $users=User::with('roles')
          ->whereHas('roles', function($query){
            $query->where('id',config('constants.ROLE_TYPE_USER_ID'));
          })
          ->where('is_active',true)
          ->pluck('name','id');

        $sales=User::with('roles')
          ->whereHas('roles', function($query){
            $query->where('id',config('constants.ROLE_TYPE_SALES_ID'));
          })
          ->where('is_active',true)
          ->pluck('name','id');
        return view('admin.schedule.form', compact('schedule','users','sales'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $schedule_id){
        $schedule = Schedule::findOrFail($schedule_id);
        $rules = [
            'sale_id'  => 'required', 
            'user_id'  => 'required', 
            'date'     => 'required', 
            'time'     => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $request->all();
            
            $date=isset($request->date)?$request->date:date(config('constants.MYSQL_STORE_DATE_FORMAT'));
            $date=date(config('constants.MYSQL_STORE_DATE_FORMAT'),strtotime($date));
            $time=isset($request->time)?$request->time:date("H:i:00");
            $data['datetime']=$date." ".$time;  
            $data['status']=$schedule->status;
            $schedule->update($data);
            
            $request->session()->flash('success',__('global.messages.update'));
            return redirect()->route('admin.schedules.index');
        }else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($schedule_id){
      $schedule = Schedule::findOrFail($schedule_id);
      $schedule->delete();
      session()->flash('danger',__('global.messages.delete'));
      return redirect()->route('admin.schedules.index');
    }
}
