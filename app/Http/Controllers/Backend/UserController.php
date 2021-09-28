<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Company;
use App\Models\Division;
use App\Models\Role;
use App\Models\Permission;  

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UserImport;

use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;
use Redirect,Response,DB;
use File;
use PDF;

use Exception;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::where('status','!=', 0)->get();
        $divisions = Division::all();
        $roles = Role::where('id','!=',(int)1)
            ->get();
        $permissions = Permission::all();

        if(request()->ajax()) {
            return datatables()->of(User::select('*')->where('role_id', '!=', 1)->orWhere('role_id', NULL))
            ->addColumn('action', 'backend.users.action')
            ->addIndexColumn()
            ->editColumn('created_at', function ($user) {
                return [
                    'formated_date' => Carbon::parse($user->created_at)->format('M d Y'),
                ];
            })
            ->editColumn('name', function($user) {
        
                return $user->first_name." ".$user->last_name;
            })
            ->editColumn('division_id', function($user) {

                $divisions = Division::all();
                foreach ($divisions as $key => $division) {
                    if($user->division_id == $division->id){
                        return $division->label;
                    }
                }
            })
            ->editColumn('role_id', function($user) {

                $roles = Role::all();
                foreach ($roles as $key => $role) {
                    if($user->role_id == $role->id){
                        return $role->name;
                    }
                }
            })
            ->editColumn('permission_id', function($user) {

                $permissions = Permission::all();
                foreach ($permissions as $key => $permission) {
                    if($user->permission_id == $permission->id){
                        return $permission->name;
                    }else{
                        return "N/A";
                    }
                }
            })
            ->editColumn('user_id', function($user_ids) {

                $users = User::all();
                foreach ($users as $key => $user) {
                    if($user_ids->user_id == $user->id){
                        return $user->first_name.' '.$user->last_name;
                    }
                }
            })
            ->editColumn('company_id', function($company_ids) {
        
                $companies = Company::where('status','!=', 0)->get();
                foreach ($companies as $key => $company) {
                    if($company_ids->company_id == $company->id){
                        return $company->name;
                    }
                }
            })
            ->make(true);
        }

        return view('backend.users.index', compact('companies','divisions','roles', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required', //|unique:users,email
        ]);

        try {
     
            $userId = $request->user_id;
        
            $details = [
                'first_name' => $request->first_name, 
                'last_name' => $request->last_name, 
                'email' => $request->email,     
                'company_id' => $request->company_id,                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
                'division_id' => $request->division_id, 
                'role_id' => $request->role_id, 
                'permission_id' => $request->permission_id,
                'user_id' => $request->session()->get('userData')->id
            ];
            
            $user = User::updateOrCreate(['id' => $userId], $details);

            return response()->json([
                'status' => 'success',
                'message'  => $request->first_name.' successfully added/updated!',
            ], 200);
            
        }catch(Exception $e){

            return response()->json([
                'status' => 'error',
                'message'  => $e->getMessage(),
            ], 400);

        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $where = array('id' => $id);
        $user  = User::where($where)->first();
      
        return Response::json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{

            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'status' => 'success',
                'message'  => $user->label.' Deleted successfully',
            ], 200);

        }catch(Exception $e){

            return response()->json([
                'status' => 'error',
                'message'  => $e->getMessage(),
            ], 500);

        }
    }

    /**
     * Import the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls,ods|max:2048',
        ]);

        try{

            Excel::import(new UserImport, $request->file('file')->store('temp'));

            return response()->json([
                'status' => 'success',
                'message'  => 'All data successfully imported!',
            ], 200);
            
        }catch(Exception $e){

            return response()->json([
                'status' => 'error',
                'message'  => $e->getMessage(),
            ], 400);

        }

    }
}
