<?php

namespace App\Http\Controllers\Api\Admin;

use App\UserType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserTypeController extends Controller
{
    public function index()
    {
        $types = UserType::all();
        $response = [
            'message' => 'List Of User Types',
            'types' => $types
        ];
        return response()->json($response, 200);
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
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
}
