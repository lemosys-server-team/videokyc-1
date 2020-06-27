<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
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
       return view('home');
    }

    public function register(Request $request){

        $validatedData = $request->validate([
            'name'       => 'required', 
            'mobile_number'     => 'required|unique:'.with(new User)->getTable().',mobile_number',
            'email'      => 'required',
            // 'state_id'        => 'required',
            // 'city_id'        => 'required',
            // 'address1'      =>'required',
            // 'address2'      =>'required',
            // 'date'        => 'required',
            // 'time'        => 'required',
        ]);


            $data = $request->all();
            User::create($data);
            return redirect()->back();
     
          
       
    }

    /*
    * To send device settings to IoT server
    * 
    */

    /*public function sendDeviceData(){
        $endpoint = "https://temspace.in/Projects/GNET/Gpro/data_for_machine.php";
        $client = new \GuzzleHttp\Client();

        $response = $client->request('POST', $endpoint, [
            'form_params' => [
                'data' => json_encode([
                    "machine_id" => "01AAA00001",
                    "voltage_up_limit" => rand(230, 235),
                    "voltage_down_limit" => rand(201, 210),
                    "voltage_up_action" => rand(0, 2),
                    "voltage_down_action" => rand(0, 2),
                    "test_on_time" => rand(200, 210),
                    "test_off_time" => rand(200, 210),
                    "relay_1_mode" => rand(1, 4),
                    "relay_2_mode" => rand(1, 4),
                    "relay_3_mode" => rand(1, 4),
                    "relay_4_mode" => rand(1, 4),
                    "relay_5_mode" => rand(1, 4),
                    "relay_1_mode_data_1" => rand(100, 1000),
                    "relay_2_mode_data_1" => rand(100, 1000),
                    "relay_3_mode_data_1" => rand(100, 1000),
                    "relay_4_mode_data_1" => rand(100, 1000),
                    "relay_5_mode_data_1" => rand(100, 1000),
                    "relay_1_mode_data_2" => rand(100, 1000),
                    "relay_2_mode_data_2" => rand(100, 1000),
                    "relay_3_mode_data_2" => rand(100, 1000),
                    "relay_4_mode_data_2" => rand(100, 1000),
                    "relay_5_mode_data_2" => rand(100, 1000),
                    "relay_1_mode_periodic_data_1" => rand(100, 200),
                    "relay_2_mode_periodic_data_1" => rand(100, 200),
                    "relay_3_mode_periodic_data_1" => rand(100, 200),
                    "relay_4_mode_periodic_data_1" => rand(100, 200),
                    "relay_5_mode_periodic_data_1" => rand(100, 200),
                    "relay_1_mode_periodic_data_2" => rand(100, 200),
                    "relay_2_mode_periodic_data_2" => rand(100, 200),
                    "relay_3_mode_periodic_data_2" => rand(100, 200),
                    "relay_4_mode_periodic_data_2" => rand(100, 200),
                    "relay_5_mode_periodic_data_2" => rand(100, 200),
                    "relay_1_test_mode" => rand(0, 1),
                    "relay_2_test_mode" => rand(0, 1),
                    "relay_3_test_mode" => rand(0, 1),
                    "relay_4_test_mode" => rand(0, 1),
                    "relay_5_test_mode" => rand(0, 1)
                ])
            ]
        ]);

        $statusCode = $response->getStatusCode();
        echo "Status Code: $statusCode<br/>";
        $content = $response->getBody();
        echo "Content: $content<br/>";
    }*/
}
