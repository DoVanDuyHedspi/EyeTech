<?php

namespace App\Http\Controllers\Api;

use App\Camera;
use App\Customer;
use App\Http\Requests\EventFormatFormRequest;
use App\Http\Requests\EventFormRequest;
use App\Event;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Integer;
use Validator;
use App\Http\Resources\Event as EventResource;

class EventController extends Controller
{
    protected $limitPage, $urlHeader, $pathHeader, $image_null_url;
    public function __construct()
    {
        $this->limitPage = 10;
        $image_null_url_body = 'images/cu/null.png';

        $this->urlHeader = 'http://202.191.56.249/';
        $this->pathHeader = '/var/www/html/';

        //$this->urlHeader = 'http://localhost/';
        //$this->pathHeader = '/Applications/MAMP/htdocs/';

        $this->image_null_url = $this->urlHeader . $image_null_url_body;
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

    public function formatEventForClient(EventFormatFormRequest $request)
    {
        // to fix error branch_id type is String from vuejs
        $branch_id = intval($request->input('branch_id'));
        $events = Event::where('branch_id', '=', $branch_id)
            ->orderBy('time_in', 'desc')
            ->get();

        $data = [];
        foreach ($events as $event)
        {
            $customer = Customer::find($event->customer_id);
            $camera = Camera::findOrFail($event->camera_id);

            $numberImageCamera = 1;
            $numberImageDetection = 1;
            $slice_image_camera = $this->handleImage($event->image_camera_url_array, $numberImageCamera);
            $slice_image_detection = $this->handleImage($event->image_detection_url_array, $numberImageDetection);
            $avatar = $this->checkImageNull($event->image_camera_url_array[0]);

            $timeInDefault = $event->time_in;
            $timeInHandle = $this->handleTimeIn($timeInDefault);

            $customer_edit_url = $request->input('route_header') . '/' . $event->customer_id . '/edit';
            $eventFormat = [
                'customer_id' => $customer->id,
                'customer_edit_url' => $customer_edit_url,
                'avatar' => $avatar,
                'name' => $customer->name,
                'type' => $customer->type,
                'camera' => $camera->name,
                'image_camera_url_array' => $slice_image_camera,
                'image_detection_url_array' => $slice_image_detection,
                'time_in' => $timeInHandle,
            ];
            array_push($data, $eventFormat);
        }

        $response = [
            'message' => 'List Event Formatted',
            'data' => $data
        ];

        return response()->json($response, 200);
    }

    public function formatQuickEventForClient(EventFormatFormRequest $request)
    {
        // to fix error branch_id type is String from vuejs
        $branch_id = intval($request->input('branch_id'));
        if ($request->has('number_field')) {
            $events = Event::where('branch_id', '=', $branch_id)
                ->orderBy('time_in', 'desc')
                ->take($request->input('number_field'))
                ->get();
        } else {
            $default_number_field = 5;
            $events = Event::where('branch_id', '=', $branch_id)
                ->orderBy('time_in', 'desc')
                ->take($default_number_field)
                ->get();
        }

        $data = [];
        foreach ($events as $event) {
            $customer = Customer::find($event->customer_id);

            $timeInDefault = $event->time_in;
            $timeInHandle = $this->handleTimeIn($timeInDefault);
            $slice_image_detection = $this->checkImageNull($event->image_detection_url_array[0]);
            $slice_image_camera = $this->checkImageNull($event->image_camera_url_array[0]);

            $quick_event_format = [
                'name' => $customer->name,
                'time_in' => $timeInHandle,
                'type' => $customer->type,
                'avatar' => $slice_image_detection,
                'image_camera_url_array' => $slice_image_camera,
            ];

            array_push($data, $quick_event_format);
        }

        $response = [
            'message' => 'List of quick events formatted',
            'data' => $data
        ];

        return response()->json($response, 200);
    }

    public function checkImageNull($imageUrl)
    {
        $imageUrlBody = str_replace( $this->urlHeader, '', $imageUrl );
        $pathImg = $this->pathHeader . $imageUrlBody;
        if (!file_exists($pathImg)) {
            $imageUrl = $this->image_null_url;
        }
        return $imageUrl;
    }

    public function handleImage($image_array_default, $quantity)
    {
        $slice_image_array = array_slice($image_array_default, 0, $quantity);
        $slice_image_array = is_null($slice_image_array) ? $this->image_null_url : $slice_image_array;
        for ($i=0; $i<$quantity; $i++) {
            $imageUrl = $slice_image_array[$i];
            $slice_image_array[$i] = $this->checkImageNull($imageUrl);
        }
        return $slice_image_array;
    }

    public function handleTimeIn($timeInDefault)
    {
        if ($this->validateDate($timeInDefault) == false) {
            return false;
        }
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $recentTime = date('Y-m-d H:i:s');

        $recentTimeToSeconds = time() - strtotime($recentTime);
        $timeInDefaultToSeconds = time() - strtotime($timeInDefault);

        $measureTime = round(($timeInDefaultToSeconds - $recentTimeToSeconds)/60);
        $measureTime = max($measureTime, 0);
        $measureHour = round($measureTime/60);
        $measureDay = round($measureHour/24);

        switch (true) {
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
                $displayTime = $timeInDefault;
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
