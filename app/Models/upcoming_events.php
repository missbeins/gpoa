<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Accomplished_Events;

class upcoming_events extends Model
{
    use HasFactory;
    protected $primaryKey = 'upcoming_event_id';
    protected $table = 'upcoming_events';

    protected $fillable = [
        
        'organization_id',
        'title_of_activity',
        'objectives',
        'semester',
        'school_year',
        'participants',
        'partnerships',
        'venue',
        'projected_budget',
        'sponsor',
        'type_of_activity',
        'time',
        'date',
        'fund_sourcing',
        
    ];
    /**
     * 
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function accomplished_events()
    {
        return $this->belongsToMany(Accomplished_Events::class, 'events_for_ar', 'upcoming_event_id', 'accomplished_event_id');
    }
 
}
