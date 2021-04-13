<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
//    protected $primaryKey = 'users_id';

    public function user(){
        return $this->belongsTo(User::class,'users_id','id');
    }
}
