<?php

namespace App\Http\Controllers\Adviser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\upcoming_events;

class AdviserController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $upcoming_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
            ->where('upcoming_events.status','=','upcoming')
            ->paginate(5, ['*'], 'upcoming-events');        
        return view('adviser.adviser',compact('upcoming_events'));
        
    }
}
