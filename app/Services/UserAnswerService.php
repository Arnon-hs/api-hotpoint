<?php
namespace App\Services;

use App\Repositories\UserAnswerRepository;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;
use App\Models\UserAnswer;
use App\Models\Answer;

class UserAnswerService
{
    protected $userAnswerRepository;

    public function __construct(UserAnswerRepository $userAnswerRepository)
    {
        $this->userAnswerRepository = $userAnswerRepository;
    }

    public function getUserAnswer(Request $request)
    {//TODO refactor this method!!!
        try {
            $result = $this->userAnswerRepository->getUserAnswer();

            $result = new UserAnswer();
            $poll_id = $request->poll_id;
            $answer_id = $request->answer_id;
            $answer = Answer::all()->where('poll_id',$poll_id)->where('answer_id',$answer_id)->pluck('id');
            $result->answer_id = $answer[0];
            $result->users_id = auth()->user()->attendee_id;
            $result->save();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get user Answers');
        }
        return $result;
    }
}
