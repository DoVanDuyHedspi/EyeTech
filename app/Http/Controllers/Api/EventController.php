<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\EventFormRequest;
use App\Event;
use App\Http\Controllers\Controller;
use Validator;
use App\Http\Resources\Event as EventResource;

class EventController extends Controller
{
    public function index()
    {
        $limit = 10;
        $events = Event::paginate($limit);

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
        if (!$event) {
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
        $event = Event::find($id);
        if (!$event) {
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
        if (!$event) {
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
        $event = Event::find($id);
        if (!$event) {
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
}
