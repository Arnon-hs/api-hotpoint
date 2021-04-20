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
            'meeting_time' => $data['meeting_time'],
            'speakers_id' => $data['speakers_id'],
            'user_id' => $data['user_id']
        ]);
        return $result;
    }

    public function updateMeeting($data)
    {
        $meeting = $this->meeting::find($data['id']);
        $meeting->meeting_confirm = 1;
        $meeting->save();
        return $meeting->toArray();
    }

    public function deleteMeeting($data)
    {
        return $this->meeting::destroy($data['id']);
    }

}
