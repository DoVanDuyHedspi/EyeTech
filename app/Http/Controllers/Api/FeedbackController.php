<?php

namespace App\Http\Controllers\Api;

use App\Branch;
use App\Http\Requests\FeedbackFormRequest;
use App\Http\Controllers\Controller;
use App\Feedback;
use App\Http\Requests\FormatFeedbackFormRequest;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $feedbacks = Feedback::all();

        $response = [
            'message' => 'All of feedback',
            'feedbacks' => $feedbacks
        ];

        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FeedbackFormRequest $request)
    {
        $data = $request->all();
        $feedbacks = Feedback::all();
        $isExist = false;
        foreach ($feedbacks as $feedback) {
            if ($data['event_id'] == $feedback->event_id) {
                $isExist = true;
                break;
            }
        }
        if ($isExist == true) {
            $response = [
                'message' => 'This event is existed in feedback',
            ];

            return response()->json($response, 400);
        }
        $feedback = new Feedback();
        $feedback->branch_id = $data['branch_id'];
        $feedback->event_id = $data['event_id'];
        $feedback->camera_id = $data['camera_name'];
        $feedback->status = $data['status'];
        $feedback->save();

        $response = [
            'message' => 'Feedback created successfully',
            'feedbacks' => $feedback
        ];

        return response()->json($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $feedback = Feedback::find($id);
        if (!$feedback) {
            $response = [
                'message' => 'Feedback does not exist',
            ];

            return response()->json($response, 404);
        }
        $response = [
            'message' => 'Info of feedback',
            'feedback' => $feedback,
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
    public function update(FeedbackFormRequest $request, $id)
    {
        $data = $request->all();
        $feedback = Feedback::find($id);
        if (!$feedback) {
            $response = [
                'message' => 'Feedback does not exist',
            ];

            return response()->json($response, 404);
        }
        if (!$feedback->update($data)) {
            $response = [
                'message' => 'Error: Update Fail',
            ];

            return response()->json($response, 404);
        }

        $response = [
            'message' => 'Feedback updated successfully',
            'feedback' => $feedback,
        ];

        return response()->json($response, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $feedback = Feedback::find($id);
        if (!$feedback) {
            $response = [
                'message' => 'Feedback does not exist',
            ];

            return response()->json($response, 404);
        }
        if (!$feedback->delete()) {
            $response = [
                'message' => 'Error: Delete Fail',
            ];

            return response()->json($response, 404);
        }
        $response = [
            'message' => 'Feedback destroy successfully',
        ];

        return response()->json($response, 200);
    }

    public function formatFeedbacks(FormatFeedbackFormRequest $request)
    {
        $data = $request->all();
        $feedbacks = Feedback::where('branch_id', '=', $data['branch_id'])->get();
        $branch = Branch::find($data['branch_id']);
        $branch_name = $branch->user-name;

        $data = [];
        foreach ($feedbacks as $feedback) {
            $value = [
                'event_id' => $feedback->event_id,
                'branch_name' => $branch_name,
                'camera_name' => $feedback->camera_name,
                'status' => $feedback->status
            ];

            array_push($data, $value);
        }

        $response = [
            'message' => 'List Feedbacks Format',
            'data' => $data,
        ];

        return response()->json($response, 200);
    }
}
