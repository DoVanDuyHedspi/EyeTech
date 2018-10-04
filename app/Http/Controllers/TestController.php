<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;

class TestController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('testindex', compact('customers'));
    }
    public function create()
    {
        return view('test');
    }
}
