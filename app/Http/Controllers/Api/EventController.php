<?php

namespace App\Http\Controllers\Api;

use App\Customer;
use App\Http\Requests\EventFormRequest;
use App\Event;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Resources\Event as EventResource;

class EventController extends Controller
{
    protected $limitPage;
    public function __construct()
    {
        $this->limitPage = 10;
    }

    public function index()
    {
        $owner = Auth::user();
        $events = Event::where('owner_id', '=', $owner->id)
            ->paginate($this->limitPage);

        return EventResource::collection($events)
            ->additional([
                'info' => [
                    'message' => 'List Of All Events',
                    'version' => '1.0'
                ]
            ])
            ->response()
            ->setStatusCode(200);
    }

    public function store(EventFormRequest $request)
    {
        $owner = Auth::user();

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

        $event = Event::create($data);
        $event->owner_id = $owner->id;
        if (!$event->save()) {
            $response = [
                'message' => 'Error: Create Event Fail',
            ];
            return response()->json($response, 404);
        }

        return (new EventResource($event))
            ->additional([
                'info' => [
                    'message' => 'Event Created Successfully',
                    'version' => '1.0'
                ]
            ])
            ->response()
            ->setStatusCode(201);
    }

    public function show($id)
    {
        $owner = Auth::user();

        $event = Event::find($id);
        if ((!$event) || ($event->owner_id != $owner->id)) {
            $response = [
                'message' => 'Event Does Not Exist',
            ];

            return response()->json($response, 404);
        }

        return (new EventResource($event))
            ->additional([
                'info' => [
                    'message' => 'Event Information',
                    'version' => '1.0'
                ]
            ])
            ->response()
            ->setStatusCode(200);
    }

    public function update(EventFormRequest $request, $id)
    {
        $owner = Auth::user();

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

        $event = Event::find($id);
        if ((!$event) || ($event->owner_id != $owner->id)) {
            $response = [
                'message' => 'Event Does Not Exist',
            ];

            return response()->json($response, 404);
        }
        if (!$event->update($data)) {
            $response = [
                'message' => 'Error: Update Event Fail',
            ];

            return response()->json($response, 404);
        }

        return (new EventResource($event))
            ->additional([
                'info' => [
                    'message' => 'Event Updated Successfully',
                    'version' => '1.0',
                ]
            ])
            ->response()
            ->setStatusCode(200);
    }

    public function destroy($id)
    {
        $owner = Auth::user();

        $event = Event::find($id);
        if ((!$event) || ($event->owner_id != $owner->id)) {
            $response = [
                'message' => 'Event Does Not Exist',
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
        ];

        return response()->json($response, 200);
    }

    public function handleRequest(EventFormRequest $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, $request->setRules());
        if ($validator->fails()) {
            return [false, $validator->errors()];
        }
        return [$data, $validator->errors()];
    }

    public function formatEventForClient()
    {
        $events = Event::all();
        $data = [];
        foreach ($events as $event)
        {
            $customer = Customer::find($event->customer_id);

            $eventFormat = [
                'name' => $customer->name,
                'type' => $customer->type,
                'time_in' => $event->time_in,
                'image_camera_url_array' => $event->image_camera_url_array,
                'image_detection_url_array' => $event->image_camera_url_array,
            ];
            array_push($data, $eventFormat);
        }
        $response = [
            'message' => 'List Event Formatted',
            'data' => $data
        ];
        return response()->json($response, 200);
    }
}
