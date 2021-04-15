<?php
namespace App\Repositories;

use App\Models\Session;
use Illuminate\Support\Facades\DB;

class SessionRepository
{
    protected $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getSessions()
    {
        return DB::table('session_list')->select(['sessionid','name','sessiondate','starttime','endtime','sort','location_name'])->orderBy('sort')->get();
    }

}
