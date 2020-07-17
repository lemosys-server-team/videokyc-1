<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Validator;
use Auth;
use File;

use App\Notifications\SendOTP;
use App\User;
use App\PasswordReset;

class AuthController extends Controller
{
    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function index(Request $request){ 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'email' => 'required|email|unique:'.with(new User)->getTable().',email',
            'password' => 'required|confirmed'
        ]);

        if ($validator->fails()) { 
            return response()->json(['message'=>$validator->errors()->first()]);            
        }
        $input = array_map('trim', $request->all());
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input); 
        if($user){
            $user->assignRole(config('constants.ROLE_TYPE_USER_ID'));
            $response['status'] = true; 
            $response['message'] = "You has been successfully registered, please login with your email and password.";
            return response()->json($response);
        }else{
            return response()->json(['message'=>'Something wrong in registration.']);
        }
    }

    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 

    public function login(Request $request){       
        $rules =   ['mobile_number' => 'required', 
                    'type'=>'required', 
                    'otp' => 'required'];
        
        $validator = Validator::make($request->all(), $rules);   

        if ($validator->fails()) {
            return response()->json(['status'=>false,'message'=>$validator->errors()->first()]);
        }    
        $data=$request->all();  
        $type=$data['type'];
        if($type==config('constants.ROLE_TYPE_SALES_ID')){
            $user = User::whereHas('roles', function($query) use ($type){
              $query->where('id', $type);
            })->where(array('mobile_number'=>$data['mobile_number'],'password'=>Hash::make($data['otp'])))->first();
        }else{
            $user = User::whereHas('roles', function($query) use ($type){
              $query->where('id', $type);
            })->where(array('mobile_number'=>$data['mobile_number'])))->first();
        }
        if($user){
             // For store access token of user
            $tokenResult = $user->createToken('Login Token');
            $token = $tokenResult->token;

            $response['status'] = true; 
            $response['message'] = "Logged in successfully.";
            $response['user'] = $user;
            $response['access_token'] = $tokenResult->accessToken;
            $response['token_type'] = 'Bearer';
            $response['expires_at'] = Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString();
            return response()->json($response); 
        }else{
             $response=array('status'=>false,'message'=>'User not found.');
             return response()->json($response);
        }
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request){
        $request->user()->token()->revoke();
        $response['status'] = true;  
        $response['message'] = "Successfully logged out";
        return response()->json($response);
    }

    /** 
     * forgotPassword api 
     * 
     * @return \Illuminate\Http\Response 
    */ 

    public function forgotPassword(Request $request){        
        $rules =   ['email' => 'required'];
        $credentials= ['email' => $request->get('email')];
        
        $messages = [
            'email.required' => "The email field is required.",
        ];
        $validator = Validator::make($request->all(), $rules,$messages);
        
        if ($validator->fails()) { 
            return response()->json(['message'=>$validator->errors()->first()]);    
        }

        $user=User::where($credentials)->first();

        if($user){
            PasswordReset::where('email', $user->email)->delete();
            $passwordReset = PasswordReset::create(
                [
                    'email' => $user->email,
                    'token' => str_random(6)
                ]
            );

            if ($user && $passwordReset)
                $user->notify(
                    new SendOTP('forget_password', $passwordReset->token)
                );
            
            $response['status'] = true; 
            $response['response'] = array();
            $response['message'] = "We have sent a verification code on your email";
            return response()->json($response); 
        }else{
            return response()->json(['message'=>'Account details not found.']);
        }
    }

    /** 
     * resetPassword api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function resetPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'token' => 'required', 
            'password' => 'required|confirmed'
        ]);
        if ($validator->fails()) { 
            return response()->json(['message'=>$validator->errors()->first()]);            
        }

        $user = User::where('email', $request->input('email'))->first();
        if (!$user)
            return response()->json([
                'message' => 'We can\'t find a user with that e-mail address.'
            ]);

        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();

        if (!$passwordReset)
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ]);

        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->where('email', $passwordReset->email)->delete();

        $response['status'] = true;
        $response['message'] = "Update Password successfully.";
        return response()->json($response); 
    }

    /**
     * update user
     *
     * @return [string] message
     */
    public function updateProfile(Request $request){
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:'.with(new User)->getTable().',email,'.$user->getKey(),
            'profile_picture' => 'image'
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()->first()]);
        }

        $data = $request->all();

        $userfile = $request->file('profile_picture');
        if($userfile){
            $path = public_path(config('constants.USERS_UPLOADS_PATH'));

            $profile_pictureName = time().'.'.$userfile->getClientOriginalExtension();
            File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

            $uploadResponse = $userfile->move($path, $profile_pictureName);
            if(isset($user->profile_picture) && $user->profile_picture!='' && file_exists($path.$user->profile_picture)){
                File::delete($path.$user->profile_picture);
            }

            $data['profile_picture'] = $profile_pictureName;
        }
        $user->update($data);

        $response['status'] = true;  
        $response['user'] = $user->getUserDetail();
        $response['message'] = "Profile updated Successfully.";
        return response()->json($response);
    }
}
