<?php

namespace App\Repositories;

use App\Models\UserAnswer;

class UserAnswerRepository
{
    protected $userAnswer;

    public function __construct(UserAnswer $userAnswer)
    {
        $this->userAnswer = $userAnswer;
    }

    public function getUserAnswer()
    {
        return $this->userAnswer::all()->toArray();
    }
}
