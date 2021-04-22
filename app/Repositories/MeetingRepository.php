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

    public function getMeeting($sort = 'meeting_time', $order = 'asc')
    {
        $result = $this->meeting::orderBy($sort, $order)->get();
        return $result;
    }

    public function updateMeeting($data)
    {
        $meeting = $this->meeting::find($data['id']);
        $meeting->meeting_confirm = (int) $data['confirm'];
        $meeting->user_id = (int) $data['user_id'];
        $meeting->save();

        return $meeting;
    }

    public function deleteMeeting($data)
    {
        return $this->meeting::destroy($data['id']);
    }

}
