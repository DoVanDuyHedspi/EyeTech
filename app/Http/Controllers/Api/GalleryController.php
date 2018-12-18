<?php

namespace App\Http\Controllers\Api;

use App\Customer;
use App\Event;
use App\Feedback;
use App\Http\Requests\InsertImageFormRequest;
use App\Http\Requests\RemoveImageFormRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\GuzzleException;


class GalleryController extends Controller
{
    protected $urlHeader, $pathHeader;

    public function __construct()
    {
        $this->urlHeader = 'http://202.191.56.249/';
        $this->pathHeader = '/var/www/html/';
    }

    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            $response = [
                'message' => 'Customer does not exist!',
            ];

            return response()->json($response, 404);
        }

        $galleries = $customer->image_url_array;

        $response = [
            'message' => 'Galleries image of ' . $customer->name,
            'galleries' => $galleries,
            'quantily' => sizeof($galleries),
        ];

        return response()->json($response, 200);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function removeImage(RemoveImageFormRequest $request)
    {
        $data = $request->all();

        $this->destroyImage($data['image_url']);
        $this->updateImageUrlArrayCustomer($data['customer_id'], $data['image_url']);
        $this->updateImageUrlArrayEvent($data['customer_id'], $data['image_url']);

        $response = [
            'message' => 'Delete image successfully!',
        ];

        return response()->json($response, 200);
    }

    public function insertImage(Request $request)
    {
        $data = $request->all();

        $old_image_base64_array = $this->getOldImageBase64Array($data['customer_id']);
        $new_image_base64_array = $data['new_image_base64_url'];

        //update customer vector, image_url_array
        $this->handleNewImageBase64Array($data['customer_id'], $old_image_base64_array, $new_image_base64_array);
        $this->updateImageDetectEvent($data['customer_id']);

        $response = [
            'message' => 'Insert images successfully!'
        ];

        return response()->json($response, 200);
    }

    public function testUpload(Request $request)
    {
//        $feedback = new Feedback();
//        $feedback->status = $_POST;
//        $feedback->save();
//
//        $response = [
//            'message' => 'test upload',
//        ];
//
//        print_r($_POST['customer_id']);
//        print_r($_FILES);
//        return response()->json($response, 200);

        if($request->file('file'))
        {
            $image = $request->file('file');
            $name = time().$image->getClientOriginalName();
            $image->move(public_path().'/images', $name);
        }

        $image= new Image();
        $image->image_name = $name;
        $image->save();

        return response()->json(['success' => 'You have successfully uploaded an image'], 200);


    }

    public function updateImageDetectEvent($customer_id)
    {
        $customer = Customer::find($customer_id);
        $events = Event::where('customer_id', '=', $customer_id)->get();

        foreach ($events as $event) {
            $event->image_detection_url_array = $customer->image_url_array;
            $event->save();
        }
    }

    public function handleNewImageBase64Array($customer_id, $old_array, $new_array)
    {
        $customer = Customer::find($customer_id);
        $client = new \GuzzleHttp\Client();
        try {
            $res = $client->request('POST', 'http://202.191.56.249:8080/embed', [
                'form_params' => [
                    'old_image_base64_array' => $old_array,
                    'new_image_base64_array' => $new_array,
                ]
            ]);

        } catch (GuzzleException $e) {
            //
        }
        $data = json_decode($res->getBody()->getContents());
        $new_image_base64_array = $data->new_image_base64_array;
        $customer->image_url_array = $this->generateImagesUrl($customer_id, $new_image_base64_array);
        $customer->vector = $data->vector;
        $customer->save();
    }

    public function generateImagesUrl($customer_id, $new_image_base64_array)
    {
        $customer = Customer::find($customer_id);
        $image_url_array = $customer->image_url_array;

        foreach ($new_image_base64_array as $image_base64) {
            $image_base64_decode = base64_decode($image_base64);
            $pathBody = 'images/cu/' . $customer_id . '/';
            $path = $this->pathHeader . $pathBody;

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $imagePathBody = str_random(10) . '.jpg';
            $imagePath = $path . $imagePathBody;
            if (file_put_contents($imagePath, $image_base64_decode)) {
                $image_url = $this->urlHeader . $pathBody . $imagePathBody;
                array_push($image_url_array, $image_url);
            }
        }

        return $image_url_array;
    }

    public function destroyImage($imageUrl)
    {
        $imageUrlBody = str_replace($this->urlHeader, '', $imageUrl);
        $pathImg = $this->pathHeader . $imageUrlBody;
        if (file_exists($pathImg)) {
            unlink($pathImg) or die('Cannot delete file');
        }
    }

    public function updateImageUrlArrayCustomer($customer_id, $image_url)
    {
        $customer = Customer::find($customer_id);
        $image_url_array = $customer->image_url_array;
        foreach ($image_url_array as $index=>$url) {
            if ($url == $image_url) {
                //remove from array
                unset($image_url_array[$index]);
                break;
            }
        }
        $customer->image_url_array = $image_url_array;
        $customer->save();
        $this->updateCustomerVector($customer_id, $image_url_array);
    }

    public function updateImageUrlArrayEvent($customer_id, $image_url)
    {
        $events = Event::where('customer_id', '=', $customer_id)->get();

        foreach ($events as $event) {
            $image_detection_url_array = $event->image_detection_url_array;
            $image_camera_url_array = $event->image_camera_url_array;

            foreach ($image_detection_url_array as $index=>$url) {
                if ($url == $image_url) {
                    //remove from array
                    unset($image_detection_url_array[$index]);
                    break;
                }
            }

            foreach ($image_camera_url_array as $index=>$url) {
                if ($url == $image_url) {
                    //remove from array
                    unset($image_camera_url_array[$index]);
                    break;
                }
            }
            $event->image_detection_url_array = $image_detection_url_array;
            $event->image_camera_url_array = $image_camera_url_array;
            $event->save();
        }
    }

    public function getOldImageBase64Array($customer_id)
    {
        $customer = Customer::find($customer_id);
        $image_base64_array = [];
        foreach ($customer->image_url_array as $url) {
            $imageUrlBody = str_replace($this->urlHeader, '', $url);
            $pathImg = $this->pathHeader . $imageUrlBody;

            if(file_exists($pathImg)) {
                $file = fopen($pathImg, 'r') or die("Unable to open file!");
                $image = fread($file, filesize($pathImg));
                $base64_image = base64_encode($image);
                array_push($image_base64_array, $base64_image);
                fclose($file);
            }
        }
        return $image_base64_array;
    }

    public function updateCustomerVector($customer_id)
    {
        $customer = Customer::find($customer_id);
        $image_base64_array = $this->getOldImageBase64Array($customer_id);

        $client = new \GuzzleHttp\Client();
        try {

            $res = $client->request('POST', 'http://103.63.108.26:8080/embed', [
                'form_params' => [
                    'old_image_base64_array' => json_encode($image_base64_array),
                ]
            ]);

        } catch (GuzzleException $e) {
            //
        }
        $data = json_decode($res->getBody()->getContents());
        $customer->vector = $data->vector;
        $customer->save();
    }
}
