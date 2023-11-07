<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;

use Laravel\Jetstream\Jetstream;
use App\Http\Controllers\Controller;
use App\Models\IpAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{


    public function loginPage(Request $request)  {
            if(Auth::user() !=null)
                return redirect('/');
            return view('login');
    }
    public function logout(Request $request)  {
        Auth::logout();
        return redirect('login');
    }
    public function Register(Request $request)  {


        $request->validate([
            "name"=>'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            "password"=>'required',
            "password_confirmation"=>'required',
        ]);

        if($request->password_confirmation != $request->password){
            session()->flash("error","The password does not match");
            return back();
        }

        session()->flash("success","     User created successfully");

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);




        return back();

 
    }   
    public function Login(Request $request)  {
        
        $validator=Validator::make($request->all(),[
            "password"=>"required",
            "email"=>"required|email",
        ]);
        if($validator->fails()){
            return back();
        }
        $credentials = $request->only('email', 'password');

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
        $check=IpAddress::where("ip",$ip_address)->where("user_agent",$request->userAgent())->first();

        if (Auth::attempt($credentials)) {
            session()->put('user',Auth::user());
            $users=User::all();
            $check->update([
                "limit"=>3,
            ]);
            
            return view('index')
            ->with("users",$users)
            ->with("search",($request->has('search')  ?$request->search  : null))
            ;
        }
        
        elseif($check->limit == -1 ){
            $check->update([
                "block"=>true
            ]);
            return response()-> view("suspeneded");
        }            
        session()->flash("error","The email or password is incorrect");
        return back();
    }
}
