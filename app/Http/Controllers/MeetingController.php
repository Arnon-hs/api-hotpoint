<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Services\MeetingService;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    protected $meetingService;

    /**
     * MeetingController constructor.
     * @param $meetingService
     */
    public function __construct(MeetingService $meetingService)
    {
        $this->middleware('auth:api', ['except' => ['index', 'update', 'destroy', 'setMeeting']]);
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

    public function setMeeting(Request $request)
    {
        try {
            $result = $this->meetingService->setMeeting($request);
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
        if ($request['action'] == 'confirm') {
            try {
                $result = $this->meetingService->updateMeeting($request);
                $result['data'] = $result;
                $result['status'] = 200;
            } catch (\Exception $e) {
                $result = [
                    'data' => $e->getMessage(),
                    'status' => 500
                ];
            }
        }
        return response()->json($result['data'], $result['status']);
    }

    public function destroy(Request $request)
    {
        if ($request['action'] == 'delete') {
            try {
                $result = $this->meetingService->deleteMeeting($request);
                $result['data'] = $result;
                $result['status'] = 200;
            } catch (\Exception $e) {
                $result = [
                    'data' => $e->getMessage(),
                    'status' => 500
                ];
            }
        }
        return response()->json($result['data'], $result['status']);
    }

}
