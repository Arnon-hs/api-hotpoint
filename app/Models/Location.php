<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'location_id', 'name', 'videoPath', 'videoPathEn', 'chat_id', 'chatWidget_id', 'stream_id'
    ];
    
    /**
     * Table
     * @var string
     */
    public $table = 'locations';
    public $primaryKey = 'location_id';
    
    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function polls()
    {
        return $this->hasMany(Poll::class, 'location_id', 'location_id');
    }

}
