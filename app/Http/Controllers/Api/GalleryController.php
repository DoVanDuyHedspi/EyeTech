<?php

namespace App\Http\Controllers\Api;

use App\Customer;
use App\Event;
use App\Http\Requests\RemoveImageFormRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
            'message' => 'Delete image successfully!'
        ];

        return response()->json($response, 200);
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

}
