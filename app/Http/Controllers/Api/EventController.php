<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\ResultFaceDetectionFormRequest;
use App\Event;
use App\Http\Controllers\Controller;

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
}
