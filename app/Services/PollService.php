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
            $data = [];
            $polls = $this->pollRepository->allPolls();
            foreach ($polls as $key => $poll) {
                $data[$key]['id'] = $poll->id;
                $data[$key]['question'] = $poll->name;
                foreach ($poll->answers as $key_answer => $answer) {
                    $answer_id = $answer->id;
                    $data[$key]['answers'][$key_answer]['id'] = $answer_id;
                    $data[$key]['answers'][$key_answer]['answer_title'] = $answer->answer_title;

                    if(!empty($answer->link))
                        $data[$key]['answers'][$key_answer]['link'] = $answer->link;

                    if(!empty($answer->textarea) && (bool) $answer->textarea === true)
                        $data[$key]['answers'][$key_answer]['textarea'] = (bool) $answer->textarea;

                    if(!isset($data[$key]['rightAnswersId']) && empty($data[$key]['rightAnswersId']))
                        $data[$key]['rightAnswersId'] = [];

                    if($this->pollRepository->isQuiz($poll->id) && (int) $answer->true_answer === 1) {
                        $data[$key]['rightAnswersId'][] = $answer_id;
                    }
                }

                if(!empty($poll->getMessage) && (bool) $poll->getMessage === true)
                    $data[$key]['getMessage'] = (bool) $poll->getMessage;
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get polls ' .$e->getMessage());
        }

        return $data;
    }

    /**
     * @param $poll_id
     * @return mixed
     */
    public function getPollResult($poll_id)
    {
        try {
            $pollResult = $this->pollRepository->getPollResult($poll_id);
            if(empty($pollResult))
                throw new InvalidArgumentException('Empty poll results!');
            $polls = [];

            foreach ($pollResult as $user_answer) {
                if($user_answer->answer()->id === 355)
                    continue;

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
            throw new InvalidArgumentException('Unable get result! '.$e->getMessage());
        }
        return $result;
    }

    public function getPoll($poll_id)
    {
        $result = [];
        $pollsExtraId = [99, 100, 101, 102]; //айди вопросов 10, 11, 12, 13
        try {
            if (in_array($poll_id, $pollsExtraId)) {
                $result['data']['before'] = $this->getPollResult((int) $poll_id - 89);//хардкодим, зато надежно
                $result['data']['after'] = $this->getPollResult((int) $poll_id);
                $result['status'] = 'success';
            } else if ($this->pollRepository->isQuiz((int) $poll_id)) {
                $result['data']['winnersCount'] = $this->pollRepository->countCorrectUserAnswers((int) $poll_id);
                $result['data']['id'] = (int) $poll_id;
            } else if ($this->pollRepository->isOpenQuestion((int) $poll_id)) {
                $result['message'] = 'Thank you!';
            } else {
                $result['status'] = 'success';
                $result['data'] = $this->getPollResult((int) $poll_id);
                $result['data']['id'] = (int) $poll_id;
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get poll '.$e->getMessage());
        }
        return $result;
    }

    /**
     * Store User Answer
     * @param $data
     * @return array
     */
    public function storeUserAnswer($data)
    {
        //лишь бы работало
        $validate = Validator::make($data, [
            'poll_id' => 'required|exists:polls,id',
            'answer_id' => 'array',
            'answer_text' => 'min:2|max:1000'
        ]);

        if ($validate->fails()) {
            throw new InvalidArgumentException($validate->errors());
        }

        try {
            $result = [];
            $scoreRepository = new ScoreRepository(new Action());
            $pollsExtraId = [99, 100, 101, 102]; //айди вопросов 10, 11, 12, 13

            if(isset($data['answer_text']) && !empty($data['answer_text']))
                $pollAnswerUser = $this->pollRepository->storeUserAnswerOpen($data);
            else
                $pollAnswerUser = $this->pollRepository->storeUserAnswer($data);

            if (in_array($data['poll_id'], $pollsExtraId)) {
                $result['data']['before'] = $this->getPollResult((int) $pollAnswerUser->poll_id - 89);//хардкодим, зато надежно
                $result['data']['after'] = $this->getPollResult((int) $pollAnswerUser->poll_id);
                $result['message'] = 'Thank you!';

                //green balls
                $scoreRepository->storeActivity([
                    'title' => 'poll',
                    'attendee_id' => auth()->user()->attendee_id
                ]);
            } else if ($this->pollRepository->isQuiz((int) $pollAnswerUser->poll_id)) {
                $result['data']['winnersCount'] = $this->pollRepository->countCorrectUserAnswers((int) $pollAnswerUser->poll_id);
                $result['data']['id'] = (int) $pollAnswerUser->poll_id;
                $result['status'] = 'success';//(bool) $pollAnswerUser->answer()->true_answer ? 'Correct' : 'Wrong';
                //green balls
                $scoreRepository->storeActivity([
                    'title' => 'quiz',
                    'attendee_id' => auth()->user()->attendee_id
                ]);
            } else if ($this->pollRepository->isOpenQuestion((int) $pollAnswerUser->poll_id)) {
                $result['message'] = 'Thank you!';

                //green balls
                $scoreRepository->storeActivity([
                    'title' => 'poll',
                    'attendee_id' => auth()->user()->attendee_id
                ]);
            } else if ((int) $pollAnswerUser->poll_id === 9) {
                if($pollAnswerUser->answer()->true_answer)
                    $result['message'] = 'Давайте это сделаем вместе, компания Schneider Electric готова гарантировать данный результат финансово.';
                else
                    $result['message'] = 'Бесплатная консультация и индикативный расчёт по возможности снижения стоимости электроэнергии с финансовой гарантией результата.';
            } else {
                $result['status'] = 'success';
                $result['data'] = $this->getPollResult((int) $pollAnswerUser->poll_id);
                $result['data']['id'] = (int) $pollAnswerUser->poll_id;

                //green balls
                $scoreRepository->storeActivity([
                    'title' => 'poll',
                    'attendee_id' => auth()->user()->attendee_id
                ]);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable store user answer ' . $e->getMessage());
        }
        return $result;
    }

    /**
     * Store Evaluation
     * @param $data
     * @return mixed
     */
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
