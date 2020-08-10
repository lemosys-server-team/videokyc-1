<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;
use Twilio\Rest\Client;
use App\User;
use App\Time;
use App\Schedule;
use Auth;

class SchedulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $schedules = Schedule::where('status', config('constants.PENDING'))->where(\DB::raw('DATE_FORMAT(datetime, "%Y-%m-%d")'),'=', date('Y-m-d'))->where(\DB::raw('DATE_FORMAT(datetime, "%H:%i:%s")'),'>=', date('H:i:s'))->where('user_id',Auth::id())->get();
        return view('schedule/index',compact('schedules'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function call($schedule_id){
        $schedule = Schedule::findOrFail($schedule_id);
        if (isset($schedule->status) && $schedule->status == config('constants.COMPLETED')) {
            return redirect()->route('schedules.index')
                        ->with('error','You have already completed this schedule.');
        }
        // if (isset($schedule->datetime) && $schedule->datetime > date('Y-m-d h:i:s')) {
        //     return redirect()->route('schedules.index')
        //                 ->with('error',"You don't have schedule for this time.");
        // }
        // Substitute your Twilio Account SID and API Key details
        $accountSid = config('constants.TWILIO_ACCOUNT_SID'); 
        $apiKeySid = config('constants.TWILIO_API_KEY_SID'); 
        $apiKeySecret = config('constants.TWILIO_API_KEY_SECRET');

        $identity = uniqid();
       
        // Create an Access Token
        $token = new AccessToken(
            $accountSid,
            $apiKeySid,
            $apiKeySecret,
            3600,
            $identity
        );
        $user_id=isset($schedule->user_id)?$schedule->user_id:'';
        $sale_id=isset($schedule->sale_id)?$schedule->sale_id:'';
        $chatroom=$sale_id.'videokycroom'.$user_id;

        //Grant access to Video
        $grant = new VideoGrant();
        $grant->setRoom($chatroom);
        $token->addGrant($grant);
        // Serialize the token as a JWT
        $token= $token->toJWT();
        return view('schedule/call',compact('chatroom','token','schedule_id'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function thankyou($schedule_id){
        $schedule = Schedule::findOrFail($schedule_id);
        $schedule->status= config('constants.COMPLETED');
        $schedule->update();
        return view('schedule/thankyou');
    }
}
