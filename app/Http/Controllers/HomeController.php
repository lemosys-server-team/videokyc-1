<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Country;
use App\State;
use App\City;
use App\Holiday;
use App\Schedule;
use Validator;

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
            'email'      => 'required',
            // 'state_id'        => 'required',
            // 'city_id'        => 'required',
            // 'address1'      =>'required',
            // 'address2'      =>'required',
            // 'date'        => 'required',
            // 'time'        => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $request->all();
            $data['password'] = Hash::make(123456);
            $user = User::create($data);
            //assign user roles
            $user->assignRole(config('constants.ROLE_TYPE_USER_ID'));
            $request->session()->flash('success',__('Details submitted successfully'));
            return redirect()->back();
        }else {
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

  // $hours  = array("01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20");
        //     $mins = array("00","15","30","45");
        //     $select='';
        //     foreach ($hours as $hour) {
        //         foreach($mins as $min) {
        //             $select .= '<option value="'.$hour.':'.$min.'">'.$hour.':'.$min.
        //                        '</option>';
        //             }
        //     }
/**
     * Show the application getstatetocity.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getScheduleTimes(Request $request){
      
        $date=isset($request->date)?$request->date:date('Y-m-d');
        $date=date('Y-m-d',strtotime($date));
        $opentime = strtotime('09:00');
        $closetime = strtotime('18:15');
        $html='';
        $sales=User::with('roles')
          ->whereHas('roles', function($query){
            $query->where('id',config('constants.ROLE_TYPE_SALES_ID'));
        })->get()->toArray();
        if(count($sales) > 0){
            while($opentime < $closetime){
                $time=date('H:i:00', $opentime);
                $newdate=$date.' '.$time;
                foreach ($sales as $key => $sale) {
                    $time_id=isset($sale['time_id'])?$sale['time_id']:0;
                    $sale_id=isset($sale['id'])?$sale['id']:0;
                    $times=$this->saleAvailability($newdate);
                    if($times=='true'){
                        $html.= '<option value="'. date('h:i A', $opentime) .'">' . date('h:i A', $opentime) . '</option>';
                        break;
                    }
                }
                $opentime = strtotime('+15 minutes', $opentime);
            }
        }
        return response()->json($html);
    }
    
    public function saleAvailability($date=null){
        $records=Schedule::where('datetime', '=',$date)->first();
        if(isset($records)){
            return 'false';
        }else{
            return 'true';
        }
    }

}
