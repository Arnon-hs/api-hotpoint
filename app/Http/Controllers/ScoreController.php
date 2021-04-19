<?php

namespace App\Http\Controllers;

use App\Services\ScoreService;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    protected $scoreService;

    public function __construct(ScoreService $scoreService)
    {
        $this->middleware('auth:api', ['except' => ['update','index']]);
        $this->scoreService = $scoreService;
    }

    public function index()
    {
        $actions = $this->scoreService->allActions();
        return view('score', compact('actions'));
    }

    public function all()
    {
        try {
            $result['data'] = $this->scoreService->allActions();
            $result['status'] = 200;
        } catch(\Exception $e) {
            $result['data']['error'] = $e->getMessage();
            $result['status'] = 500;
        }
        return response()->json($result['data'], $result['status']);
    }

    public function update(Request $request)
    {
        try {
            $data = $request->only(['id', 'score_correct', 'score_wrong']);
            $result['data'] = $this->scoreService->updateAction($data);
            $result['status'] = 200;
        } catch(\Exception $e) {
            $result['data']['error'] = $e->getMessage();
            $result['status'] = 500;
        }
        return response()->json($result['data'], $result['status']);
    }

    /**
     * Store user activity
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $data = [
                'title' => $request->title,
                'attendee_id' => auth()->user()->attendee_id
            ];
            $result['data'] = $this->scoreService->storeActivity($data);
            $result['status'] = 200;
        } catch(\Exception $e) {
            $result['data']['error'] = $e->getMessage();
            $result['status'] = 500;
        }
        return response()->json($result['data'], $result['status']);
    }

    /**
     * Rating TOP-50
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function rating(Request $request)
    {
        try {
            $result['data'] = $this->scoreService->getRating();
            $result['status'] = 200;
        } catch(\Exception $e) {
            $result['data']['error'] = $e->getMessage();
            $result['status'] = 500;
        }
        return response()->json($result['data'], $result['status']);
    }
}
