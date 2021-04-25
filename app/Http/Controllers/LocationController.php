<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Services\LocationService;

class LocationController extends Controller
{
    protected $locationService;
    
    /**
     * LocationController constructor.
     * @param $locationService
     */
    public function __construct(LocationService $locationService)
    {
        $this->middleware('auth:api');
        $this->locationService = $locationService;
    }
    
    /**
     * @param $stream_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStreamSettings($stream_id)
    {
        try {
            $result['data'] = $this->locationService->getLocationSetting($stream_id);// test
            $result['status'] = 200;
        } catch (\Exception $e) {
            $result = [
                'data' => $e->getMessage(),
                'status' => 500
            ];
        }
        return response()->json($result['data'], $result['status']);
    }
}
