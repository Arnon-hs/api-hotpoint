<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Speaker;

class SpeakerControllers extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function all()
    {
        return Speaker::all();
    }
}
