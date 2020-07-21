<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;
use Twilio\Rest\Client;
use App\Schedule;

use Validator;

class Webservices extends Controller
{
    /**
     * For store device data
     *
     * @return [json] array
     */
    public function storeDeviceReading(Request $request){
        $status = FALSE;
        $validator = Validator::make($request->all(), [
            'readings' => 'required|json'
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>$status,'message'=>$validator->errors()->first()]);
        }

        $data = $request->all();

        // Store json in a file for data lose
        $readings = json_decode($data['readings']);
        $current_datetime = date('Y-m-d H:i:s');
        $readings->datetime = $current_datetime;
        $fileName = strtotime($current_datetime).'.json';
        Storage::put("readings/$fileName", json_encode($readings));

        $status = TRUE;
        $response['status'] = $status;  
        $response['message'] = "Data saved Successfully.";
        return response()->json($response);
    }
   
   /**
     * delete users notification
     *
     * @return [string] message
    */
    public function getUserBySales(Request $request){

        $rules =   ['sale_id' => 'required'];
        
        $validator = Validator::make($request->all(), $rules);   

        if ($validator->fails()) {
            return response()->json(['status'=>false,'message'=>$validator->errors()->first()]);
        }    
        $data=$request->all();  
        $current_date=date("Y-m-d");
        $user = Schedule::with('user')->where('sale_id',$data['sale_id'])->whereDate('datetime', '=', $current_date)->get();
        if($user->count() > 0){ 
            $response=array('status'=>true,'message'=>'Records.','response'=>$user);
        }else{
            $response=array('status'=>false,'message'=>'Record not found.');
        }
        return response()->json($response);
    }

   /**
     * delete users notification
     *
     * @return [string] message
    */
    public function uploadDocumentByUser(Request $request){

        $rules =   ['schedule_date' => 'required',
                    'user_id' => 'required',
                    'final_status' => 'required',
                    'face_status' => 'required',
                    'image_pen'=>['required',
                              'file',
                              'image'],
                    'image_adhar'=>['required',
                            'file'],
                    'image_photo'=>['required',
                            'file',
                            'image']
                    ];
        
        $validator = Validator::make($request->all(), $rules);   

        if ($validator->fails()) {
            return response()->json(['status'=>false,'message'=>$validator->errors()->first()]);
        }    
        $data=$request->all();  
        $date=date('Y-m-d',strtotime($data['schedule_date']));
        $schedule = Schedule::where('user_id',$data['user_id'])->whereDate('datetime', '=', $date)->first();
        if(isset($schedule)){ 
            if ($request->hasFile('image_pen')){
                $file = $request->file('image_pen');
                $image_pen  = time().$schedule->id. 'image_pen.' . $file->getClientOriginalExtension();
                $file->storeAs(config('constants.SCHEDULE_UPLOAD_PATH_USER'), $image_pen);
               
                $old_image_pen = isset($schedule->image_pen)?$schedule->image_pen:'';
                if (isset($old_image_pen) && $old_image_pen!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_USER').$old_image_pen)) {
                    \Storage::delete(config('constants.SCHEDULE_UPLOAD_PATH_USER').$old_image_pen);
                }
                $data['image_pen'] = $image_pen;
            }

            if ($request->hasFile('image_adhar')){
                $file = $request->file('image_adhar');
                $image_adhar  = time().$schedule->id.'image_adhar.' . $file->getClientOriginalExtension();
                $file->storeAs(config('constants.SCHEDULE_UPLOAD_PATH_USER'), $image_adhar);
               
                $old_image_adhar = isset($schedule->image_adhar)?$schedule->image_adhar:'';
                if (isset($old_image_adhar) && $old_image_adhar!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_USER').$old_image_adhar)) {
                    \Storage::delete(config('constants.SCHEDULE_UPLOAD_PATH_USER').$old_image_adhar);
                }
                $data['image_adhar'] = $image_adhar;
            }

            if ($request->hasFile('image_photo')){
                $file = $request->file('image_photo');
                $image_photo  = time().$schedule->id.'image_photo.' . $file->getClientOriginalExtension();
                $file->storeAs(config('constants.SCHEDULE_UPLOAD_PATH_USER'), $image_photo);
               
                $old_image_photo = isset($schedule->image_photo)?$schedule->image_photo:'';
                if (isset($old_image_photo) && $old_image_photo!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_USER').$old_image_photo)) {
                    \Storage::delete(config('constants.SCHEDULE_UPLOAD_PATH_USER').$old_image_photo);
                }
                $data['image_photo'] = $image_photo;
            }

            $data['status']=config('constants.COMPLETED');
            $data['final_status']= isset($data['final_status'])?$data['final_status']:'';
            $data['kyc_status']= isset($data['face_status'])?$data['face_status']:'';
            $schedule->update($data);

            $response=array('status'=>true,'message'=>'Document uploaded Successfully.');
        }else{
            $response=array('status'=>false,'message'=>'User schedule not found.');
        }
        return response()->json($response);
    }


     /**
     * delete users notification
     /**
     * delete users notification
     *
     * @return [string] message
    */
    public function uploadDocumentBysale(Request $request){

       $rules =   ['schedule_id' => 'required',
                   'room_id'=>'required',    
                   /* 'user_id' => 'required',*/
                    'ss01'=>['required',
                              'file',
                              'image'],
                    'ss02'=>['required',
                            'file',
                            'image'],
                    'ss03'=>['required',
                            'file',
                            'image']

                   ];
        
        $validator = Validator::make($request->all(), $rules);   

        if ($validator->fails()) {
            return response()->json(['status'=>false,'message'=>$validator->errors()->first()]);
        }    
        $data=$request->all();  
        $schedule = Schedule::where('id',$data['schedule_id'])->first();

        if(isset($schedule)){ 

            if ($request->hasFile('ss01')){
                $file = $request->file('ss01');
                $ss01  = time().$schedule->id. 'ss01.' . $file->getClientOriginalExtension();
                $file->storeAs(config('constants.SCHEDULE_UPLOAD_PATH_SALES'), $ss01);
               
                $old_profile_picture = isset($schedule->ss01)?$schedule->ss01:'';
                if (isset($old_ss01) && $old_ss01!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$old_ss01)) {
                    \Storage::delete(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$old_ss01);
                }
                $data['ss01'] = $ss01;
            }

            if ($request->hasFile('ss02')){
                $file = $request->file('ss02');
                $ss02  = time().$schedule->id.'ss02.' . $file->getClientOriginalExtension();
                $file->storeAs(config('constants.SCHEDULE_UPLOAD_PATH_SALES'), $ss02);
               
                $old_profile_picture = isset($schedule->ss02)?$schedule->ss02:'';
                if (isset($old_ss02) && $old_ss02!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$old_ss02)) {
                    \Storage::delete(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$old_ss02);
                }
                $data['ss02'] = $ss02;
            }

            if ($request->hasFile('ss03')){
                $file = $request->file('ss03');
                $ss03  = time().$schedule->id.'ss03.' . $file->getClientOriginalExtension();
                $file->storeAs(config('constants.SCHEDULE_UPLOAD_PATH_SALES'), $ss03);
               
                $old_ss03 = isset($schedule->ss03)?$schedule->ss03:'';
                if (isset($old_ss03) && $old_ss03!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$old_ss03)) {
                    \Storage::delete(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$old_ss03);
                }
                $data['ss03'] = $ss03;
            }
            $data['twilio_room_id'] = isset($data['room_id'])?$data['room_id']:'';
            $data['status']=config('constants.COMPLETED');
            $schedule->update($data);

            $response=array('status'=>true,'message'=>'Document uploaded Successfully.');
        }else{
            $response=array('status'=>false,'message'=>'Document uploaded failed.');
        }
        return response()->json($response);

    }


    /**
     * delete users notification
     *
     * @return [string] message
    */
    public function twilioBySale(Request $request){  
        $rules =  ['schedule_id' => 'required'];
        
        $validator = Validator::make($request->all(), $rules);   

        if ($validator->fails()) {
            return response()->json(['status'=>false,'message'=>$validator->errors()->first()]);
        }    
        $data=$request->all();  
        $schedule = Schedule::where('id',$data['schedule_id'])->first();
        if(isset($schedule)){
            // Substitute your Twilio Account SID and API Key details
            $accountSid = config('constants.TWILIO_ACCOUNT_SID'); //'AC9c4946e7297ef20525589bab03294be4'
            $apiKeySid = config('constants.TWILIO_API_KEY_SID'); //'SK0450f5649c3fbf181c37eae780576937';
            $apiKeySecret = config('constants.TWILIO_API_KEY_SECRET'); //'j6WJ6pw0GhBmAbafpM7vDb4Akt7jYgTR';

            //$twilio = new Client($accountSid, $apiKeySecret);
            // $new_key = $twilio->newKeys->create();
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
            $res= $token->toJWT();
            $response=array('status'=>true,'token'=>$res,'message'=>'Token generate Successfully.','room_name'=>$chatroom);
        }else{
             $response=array('status'=>false,'message'=>'Token generate failed.');
        }
        return response()->json($response);
    }

    /**
     * delete users notification
     *
     * @return [string] message
    */
    public function twilioByUser(Request $request){  
        $rules =  ['user_id' => 'required',
                   'schedule_date' => 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);   

        if ($validator->fails()) {
            return response()->json(['status'=>false,'message'=>$validator->errors()->first()]);
        }    
        $data=$request->all();  
        $date=date('Y-m-d',strtotime($data['schedule_date']));
        $schedule = Schedule::where('user_id',$data['user_id'])->whereDate('datetime', '=', $date)->first();
        if(isset($schedule)){
            // Substitute your Twilio Account SID and API Key details

            $accountSid = config('constants.TWILIO_ACCOUNT_SID'); //'AC9c4946e7297ef20525589bab03294be4'
            $apiKeySid = config('constants.TWILIO_API_KEY_SID'); //'SK0450f5649c3fbf181c37eae780576937';
            $apiKeySecret = config('constants.TWILIO_API_KEY_SECRET'); //'j6WJ6pw0GhBmAbafpM7vDb4Akt7jYgTR';

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
            $res= $token->toJWT();

            $response=array('status'=>true,'token'=>$res,'message'=>'Token generate Successfully.','room_name'=>$chatroom);
        }else{
             $response=array('status'=>false,'message'=>'Token generate failed.');
        }
        return response()->json($response);
    }

}
