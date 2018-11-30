<?php

namespace App\Http\Controllers\Api;

use App\Customer;
use App\Http\Requests\EventFormRequest;
use App\Event;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
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

    public function formatEventForClient(Request $request)
    {
        $events = Event::where('camera_id', '=', $request->input('camera_id'))->get();
        $data = [];
        $numberImageCamera = 2;
        $numberImageDetection = 2;
        $image_null_path = 'images/cu/null.png';
        $dataEmptyString = 'Data is empty';

        foreach ($events as $event)
        {
            $timeInFormat = $event->time_in;
            $customer = Customer::find($event->customer_id);
            $name = $customer->name;
            $type = $customer->type;
            $favorites = $customer->favorites;

            $slice_image_camera = array_slice($event->image_camera_url_array, 0, $numberImageCamera);
            $slice_image_detection = array_slice($event->image_detection_url_array, 0, $numberImageDetection);

            for ($i=0; $i<$numberImageCamera; $i++) {
                $pathImg = $slice_image_camera[$i];
                if (!file_exists($pathImg)) {
                    $slice_image_camera[$i] = $image_null_path;
                }
            }

            for ($i=0; $i<$numberImageDetection; $i++) {
                $pathImg = $slice_image_detection[$i];
                if (!file_exists($pathImg)) {
                    $slice_image_detection[$i] = $image_null_path;
                }
            }

            $timeInHandle = $this->handleTimeIn($timeInFormat);

            if ($name === null) $name = $dataEmptyString;
            if ($type === null) $type = $dataEmptyString;
            if ($favorites === null) $favorites = $dataEmptyString;
            if ($slice_image_camera === null) $slice_image_camera = $image_null_path;
            if ($slice_image_detection === null) $slice_image_detection = $image_null_path;
            if ($timeInHandle === null) $timeInHandle = $dataEmptyString;

            $eventFormat = [
                'name' => $name,
                'type' => $type,
                'time_in' => $timeInHandle,
                'favorites' => $favorites,
                'image_camera_url_array' => $slice_image_camera,
                'image_detection_url_array' => $slice_image_detection,
            ];
            array_push($data, $eventFormat);
        }

        $response = [
            'message' => 'List Event Formatted',
            'data' => $data
        ];
        return response()->json($response, 200);
    }

    public function handleTimeIn($timeInFormat)
    {
        if ($this->validateDate($timeInFormat) == false) {
            return false;
        }
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $recentTime = date('Y-m-d H:i:s');

        $recentTimeToSeconds = time() - strtotime($recentTime);
        $timeInFormatToSeconds = time() - strtotime($timeInFormat);

        $measureTime = round(($timeInFormatToSeconds - $recentTimeToSeconds)/60);
        $displayTime = '';
        $measureHour = round($measureTime/60);
        $measureDay = round($measureHour/24);

        switch ($measureTime) {
            case ($measureTime == 0):
                $displayTime = 'Now';
                break;
            case ($measureTime < 60):
                $displayTime = $measureTime . ' minutes ago';
                break;
            case ($measureTime >= 60 && $measureTime<1440):
                $displayTime = $measureHour . ' hour ago';
                break;
            case ($measureTime >= 1440 && $measureTime<=10080):
                $displayTime = $measureDay . ' day ago';
                break;
            default:
                $displayTime = $timeInFormat;
                break;
        }

        return $displayTime;
    }

    public function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}
