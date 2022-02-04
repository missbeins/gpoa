<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Disapproved_events extends Model
{
    use HasFactory;
    protected $table = 'disapproved_events';
    protected $primaryKey = 'disapproved_event_id';
    protected $fillable = ['reason','upcoming_event_id','disapproved_by']; 
    public function user(){
        return $this->hasOne(User::class, 'user_id', 'disapproved_by');
     }
}
