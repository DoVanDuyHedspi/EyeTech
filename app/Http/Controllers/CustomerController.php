<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Http\Requests\CustomerFormRequest;
use App\Http\Requests\UpdateDataAfterChangeImageOrCustomerFormRequest;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        foreach ($customers as $customer) {
            $customer->view_customer = [
              'href' => 'api/v1/customers/' . $customer->_id,
              'method' => 'GET',
            ];
        }
        $response = [
            'message' => 'List Of All Customers',
            'data' => $customers,
        ];

        return response()->json($response, 200);
    }

    public function store(CustomerFormRequest $request)
    {
        $customer = Customer::create($request->all());
        if (!$customer) {
            $response = [
                'message' => 'Error: Create Customer Fail'
            ];
            return response()->json($response, 404);
        }
        $customer->view_customer = [
            'href' => 'api/v1/customers/' . $customer->_id,
            'method' => 'GET',
        ];
        $customer->destroy_customer = [
            'href' => 'api/v1/customers/' . $customer->_id,
            'method' => 'DELETE',
        ];
        $response = [
            'message' => 'Customer Created Successfully',
            'data' => $customer,
        ];

        return response()->json($response,201);
    }

    public function show($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            $response = [
                'message' => 'Customer Does Not Exist',
                'create_customer' => [
                    'href' => 'api/v1/customers',
                    'method' => 'POST',
                    'params' => [
                        'image_url_array' => 'string : required',
                        'vector' => 'string : required',
                        'name' => 'string',
                        'age' => 'int',
                        'gender' => 'string',
                        'telephone' => 'int',
                        'address' => [
                            'country' => 'string',
                            'city' => 'string',
                            'location' => 'string'
                        ],
                        'favorites' => 'string',
                        'type' => 'string',
                        'note' => 'string',
                    ],
                ],
            ];

            return response()->json($response, 404);
        }
        $customer->view_customers = [
            'href' => 'api/v1/customers',
            'method' => 'GET',
        ];
        $response = [
            'message' => 'Customer Information',
            'data' => $customer,
        ];

        return response()->json($response, 200);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            $response = [
                'message' => 'Customer Does Not Exist',
                'create_customer' => [
                    'href' => 'api/v1/customers',
                    'method' => 'POST',
                    'params' => [
                        'image_url_array' => 'string : required',
                        'vector' => 'string : required',
                        'name' => 'string',
                        'age' => 'int',
                        'gender' => 'string',
                        'telephone' => 'int',
                        'address' => [
                            'country' => 'string',
                            'city' => 'string',
                            'location' => 'string'
                        ],
                        'favorites' => 'string',
                        'type' => 'string',
                        'note' => 'string',
                    ],
                ],
            ];

            return response()->json($response, 404);
        }
        if (!$customer->update($request->all())) {
            $response = [
                'message' => 'Error: Update Fail',
            ];
            return response()->json($response, 404);
        }
        $customer->view_customer = [
            'href' => 'api/v1/customers/' . $customer->_id,
            'method' => 'GET',
        ];
        $customer->update_customer = [
            'href' => 'api/v1/customers/' . $customer->_id,
            'method' => 'PATCH',
        ];
        $response = [
            'message' => 'Customer Updated Successfully',
            'data' => $customer,
        ];

        return response()->json($response, 200);
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            $response = [
                'message' => 'Customer Does Not Exist',
                'create_customer' => [
                    'href' => 'api/v1/customers',
                    'method' => 'POST',
                    'params' => [
                        'image_url_array' => 'string : required',
                        'vector' => 'string : required',
                        'name' => 'string',
                        'age' => 'int',
                        'gender' => 'string',
                        'telephone' => 'int',
                        'address' => [
                            'country' => 'string',
                            'city' => 'string',
                            'location' => 'string'
                        ],
                        'favorites' => 'string',
                        'type' => 'string',
                        'note' => 'string',
                    ],
                ],
            ];

            return response()->json($response, 404);
        }
        if (!$customer->delete()) {
            $response = [
                'message' => 'Error: Delete Customer Fail',
            ];

            return response()->json($response, 404);
        }
        $response = [
            'message' => 'Customer Deleted Successfully',
            'create_customer' => [
                'href' => 'api/v1/customers',
                'method' => 'POST',
                'params' => [
                    'image_url_array' => 'string : required',
                    'vector' => 'string : required',
                    'name' => 'string',
                    'age' => 'int',
                    'gender' => 'string',
                    'telephone' => 'int',
                    'address' => [
                        'country' => 'string',
                        'city' => 'string',
                        'location' => 'string'
                    ],
                    'favorites' => 'string',
                    'type' => 'string',
                    'note' => 'string',
                ],
            ],
        ];

        return response()->json($response, 200);
    }

    public function getDataForIdVector()
    {
        $customers = Customer::all('_id', 'vector');
        foreach ($customers as $customer) {
            $customer->view_customer = [
                'href' => 'api/v1/customers/' . $customer->_id,
                'method' => 'GET'
            ];
        }
        $response = [
            'message' => 'List Id And Vector Of All Customers',
            'data' =>  $customers,
        ];

        return response()->json($response, 200);
    }
}
