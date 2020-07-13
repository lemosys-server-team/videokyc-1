<?php

use Illuminate\Support\Facades\Cache;

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
    $holidays=Holiday::where('date', '=',$date)->first();
    if(isset($holidays)){
        return 'false ';
    }else{
        $user=User::where('id',$sale_id)->first();
        $time_id=isset($user->time_id)?$user->time_id:0;
        $breack_time = date("H:i:s",strtotime('+1 minutes', strtotime($time)));
        $times=Time::where('id',$time_id)->whereRaw("('$time' BETWEEN start_time AND  end_time)")->whereRaw("('$breack_time' NOT BETWEEN break_start_time AND  break_end_time)")->first();
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

?>