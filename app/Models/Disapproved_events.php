<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disapproved_events extends Model
{
    use HasFactory;
    protected $table = 'disapproved_events';
    protected $primaryKey = 'disapprved_event_id';
    protected $fillable = ['reason','upcoming_event_id','disapproved_by']; 
}
