<?php

namespace App\Http\Controllers\Api\Admin;

use App\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StoreController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function getStoreID()
    {
        $stores_id = Store::all('id');

        $response = [
            'message' => 'List Of Store ID',
            'stores_id' => $stores_id
        ];

        return response()->json($response, 200);
    }
}
