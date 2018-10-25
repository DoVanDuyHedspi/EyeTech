<?php

namespace App\Http\Controllers\Api;

use App\Customer;
use App\Http\Requests\CustomerFormRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\Customer as CustomerResource;
use App\Http\Resources\CustomerIdVector as CustomerIdVectorResource;
use App\Store;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class CustomerController extends Controller
{
    protected $limitPage;
    public function __construct()
    {
        $this->limitPage = 10;
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
        $owner = Auth::user();
        $customer = Customer::find($id);

        if ((!$customer) || ($customer->owner_id != $owner->id)) {
            $response = [
                'message' => 'Customer Does Not Exist',
            ];
            return response()->json($response, 404);
        }

        return (new CustomerResource($customer))
            ->additional([
                'info' => [
                    'message' => 'Customer Information',
                    'version' => '1.0',
                ]
            ])
            ->response()
            ->setStatusCode(200);
    }

    public function update(CustomerFormRequest $request, $id)
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

        $customer = Customer::find($id);
        if ((!$customer) || ($customer->owner_id != $owner->id)) {
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

        return (new CustomerResource($customer))
            ->additional([
                'info' => [
                    'message' => 'Customer Updated Successfully',
                    'version' => '1.0',
                ]
            ])
            ->response()
            ->setStatusCode(201);
    }

    public function destroy($id)
    {
        $owner = Auth::user();

        $customer = Customer::find($id);
        if ((!$customer) || ($customer->owner_id != $owner->id)) {
            $response = [
                'message' => 'Customer Does Not Exist',
            ];

            return response()->json($response, 404);
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

    public function getDataForIdVector(Request $request)
    {
        $store_id = $request->input('store_id');
        $store = Store::findOrFail($store_id);
        $cameras = $store->users()->get();

        $projections = ['_id', 'vector'];
        $data = Customer::where('store_id', '=', $store_id)
            ->paginate($this->limitPage, $projections);

        return (CustomerIdVectorResource::collection($data))
            ->additional([
                'cameras' => $cameras,
                'info' => [
                    'message' => 'List Id And Vector Of All Customers',
                    'version' => '1.0'
                ],
            ])
            ->response()
            ->setStatusCode(200);
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
}
