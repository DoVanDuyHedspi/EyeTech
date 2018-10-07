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
        $limit = 10;
        $customers = Customer::paginate($limit);
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
        $customer = new Customer();
        $customer->vector = $request->input('vector');
        $customer->image_url_array = $request->input('image_url_array');
        $customer->name = $request->input('name');
        $customer->age = $request->input('age');
        $customer->gender = $request->input('gender');
        $customer->telephone = $request->input('telephone');
        $customer->address = [
            'country' => $request->input('country'),
            'city' => $request->input('city'),
            'location' => $request->input('location'),
        ];
        $customer->favorites = $request->input('favorites');
        $customer->type = $request->input('type');
        $customer->note = $request->input('note');

        if (!$customer->save()) {
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
                        'country' => 'string',
                        'city' => 'string',
                        'location' => 'string',
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
                        'country' => 'string',
                        'city' => 'string',
                        'location' => 'string',
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
                        'country' => 'string',
                        'city' => 'string',
                        'location' => 'string',
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
                    'country' => 'string',
                    'city' => 'string',
                    'location' => 'string',
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
        $limit = 10;
        $projections = ['_id', 'vector'];
        $customers = Customer::paginate($limit, $projections);
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
    public function test()
    {
        $customer = Customer::find('5bba4369e00832c1b4dc4a5b');

        $json = [
            'country' => 'Viet nam',
            'city' => 'asdasdasd',
            'location' => 'asdasd',
        ];
        $json_decode = json_decode(response()->json($json));
        $addressJson = json_encode($customer->address, true);


        return $json_decode;
    }
}
