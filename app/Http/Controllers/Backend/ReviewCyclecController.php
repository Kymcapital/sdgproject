<?php

namespace App\Http\Controllers\Backend;

use App\Models\ReviewCycle;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Response,Exception;


class ReviewCyclecController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            $cycle = ReviewCycle::select('*');
            return datatables()->of($cycle)
            ->addColumn('action', 'backend.reviewcycle.action')
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);

        }
        return view('backend.reviewcycle.index');
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
            'label'      =>  'string|required',
            'year'       =>  'string|required',
            'start_date' =>  'required|date',
            'end_date'   =>  'required|date',

        ]);


        try {
             ReviewCycle::Create([
                'label'      => $request->label,
                'year'       => $request->year,
                'start_date' => $request->start_date,
                'end_date'   => $request->end_date,
            ]);

            return response()->json([
                'data'     => $request->all(),
                'status'   => 'success',
                'message'  => $request->label.' successfully added/updated!',
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
       $reviewcycle  = ReviewCycle::where('id',$id)->first();
        return Response::json($reviewcycle);
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

            $reviewcycle = ReviewCycle::findOrFail($id);
            $reviewcycle->delete();

            return response()->json([
                'status' => 'success',
                'message'  => $reviewcycle->label.' Deleted successfully',
            ], 200);

        }catch(Exception $e){

            return response()->json([
                'status' => 'error',
                'message'  => $e->getMessage(),
            ], 500);

        }
    }

}
