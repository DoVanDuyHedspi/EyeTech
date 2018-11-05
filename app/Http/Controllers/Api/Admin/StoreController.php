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
        $store = new Store();

        $store->name = $request->input('name');
        $store->email = $request->input('email');
        $store->telephone = $request->input('telephone');
        $store->active = $request->input('active');
        $store->password = bcrypt($request->input('password'));
        $store->save();

        return $store;
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
