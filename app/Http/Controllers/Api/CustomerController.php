<?php

namespace App\Http\Controllers\Api;

use App\Customer;
use App\Http\Requests\CustomerFormRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\Customer as CustomerResource;
use App\Http\Resources\CustomerIdVector as CustomerIdVectorResource;
use Validator;

class CustomerController extends Controller
{
    public function index()
    {
        $limit = 10;
        $customers = Customer::paginate($limit);
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
        $customer = Customer::find($id);
        if (!$customer) {
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

    public function getDataForIdVector()
    {
        $limit = 10;
        $projections = ['_id', 'vector'];
        $data = Customer::paginate($limit, $projections);

        return (CustomerIdVectorResource::collection($data))
            ->additional([
                'info' => [
                    'message' => 'List Id And Vector Of All Customers',
                    'version' => '1.0'
                ]
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
