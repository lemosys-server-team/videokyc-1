<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Time;
use Validator;
use Auth;
use DataTables;
use Config;
use Form;
use DB;

class Times extends Controller
{

/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){  
    	return view('admin/time/index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTimes(Request $request){
        
        $times=Time::select([\DB::raw(with(new Time)->getTable().'.*')])->groupBy('id');
        
        return DataTables::of($times)
            ->addColumn('break_time', function($time){

            	$break_start_time=isset($time->break_start_time)?$time->break_start_time:'';
            	$break_end_time=isset($time->break_end_time)?$time->break_end_time:'';
                
                if($break_end_time!='' || $break_start_time!=''){
                	$break_start_time=date(config('constants.TIME_FORMAT'), strtotime($break_start_time));
                	$break_end_time=date(config('constants.TIME_FORMAT'), strtotime($break_end_time));
                    return $break_start_time.' - '. $break_end_time;
                }
                return 'NA';

            })
            ->editColumn('start_time', function($time){
                 return date(config('constants.TIME_FORMAT'), strtotime($time->start_time));
            })
            ->editColumn('end_time', function($time){
                 return date(config('constants.TIME_FORMAT'), strtotime($time->end_time));
            })
            ->editColumn('is_active', function ($time) {
                if($time->is_active == TRUE )
                {
                    return "<a href='".route('admin.times.status',$time->id)."'><span class='badge badge-success'>Active</span></a>";
                }else{
                    return "<a href='".route('admin.times.status',$time->id)."'><span class='badge badge-danger'>Inactive</span></a>";
                }
            })
            ->addColumn('action', function ($time) {
            	/*'<a href="'.route('admin.time.show',[$time->id]).'" class="btn btn-success btn-circle btn-sm"><i class="fa fa-eye"></i></a>'*/
            	  // Delete
                     
                return '<a href="'.route('admin.times.edit',[$time->id]).'" class="btn btn-primary btn-circle btn-sm"><i class="fas fa-edit"></i></a> '.

                Form::open(array(
                                  'style' => 'display: inline-block;',
                                  'method' => 'DELETE',
                                   'onsubmit'=>"return confirm('Do you really want to delete?')",
                                  'route' => ['admin.times.destroy', $time->id])).
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
       return view('admin.time.form');
    }
   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $rules = [
            'title'  => 'required|unique:'.with(new Time)->getTable().',title',
            'start_time'  => 'required', 
            'end_time'     => 'required', 
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->passes()) {
            
            $data = $request->all();
          
            $start_time=isset($request->start_time)?$request->start_time:'';
            $data['start_time']=date(config('constants.MYSQL_STORE_TIME_FORMAT'),strtotime($start_time));

            $end_time=isset($request->end_time)?$request->end_time:'';
            $data['end_time']=date(config('constants.MYSQL_STORE_TIME_FORMAT'),strtotime($end_time));

            $break_start_time=isset($request->break_start_time)?$request->break_start_time:'';
            if($break_start_time!=''){
            	$data['break_start_time']=date(config('constants.MYSQL_STORE_TIME_FORMAT'),strtotime($break_start_time));
            }
            
            $break_end_time=isset($request->break_end_time)?$request->break_end_time:'';
            if($break_end_time!=''){
            	 $data['break_end_time']=date(config('constants.MYSQL_STORE_TIME_FORMAT'),strtotime($break_end_time));
            }
           
            Time::create($data);
            $request->session()->flash('success',__('global.messages.add'));
            return redirect()->route('admin.times.index');
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
    public function show($time_id){
    	$time = Time::with('user','sale')->where('id',$time_id)->first();
    	return view('admin.schedule.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($time_id){
    	$time = Time::findOrFail($time_id);
        return view('admin.time.form', compact('time'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $time_id){
        $time = Time::findOrFail($time_id);
        $rules = [
            'title'     => 'required|unique:'.with(new Time)->getTable().',title,'.$time->getKey(),
            'start_time'  => 'required', 
            'end_time'     => 'required', 
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $request->all();
            
            $start_time=isset($request->start_time)?$request->start_time:'';
            $data['start_time']=date(config('constants.MYSQL_STORE_TIME_FORMAT'),strtotime($start_time));

            $end_time=isset($request->end_time)?$request->end_time:'';
            $data['end_time']=date(config('constants.MYSQL_STORE_TIME_FORMAT'),strtotime($end_time));

            $break_start_time=isset($request->break_start_time)?$request->break_start_time:'';
            if($break_start_time!=''){
            	$data['break_start_time']=date(config('constants.MYSQL_STORE_TIME_FORMAT'),strtotime($break_start_time));
            }
            
            $break_end_time=isset($request->break_end_time)?$request->break_end_time:'';
            if($break_end_time!=''){
            	 $data['break_end_time']=date(config('constants.MYSQL_STORE_TIME_FORMAT'),strtotime($break_end_time));
            }
            $time->update($data);
            
            $request->session()->flash('success',__('global.messages.update'));
            return redirect()->route('admin.times.index');
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
    public function destroy($time_id){
      $time = Time::findOrFail($time_id);
      $time->delete();
      session()->flash('danger',__('global.messages.delete'));
      return redirect()->route('admin.time.index');
    }
}
