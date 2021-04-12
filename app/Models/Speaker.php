<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'speakerid', 'questionid', 'speaker_fname', 'speaker_mname', 'speaker_lname', 'speaker_image'
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
    public $table = 'speakers';

    /**
     * Primary key
     * @var integer
     */
    protected $primaryKey = 'speakerid';
}