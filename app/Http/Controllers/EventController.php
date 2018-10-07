<?php

namespace App\Http\Controllers;

use App\Event;
use App\Customer;
use App\Http\Requests\EventFormRequest;
use App\Http\Requests\ResultBeginnerFaceDetectionFormRequest;
use App\Http\Requests\ResultDetectionFormRequest;
use App\Http\Requests\ResultFaceDetectionFormRequest;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $limit = 10;
        $events = Event::paginate($limit);
        foreach ($events as $event) {
            $event->view_event = [
                'href' => 'api/v1/events/' . $event->_id,
                'method' => 'GET',
            ];
        }
        $response = [
            'message' => 'List Of All Events',
            'data' => $events,
        ];

        return response()->json($response, 200);
    }

    public function store(ResultFaceDetectionFormRequest $request)
    {
        $event = Event::create($request->all());
        if (!$event) {
            $response = [
                'message' => 'Error: Create Event Fail',
            ];
            return response()->json($response, 404);
        }
        $event->view_event = [
            'href' => 'api/v1/events/' . $event->_id,
            'method' => 'GET',
        ];
        $event->destroy_event = [
            'href' => 'api/v1/events/' . $event->_id,
            'method' => 'DELETE',
        ];
        $response = [
            'message' => 'Event Created Successfully',
            'data' => $event,
        ];

        return response()->json($response, 201);
    }

    public function show($id)
    {
        $event = Event::find($id);
        if (!$event) {
            $response = [
                'message' => 'Event Does Not Exist',
                'create_event' => [
                    'href' => 'api/v1/events',
                    'method' => 'POST',
                    'params' => [
                        'customer_id' => 'string : required',
                        'vector' => 'string : required',
                        'time_in' => 'date : required',
                        'camera_id' => 'string: required',
                        'image_camera_url_array' => 'string : required',
                        'image_detection_url_array' => 'string : required',
                    ],
                ],
            ];

            return response()->json($response, 404);
        }
        $event->view_events = [
            'href' => 'api/v1/events',
            'method' => 'GET',
        ];
        $response = [
            'message' => 'Event Information',
            'data' => $event,
        ];

        return response()->json($response, 200);
    }

    public function update(Request $request, $id)
    {
        $event = Event::find($id);
        if (!$event) {
            $response = [
                'message' => 'Event Does Not Exist',
                'create_event' => [
                    'href' => 'api/v1/events',
                    'method' => 'POST',
                    'params' => [
                        'customer_id' => 'string : required',
                        'vector' => 'string : required',
                        'time_in' => 'date : required',
                        'camera_id' => 'string: required',
                        'image_camera_url_array' => 'string : required',
                        'image_detection_url_array' => 'string : required',
                    ],
                ],
            ];

            return response()->json($response, 404);
        }
        if (!$event->update($request->all())) {
            $response = [
                'message' => 'Error: Update Event Fail',
            ];

            return response()->json($response, 404);
        }
        $event->view_event = [
            'href' => 'api/v1/events/' . $event->_id,
            'method' => 'GET',
        ];
        $event->update_event = [
            'href' => 'api/v1/events/' . $event->_id,
            'method' => 'PATCH',
        ];
        $response = [
            'message' => 'Event Updated Successfully',
            'data' => $event,
        ];

        return response()->json($response,200);
    }

    public function destroy($id)
    {
        $event = Event::find($id);
        if (!$event) {
            $response = [
                'message' => 'Event Does Not Exist',
                'create_event' => [
                    'href' => 'api/v1/events',
                    'method' => 'POST',
                    'params' => [
                        'customer_id' => 'string : required',
                        'vector' => 'string : required',
                        'time_in' => 'date : required',
                        'camera_id' => 'string: required',
                        'image_camera_url_array' => 'string : required',
                        'image_detection_url_array' => 'string : required',
                    ],
                ],
            ];

            return response()->json($response, 404);
        }
        if (!$event->delete()) {
            $response = [
                'message' => 'Error: Delete Event Fail',
            ];

            return response()->json($response, 404);
        }
        $response = [
            'message' => 'Event Deleted Successfully',
            'create_event' => [
                'href' => 'api/v1/events',
                'method' => 'POST',
                'params' => [
                    'customer_id' => 'string : required',
                    'vector' => 'string : required',
                    'time_in' => 'date : required',
                    'camera_id' => 'string: required',
                    'image_camera_url_array' => 'string : required',
                    'image_detection_url_array' => 'string : required',
                ],
            ],
        ];

        return response()->json($response, 200);
    }

    public function sendResultDetection(ResultDetectionFormRequest $request)
    {
        $event = new Event();
        $vector = $request->input('vector');
        $customer_id = $request->input('customer_id');
        $time_in = $request->input('time_in');
        $camera_id = $request->input('camera_id');
        $emotion = $request->input('emotion');
        $image_camera_base64_array = $request->input('image_camera_base64_array');

        $image_camera_url_array = $this->handleBase64ToUrl($image_camera_base64_array);

        if ($customer_id == -1) {
            $customer = new Customer();
            $customer->vector = $vector;
            $customer->image_url_array = $image_camera_url_array;
            if (!$customer->save()) {
                $response = [
                    'message' => 'Error During Create Customer',
                ];
                return response()->json($response,404);
            }
            $event->customer_id = $customer->_id;
            $event->image_detection_url_array = $customer->image_url_array;
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
        }
        $event->image_camera_url_array = $image_camera_url_array;
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
        $response = [
            'message' => 'Event Created Successfully',
            'data' => $event,
        ];

        return response()->json($response, 201);
    }
    public function handleBase64ToUrl(string $base64_array)
    {
//        $path = 'images/'.$customer_id.'/';
//        if (!file_exists($path)) {
//            mkdir($path, 0777, true);
//        }
//
//        $this->base64_decode_image($image_camera_base64_array, $path);
        return 'url';
    }
    public function base64_decode_image(string $image_encode, string $path)
    {
        $path = $path.'xyz.txt';
        $myFile = fopen($path, "w");
        fwrite($myFile, $image_encode);
        fclose($myFile);
        $image_encode_from_url = fread(fopen($path, "r"), filesize($path));
        $image_url = $path.str_random(60).'.jpg';
        $decode = base64_decode($image_encode_from_url);
        file_put_contents($image_url, $decode);
    }
}
