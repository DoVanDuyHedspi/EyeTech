<?php

namespace App\Http\Controllers;

use App\Event;
use App\Customer;
use App\Http\Requests\EventFormRequest;
use App\Http\Requests\ResultBeginnerFaceDetectionFormRequest;
use App\Http\Requests\ResultFaceDetectionFormRequest;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        //
    }
    public function store(EventFormRequest $request)
    {
        $event = new Event();
        $event->customer_id = $request->input('customer_id');
        $event->vector = $request->input('vector');
        $event->time_in = $request->input('time_in');
        $event->camera_id = $request->input('camera_id');
        $event->image_camera_url_array = $request->input('image_camera_url_array');
        $event->image_detection_url_array = $request->input('image_detection_url_array');
        $event->emotion = $request->input('emotion');

        $event->save();

        return response()->json($event, 201);
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
    /**
     * Api for phase 2
     */
    public function sendResultFaceDetection(ResultFaceDetectionFormRequest $request)
    {
        $image_camera_base64_array = $request->input('image_camera_base64_array');
        $image_camera_url_array = $image_camera_base64_array;
        $customer_id = $request->input('customer_id');
        $customer = Customer::findOrFail($customer_id);
        $image_detection_url_array = $customer->image_url_array;

        //$image_camera_url_array = convertImageBase64ToUrl($image_camera_base64_array);

        $event = new Event();
        $event->customer_id = $request->input('customer_id');
        $event->vector = $request->input('vector');
        $event->time_in = $request->input('time_in');
        $event->camera_id = $request->input('camera_id');
        $event->image_camera_url_array = $image_camera_url_array;
        $event->image_detection_url_array = $image_detection_url_array;
        $event->emotion = $request->input('emotion');
        $event->save();

        return response()->json($event, 201);
    }
    public function sendResultBeginnerFaceDetection(ResultBeginnerFaceDetectionFormRequest $request)
    {
        $customer_id = $request->input('customer_id');
        $image_camera_base64_array = $request->input('image_camera_base64_array');
        $image_detection_url_array = $image_camera_base64_array;
        $image_camera_url_array = $image_camera_base64_array;

        if ($customer_id == -1){
            $customer = new Customer();
            $customer->vector = $request->input('vector');
            $customer->image_url_array = $image_detection_url_array;
            $customer->gender = $request->input('gender');
            $customer->age = $request->input('age');
            $customer->save();
        }

        $event = new Event();
        $event->customer_id = $customer->_id;
        $event->vector = $request->input('vector');
        $event->time_in = $request->input('time_in');
        $event->camera_id = $request->input('camera_id');
        $event->image_camera_url_array = $image_camera_url_array;
        $event->image_detection_url_array = $image_detection_url_array;
        $event->emotion = $request->input('emotion');
        $event->save();

        return response()->json($event, 201);
    }
}
