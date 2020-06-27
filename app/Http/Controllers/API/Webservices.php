<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
}
