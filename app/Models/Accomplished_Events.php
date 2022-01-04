<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\upcoming_events;

class Accomplished_Events extends Model
{
    use HasFactory;
    protected $primaryKey = 'accomplished_event_id';
    protected $table = 'accomplished_events';

    public function upcoming_events()
    {
        return $this->belongsToMany(upcoming_events::class, 'events_for_ar', 'accomplished_event_id','upcoming_event_id' );
    }
}
