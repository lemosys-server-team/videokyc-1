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
        $user = User::with('schedules')->whereHas('schedules',function($query){
          $query->where(\DB::raw('DATE_FORMAT(datetime, "%Y-%m-%d h:i:s")'),'>=', date('Y-m-d h:i:s'));
          $query->where('status', 'pending');
        })->find(Auth::id());
        return view('schedule/index',compact('user'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function call($schedule_id){
        $schedule = Schedule::findOrFail($schedule_id);
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
       return view('schedule/call',compact('chatroom','token'));
    }
}
