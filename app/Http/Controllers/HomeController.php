<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Country;
use App\State;
use App\City;
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
        $state = State::where(['is_active'=>TRUE])->pluck('title', 'id');

        $city=City::where(['is_active'=>TRUE])->pluck('title', 'id');

       return view('home',compact('state','city'));
    }

    public function register(Request $request){

        $rules = [
            'name'       => 'required', 
            'mobile_number'     => 'required|unique:'.with(new User)->getTable().',mobile_number',
            'email'      => 'required',
            // 'state_id'        => 'required',
            // 'city_id'        => 'required',
            // 'address1'      =>'required',
            // 'address2'      =>'required',
            // 'date'        => 'required',
            // 'time'        => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $request->all();
            $data['password'] = Hash::make(123456);
            $user = User::create($data);
            //assign user roles
            $user->assignRole(config('constants.ROLE_TYPE_USER_ID'));
            $request->session()->flash('success',__('Details submitted successfully'));
            return redirect()->back();
        }else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function getstatetocity(Request $request){
        $city = City::where('state_id',$request->state_id)->where(['is_active'=>TRUE])->pluck('title', 'id'); 
        return response()->json($city);
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
