<?php

namespace App\Http\Controllers;

use App\Services\UserAnswerService;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Speaker;
use App\Models\Poll;
use App\Models\UserAnswer;
use App\Models\Answer;
use App\Services\SessionService;

class UserAnswerController extends Controller
{

    protected $userAnswerService;

    public function __construct(UserAnswerService $userAnswerService)
    {
        $this->middleware('auth:api');
        $this->userAnswerService = $userAnswerService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function all(Request $request)//TODO validate in Service, return response()->json();
    {
        //validate incoming request
        $this->validate($request, [
            'poll_id' => 'required|integer|exists:answers',
            'answer_id' => 'required|integer|exists:answers',
        ]);

        try
        {
            $result =  $this->userAnswerService->getUserAnswer($request);
            $res['data'] = $result;
            $res['status'] = '201';
        }
        catch (\Exception $e){
            $res = [
                'data' => $e->getMessage(),
                'status' => 500
            ];
        }
        return $res;
    }
}
