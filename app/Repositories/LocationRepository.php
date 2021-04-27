<?php

namespace App\Repositories;

//use App\Models\Location;
use App\Models\Stream;

class LocationRepository
{
//    protected $location;

//    public function __construct(Location $location)
//    {
//        $this->location = $location;
//    }
    
    public function getLocationSetting($stream_id)
    {
        $stream = Stream::find($stream_id);
        $location = $stream->location();
        $settings = array_merge($stream->toArray(), $location->toArray());
        $settings['stream_id'] = $stream->id;
        unset($settings['id']);

        return $settings;
    }
}
