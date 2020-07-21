<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\User;
use App\Setting;
use App\Holiday;
use App\Time;
use App\Schedule;

/**
* Method Name : setSetting 
* Parameter : $option_name,$option_value
* This is using for set setting option_value 
*/

function setSetting($option_name,$option_value){
    $setting=Setting::where(array('option_name'=>$option_name))->first();
    if ($setting!=NULL) {
        $setting->option_value = $option_value;
        $setting->save();
    }else{
        $setting = new Setting;
        $setting->option_name = $option_name;
        $setting->option_value = $option_value;
        $setting->save();
    }
    return true;
} 

/**
* Method Name : getSetting 
* Parameter : $option_name
* This is using for return setting option_value 
*/

function getSetting($option_name){
    if (isset($option_name) && $option_name!='') {        
        $setting = Cache::rememberForever('app_settings', function () {
            return Setting::get();
        });

        $setting=$setting->where('option_name', $option_name)->first();
        return isset($setting->option_value)?$setting->option_value:'';
    }
    return '';
}

function saleAvailability($date=null,$sale_id=null,$time=null){
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
                return 'false';
            }else{
                return 'true';
            }
        }else{
            return 'false ';
        }
    }
}

/**
* Method Name : getShortURL 
* This is using for return short URL
*/

function getShortURL(){
    $code = 'V'.Str::random(7);

    $validator = Validator::make(
        ['code' => $code],
        ['code' => 'required|unique:shorturls']
    );

    if ($validator->fails()) {
        return getShortURL();
    }
    return $code;
}

/**
* Method Name : getShortURL 
* This is using for return short URL
*/

function sendSMS($mobile_number,$code,$SMSlink){
    $message = "Welcome to VideoKYC! Click on link ".$SMSlink." to verify. Your OTP is ".$code." Thank You";  
    $url ="https://buzzify.in/V2/http-api.php?apikey=lB62uhTi7qPXXX6N&senderid=INSPCN&number=".$mobile_number."&message=".$message."&format=json";  
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);

    return true;
}

?>