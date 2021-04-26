<?php

namespace App\Services;

use App\Models\Action;
use App\Models\Poll;
use App\Repositories\PollRepository;
use App\Repositories\ScoreRepository;
use InvalidArgumentException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PollService
{
    protected $pollRepository;

    /**
     * PollResultService constructor.
     * @param $pollRepository
     */
    public function __construct(PollRepository $pollRepository)
    {
        $this->pollRepository = $pollRepository;
    }

    public function allPolls()
    {
        try {
            $result = $this->pollRepository->allPolls();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get polls');
        }

        return $result;
    }

    /**
     * @param $poll_id
     * @return mixed
     */
    public function getPollResult($poll_id)
    {
        try {
            $pollResult = $this->pollRepository->getPollResult($poll_id);
            $polls = [];
            foreach ($pollResult as $user_answer){
                $answer_title = $user_answer->answer()->answer_title;
                if(!isset($polls[$answer_title]))
                    $polls[$answer_title] = 0;
                $polls[$answer_title]++;
            }
            $titlePoll = Poll::find($poll_id)->name;
            $result = [
                'title' => $titlePoll,
                'poll' => $polls
            ];
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get result!');
        }
        return $result;
    }

    public function storeUserAnswer($data)
    {
        try {
            $validate = Validator::make($data, [
                'poll_id' => 'required|exists:polls,id',
                'answer_id' => 'required|exists:answers,id'
            ]);

            if ($validate->fails())
                throw new InvalidArgumentException($validate->errors());

            $pollAnswerUser = $this->pollRepository->storeUserAnswer($data);
            $isQuiz = $this->pollRepository->isQuiz($data['poll_id']);

            $result = [];
            if($isQuiz) {
                $result['message'] = (bool) $pollAnswerUser->answer()->true_answer ? 'Correct' : 'Wrong';
                $result['data'] = $this->getPollResult($pollAnswerUser->poll_id);
            } else {
                $result['message'] = 'Thank you!';
                $result['data'] = null;
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable store user answer');
        }
        return $result;
    }

    public function storeUserEvaluation($data)
    {
        try {
            $validate = Validator::make($data, [
                'stream_id' => 'required|exists:locations,stream_id',
                'rating' => 'required|numeric',
                'text' => 'min:2|max:255'
            ]);

            if ($validate->fails())
                throw new InvalidArgumentException($validate->errors());

            $evaluation = $this->pollRepository->storeUserEvaluation($data);
            
            $data = [
                'title' => 'appraisal',
                'attendee_id' => auth()->user()->attendee_id
            ];
            $scoreRepository = new ScoreRepository(new Action());
            $scoreRepository->storeActivity($data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable store evaluation, '.$e->getMessage());
        }
        return $evaluation;
    }
}
