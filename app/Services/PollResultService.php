<?php

namespace App\Services;

use App\Repositories\PollResultRepository;
use InvalidArgumentException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PollResultService
{
    protected $pollResultRepository;

    /**
     * PollResultService constructor.
     * @param $pollResultRepository
     */
    public function __construct(PollResultRepository $pollResultRepository)
    {
        $this->pollResultRepository = $pollResultRepository;
    }

    /**
     * @param $poll_id
     * @return mixed
     */
    public function getPollResult($poll_id)
    {
        try {
            $pollResult = $this->pollResultRepository->getPollResult($poll_id);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get result!');
        }
        return $pollResult;
    }

    public function all($data)
    {
        try {
            $validate = Validator::make($data, [
                'poll_id' => 'required'
            ]);
            if ($validate->fails()) {
                throw new InvalidArgumentException($validate->errors());
            }
            $result = $this->pollResultRepository->all($data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable set meeting');
        }
        return $result;
    }

}
