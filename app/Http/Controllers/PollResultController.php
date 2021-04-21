<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Poll;
use App\Models\PollResult;
use App\Services\PollResultService;
use Illuminate\Http\Request;

class PollResultController extends Controller
{
    protected $pollResultService;

    /**
     * PollResultController constructor.
     * @param $pollResultService
     */
    public function __construct(PollResultService $pollResultService)
    {
        $this->middleware('auth:api');
        $this->pollResultService = $pollResultService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPollResult(Request $request)
    {
        try {
            $data = $request->only(['poll_id']);
            $pollResult = $this->pollResultService->getPollResult($data);
            $polls = [];
            foreach ($pollResult as $user_answer){
                $answer_title = $user_answer->answer()->answer_title;
                if(!isset($polls[$answer_title]))
                    $polls[$answer_title] = 0;
                $polls[$answer_title]++;
            }
            $titlePoll = Poll::find($data)->first()->name;
            $pollResult['data'] = [
                'title' => $titlePoll,
                'poll' => $polls
            ];
            $pollResult['status'] = 200;
        } catch (\Exception $e) {
            $pollResult = [
                'data' => $e->getMessage(),
                'status' => 500
            ];
        }
        return response()->json($pollResult['data'], $pollResult['status']);
    }

    public function all(Request $request)
    {
        try {
            $result = $this->pollResultService->all($request);
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
}
