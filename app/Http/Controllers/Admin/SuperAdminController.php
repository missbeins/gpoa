<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\upcoming_events;

class SuperAdminController extends Controller
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
        return view('admin.admin',compact('upcoming_events'));
        
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
            ->where('upcoming_events.studAffairs_approval','=','pending')
            ->paginate(5, ['*'], 'upcoming-events');        
        return view('admin.admin-approval',compact('upcoming_events'));
        
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
        
        return redirect(route('admin.admin.event-approval'));
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
        
        return redirect(route('admin.admin.event-approval'));
    }
}
