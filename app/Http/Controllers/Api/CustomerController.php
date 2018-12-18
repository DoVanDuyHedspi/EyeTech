<?php

namespace App\Http\Controllers\Api;

use App\Customer;
use App\Event;
use App\Http\Requests\CustomerFormRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\NumberVistedBranchFormRequest;
use App\Http\Requests\VectorIdFormRequest;
use App\Http\Resources\Customer as CustomerResource;
use App\Http\Resources\CustomerIdVector as CustomerIdVectorResource;
use Illuminate\Support\Facades\Auth;
use Validator;

class CustomerController extends Controller
{
    protected $limitPage, $customerProfileUrl, $urlHeader, $pathHeader, $image_null_url;
    public function __construct()
    {
        $image_null_url_body = 'images/cu/null.png';
        $this->limitPage = 10;
//        $this->customerProfileUrlHeader = 'http://localhost/eyetech-client/customers/';
        $this->customerProfileUrlHeader = 'http://202.191.56.249/client/customers/';
        $this->urlHeader = 'http://202.191.56.249/';
        $this->pathHeader = '/var/www/html/';
        $this->image_null_url = $this->urlHeader . $image_null_url_body;
    }

    public function index()
    {
        $owner = Auth::user();
        $customers = Customer::where('owner_id', '=', $owner->id)
            ->paginate($this->limitPage);

        return (CustomerResource::collection($customers))
            ->additional([
                'info' => [
                    'message' => 'List Of All Customers',
                    'version' => '1.0',
                ]
            ])
            ->response()
            ->setStatusCode(200);
    }

    public function store(CustomerFormRequest $request)
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

        $customer = Customer::create($data);
        if (!$customer) {
            $response = [
                'message' => 'Error: Create Customer Fail'
            ];
            return response()->json($response, 404);
        }
        $customer->store_id = $data['store_id'];
        $customer->address = [
            'country' => $request->input('country'),
            'city' => $request->input('city'),
            'location' => $request->input('location'),
        ];
        $customer->save();

        return (new CustomerResource($customer))
            ->additional([
                'info' => [
                    'message' => 'Customer Created Successfully',
                    'version' => '1.0'
                ]
            ])
            ->response()
            ->setStatusCode(201);

    }

    public function show($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            $response = [
                'message' => 'Customer Does Not Exist',
            ];
            return response()->json($response, 404);
        }

        $image_url_array = $customer->image_url_array;
        foreach ($image_url_array as $url) {
            $avatar_url = $url;
            break;
        }

        $avatar = $this->checkImageNull($avatar_url);

        return (new CustomerResource($customer))
            ->additional([
                'info' => [
                    'message' => 'Customer Information',
                    'version' => '1.0',
                ],
                'avatar_url' => $avatar,
            ])
            ->response()
            ->setStatusCode(200);
    }

    public function update(CustomerFormRequest $request, $id)
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

        $customer = Customer::find($id);
        if (!$customer) {
            $response = [
                'message' => 'Customer Does Not Exist',
            ];

            return response()->json($response, 404);
        }
        if (!$customer->update($data)) {
            $response = [
                'message' => 'Error: Update Fail',
            ];
            return response()->json($response, 400);
        }
        $customer->address = [
            'country' => $request->input('country'),
            'city' => $request->input('city'),
            'location' => $request->input('location'),
        ];
        $customer->save();

        return (new CustomerResource($customer))
            ->additional([
                'info' => [
                    'message' => 'Customer Updated Successfully',
                    'version' => '1.0',
                ],
                'redirect' => $this->customerProfileUrl . $id,
            ])
            ->response()
            ->setStatusCode(201);
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);
        $events = Event::where('customer_id', '=', $id)->get();
        if (!$customer) {
            $response = [
                'message' => 'Customer Does Not Exist',
            ];

            return response()->json($response, 404);
        }
        foreach ($events as $event) {
            if (!$event->delete()) {
                $response = [
                    'message' => 'Error: Delete Event Of Customer Fail',
                ];

                return response()->json($response, 400);
            }
        }
        if (!$customer->delete()) {
            $response = [
                'message' => 'Error: Delete Customer Fail',
            ];

            return response()->json($response, 400);
        }
        $response = [
            'message' => 'Customer Deleted Successfully',
        ];

        return response()->json($response, 200);
    }

    public function getDataForIdVector(VectorIdFormRequest $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, $request->setRules());

        if ($validator->fails()) {
            $response = [
                'message' => 'Error: Request Params Is Not Invalid',
                'errors' => $validator->errors(),
            ];

            return response()->json($response, 400);
        }

//        $store = Store::findOrFail($data['store_id']);
//        $branches = $store->branches;
//        $branches_id = [];
//        foreach ($branches as $branch) {
//            array_push($branches_id, $branch->id);
//        }

        $projections = ['_id', 'vector'];
        $data = Customer::where('store_id', '=', $data['store_id'])
            ->paginate($this->limitPage, $projections);

        return (CustomerIdVectorResource::collection($data))
            ->additional([
                'info' => [
                    'message' => 'List Id And Vector Of All Customers',
                    'version' => '1.0'
                ],
            ])
            ->response()
            ->setStatusCode(200);
    }

    public function getNumberVistedBranch(NumberVistedBranchFormRequest $request)
    {
        $data = $request->all();
        $events = Event::where('customer_id', '=', $data['customer_id'])->get();

        $response = [
            'message' => 'Number visted',
            'numberVisted' => sizeof($events),
        ];

        return response()->json($response, 200);
    }

    public function handleRequest(CustomerFormRequest $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, $request->setRules());
        if ($validator->fails()) {
            return [false, $validator->errors()];
        }
        return [$data, $validator->errors()];
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
}
