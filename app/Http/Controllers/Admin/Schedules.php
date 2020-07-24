<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\User;
use App\Schedule;
use App\Holiday;
use App\Time;
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
                return isset($schedule->user->email)?$schedule->user->email:'';
            })
            ->addColumn('user_mobile', function($schedule){
                return isset($schedule->user->mobile_number)?$schedule->user->mobile_number:'';
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
                
                      $action = '<a href="'.route('admin.schedules.show',[$schedule->id]).'" class="btn btn-warning btn-circle btn-sm"><i class="fa fa-eye"></i></a> ';

                      if($schedule->status == config('constants.PENDING')){
                        $action .='<a href="'.route('admin.schedules.edit',[$schedule->id]).'" class="btn btn-primary btn-circle btn-sm"><i class="fas fa-edit"></i></a> ';
                      }
                    
                      $action .= Form::open(array(
                                  'style' => 'display: inline-block;',
                                  'method' => 'DELETE',
                                   'onsubmit'=>"return confirm('Do you really want to delete?')",
                                  'route' => ['admin.schedules.destroy', $schedule->id])).
                      ' <button type="submit" class="btn btn-danger btn-circle btn-sm"><i class="fas fa-trash"></i></button>'.
                      Form::close();

                return $action;
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

        $holidays=Holiday::where(['is_active'=>TRUE])->pluck('date')->toArray();
        $holiday='';
        if(!empty($holidays)){
            $date=implode(' ', $holidays);
            $holiday= json_encode(str_replace(' ', ',', $date));
        }
        return view('admin.schedule.form', compact('users','sales','holiday'));
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

            $user_id=isset($request->user_id)?$request->user_id:0;
            $sale_id=isset($request->sale_id)?$request->sale_id:0;
            $time=isset($request->time)?$request->time:'';
            
            $time=date('H:i:00',strtotime($time));
            $newdate=$date.' '.$time;

            $availabiletime=$this->saleAvailability($newdate,$sale_id,$time);
            if($availabiletime=='true'){
              $scheduleArray=array('status'=>config('constants.PENDING'),'sale_id'=>$sale_id,'user_id'=>$user_id,'datetime'=>$newdate);
              Schedule::create($scheduleArray);
              $user = User::find($user_id);
              if ($user) {
                $code = rand(100000,999999);
                $user->password = Hash::make($code);
                $user->update();
                //send SMS through buzzify 
                $SMScode = getShortURL();
                $SMSlink = route('home',['url'=>$SMScode]);
                sendSMS($user->mobile_number,$code,$SMSlink);

                $verifyLink = route('verify',['id'=>Crypt::encryptString($user->id)]);
                $user->shorturls()->create(['code'=>$SMScode,'link'=>$verifyLink]);
              }
              $request->session()->flash('success',__('global.messages.add'));
            }else{
              $request->session()->flash('danger',__('Sale Time Not Availabile.'));
            }
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

        $datetime=isset($schedule->datetime)?$schedule->datetime:'';
        return view('admin.schedule.form', compact('schedule','users','sales','datetime'));
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

            $user_id=isset($request->user_id)?$request->user_id:0;
            $sale_id=isset($request->sale_id)?$request->sale_id:0;
            $time=isset($request->time)?$request->time:'';
            
            $time=date('H:i:00',strtotime($time));
            $newdate=$date.' '.$time;

            $selected_date=isset($schedule->datetime)?$schedule->datetime:'';
           
            $availabiletime=$this->saleAvailability($newdate,$sale_id,$time,$selected_date);
            if($availabiletime=='true'){
              $scheduleArray=array('status'=>config('constants.PENDING'),'sale_id'=>$sale_id,'user_id'=>$user_id,'datetime'=>$newdate);
               $schedule->update($scheduleArray);

               if ($user) {
                  $code = rand(100000,999999);
                  $user->password = Hash::make($code);
                  $user->update();
                  //send SMS through buzzify 
                  $SMScode = getShortURL();
                  $SMSlink = route('home',['url'=>$SMScode]);
                  sendSMS($user->mobile_number,$code,$SMSlink);

                  $verifyLink = route('verify',['id'=>Crypt::encryptString($user->id)]);
                  $user->shorturls()->create(['code'=>$SMScode,'link'=>$verifyLink]);
                }

               $request->session()->flash('success',__('global.messages.update'));
            }else{
              $request->session()->flash('danger',__('Sale Time Not Availabile.'));
            }
            return redirect()->route('admin.schedules.index');
        }else{
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

    /**
     * Show the application getstatetocity.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getScheduleTimes(Request $request){
        $date=isset($request->date)?$request->date:'';
        $selected_date=isset($request->selected_date)?$request->selected_date:'';
        $selected_time='';
        if($selected_date!=''){
          $selected_time=date('h:i A', strtotime($selected_date));
        }
       
        $date=date('Y-m-d',strtotime($date));
        $sale_id=isset($request->sale_id)?$request->sale_id:0;
        if(intval($sale_id) > 0 && $date!=''){
            $sale=User::where('id',$sale_id)->first()->toArray();
            $time_id=isset($sale['time_id'])?$sale['time_id']:'';

            $startTime =Time::where('id',$time_id)->where('is_active',TRUE)->min('start_time');
            $start_time=Time::where('id',$time_id)->where('is_active',TRUE)->min('start_time');
            $end_time=Time::where('id',$time_id)->where('is_active',TRUE)->max('end_time');
            if (isset($request->date) && strtotime(date('Y-m-d', strtotime($request->date)))==strtotime(date('Y-m-d'))) {
              $startTime=date('H:i:s');
            }

            if($start_time!='' && $end_time!=''){
              $opentime = strtotime($start_time);
              $closetime = strtotime($end_time);
              $html='';
              while($opentime < $closetime){
                  $time=date('H:i:00', $opentime);
                  $newdate=$date.' '.$time;
                      $times=$this->saleAvailability($newdate,$sale_id,$time,$selected_date);
                      $select='';
                      if($times=='true' && $opentime >= strtotime($startTime)){
                          if($selected_time==date('h:i A', $opentime)){
                            $select='selected';
                          }
                          $html.= '<option '.$select.' value="'.date('h:i A', $opentime) .'">' . date('h:i A', $opentime) . '</option>';
                        
                      }
                  $opentime = strtotime('+15 minutes', $opentime);
              }
              return response()->json($html);

            }
        
        }
    }


    /**
     * Show the application getstatetocity.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    function saleAvailability($date=null,$sale_id=null,$time=null,$selected_date=''){
        $holidays=Holiday::where('date', '=',$date)->where('is_active',true)->first();
        if(isset($holidays)){
            return 'false ';
        }else{
            $user=User::where('id',$sale_id)->first();
            $time_id=isset($user->time_id)?$user->time_id:0;
            $breack_time = date("H:i:s",strtotime('+1 minutes', strtotime($time)));
            //$times=Time::where('id',$time_id)->where('is_active',true)->whereRaw("('$time' BETWEEN start_time AND  end_time)")->whereRaw("('$breack_time' NOT BETWEEN break_start_time AND  break_end_time)")->first();
            $times=Time::where('id',$time_id)->whereRaw("('$time' BETWEEN start_time AND  end_time)")->whereRaw("CASE WHEN IFNULL(break_start_time,'')!='' THEN ('$breack_time' NOT BETWEEN break_start_time AND break_end_time) ELSE 1 END")->first();
            if(isset($times)){
                $records=Schedule::where('datetime', '=',$date)->where('sale_id',$sale_id)->first();
                if(isset($records)){
                    if($selected_date!=''){
                        if($date==$selected_date){
                          return 'true';
                        }
                    }
                    return 'false';
                }else{
                    return 'true';
                }
            }else{
                return 'false ';
            }
        }
    }


}
