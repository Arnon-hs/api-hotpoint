<?php

namespace App\Services;

use App\Repositories\LocationRepository;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;

class LocationService
{
    protected $locationRepository;
    
    /**
     * LocationService constructor.
     * @param $locationRepository
     */
    public function __construct(LocationRepository $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }
    
    /**
     * @param $stream_id
     * @return mixed
     */
    public function getLocationSetting($stream_id)
    {
        try {
            $result = $this->locationRepository->getLocationSetting($stream_id);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get result!');
        }
        return $result;
    }
}