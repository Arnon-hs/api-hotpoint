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

    public function getSessionsUser()
    {
            $res = $this->sessionRepository->getSessionsUser();
        $result_arr = [];
            foreach ($res as $key => $result){
                if(!isset($result_arr[$result->sessiondate]))
                    $result_arr[$result->sessiondate] = [];
                $start_time_ex = substr($result->starttime, 0, -3);
                $end_time_ex = substr($result->endtime, 0, -3);
                $result_arr[$result->sessiondate][$key]['time'] = $start_time_ex . ' - ' . $end_time_ex;
                $result_arr[$result->sessiondate][$key]['name'] = $result->name;
                $result_arr[$result->sessiondate][$key]['sessionid'] = $result->sessionid;
                $result_arr[$result->sessiondate][$key]['location_name'] = $result->location_name;
                unset($result->starttime);
                unset($result->endtime);
                unset($result->sort);
            }
        return $result_arr;
    }
}
