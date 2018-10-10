<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\DetectionFormRequest;
use App\Event;
use App\Customer;
use App\Http\Controllers\Controller;

class DetectionController extends Controller
{
    public function store(DetectionFormRequest $request)
    {
        $event = new Event();
        $vector = $request->input('vector');
        $customer_id = $request->input('customer_id');
        $time_in = $request->input('time_in');
        $camera_id = $request->input('camera_id');
        $emotion = $request->input('emotion');
        $image_camera_base64_array = $request->input('image_camera_base64_array');

        if ($customer_id == -1) {
            $customer = new Customer();
            $customer->vector = $vector;
            if (!$customer->save()) {
                $response = [
                    'message' => 'Error During Create Customer',
                ];
                return response()->json($response, 404);
            }
            $customer->image_url_array = $this->generateImagesUrl($image_camera_base64_array, $customer->_id);
            $customer->save();

            $event->customer_id = $customer->_id;
            $event->image_detection_url_array = $customer->image_url_array;
            $event->image_camera_url_array = $customer->image_url_array;
        } else {
            $customer = Customer::find($customer_id);
            if (!$customer) {
                $response = [
                    'message' => 'Customer Do Not Exist',
                ];
                return response()->json($response, 404);
            }
            $event->customer_id = $customer_id;
            $event->image_detection_url_array = $customer->image_url_array;
            $event->image_camera_url_array = $this->generateImagesUrl($image_camera_base64_array, $customer->_id);
        }
        $event->vector = $vector;
        $event->time_in = $time_in;
        $event->camera_id = $camera_id;
        $event->emotion = $emotion;
        if(!$event->save()) {
            $response = [
                'message' => 'Error: Create Event Fail',
            ];
            return response()->json($response, 404);
        }
        $event->view_event = [
            'href' => 'api/v1/events/' . $event->_id,
            'method' => 'GET',
        ];
        $event->view_customer = [
            'href' => 'api/v1/customers/' . $event->customer_id,
            'method' => 'GET',
        ];
        $response = [
            'message' => 'Event Created Successfully',
            'data' => $event,
        ];

        return response()->json($response, 201);
    }

    public function generateImagesUrl(array $image_camera_base64_array, $id)
    {
        $image_url_array = [];
        foreach ($image_camera_base64_array as $image_base64) {
            $image_base64_decode = base64_decode($image_base64);
            $path = 'images/cu/' . $id . '/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $image_url = $path . str_random(10) . '.jpg';
            if(file_put_contents($image_url, $image_base64_decode)) {
                array_push($image_url_array, $image_url);
            }
        }

        return $image_url_array;
    }
}
