<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Notifications\OTPVerification;
use Illuminate\Support\Facades\Crypt;
use App\User;
use App\Country;
use App\State;
use App\City;
use App\Time;
use App\Holiday;
use App\Schedule;
use Validator;
use Auth;

class HomeController extends Controller
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
    public function index(){
        $state = State::where(['is_active'=>TRUE])->pluck('title', 'id');
        $city=City::where(['is_active'=>TRUE])->pluck('title', 'id');
        $holidays=Holiday::where(['is_active'=>TRUE])->pluck('date')->toArray();
        $holiday='';
        if(!empty($holidays)){
            $date=implode(' ', $holidays);
            $holiday= json_encode(str_replace(' ', ',', $date));
        }
        return view('auth.register',compact('state','city','holiday'));
    }
    
       /**
     * Show the application register.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function register(Request $request){
        $rules = [
            'name'       => 'required', 
            'mobile_number'     => 'required|unique:'.with(new User)->getTable().',mobile_number',
            'email'      => 'required|email|unique:'.with(new User)->getTable().',email',
            'state_id'        => 'required',
            'city_id'        => 'required',
            'address1'      =>'required',
            'address2'      =>'required',
            'date'        => 'required',
            'time'        => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->passes()){
            $data = $request->all();
            $scheduledata=explode("-",$data['time']);
            $sale_id=isset($scheduledata[0])?$scheduledata[0]:0;

            $schedule_time=isset($scheduledata[1])?$scheduledata[1]:'';
            $schedule_time=date('H:i:00',strtotime($schedule_time));

            $date=isset($request->date)?$request->date:date(config('constants.MYSQL_STORE_DATE_FORMAT'));
			      $date=date(config('constants.MYSQL_STORE_DATE_FORMAT'),strtotime($date));
            $newdate=$date.' '.$schedule_time;
            
            if(intval($sale_id > 0) && $schedule_time!=''){
               $availabiletime=saleAvailability($newdate,$sale_id,$schedule_time);
                if($availabiletime == 'true'){
                    $code = rand(100000,999999);
               	    $data['password'] = Hash::make($code);
                    $user = User::create($data);

                    if ($user)
                        $user->notify(
                            new OTPVerification($code,$request->mobile_number)
                        );

                    //send SMS through buzzify 

                    $SMScode = getShortURL();
                    $SMSlink = route('home',['url'=>$SMScode]);
                    sendSMS($request->mobile_number,$code,$SMSlink);

                    $verifyLink = route('verify',['id'=>Crypt::encryptString($user->id)]);
                    $user->shorturls()->create(['code'=>$SMScode,'link'=>$verifyLink]);

                    //assign user roles
               	    $user->assignRole(config('constants.ROLE_TYPE_USER_ID'));
		            if(isset($user->id)){
		            	$scheduleArray=array('status'=>config('constants.PENDING'),'sale_id'=>$sale_id,'user_id'=>$user->id,'datetime'=>$newdate);
		                Schedule::create($scheduleArray);
		            }
		            $request->session()->flash('success','We have sent a OTP on your mobile number');
		            return redirect()->back();
                }else{
 				 	$request->session()->flash('danger',__('Sale Time Not Availabile.'));
 				  	return redirect()->back()->withInput();
                }
            }else{
            	$request->session()->flash('danger',__('User Registration Faild.'));
            	return redirect()->back();
            }
        }else{
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }
   
/**
* Show the application getstatetocity.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getstatetocity(Request $request){
        $city = City::where('state_id',$request->state_id)->where(['is_active'=>TRUE])->pluck('title', 'id'); 
        return response()->json($city);
    }

/**
     * Show the application getstatetocity.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getScheduleTimes(Request $request){
        $startTime =Time::where('is_active',TRUE)->min('start_time');
      	$start_time=Time::where('is_active',TRUE)->min('start_time');
        $end_time=Time::where('is_active',TRUE)->max('end_time');
        if (isset($request->date) && strtotime(date('Y-m-d', strtotime($request->date))==strtotime(date('Y-m-d'))) {
            $startTime=date('H:i:s');
        }
        $date=isset($request->date)?$request->date:date('Y-m-d');
        $date=date('Y-m-d',strtotime($date));
        $opentime = strtotime($start_time);
        $closetime = strtotime($end_time);
        $html='';
        $sales=User::with('roles')
          ->whereHas('roles', function($query){
            $query->where('id',config('constants.ROLE_TYPE_SALES_ID'));
        })->where('is_active',TRUE)->get()->toArray();
        if(count($sales) > 0){
            while($opentime < $closetime){
                if (strtotime($startTime) > $opentime) {
                    $time=date('H:i:00', $opentime);
                    $newdate=$date.' '.$time;
                    foreach ($sales as $key => $sale) {
                        //$time_id=isset($sale['time_id'])?$sale['time_id']:0;
                        $sale_id=isset($sale['id'])?$sale['id']:0;
                        $times=saleAvailability($newdate,$sale_id,$time);
                        if($times=='true'){
                            $html.= '<option value="'.$sale_id.'-'. date('h:i A', $opentime) .'">' . date('h:i A', $opentime) . '</option>';
                            break;
                        }
                    }
                }
                $opentime = strtotime('+15 minutes', $opentime);
            }
        }
        return response()->json($html);
    }

	public function saleAvailability1($date=null,$time_id=null,$time=null){
    	$times=Time::where('id',$time_id)->whereRaw("('$time' BETWEEN start_time AND  end_time)")->first();
		if(isset($times)){
		    $breacktime=Time::where('id',$time_id)->whereRaw("('$time' BETWEEN break_start_time AND  break_end_time)")->first();
			if(isset($breacktime)){
				if($breacktime->break_end_time == $time){
					$records=Schedule::where('datetime', '=',$date)->first();
			        if(isset($records)){
			            return 'false';
			        }else{
			            return 'true';
			        }
				}else{
					return 'false ';
				}
			}else{
				$records=Schedule::where('datetime', '=',$date)->first();
		        if(isset($records)){
		            return 'false';
		        }else{
		            return 'true';
		        }
			}
		}else{
			return 'false ';
	    }
	}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function verify($user_id){
        $user_id = Crypt::decryptString($user_id);
        $user = User::findOrFail($user_id);
        return view('auth.verifyOTP',compact('user'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function verifyOTP(Request $request){
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return redirect('user/schedules');
        }else{
            $request->session()->flash('danger','You have entered invalid OTP');
            return redirect()->back()->withInput();
        }
    }    

}
