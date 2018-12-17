<?php

namespace App\Http\Controllers\Api\Admin;

use App\Branch;
use App\Http\Requests\ListBranchFormRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BranchController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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

    public function getBranchID(ListBranchFormRequest $request)
    {
        $data = $request->all();
        $branches = Branch::where('store_id', '=', $data['store_id'])->get();
        $response = [
          'message' => 'List Branch Of StoreId ' . $data['store_id'],
          'data' => $branches
        ];

        return response()->json($response, 200);
    }
    public function getBranchForClient(ListBranchFormRequest $request)
    {
        $data = $request->all();
        $branches = Branch::where('store_id', '=', $data['store_id'])->get();
        $data = [];
        foreach ($branches as $branch) {
            $branch_name = $branch->user->name;
            $value = [
                'value' => $branch->id,
                'text' => $branch_name
            ];
            array_push($data, $value);
        }

        $response = [
            'message' => 'List branches format for client',
            'branches' => $data
        ];

        return response()->json($response, 200);
    }
}
