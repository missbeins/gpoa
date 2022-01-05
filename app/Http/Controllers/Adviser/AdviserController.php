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
            ->where('upcoming_events.advisers_approval','=','approved')
            ->where('upcoming_events.studAffairs_approval','=','approved')
            ->paginate(5, ['*'], 'upcoming-events');        
        return view('adviser.adviser',compact('upcoming_events'));
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function eventApproval()
    {
        $upcoming_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
            ->where('upcoming_events.status','=','upcoming')
            ->where('upcoming_events.advisers_approval','=','pending')
            ->paginate(5, ['*'], 'upcoming-events');        
        return view('adviser.event-approval',compact('upcoming_events'));
        
    }

    public function approved($id, Request $request){
        $request->validate([

            'title_of_activity' => ['required', 'string', 'max:100'],
            'date' => ['required', 'date'],
            'time' => ['required'],
             
        ]);
       
        $upcoming_events = upcoming_events::where('upcoming_event_id',$id)->update([

            'advisers_approval' => 'approved'

        ]);
        
        return redirect(route('adviser.adviser.event-approval'));
    }

    public function disapproved($id, Request $request){
        $request->validate([

            'title_of_activity' => ['required', 'string', 'max:100'],
            'date' => ['required', 'date'],
            'time' => ['required'],
             
        ]);
       
        $upcoming_events = upcoming_events::where('upcoming_event_id',$id)->update([

            'advisers_approval' => 'disapproved'

        ]);
        
        return redirect(route('adviser.adviser.event-approval'));
    }
}
