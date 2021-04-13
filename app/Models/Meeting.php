<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'id', 'meeting_time', 'speakers_id', 'meeting_confirm'
    ];

    public $timestamps = false;
    public $table = 'meeting';
    protected $primaryKey = 'id';

    public function speaker()
    {
        return $this->hasOne(Speaker::class, 'speaker_id', 'speakers_id')->first();
    }
}
