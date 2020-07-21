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
                     return '<a href="javascript:void(0)" class="pop"><img width="100px"  height="100px" src="'.\Storage::url(config('constants.SCHEDULE_UPLOAD_PATH_USER').$schedule->image_pen).'" /></a>';
                }
                return '';
            })

            ->editColumn('image_photo', function ($schedule) {
                if (isset($schedule->image_photo) && $schedule->image_photo!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_USER').$schedule->image_photo)) {
                     return '<a href="javascript:void(0)" class="pop"><img width="100px"  height="100px" src="'.\Storage::url(config('constants.SCHEDULE_UPLOAD_PATH_USER').$schedule->image_photo).'" /></a>';
                }
                return '';
            })
            ->editColumn('ss01', function ($schedule) {
                if (isset($schedule->ss01) && $schedule->ss01!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$schedule->ss01)) {
                     return '<a href="javascript:void(0)" class="pop"><img width="100px" height="100px" src="'.\Storage::url(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$schedule->ss01).'" /></a>';
                }
                return '';
            })
            ->editColumn('ss02', function ($schedule) {
                if (isset($schedule->ss02) && $schedule->ss02!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$schedule->ss02)) {
                     return '<a href="javascript:void(0)" class="pop"><img width="100px" height="100px" src="'.\Storage::url(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$schedule->ss02).'" /></a>';
                }
                return '';
            })
            ->editColumn('ss03', function ($schedule) {
                if (isset($schedule->ss03) && $schedule->ss03!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$schedule->ss03)) {
                     return '<a href="javascript:void(0)" class="pop"><img width="100px" height="100px" src="'.\Storage::url(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$schedule->ss03).'" /></a>';
                }
                return '';
            })
            ->editColumn('admin_by_status', function ($schedule) {
                  $html='';
                    if($schedule->admin_by_status==''){
                        $html.='<a href="'.route('admin.kyc.declined',[$schedule->id]).'" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></a> <a href="'.route('admin.kyc.accepted',[$schedule->id]).'" class="btn btn-success btn-circle btn-sm"><i class="fa fa-check"></i></a>';

                    }else{
                        if($schedule->admin_by_status=='accepted'){
                            $html.='<span class="badge badge-success">Accepted</span>';
                        }else{
                             $html.='<span class="badge badge-danger">Rejected</span>';
                        }
                       
                    }
                    return  $html;
            })
            ->addColumn('action', function ($schedule) {
                   // edit
                    return '<a href="'.route('admin.kyc.show',[$schedule->id]).'" class="btn btn-warning btn-circle btn-sm"><i class="fa fa-eye"></i></a>';
            })
            
            ->rawColumns(['admin_by_status','image_pen','action','image_photo','ss01','ss02','ss03'])
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
    public function declined(Request $request, $schedule_id){
      $schedule = Schedule::where('id',$schedule_id)->first();
      if(isset($schedule)){
         $schedule->update(['admin_by_status'=>'rejected']);
         $request->session()->flash('success',__('Kyc Rejected Successfully.'));
      }else{
         $request->session()->flash('success',__('Kyc Rejected Faild.'));
      }
      return redirect()->route('admin.kyc.index');
     
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function accepted(Request $request,$schedule_id){
      $schedule = Schedule::where('id',$schedule_id)->first();
      if(isset($schedule)){
         $schedule->update(['admin_by_status'=>'accepted']);
         $request->session()->flash('success',__('Kyc Accepted Successfully.'));
      }else{
         $request->session()->flash('success',__('Kyc Accepted Faild.'));
      }
      return redirect()->route('admin.kyc.index');
     
    }

    

      /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function twilioVideo($twilio_room_id=null){
        $sid    = config('constants.TWILIO_ACCOUNT_SID'); //"AC9c4946e7297ef20525589bab03294be4";
        $token  = config('constants.TWILIO_API_TOKEN'); //"1794a3ade2ba50c6ec30ddf175038ab1";
        $twilio = new Client($sid, $token);

        // $room = $twilio->video->v1->rooms($twilio_room_id)
        //                           ->fetch();
        //                           echo "<pre>";
        //                           print_r($room);die;

        // $recordings = $twilio->video->v1->recordings
        // ->read(["groupingSid" => [$twilio_room_id],"type"=>'audio'],2
        // );

        $recordings = $twilio->video->v1->rooms($twilio_room_id)
                                ->recordings
                                ->read([], 20);

        $video = $audio = FALSE;
        $recordings_id=$mediaLocation=[];
        foreach ($recordings as $record) {
            if($record->type=='video' && $video==FALSE){
                $video = TRUE;
                $recordings_id['video']=$record->sid;
            }
            if($record->type=='audio' && $audio==FALSE){
                $audio = TRUE;
                $recordings_id['audio']=$record->sid;
            }
        }
        if(!empty($recordings_id)){
            foreach ($recordings_id as $key => $recording_id) {
                $uri = "https://video.twilio.com/v1/Recordings/$recording_id/Media";
                $response = $twilio->request("GET", $uri);
                $mediaLocation[$key] = $response->getContent()["redirect_to"];
            }
            return $mediaLocation;
        }else{
             return $recordings_id;
        }
    }
}
