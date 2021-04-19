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
        'speaker_id', 'questionid', 'speaker_fname', 'speaker_mname', 'speaker_lname', 'speaker_image', 'speaker_companies', 'speaker_titles'
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
    protected $primaryKey = 'speaker_id';
}