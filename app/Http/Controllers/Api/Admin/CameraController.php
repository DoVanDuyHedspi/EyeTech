<?php

namespace App\Http\Controllers\Api\Admin;

use App\Camera;
use App\Http\Requests\CameraFormRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CameraController extends Controller
{
    public function index()
    {
        //check auth user_id ??
        $cameras = Camera::all();
        $response = [
            'message' => 'List Cameras',
            'cameras' => $cameras,
        ];

        return response()->json($response, 200);
    }

    public function store(CameraFormRequest $request)
    {
        $data = $request->all();
        $camera = new Camera();
        $camera->name = $data['name'];
        $camera->branch_id = $data['branch_id'];
        $camera->save();

        $response = [
            'message' => 'Create camera successfully',
            'camera' => $camera,
            'redirect' => route('branch-cameras.index')
        ];
        return response()->json($response, 201);
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
        $camera = Camera::findOrFail($id);
        $camera->delete();

        $response = [
            'message' => 'Delete camera successfully',
        ];
        return response()->json($response, 200);
    }
}
