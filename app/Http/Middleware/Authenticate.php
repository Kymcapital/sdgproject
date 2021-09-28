<?php

namespace App\Http\Middleware;

//use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;

class Authenticate //extends Middleware
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
    * ensure all users have an authenticated access token & that it is not expired.
    * Logs out people with expired tokens
    * Extends the life of a token if more than 5 minutes
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
     public function handle(Request $request, Closure $next)
     {
        $accessToken = $request->session()->get('accessToken')??null;
        $decipherToken = $this->decipherAccessToken($request,$accessToken);

        if( is_array($decipherToken) && !empty($decipherToken) ){

            $sessionLifetime = config('SESSION_LIFETIME','3600');
            $isExpiring = ( time() - floatval($decipherToken[1]) )>$sessionLifetime ?true:false;

            if( $isExpiring ) {
                // $request->session()->flush();
                // return redirect()->route('entrypoint');
                $accessToken = $this->encryptAccessToken($decipherToken[0]);
                $request->session()->put('accessToken', $accessToken);

            }

        }else{
            $this->redirectTo($request);
        }

        return $next($request);
     }
 
    /**
     * Returns an encrypted string. Concantenanted email + time created
     * @param  string $emailAddress
     * @return string $encryptedToken
     */
    private function encryptAccessToken($emailAddress)
    {

        $token = $emailAddress.'&'.time();
        $encryptedToken = Crypt::encryptString($token);
        $encryptedToken = base64_encode($encryptedToken);

        return $encryptedToken;
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

}
