<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index(Request $request){
        $users=User::when($request->search,function($q,$val){
            $q->where("name","like","%{$val}%");
        })->get();
        return view('index')
        ->with("users",$users)
        ->with("search",($request->has('search')  ?$request->search  : null))
        ;
    }

    public function store(Request $request){
        $request->validate([
            'email' => 'required|string|email',
            'name' => 'required|string',
            'password' => 'required',
            'confrim_password' => 'required',
        ]);
        if($request->password != $request->confrim_password){
            session()->flash("error","The password does not match  ");
            return back();
        }
        User::create([
            "name"=>$request->name,
            "email"=>$request->email,
            'password' => Hash::make($request->password),
        ]);
        session()->flash("success","Added successfully");
        return back();
    }
    public function destroy(Request $request){
        $check=User::find($request->user_id);
        if($check==null){
            session()->flash("error","User not found ");
            return back();
        }
        User::destroy($request->user_id);
        session()->flash("success","Deleted Successfully");
        return back();
    }
}
