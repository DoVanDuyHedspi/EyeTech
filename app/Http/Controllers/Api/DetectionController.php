<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\DetectionFormRequest;
use App\Event;
use App\Customer;
use App\Http\Controllers\Controller;
use Validator;
use App\Http\Resources\Event as EventResource;

class DetectionController extends Controller
{
    /**
     * Handle detect
     * Step1: handle request
     * Step2: handle customer_id, store image from image_camera_base64 and get url
     * Step3: Create new event and return
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(DetectionFormRequest $request)
    {
        //step1
        $resultR = $this->handleRequest($request);
        $data = $resultR[0];
        $errors = $resultR[1];
        if (!$data) {
            $response = [
                'message' => 'Error: Request Params Is Not Invalid',
                'errors' => $errors,
            ];
            return response()->json($response, 400);
        }

        //step2
        $customer_id = $data['customer_id'];
        $resultDetect = $this->handleCustomerId($customer_id, $data);

        if ($customer_id == -1) {
            if (!$resultDetect) {
                $response = [
                    'message' => 'Error: Create Customer Fail',
                ];
                return response()->json($response, 400);
            }
        } else {
            if (!$resultDetect) {
                $response = [
                    'message' => 'Error: CustomerID ' . $customer_id . ' Do Not Exists',
                ];
                return response()->json($response, 404);
            }
        }
        $customer = $resultDetect[0];
        $image_camera_url_array = $resultDetect[1];

        //step3
        $event = $this->createEvent($customer, $data, $image_camera_url_array);

        if (!$event) {
            $response = [
                'message' => 'Error: Create Event Fail',
            ];
            return response()->json($response, 400);
        }

        return (new EventResource($event))
            ->additional([
                'info' => [
                    'message' => 'Detected! Event Created Successfully',
                    'version' => '1.0'
                ]
            ])
            ->response()
            ->setStatusCode(201);
    }

    public function generateImagesUrl(array $image_camera_base64_array, $id)
    {
        $image_url_array = [];
        foreach ($image_camera_base64_array as $image_base64) {
            $image_base64_decode = base64_decode($image_base64);
            $pathHeader = 'http://202.191.56.249/';

//            $path = '/Applications/MAMP/htdocs/images/cu/' . $id . '/';
            $path ='/var/www/html/images/cu/' . $id . '/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $image_url_body = str_random(10) . '.jpg';
            $image_url = $path . $image_url_body;
            if (file_put_contents($image_url, $image_base64_decode)) {
                $fix_image_url = $pathHeader . 'images/cu/' . $image_url_body;
                array_push($image_url_array, $fix_image_url);
            }
        }

        return $image_url_array;
    }

    public function handleRequest(DetectionFormRequest $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, $request->setRules());
        if ($validator->fails()) {
            return [false, $validator->errors()];
        }
        return [$data, $validator->errors()];
    }

    public function handleCustomerId($id, $data)
    {
        $store_id = $data['store_id'];
        $image_camera_base64_array = $data['image_camera_base64_array'];

        if ($id == -1) {
            $customer = new Customer();
            $customer->vector = $data['vector'];
            $customer->store_id = $store_id;
            if (!$customer->save()) {
                return false;
            }
            $image_camera_url_array = $this->generateImagesUrl($image_camera_base64_array, $customer->_id);
            $customer->image_url_array =  $image_camera_url_array;
            $customer->name = '';
            $customer->age = '';
            $customer->gender = '';
            $customer->telephone = '';
            $customer->type = '';
            $customer->address = '';
            $customer->favorites = '';
            $customer->note = '';
            $customer->save();

            return [$customer, $image_camera_url_array];
        } else {
            $customer = Customer::find($id);
            if (!$customer) {
                return false;
            }
            $image_camera_url_array = $this->generateImagesUrl($image_camera_base64_array, $customer->_id);
            return [$customer, $image_camera_url_array];
        }
    }

    public function createEvent($customer, $data, $image_camera_url_array)
    {
        $event = new Event();
        $event->customer_id = $customer->_id;
        $event->vector = $customer->vector;
        $event->store_id = $data['store_id'];
        $event->time_in = $data['time_in'];
        $event->camera_id = $data['camera_id'];
        $event->image_camera_url_array = $image_camera_url_array;
        $event->image_detection_url_array = $customer->image_url_array;
        $event->save();
        if (!$event) {
            return false;
        }
        return $event;
    }
}
