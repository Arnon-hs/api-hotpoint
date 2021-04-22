<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $fillable = [
        'name'
    ];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at', 'created_at'
    ];
    /**
     * Hide timestamps
     * @var bool
     */
    public $timestamps = false;

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
