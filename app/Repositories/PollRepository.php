<?php

namespace App\Repositories;

use App\Models\Poll;
use App\Models\PollResult;

class PollRepository
{
    protected $pollResult,$poll;

    /**
     * PollRepository constructor.
     * @param $pollResult
     */
    public function __construct(PollResult $pollResult, Poll $poll)
    {
        $this->pollResult = $pollResult;
        $this->poll = $poll;
    }

    public function allPolls()
    {
        return $this->poll::all();
    }

    /**
     * @param $poll_id
     * @return mixed
     */
    public function getPollResult($poll_id)
    {
        $pollResults = $this->pollResult::where('poll_id', $poll_id)->get();
        return $pollResults;
    }

    /**
     * @param $data
     * @return PollResult|null
     */
    public function storeUserAnswer($data)
    {
        try {
            $pollResult = new PollResult();
            $pollResult->user_id = auth()->user()->attendee_id;
            $pollResult->answer_id = $data['answer_id'];
            $pollResult->poll_id = $data['poll_id'];
            $pollResult->save();

            $result = $pollResult->fresh();
        } catch (\Exception $e){
            throw new \InvalidArgumentException($e->getMessage());
        }

        return $result;
    }

    /**
     * @param $poll_id
     * @return bool
     */
    public function isQuiz($poll_id)
    {
        $isQuiz = false;

        Poll::find($poll_id)->answers->each(function ($element) use (&$isQuiz){
            if ((int) $element->true_answer === 1) {
                $isQuiz = !$isQuiz;
                return false;
            }
        });

        return (bool) $isQuiz;
    }
}
