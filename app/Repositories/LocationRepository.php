<?php

namespace App\Repositories;

use App\Models\Location;

class LocationRepository
{
    protected $location;
    
    public function __construct(Location $location)
    {
        $this->location = $location;
    }
    
    public function getLocationSetting($stream_id)
    {
        $streamSetting = $this->location::where('stream_id', $stream_id)->get();
        return $streamSetting;
    }
}
