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

        return $customers;
    }
    public function store(CustomerFormRequest $request)
    {
        $customer = new Customer();
        $customer->image_url_array = $request->input('image_url_array');
        $customer->vector = $request->input('vector');
        $customer->name = $request->input('name');
        $customer->age = $request->input('age');
        $customer->gender = $request->input('gender');
        $customer->telephone = $request->input('telephone');
        $customer->address = $request->input('address');
        $customer->favorites = $request->input('favorites');
        $customer->type = $request->input('type');
        $customer->note = $request->input('note');

        $customer->save();

        return response()->json($customer,201);
    }
    public function show($id)
    {
        $customer = Customer::find($id);

        return $customer;
    }
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->update($request->all());

        return response()->json($customer, 200);
    }
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return response()->json($customer, 204);
    }
    /**
     * Api for phase 2
     */
    public function getDataForIdVector()
    {
        $customers = Customer::all('_id', 'vector');

        return $customers;
    }
    public function updateDataAfterChangeImageOrCustomer(UpdateDataAfterChangeImageOrCustomerFormRequest $request)
    {
        $customer_id = $request->input('customer_id');
        $vector = $request->input('vector');

        $customer = Customer::findOrFail($customer_id);
        $customer->update(['vector' => $vector]);

        return response()->json($customer,200);
    }
}
