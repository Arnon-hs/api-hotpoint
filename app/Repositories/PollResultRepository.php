<?php

namespace App\Repositories;

use App\Models\PollResult;

class PollResultRepository
{
    protected $pollResult;

    /**
     * PollResultRepository constructor.
     * @param $pollResult
     */
    public function __construct(PollResult $pollResult)
    {
        $this->pollResult = $pollResult;
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

    public function all($data)
    {
        try {
            $validate = Validator::make($data, [
                'poll_id' => 'required|'
            ]);
            if ($validate->fails()) {
                throw new InvalidArgumentException($validate->errors());
            }
            $result = $this->pollResult::all();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get all result');
        }
        return $result;
    }
}
