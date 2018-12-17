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
    public function getStoreForClient()
    {
        $stores = Store::all();
        $data = [];
        foreach ($stores as $store) {
            $store_name = $store->user->name;
            $value = [
                'value' => $store->id,
                'text' => $store_name,
            ];
            array_push($data, $value);
        }

        $response = [
            'message' => 'List stores format for client.',
            'stores' => $data,
        ];
        return response()->json($response, 200);
    }
}
