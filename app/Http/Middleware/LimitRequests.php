<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\IpAddress;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LimitRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

         
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
        $check= IpAddress::where("ip",$ip_address)->where("user_agent",$request->userAgent())->first();
        

        if($check==null){
            IpAddress::create([
                "ip"=>$ip_address,
                "limit"=>2,
                "user_agent"=>$request->userAgent(),

            ]);
        }
        elseif($check->block == true){
            return response()->view("suspeneded");
        }  
 
        elseif($check->timestamp > time()){

            return response()-> view("error_page");
        }
        elseif($check->limit ==1){
            $check->update([
                "timestamp"=>time()+30,
                "limit"=>-1,
            ]);
            return response()->view("error_page");
        }
        elseif($check->limit != -1){
            $check->update(["limit"=>$check->limit-1]);
        }

        return $next($request);
    }
}
