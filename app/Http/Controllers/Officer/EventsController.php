<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Imports\UpcomingEventsImport;
use App\Models\organization;
use App\Models\upcoming_events;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Accomplished_Events;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use PDF;

class EventsController extends Controller


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
 
         // If User has GPOA Admin role...
        
         $memberRoleKey = $this->hasRole($userRoles,'User');
         // Get the Organization from which the user is Membeship Admin
         $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
         $organizationID = $userRoles[$userRoleKey]['organization_id'];

        if(Gate::allows('is-officer')){
            
            $role = Role::with('permissions')->get();
            $user = Auth::user();
            $user->permissions()->attach(1);
            
            $upcoming_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
                            ->where('upcoming_events.organization_id',$organizationID)
                            ->paginate(5, ['*'], 'upcoming-events');
                        // ->sortBy(['created_at','desc']);
            //dd($upcoming_events);
            return view('officer.events',compact('upcoming_events'));
        }
        else{
            abort(403);
        }

    }
    
    /**
     * Display a listing of the resource of upcoming events.
     *
     * @return \Illuminate\Http\Response
     */
    public function upcomingEvents()
    {
        // Pluck all User Roles
        $userRoleCollection = Auth::user()->roles;

        // Remap User Roles into array with Organization ID
        $userRoles = array();
        foreach ($userRoleCollection as $role) 
        {
            array_push($userRoles, ['role' => $role->role, 'organization_id' => $role->pivot->organization_id]);
        }

        // If User has GPOA Admin role...
       
        $memberRoleKey = $this->hasRole($userRoles,'User');
        // Get the Organization from which the user is Membeship Admin
        $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
        $organizationID = $userRoles[$userRoleKey]['organization_id'];

       if(Gate::allows('is-officer')){
        $upcoming_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
                        ->where('upcoming_events.completion_status','=','upcoming')
                        ->where('upcoming_events.advisers_approval','=','approved')
                        ->where('upcoming_events.studAffairs_approval','=','approved')
                        ->where('upcoming_events.organization_id',$organizationID)
                        ->paginate(5, ['*'], 'upcoming-events');
        
        return view('officer.upcoming-events',compact('upcoming_events'));
        }
        else{
            abort(403);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Gate::allows('is-officer')){
        $organizations = organization::all();
        return view('officer.create',compact('organizations'));
        }
        else{
            abort(403);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         // Pluck all User Roles
         $userRoleCollection = Auth::user()->roles;

         // Remap User Roles into array with Organization ID
         $userRoles = array();
         foreach ($userRoleCollection as $role) 
         {
             array_push($userRoles, ['role' => $role->role, 'organization_id' => $role->pivot->organization_id]);
         }
 
         // If User has GPOA Admin role...
        
         $memberRoleKey = $this->hasRole($userRoles,'User');
         // Get the Organization from which the user is Membeship Admin
         $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
         $organizationID = $userRoles[$userRoleKey]['organization_id'];
        if(Gate::allows('is-officer')){
        $semesters = ['1st Semester','2nd Semester'];
        $request->validate([

            // 'head_organization' => ['required','string','exists:App\Models\organization,organization_id'],
            'head_organization' => ['required','string'],
            'title_of_activity' => ['required', 'string', 'max:100'],
            'objectives' => ['required', 'string', 'max:255'],
            'partnerships' => ['required', 'string', 'max:255'],
            'participants' => ['required', 'string', 'max:255'],
            'venue' => ['required', 'string', 'max:255'],
            'projected_budget' => ['nullable','integer'],
            'sponsors' => ['required','string'],
            'date' => ['required', 'date'],
            'time' => ['required','date_format:H:i'],
            'type_of_activity' => ['required', 'string'],   
            'fund_sourcing' => ['required','string'],    
            'semester' => ['required',Rule::in($semesters)],   
            'school_year' => ['required'],      
           
        ]);
        
        //  dd($request);
        $upcomming_events = upcoming_events::create([

            'organization_id' => $organizationID,
            'head_organization' => $request['head_organization'],
            'title' => $request['title_of_activity'],
            'objectives' => $request['objectives'],
            'partnerships' => $request['partnerships'],
            'participants' => $request['participants'],
            'venue' =>$request['venue'],
            'projected_budget' =>$request['projected_budget'],
            'time' => $request['time'],
            'sponsor' => $request['sponsors'],
            'date' => $request['date'],
            'semester' => $request['semester'],
            'school_year' => $request['school_year'],
            'fund_source' => $request['fund_sourcing'],
            'activity_type' => $request['type_of_activity']
        ]);

        return redirect(route('officer.events.index'));
        }
        else{
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\upcomming_events  $upcomming_events
     * @return \Illuminate\Http\Response
     */
   
    public function show($id)
    {
        if(Gate::allows('is-officer')){
        abort_if(! upcoming_events::where('upcoming_event_id', $id)->exists(), 403);
        $organizations = organization::all();
        $upcoming_event = upcoming_events::find($id);
        return view('officer.show',compact([
            'upcoming_event',
            'organizations'
        ]));
        }
        else{
            abort(403);
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\upcomming_events  $upcoming_events
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Gate::allows('is-officer')){
        //abort_if(! upcoming_events::where('organization_id', Auth::user()->organization_id), 403);
        abort_if(! upcoming_events::where('upcoming_event_id', $id)->exists(), 403);
        $organizations = organization::all();
        $upcoming_events = upcoming_events::find($id);
        // dd($upcoming_events);
        return view('officer.edit',compact([
            'organizations',
            'upcoming_events'
        ]));
        }
        else{
            abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\upcomming_events  $upcomming_events
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {    if(Gate::allows('is-officer')){
            $request->validate([

                'organization' => ['required'],
                'head_organization' => ['required', 'string', 'max:100'],
                'title_of_activity' => ['required', 'string', 'max:100'],
                'objectives' => ['required', 'string', 'max:255'],
                'partnerships' => ['required', 'string', 'max:255'],
                'participants' => ['required', 'string', 'max:255'],
                'venue' => ['required', 'string', 'max:255'],
                'projected_budget' => ['required','integer', 'max:255'],
                'sponsors' => ['required','string'],
                'date' => ['required', 'date'],
                'time' => ['required'],
                'type_of_activity' => ['required', 'string'],   
                'fund_sourcing' => ['required','string'],    
                'semester' => ['required'],   
                'school_year' => ['required'],      
            
            ]);
            
            // dd($request);
            $upcomming_events = upcoming_events::where('upcoming_event_id',$id)->update([

                'organization_id' => Auth::user()->organization_id  ,
                'head_organization' => $request['head_organization'],
                'title_of_activity' => $request['title_of_activity'],
                'objectives' => $request['objectives'],
                'partnerships' => $request['partnerships'],
                'participants' => $request['participants'],
                'venue' =>$request['venue'],
                'projected_budget' =>$request['projected_budget'],
                'time' => $request['time'],
                'sponsor' => $request['sponsors'],
                'date' => $request['date'],
                'semester' => $request['semester'],
                'school_year' => $request['school_year'],
                'fund_sourcing' => $request['fund_sourcing'],
                'type_of_activity' => $request['type_of_activity']
            ]);

            return redirect(route('officer.events.index'));
        }
        else{
            abort(403);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\upcomming_events  $upcomming_events
     * @return \Illuminate\Http\Response
     */
    public function markasDone($id, Request $request)
    {
        if(Gate::allows('is-officer')){
            //$upcoming_event = upcoming_events::find($id);
            $request->validate([

                'title_of_activity' => ['required', 'string', 'max:100'],
                'date' => ['required', 'date'],
                'time' => ['required'],
                
            ]);
        
            $upcoming_events = upcoming_events::where('upcoming_event_id',$id)->update([

                'completion_status' => 'accomplished'

            ]);
            $upcoming_events = upcoming_events::where('upcoming_event_id',$id)->first();
            $upcoming_events->accomplished_events()->attach([null]);
            //dd($upcoming_events->accomplished_events);
            return redirect(route('officer.events.index'));
        }
        else{
            abort(403);
        }
    }

    public function import(Request $request){
        // $request->validate([
        //     'file' => 'required|max:10000|mimes:xlsx,xls',
        // ]);
        
        // $path = $request->file('file');
        // $import = new UpcomingEventsImport;
        // $import->import($path);

        // if ($import->failures()->isNotEmpty()) {
        //     return back()->withFailures($import->failures());
        // }
        // //Excel::import(new ExpectedStudentsImport, $path);  
        // //dd($import->failures());
        // $request->session()->flash('success','Imported successfully!');    
        // return redirect()->back();
    }
    public function generatePDF()
    {
        // Pluck all User Roles
        $userRoleCollection = Auth::user()->roles;

        // Remap User Roles into array with Organization ID
        $userRoles = array();
        foreach ($userRoleCollection as $role) 
        {
            array_push($userRoles, ['role' => $role->role, 'organization_id' => $role->pivot->organization_id]);
        }

        // If User has GPOA Admin role...
       
        $memberRoleKey = $this->hasRole($userRoles,'User');
        // Get the Organization from which the user is Membeship Admin
        $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
        $organizationID = $userRoles[$userRoleKey]['organization_id'];

        //Get Organization Details including a single Logo
        $organization = Organization::with('logo')
                ->where('organization_id', $organizationID)
                ->first();
        // dd($organization);
        $upcoming_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
            ->where('upcoming_events.completion_status','=','upcoming')
            ->where('upcoming_events.advisers_approval','=','approved')
            ->where('upcoming_events.studAffairs_approval','=','approved')
            ->where('upcoming_events.organization_id',$organizationID)
            ->get();
      
          
        $pdf = PDF::loadView('officer.pdf-file', compact(['upcoming_events', 'organization']))->setPaper('legal', 'landscape');
        
        return $pdf->stream('gpoa.pdf');
    }
}
