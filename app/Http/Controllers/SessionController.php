<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Speaker;
use App\Services\SessionService;

class SessionController extends Controller
{
    protected $sessionService;

    public function __construct(SessionService $sessionService)
    {
//        $this->middleware('auth:api');
        $this->sessionService = $sessionService;
    }

    public function all()
    {
        try {
            $res['data'] = $this->sessionService->getSession();
            $res['status'] = 200;
        } catch (\Exception $e) {
            $res = [
                'data' => $e->getMessage(),
                'status' => 500
            ];
        }

        return response()->json($res['data'], $res['status']);
    }
}
