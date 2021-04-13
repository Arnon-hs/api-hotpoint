<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Services\MeetingService;
use http\Client\Request;

class MeetingController extends Controller
{
    protected $meetingService;

    /**
     * MeetingController constructor.
     * @param $meetingService
     */
    public function __construct(MeetingService $meetingService)
    {
        $this->middleware('auth:api', ['except' => ['index', 'update']]);
        $this->meetingService = $meetingService;
    }

    public function getMeeting()
    {
        try {
            $result = $this->meetingService->getMeeting();
            $result['data'] = $result;
            $result['status'] = 200;
        } catch (\Exception $e) {
            $result = [
                'data' => $e->getMessage(),
                'status' => 500
            ];
        }
        return response()->json($result['data'], $result['status']);
    }

    public function setMeeting($data)
    {
        try {
            $result = $this->meetingService->setMeeting($data);
            $result['data'] = $result;
            $result['status'] = 200;
        } catch (\Exception $e) {
            $result = [
                'data' => $e->getMessage(),
                'status' => 500
            ];
        }
        return response()->json($result['data'], $result['status']);
    }

    public function index()
    {
        $result = $this->meetingService->getMeeting();
        return view('meeting', compact('result'));
    }

    public function update(Request $request)
    {
        echo $request->key();
    }

    public function destroy()
    {

    }

}
