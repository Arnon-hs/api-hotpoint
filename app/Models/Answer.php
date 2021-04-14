<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class QuestionAndAnswer extends Model
{
    protected $primaryKey = 'id';

    public function poll(){
        return $this->belongsTo(Poll::class, 'poll_id', 'id');
    }
}
