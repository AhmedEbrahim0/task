<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\IpAddress;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLimitRequests
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
        
        if($check != null && $check->timestamp > time()){

            return response()-> view("error_page");
        }elseif( $check != null && $check->block == true ){
            return response()->view("suspeneded");
        }
        return $next($request);
    }
}
