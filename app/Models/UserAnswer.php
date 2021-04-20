<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
//    protected $primaryKey = 'users_id';
    /**
     * Hide timestamps
     * @var bool
     */
    public $timestamps = false;

    public function User(){
        return $this->belongsTo(User::class,'users_id','attendee_id');
    }

    public function Answer(){
        return $this->belongsTo(Answer::class,'answer_id','id');
    }
}
