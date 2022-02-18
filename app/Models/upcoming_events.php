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
        'head_organization',
        'title',
        'objectives',
        'semester',
        'school_year',
        'participants',
        'partnerships',
        'venue',
        'projected_budget',
        'sponsor',
        'activity_type',
        'time',
        'date',
        'fund_source',
        'status',
        'adviser_approval',
        'studAffairs_approval',
        'partnership_status'
        
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
