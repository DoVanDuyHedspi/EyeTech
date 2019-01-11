<?php

namespace App\Http\Controllers\APi;

use App\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AnalyticalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getTotalCustomers($branch_id)
    {
        $count = Event::where('branch_id', '=', 5)->distinct('customer_id')->count('customer_id');
        $response = [
            'message' => $count
        ];

        return response()->json($response, 200);
    }

    public function getTodayCustomers($branch_id)
    {

    }

    public function getGrowthRate($branch_id)
    {

    }

    public function getNewCustomers($branch_id)
    {

    }

    public function getCustomerTypes($branch_id)
    {

    }
}
