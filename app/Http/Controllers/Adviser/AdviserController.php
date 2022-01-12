<?php

namespace App\Http\Controllers\Adviser;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\upcoming_events;

class AdviserController extends Controller
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
        // Pluck all User Roles
        $userRoleCollection = Auth::user()->roles;

        // Remap User Roles into array with Organization ID
        $userRoles = array();
        foreach ($userRoleCollection as $role) 
        {
            array_push($userRoles, ['role' => $role->role, 'organization_id' => $role->pivot->organization_id]);
        }

        // If User has ADVISER Admin role...
       
        $memberRoleKey = $this->hasRole($userRoles,'User');
        // Get the Organization from which the user is Membeship Admin
        $userRoleKey = $this->hasRole($userRoles, 'Adviser');
        $organizationID = $userRoles[$userRoleKey]['organization_id'];

        //check if USER has ADVISER role 
        if(Gate::allows('is-adviser')){
        $upcoming_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
            ->where('upcoming_events.completion_status','=','upcoming')
            ->where('upcoming_events.advisers_approval','=','approved')
            ->where('upcoming_events.studAffairs_approval','=','approved')
            ->where('upcoming_events.organization_id',$organizationID)
            ->paginate(5, ['*'], 'upcoming-events');        
        return view('adviser.adviser',compact('upcoming_events'));
        
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
        // Pluck all User Roles
        $userRoleCollection = Auth::user()->roles;

        // Remap User Roles into array with Organization ID
        $userRoles = array();
        foreach ($userRoleCollection as $role) 
        {
            array_push($userRoles, ['role' => $role->role, 'organization_id' => $role->pivot->organization_id]);
        }

        // If User has ADVISER Admin role...
       
        $memberRoleKey = $this->hasRole($userRoles,'User');
        // Get the Organization from which the user is Membeship Admin
        $userRoleKey = $this->hasRole($userRoles, 'Adviser');
        $organizationID = $userRoles[$userRoleKey]['organization_id'];
        if(Gate::allows('is-adviser')){
        $upcoming_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
            ->where('upcoming_events.completion_status','=','upcoming')
            ->where('upcoming_events.advisers_approval','=','pending')
            ->where('upcoming_events.organization_id',$organizationID)
            ->paginate(5, ['*'], 'upcoming-events');        
        return view('adviser.event-approval',compact('upcoming_events'));
        }
        else{
            abort(403);
        }
        
    }

    public function approved($id, Request $request)
    {
        // Pluck all User Roles
        $userRoleCollection = Auth::user()->roles;

        // Remap User Roles into array with Organization ID
        $userRoles = array();
        foreach ($userRoleCollection as $role) 
        {
            array_push($userRoles, ['role' => $role->role, 'organization_id' => $role->pivot->organization_id]);
        }

        // If User has ADVISER Admin role...
       
        $memberRoleKey = $this->hasRole($userRoles,'User');
        // Get the Organization from which the user is Membeship Admin
        $userRoleKey = $this->hasRole($userRoles, 'Adviser');
        $organizationID = $userRoles[$userRoleKey]['organization_id'];
        if(Gate::allows('is-adviser')){
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
        else{
            abort(403);
        }
    }

    public function disapproved($id, Request $request)
    {
        if(Gate::allows('is-adviser')){
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
        else{
            abort(403);
        }
    }
}
