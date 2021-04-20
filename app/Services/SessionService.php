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

    public function getSession()
    {
//        try {
            $res = $this->sessionRepository->getSessions();
//            $result = $res->only(['sessionid','name']);
//            $day1 = $res->where('sessiondate','2021-04-27');
//            $day2 = $res->where('sessiondate','2021-04-28');
//            $day3 = $res->where('sessiondate','2021-04-29');
//            $result_up = array('0' => $day1,'1' =>$day2,'2' =>$day3);
//            $result = $result_up[0];
//            for($j = 0; $j < count($result_up); $j++) {
//                for($i = 0; $i < count($result); $i++) {
        $a = [];
            foreach ($res as $key => $result){
                if(!isset($a[$result->sessiondate]))
                    $a[$result->sessiondate] = [];

                $start_time_ex = substr($result->starttime, 0, -3);
                $end_time_ex = substr($result->endtime, 0, -3);
                $a[$result->sessiondate][$key]['time'] = $start_time_ex . ' - ' . $end_time_ex;
                $a[$result->sessiondate][$key]['name'] = $result->name;
                $a[$result->sessiondate][$key]['sessionid'] = $result->sessionid;
                $a[$result->sessiondate][$key]['location_name'] = $result->location_name;
                unset($result->starttime);
                unset($result->endtime);
                unset($result->sort);
            }
//            dd($a);
//            }
//            foreach ($result_up as $result){
//                $result = '123';
//            }
//            $result['2021-04-27'] = $day1;
//            $result['2021-04-28'] = $day2;
//            $result['2021-04-29'] = $day3;
//            $result =  $res[0]['sessiondate'];
//            foreach ($res as $result){
//                for($i = 0; $i < count($result); $i++) {
//                    unset($result[$i]->start_time);
//                }
//            }




//            for($i = 0; $i < count($day1); $i++) {
////              if (!$result[$i]->starttime == null){
////              $result[$i]['sessiondate'] = $result[$i]->sessiondate;
//                $start_time = $day1[$i]->starttime;
//                $start_time_ex = substr($start_time, 0, -3);
//                $end_time = $day1[$i]->endtime;
//                $end_time_ex = substr($end_time, 0, -3);
//                $day1[$i]->time = $start_time_ex . ' - ' . $end_time_ex;
//                unset($day1[$i]->starttime);
//                unset($day1[$i]->endtime);
//                unset($day1[$i]->sessiondate);
//                unset($day1[$i]->sort);
//                unset($day1[$i]->questionid);
////                }
//            }





//        $result[] =
//        } catch (\Exception $e) {
//            Log::error($e->getMessage());
//            throw new InvalidArgumentException('Unable get sessions');
//        }

        return $a;
    }
}
