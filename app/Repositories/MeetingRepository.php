<?php

namespace App\Repositories;

use App\Models\Meeting;

class MeetingRepository
{
    protected $meeting;

    /**
     * MeetingRepository constructor.
     * @param $meeting
     */
    public function __construct(Meeting $meeting)
    {
        $this->meeting = $meeting;
    }

    public function getMeeting()
    {
        $result = $this->meeting::all();
        return $result;
    }

    public function setMeeting($data)
    {
        $result = $this->meeting::create([
            'meeting_time' => $data['time'],
            'speaker_id' => $data['speaker_id'],
            'meeting_confirm' => $data['confirm']
        ]);
        return $result;
    }

}
