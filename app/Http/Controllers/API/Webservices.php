<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $user = Schedule::with('user')->where('sale_id',$data['sale_id'])->get();
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
               
                $old_profile_picture = isset($user->image_pen)?$user->image_pen:'';
                if (isset($old_image_pen) && $old_image_pen!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_USER').$old_image_pen)) {
                    \Storage::delete(config('constants.SCHEDULE_UPLOAD_PATH_USER').$old_image_pen);
                }
                $data['image_pen'] = $image_pen;
            }

            if ($request->hasFile('image_adhar')){
                $file = $request->file('image_adhar');
                $image_adhar  = time().$schedule->id.'image_adhar.' . $file->getClientOriginalExtension();
                $file->storeAs(config('constants.SCHEDULE_UPLOAD_PATH_USER'), $image_adhar);
               
                $old_profile_picture = isset($user->image_adhar)?$user->image_adhar:'';
                if (isset($old_image_adhar) && $old_image_adhar!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_USER').$old_image_adhar)) {
                    \Storage::delete(config('constants.SCHEDULE_UPLOAD_PATH_USER').$old_image_adhar);
                }
                $data['image_adhar'] = $image_adhar;
            }

            if ($request->hasFile('image_photo')){
                $file = $request->file('image_photo');
                $image_photo  = time().$schedule->id.'image_photo.' . $file->getClientOriginalExtension();
                $file->storeAs(config('constants.SCHEDULE_UPLOAD_PATH_USER'), $image_photo);
               
                $old_image_photo = isset($user->image_photo)?$user->image_photo:'';
                if (isset($old_image_photo) && $old_image_photo!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_USER').$old_image_photo)) {
                    \Storage::delete(config('constants.SCHEDULE_UPLOAD_PATH_USER').$old_image_photo);
                }
                $data['image_photo'] = $image_photo;
            }
            $schedule->update($data);

            $response=array('status'=>true,'message'=>'Document uploaded Successfully.');
        }else{
            $response=array('status'=>false,'message'=>'User schedule not found.');
        }
        return response()->json($response);
    }


     /**
     * delete users notification
     *
     * @return [string] message
    */
    public function uploadDocumentBysale(Request $request){

       $rules =   ['schedule_id' => 'required',
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
               
                $old_profile_picture = isset($user->ss01)?$user->ss01:'';
                if (isset($old_ss01) && $old_ss01!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$old_ss01)) {
                    \Storage::delete(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$old_ss01);
                }
                $data['ss01'] = $ss01;
            }

            if ($request->hasFile('ss02')){
                $file = $request->file('ss02');
                $ss02  = time().$schedule->id.'ss02.' . $file->getClientOriginalExtension();
                $file->storeAs(config('constants.SCHEDULE_UPLOAD_PATH_SALES'), $ss02);
               
                $old_profile_picture = isset($user->ss02)?$user->ss02:'';
                if (isset($old_ss02) && $old_ss02!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$old_ss02)) {
                    \Storage::delete(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$old_ss02);
                }
                $data['ss02'] = $ss02;
            }

            if ($request->hasFile('ss03')){
                $file = $request->file('ss03');
                $ss03  = time().$schedule->id.'ss03.' . $file->getClientOriginalExtension();
                $file->storeAs(config('constants.SCHEDULE_UPLOAD_PATH_SALES'), $ss03);
               
                $old_ss03 = isset($user->ss03)?$user->ss03:'';
                if (isset($old_ss03) && $old_ss03!='' && \Storage::exists(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$old_ss03)) {
                    \Storage::delete(config('constants.SCHEDULE_UPLOAD_PATH_SALES').$old_ss03);
                }
                $data['ss03'] = $ss03;
            }
            $schedule->update($data);

            $response=array('status'=>true,'message'=>'Document uploaded Successfully.');
        }else{
            $response=array('status'=>false,'message'=>'Document uploaded failed.');
        }
        return response()->json($response);

}

}
