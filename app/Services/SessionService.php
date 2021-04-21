<?php

namespace App\Services;

use App\Models\Location;
use App\Repositories\SessionRepository;
use Illuminate\Support\Facades\DB;


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

    /**
     * Get Personal User Schedule
     * @return $data
     *
     */
    public function getSessionsPersonal()
    {
        $data = [];
        $sessions = $this->sessionRepository->getSessionsPersonal();
        foreach ($sessions as $key => $session) {
            if(!isset($result_arr[$session->sessiondate]))
                $result_arr[$session->sessiondate] = [];
            $location_pluck = Location::all()->where('location_id',$session->location_id)->pluck('name');

            $explode_speakers = explode(';' ,$session->speaker_ids);//работает,разбивает на масив
            $names = [];
                DB::table('speakers')->whereIn('speaker_id',$explode_speakers)->get('name')->each(function ($speaker_name) use (&$names){
                    $names[] = $speaker_name->name;
                })->toArray();
            $start_time_ex = substr($session->starttime, 0, -3);
            $end_time_ex = substr($session->endtime, 0, -3);

            $data[] = [
                'title' => $session->name,
                'time' => $start_time_ex . ' - ' . $end_time_ex,
                'location' => $location_pluck[0],
                'speakers' => implode(", " , $names)
            ];
        }
        return $data;
    }
}
