<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Schedule;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;
use Twilio\Rest\Client;
use Validator;
use Auth;
use DataTables;
use Config;
use Form;
use DB;


class Kyc extends Controller
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

        $users=User::with('roles')
          ->whereHas('roles', function($query){
            $query->where('id',config('constants.ROLE_TYPE_USER_ID'));
          })
          ->where('is_active',true)
          ->pluck('name','id');
        return view('admin/kyc/index',compact('sales','users'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getkyc(Request $request){
        
        $schedules=Schedule::with('user','sale')->select([\DB::raw(with(new Schedule)->getTable().'.*')])->where('status',config('constants.COMPLETED'))->groupBy('id');

        $sale_id = intval($request->input('sales_id'));
        if($sale_id > 0) 
            $schedules->where('sale_id', $sale_id); 
           
        $user_id = intval($request->input('user_id'));
        if($user_id > 0) 
            $schedules->where('user_id', $user_id); 

        $date = $request->input('date');
        if($date!=''){
           $date = date('Y-m-d', strtotime($date));
           $schedules->whereDate('datetime', '=', $date);
        }
        
        return DataTables::of($schedules)
       
            ->editColumn('datetime', function($schedule){
                return date(config('constants.DATE_FORMAT').' @ '.config('constants.TIME_FORMAT') , strtotime($schedule->datetime));
            })            
            ->editColumn('final_status', function ($schedule) {
               return ucwords(isset($schedule->final_status)?$schedule->final_status:'');
            })
            ->editColumn('image_adhar', function ($schedule) {
               return 'Xml file save in backend';
            })
            ->editColumn('kyc_status', function ($schedule) {
              return ucwords(isset($schedule->kyc_status)?$schedule->kyc_status:'');
            })
            ->editColumn('image_pen', function ($schedule) {
                if (isset($schedule->image_pen) && $schedule->image_pen!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_USER').$schedule->image_pen)) {
                     return '<img width="100px"  height="100px" src="'.\Storage::url(config('constants.SCHEDULE_UPLOAD_PATH_USER').$schedule->image_pen).'" />';
                }
                return '';
            })

            ->editColumn('image_photo', function ($schedule) {
                if (isset($schedule->image_photo) && $schedule->image_photo!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_USER').$schedule->image_photo)) {
                     return '<img width="100px"  height="100px" src="'.\Storage::url(config('constants.SCHEDULE_UPLOAD_PATH_USER').$schedule->image_photo).'" />';
                }
                return '';
            })
            ->editColumn('ss01', function ($schedule) {
                if (isset($schedule->ss01) && $schedule->ss01!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$schedule->ss01)) {
                     return '<img width="100px" height="100px" src="'.\Storage::url(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$schedule->ss01).'" />';
                }
                return '';
            })
            ->editColumn('ss02', function ($schedule) {
                if (isset($schedule->ss02) && $schedule->ss02!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$schedule->ss02)) {
                     return '<img width="100px" height="100px" src="'.\Storage::url(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$schedule->ss02).'" />';
                }
                return '';
            })
            ->editColumn('ss03', function ($schedule) {
                if (isset($schedule->ss03) && $schedule->ss03!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$schedule->ss03)) {
                     return '<img width="100px" height="100px" src="'.\Storage::url(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$schedule->ss03).'" />';
                }
                return '';
            })
            ->addColumn('action', function ($schedule) {
                return
                    // edit
                    '<a href="'.route('admin.kyc.show',[$schedule->id]).'" class="btn btn-success btn-circle btn-sm"><i class="fa fa-eye"></i></a>';
            })
            ->rawColumns(['image_pen','action','image_photo','ss01','ss02','ss03'])
            ->make(true);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($schedule_id){
      $schedule = Schedule::with('user','sale')->where('id',$schedule_id)->first();
      $twilio_room_id=isset($schedule['twilio_room_id'])?$schedule['twilio_room_id']:'';
      $videourl='';
      if($twilio_room_id !=''){
          $videourl=$this->twilioVideo($twilio_room_id);
      }
      return view('admin.kyc.show', compact('schedule','videourl'));
    }

      /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function twilioVideo($twilio_room_id=null){
        $sid    = "AC9c4946e7297ef20525589bab03294be4";
        $token  = "3e25eb6bc857461b68d72d839642fd69";
        $twilio = new Client($sid, $token);

        // $room = $twilio->video->v1->rooms("RM75976f7a4f963c96ca7588b71217f869")
        //                           ->fetch();
        //                           echo "<pre>";
        //                           print_r($room);die;

        $recordings = $twilio->video->v1->recordings
        ->read(["groupingSid" => [$twilio_room_id],
                "type"=>'video'],2
        );
        $recordings_id='';
        foreach ($recordings as $record) {
            if($record->type=='video'){
                $recordings_id=$record->sid;
            }
        }
        if($recordings_id!=''){
            $uri = "https://video.twilio.com/v1/Recordings/$recordings_id/Media";
            $response = $twilio->request("GET", $uri);
            $mediaLocation = $response->getContent()["redirect_to"];
            return $mediaLocation;
        }else{
             return $recordings_id;
        }
    }
}
