<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Poll;
use App\Services\PollService;
use Illuminate\Http\Request;

class RoomsController extends Controller
{
    protected $pollService;

    public function __construct(PollService $pollService)
    {
        $this->middleware('auth:api', ['except' => ['update','index']]);
        $this->pollService = $pollService;
    }

    /**
     * View
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $polls = $this->pollService->allPolls();
        $locations = Location::all();
//        $polls = Poll::all();
//        dd($polls);
//        return view('rooms');
        return view('rooms', compact('polls', 'locations'));
    }

}
