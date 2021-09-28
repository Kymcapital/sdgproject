<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use DB;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendAccessLink;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Exception;

class DashboardController extends Controller
{   

    public function index(Request $request){
        
        $accessToken = $request->input('accessToken')??$request->session()->get('accessToken');
        if($accessToken){
            $credentials = $this->decipherAccessToken($request,$accessToken);
            if($credentials){

                $email = $credentials[0];
                $isUser = DB::table('users')
                    ->join('roles', function ($join) {
                        $join->on('users.role_id', '=', 'roles.id');
                    })
                    ->leftJoin('permissions', function ($join) {
                        $join->on('users.permission_id', '=', 'permissions.id');
                    })
                    ->leftJoin('divisions', function ($join) {
                        $join->on('users.division_id', '=', 'divisions.id');
                    })
                    ->where('users.email', '=', $email)
                    ->select(DB::raw('users.*, roles.id as roles_id, roles.name as roles_name, permissions.id as permissions_id, permissions.name as permissions_name, divisions.label as divisions_label, divisions.id as division_id'))
                    ->first();
                    
                    //store user data to session
                    $request->session()->put('userData', $isUser);

                    //dd($isUser); //$request->session()->get('userData')->division_id

                if(!empty($isUser)){
                    if($isUser->roles_name == 'Super Admin' OR $isUser->roles_name == 'Admin'){
                        $request->session()->put('accessToken', $accessToken);
                        $request->session()->put('AdminAccess', true);
                        return redirect()->route('divisions.index');
                    }else if($isUser->roles_name == 'Employee'){
                        // if($isUser->permissions_name == 'Manage Reviews'){
                        //     $request->session()->put('accessToken', $accessToken);
                        //     $request->session()->put('AdminAccess', false);
                        //     return redirect()->route('champions.index');
                        // }else{
                        $request->session()->put('accessToken', $accessToken);
                        $request->session()->put('AdminAccess', false);
                        return redirect()->route('frontend.overview');
                        //}
                        
                    }
                }else{
                    return view('welcome')->with('error','You do not have access to this resource!');
                }

            }
        }
        return view('welcome');
    }

    /**
    * Validates an Email Address and sends an authorized link to their email
    *@param /Illuminate/Http/Request
    *@return /Illuminate/Http/Response
    */
    public function accessLink(Request $request){

        $email = $request->input('email');
      
        //validate
        $request->validate([
            'email' => 'required', //|unique:users,email
        ]);

        try {
      
            //confirm the user is in the Database
            $isUser = User::where('email',$email)->first();

            if($isUser){
      
                $encryptedToken = $this->encryptAccessToken($email);
                //send link to respective Email
                Mail::to($email)->send(new SendAccessLink($encryptedToken) );

                return back()->with('success', 'Kindly check your e-mail for the Access link!');
            }
            return back()->with('warning','We are unable to find your email in our database!');
            
        }catch(Exception $e){

            return back()->with('error', $e->getMessage()); //->route('champions.index')

        }

    }

    /**
    * Returns an encrypted string. Concantenanted email + time created
    *@param string $email
    *@return string $encryptedToken
    */
    private function encryptAccessToken($email){

        $token = $email.'&'.time();
        $encryptedToken = Crypt::encryptString($token);
        $encryptedToken = base64_encode($encryptedToken);
  
        return $encryptedToken;

    }

    /**
     *Decrypts Access Tokens for authentication
    *@param /Illuminate/Http/Request $request
    *@param string $accessToken
    *@return $token|false
    */
    private function decipherAccessToken(Request $request,$accessToken){

        //$encryptedToken = url_decode($token);
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

        return false;

    }

    // Switch user with any role to frontend resources i.e (Overview)
    public function switchToFront(Request $request){

        $accessToken = $request->input('accessToken')??$request->session()->get('accessToken');

        if($accessToken){
            $credentials = $this->decipherAccessToken($request,$accessToken);
            
            if($credentials){

                $email = $credentials[0];
                $isUser = DB::table('users')
                    ->join('roles', function ($join) {
                        $join->on('users.role_id', '=', 'roles.id');
                    })
                    ->where('users.email', '=', $email)
                    ->select(DB::raw('users.*, roles.id as roles_id, roles.name as roles_name'))
                    ->first();

                if($isUser->roles_name){
                    $request->session()->put('accessToken', $accessToken);
                    $request->session()->put('AdminAccess', false);
                    return redirect()->route('frontend.overview');
                }

            }
        }
        return back()->with('error','You do not have access to this resource!');

    }

    /**
    * Remove session data & return user to login page
    */
    public function logout(Request $request){
        $request->session()->flush();
        return redirect()->route('entrypoint')->with('info','Successfully logged out!');
    }
}
