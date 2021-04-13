<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Speaker;
use App\Models\Poll;
use App\Models\UserAnswer;
use App\Models\QuestionAndAnswer;
use App\Services\SessionService;

class UserAnswerController extends Controller
{
//    protected $sessionService;

    public function __construct()
    {
        $this->middleware('auth:api');

    }

    public function all()
    {
        $result = User::first()->userAnswers();

//        foreach ($result as $answer){
//            $answer->Answers;
//        }
//        $res['data'] = $result;
//        $res['status'] = 200;
        return $result;
//        $res = $result->poll;
//        $result = Poll::all()->QuestionAndAnswer();
//        return $resultAnswer,$resultAnswer;
    }
//        try {
//            $result = $this->sessionService->getSession();
//            $res['data'] = $result;
//            $res['status'] = 200;
//        } catch (\Exception $e) {
//            $res = [
//                'data' => $e->getMessage(),
//                'status' => 500
//            ];
//        }
//
//        return response()->json($res['data'], $res['status']);
//    }
}
