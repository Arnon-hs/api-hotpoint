<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sessionid', 'questionid', 'name', 'desc', 'sessiondate', 'starttime', 'endtime', 'sort', 'location_name', 'locationid', 'openflag', 'visible'
    ];

    /**
     * Hide timestamps
     * @var bool
     */
    public $timestamps = false;

    /**
     * Table
     * @var string
     */
    public $table = 'session_list';

    /**
     * Primary key
     * @var integer
     */
    protected $primaryKey = 'sessionid';
}