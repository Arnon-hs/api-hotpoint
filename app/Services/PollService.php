<?php

namespace App\Services;

use App\Models\Poll;
use App\Models\PollResult;
use App\Repositories\PollRepository;
use Carbon\Carbon;
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

            foreach ($pollResult as $user_answer) {
                $answer_title = $user_answer->answer()->answer_title;
                if (!isset($polls[$answer_title]))
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

    /**
     * @param $data
     * @return array
     */
    public function storeUserAnswer($data)
    {
        try {
            $validate = Validator::make($data, [
                'poll_id' => 'required|exists:polls,id',
                'answer_id' => 'required|exists:answers,id'
            ]);

            if ($validate->fails()) {
                throw new InvalidArgumentException($validate->errors());
            }
            $pollAnswerUser = $this->pollRepository->storeUserAnswer($data);
            $isQuiz = $this->pollRepository->isQuiz($data['poll_id']);

            date_default_timezone_set('UTC');
            $carbon = new Carbon();
            $carbon = Carbon::createFromFormat('Y-m-d H:i:s', '2021-04-29 17:30:00');
            $date = $data['time'];

            $pollId = [10, 11, 12, 13]; //айди вопросов из документа
            $result = [];

            if (in_array($data['poll_id'], $pollId)) {
                if ($carbon->gte($date)) { //Больше равно
                    $result['message'] = 'Before!';

                    $before = $this->pollRepository->getPollResultBefore($data['poll_id']);

                    $result['data'] = $before;
                } elseif ($carbon->lt($date)) { //Меньше
                    $result['message'] = 'After!';

                    $after = $this->pollRepository->getPollResultAfter($data['poll_id']);
                    $before = $this->pollRepository->getPollResultBefore($data['poll_id']);

                    $result['data']['after'] = $after;
                    $result['data']['before'] = $before;
                }
            } else if ($isQuiz) {
                $result['message'] = (bool)$pollAnswerUser->answer()->true_answer ? 'Correct' : 'Wrong';
                $result['data'] = $this->getPollResult($pollAnswerUser->poll_id);
            } else {
                $result['message'] = 'Thank you!';
                $result['data'] = $this->getPollResult($pollAnswerUser->poll_id);
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
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable store evaluation, ' . $e->getMessage());
        }
        return $evaluation;
    }
}
