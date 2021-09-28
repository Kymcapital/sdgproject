<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use DB;

class EmployeeTracker
{

    /**
    * Get the path the user should be redirected to when they are not authenticated.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return string|null
    */
    protected function redirectTo($request)
    {
        $request->session()->flush();
        return redirect()->route('entrypoint')->with('warning', 'You are not authorized. Your session has been closed!');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $accessToken = $request->session()->get('accessToken')??null;

        if( null != $accessToken ){
            $decipherToken = $this->decipherAccessToken($request,$accessToken);
            if(!$decipherToken) $this->retireUser();

            $isUser = DB::table('users')
                ->join('roles', function ($join) {
                    $join->on('users.role_id', '=', 'roles.id');
                })
                ->leftJoin('permissions', function ($join) {
                    $join->on('users.permission_id', '=', 'permissions.id');
                })
                ->where('users.email', '=', $decipherToken[0])
                ->select(DB::raw('users.*, roles.id as roles_id, roles.name as roles_name, permissions.id as permissions_id, permissions.name as permissions_name'))
                ->first();

            //dd($isUser);
            
            if($isUser->roles_name == 'Employee' OR $isUser->roles_name == 'Admin' OR $isUser->roles_name == 'Super Admin'){
                return $next($request);
            }
            return $this->redirectTo($request);

        }else{
            return $this->redirectTo($request);
        }
        
    }

    private function decipherAccessToken(Request $request,$accessToken)
    {

        if(null !=$accessToken ){

            $encryptedToken = base64_decode($accessToken);

            //decrypt

            try {
                $decrypted = Crypt::decryptString($encryptedToken);
                $token = explode('&',$decrypted);
                if( is_array($token) && !empty($token) ){
                    return $token;
                }

            } catch (DecryptException $e) {
                    return false;
            }

        }

        return false;
    }

    /*
    * Invalidate Access and return them to root
    *
    */
    private function retireUser(Request $request){
      $request->session()->flush();
      return redirect()->route('entrypoint')->with('warning', 'You are not authorized. Your session has been closed!');
    }
}
