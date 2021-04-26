<?php

namespace App\Http\Controllers;

use App\Models\Location;
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
        $this->middleware('auth:api',['except' => ['index']]);
        $this->pollService = $pollService;
    }

    public function index()
    {
        $polls = $this->pollService->allPolls();
        $locations = Location::all();

        return view('rooms', compact('polls', 'locations'));
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
            $data = $request->only(['poll_id', 'answer_id', 'time']);
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeUserEvaluation(Request $request)
    {
        try { //todo
            $data = $request->only(['stream_id', 'text', 'rating']);

            if($this->pollService->storeUserEvaluation($data)) {
                $result['status'] = 200;
                $result['data']['data'] = 'Data successfully entered!';
                $result['data']['status'] = 'success';
            }
            else{
                $result['status'] = 500;
                $result['data']['data'] = 'Unable store evaluation';
                $result['data']['status'] = 'error';
            }
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

    public function getPollResultBefore($poll_id)
    {
        try {
            $pollResultBefore['data'] = $this->pollService->getPollResultBefore($poll_id);
            $pollResultBefore['status'] = 200;
        } catch (\Exception $e) {
            $pollResultBefore = [
                'data' => $e->getMessage(),
                'status' => 500
            ];
        }
        return response()->json($pollResultBefore['data'], $pollResultBefore['status']);
    }

    public function getPollResultAfter($poll_id)
    {
        try {
            $pollResultAfter['data'] = $this->pollService->getPollResultAfter($poll_id);
            $pollResultAfter['status'] = 200;
        } catch (\Exception $e) {
            $pollResultAfter = [
                'data' => $e->getMessage(),
                'status' => 500
            ];
        }
        return response()->json($pollResultAfter['data'], $pollResultAfter['status']);
    }
}
