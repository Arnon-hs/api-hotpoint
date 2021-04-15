<?php

namespace App\Repositories;

use App\Models\Speaker;

class SpeakerRepository
{
    protected $speaker;

    public function __construct(Speaker $speaker)
    {
        $this->speaker = $speaker;
    }

    public function getSpeakers()
    {
        return $this->speaker::all()->toArray();
    }
}
