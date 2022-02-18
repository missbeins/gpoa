<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event_Partnerships extends Model
{
    use HasFactory;
    protected $table = 'event_partnerships';
    protected $primaryKey = 'event_partnership_id';
    protected $fillable = ['event_id','partnership_to'];
}
