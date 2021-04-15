<?php

namespace App\Services;

use App\Repositories\MeetingRepository;
use InvalidArgumentException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MeetingService
{
    protected $meetingRepository;

    /**
     * MeetingService constructor.
     * @param $meetingRepository
     */
    public function __construct(MeetingRepository $meetingRepository)
    {
        $this->meetingRepository = $meetingRepository;
    }

    public function getMeeting()
    {
        try {
            $result = $this->meetingRepository->getMeeting();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get meeting');
        }

        return $result;
    }

    public function setMeeting($data)
    {
        try {
            $result = $this->meetingRepository->setMeeting($data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable set meeting');
        }
        return $result;
    }

    public function updateMeeting($data)
    {
        try {
            $result = $this->meetingRepository->updateMeeting($data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable update meeting');
        }
        return $result;
    }

    public function deleteMeeting($data)
    {
        try {
            $result = $this->meetingRepository->deleteMeeting($data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable delete meeting');
        }
        return $result;
    }

}
