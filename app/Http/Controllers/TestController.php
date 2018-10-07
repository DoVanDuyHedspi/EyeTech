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
    public function base64_encode_image(string $image_url, string $image_type)
    {
        if ($image_url) {
            $imgbinary = fread(fopen($image_url, "r"), filesize($image_url));
//            $decode = base64_decode(base64_encode($imgbinary));
//            file_put_contents('images/b.png', $decode);
            return base64_encode($imgbinary);
        }
    }
    public function base64_decode_image(string $image_binary_url)
    {
        $endcode_img = fread(fopen($image_binary_url, "r"), filesize($image_binary_url));
        $decode = base64_decode($endcode_img);
        file_put_contents('images/ee.jpg', $decode);
        echo gettype($endcode_img);
    }
    public function test_base64()
    {
        echo $this->base64_encode_image('images/a.png', 'png');
        exit();
    }
    public function test_decode()
    {
        $this->base64_decode_image('images/xyz.txt');
        exit();
    }
}
