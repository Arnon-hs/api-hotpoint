<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poll;
use App\Services\PollService;

class PollController extends Controller
{
    protected $pollService;

    /**
     * PollResultController constructor.
     * @param $pollService
     */
    public function __construct(PollService $pollService)
    {
        $this->middleware('auth:api');
        $this->pollService = $pollService;
    }

    /**
     * TODO formatted
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()//TODO Pattern Repository!!!
    {
        $result = Poll::all();

        foreach ($result as $answer)
           $answer->answers;

        $res['data'] = $result;
        $res['status'] = 200;
        return response()->json($res['data'], $res['status']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeUserAnswer(Request $request)
    {
        try {
            $data = $request->only(['poll_id', 'answer_id']);
            $result = $this->pollService->storeUserAnswer($data);

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

    /**
     * @param $poll_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPollResult($poll_id)
    {
        try {
            $pollResult['data'] = $this->pollService->getPollResult($poll_id);// test
            $pollResult['status'] = 200;
        } catch (\Exception $e) {
            $pollResult = [
                'data' => $e->getMessage(),
                'status' => 500
            ];
        }
        return response()->json($pollResult['data'], $pollResult['status']);
    }
}
