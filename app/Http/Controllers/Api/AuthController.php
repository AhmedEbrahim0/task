<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use App\Models\IpAddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{


    public function login(Request $request)
    {

        $validator=Validator::make($request->all(),[
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if($validator->fails()){
            return response()->json([
                "data"=>$validator->errors(),
                "message"=>"data Not Valid",
                "status"=>404
            ],404);
        }

        $credentials = $request->only('email', 'password');

        
        Auth::shouldUse('api');
        
        $token = Auth::attempt($credentials);



        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }


        session()->put('user',Auth::user());

        $user = Auth::user();
        if($user->limit_login == 0){

            return response()->json([
                'message' => "You're logged in from two devices",
            ], 422);
        }


        if(User::find($user->id)->token1 == null){

            User::find($user->id)->update([
                "token1"=>($user->token1 == null ? $token : $user->token1),
                "limit_login"=>$user->limit_login - 1,
            ]);
        }else{
            User::find($user->id)->update([
                "token2"=>($user->token2 == null ? $token : $user->token2),
                "limit_login"=>$user->limit_login - 1,
            ]);
        }
        

        $ip_address=null;
        // if user from the share internet   
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {   
            $ip_address= $_SERVER['HTTP_CLIENT_IP'];   
        }   
        //if user is from the proxy   
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   
             $ip_address= $_SERVER['HTTP_X_FORWARDED_FOR'];   
        }   
        //if user is from the remote address   
        else{   
             $ip_address= $_SERVER['REMOTE_ADDR'];   
        }  
        IpAddress::where("ip",$ip_address)->update([
            "limit"=>3,
        ]);

        return response()->json([

                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);

    }

    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout(Request $request)
    {

        if($request->has("user_id") && $request->has('token')){
            $user = User::find($request->user_id);

            if($user==null )
                return response()->json([
                    "message"=>"user not found",
                ]);
            if($user->token1 == $request->token )
                $user->update([
                    "token1"=>null,
                    "limit_login"=>$user->limit_login +1 ,
                ]);
            elseif($user->token2 == $request->token)
                $user->update([
                    "token2"=>null,
                    "limit_login"=>$user->limit_login +1 ,
                ]);
            else
                return response()->json(["message"=>"Token error"],404);

                
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged out',
            ]);
            
        }else{
            return response()->json([
                "message"=>" Not allowed ",
            ]);
        }

    }



}