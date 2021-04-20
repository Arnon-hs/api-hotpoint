<?php

namespace App\Http\Controllers;

use App\Services\UserAnswerService;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Speaker;
use App\Models\Poll;
use App\Models\UserAnswer;
use App\Models\Answer;
use App\Models\Session;
use App\Services\SessionService;

class UserScheduleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */

    public function all(Request $request)
    {
        try
        {
            $token_result = auth()->user()->sessions;
            $result_arr = [];
            foreach ($token_result as $key => $result){
                if(!isset($result_arr[$result->sessiondate]))
                    $result_arr[$result->sessiondate] = [];

                $start_time_ex = substr($result->starttime, 0, -3);
                $end_time_ex = substr($result->endtime, 0, -3);
                $result_arr[$result->sessiondate][$key]['time'] = $start_time_ex . ' - ' . $end_time_ex;
                $result_arr[$result->sessiondate][$key]['title'] = $result->name;
                $result_arr[$result->sessiondate][$key]['speakers'] = $result->speaker_id;
                unset($result->starttime);
                unset($result->endtime);
                unset($result->sort);
            }

            $res['data'] = $result_arr;
            $res['status'] = '200';
        }
        catch (\Exception $e){
            $res = [
                'data' => $e->getMessage(),
                'status' => 500
            ];
        }
        return response()->json($res['data'], $res['status']);
    }
}
