<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Speaker;
use App\Models\Poll;
use App\Models\QuestionAndAnswer;
use App\Services\SessionService;

class PollController extends Controller
{
    protected $sessionService;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function all()//TODO Repository!!!
    {
        $result = Poll::all();

        foreach ($result as $answer)
           $answer->Answers;

        $res['data'] = $result;
        $res['status'] = 200;
        return $res;
    }
}
