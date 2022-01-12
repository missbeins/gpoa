<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\upcoming_events;

class SuperAdminController extends Controller
{
    /**
    * @param Array $roles, String $role
    * Function to search for a role under 'role' column in $roles Array 
    * Return Array Key if found, False if not
    * @return True: Integer, False: Boolean
    */ 
   private function hasRole($roles, $role)
   {
       return array_search($role, array_column($roles, 'role'));
   }
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()

   {
       //check if USER has SUPER ADMIN role 
       if(Gate::allows('is-superadmin')){
        $upcoming_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
            ->where('upcoming_events.completion_status','=','upcoming')
            ->where('upcoming_events.advisers_approval','=','approved')
            ->where('upcoming_events.studAffairs_approval','=','approved')
            ->paginate(5, ['*'], 'upcoming-events');        
        return view('admin.admin',compact('upcoming_events'));
        
        }
        else{
            abort(403);
        }
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function eventApproval()
    {
        //check if USER has SUPER ADMIN role 
        if(Gate::allows('is-superadmin')){
            $upcoming_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
                ->where('upcoming_events.completion_status','=','upcoming')
                ->where('upcoming_events.studAffairs_approval','=','pending')
                ->paginate(5, ['*'], 'upcoming-events');        
            return view('admin.admin-approval',compact('upcoming_events'));
        }
        else{
            abort(403);
        }
    }

    public function approved($id, Request $request)
    {
        //check if USER has SUPER ADMIN role 
        if(Gate::allows('is-superadmin')){
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
        else{
            abort(403);
        }
    }

    public function disapproved($id, Request $request)
    {   
        //check if USER has SUPER ADMIN role 
        if(Gate::allows('is-superadmin')){
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
        else{
            abort(403);
        }
    }
}
