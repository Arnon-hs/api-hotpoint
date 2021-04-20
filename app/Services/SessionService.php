<?php

namespace App\Services;

use App\Models\Session;
use App\Repositories\SessionRepository;
use Carbon\Carbon;
use InvalidArgumentException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SessionService
{
    protected $sessionRepository;

    public function __construct(SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }

    public function getSessions()//TODO
    {
        $sessions = $this->sessionRepository->getSessions();
        $result = [];
        foreach ($sessions as $key => $session) {
            if (!isset($result[$session->sessiondate]))
                $result[$session->sessiondate] = [];

            $start_time_ex = substr($session->starttime, 0, -3);
            $end_time_ex = substr($session->endtime, 0, -3);
            $result[$session->sessiondate][$key]['time'] = $start_time_ex . ' - ' . $end_time_ex;
            $result[$session->sessiondate][$key]['header'] = "Пленарная сессия";
            $result[$session->sessiondate][$key]['title'] = "Сервис в эпоху цифровизации";
            $result[$session->sessiondate][$key]['speakers'] = "Иван Иванов";
            $result[$session->sessiondate][$key]['streamId'] = 1;
            unset($session->starttime);
            unset($session->endtime);
            unset($session->sort);
        }
        return $result;
    }


    public function getSessionsPersonal()
    {
        $sessions = $this->sessionRepository->getSessionsPersonal();

        foreach ($sessions as $key => $session) {
            if(!isset($result_arr[$session->sessiondate]))
                $result_arr[$session->sessiondate] = [];

            $start_time_ex = substr($session->starttime, 0, -3);
            $end_time_ex = substr($session->endtime, 0, -3);
            $res['data'][$session->sessiondate][$key]['time'] = $start_time_ex . ' - ' . $end_time_ex;
            $res['data'][$session->sessiondate][$key]['title'] = $session->name;
            $res['data'][$session->sessiondate][$key]['speakers'] = $session->speaker_id;
            unset($session->starttime);
            unset($session->endtime);
            unset($session->sort);
        }

        return $sessions;
    }
}
