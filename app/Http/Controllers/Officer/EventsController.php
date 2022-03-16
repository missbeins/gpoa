<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Imports\UpcomingEventsImport;
use App\Models\Budget_Breakdown;
use App\Models\organization;
use App\Models\upcoming_events;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\course;
use App\Models\Disapproved_events;
use App\Models\Event_Partnerships;
use App\Models\event_signatures;
use App\Models\Genders;
use App\Models\GPOA_Notifications;
use App\Models\Partnerships_Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use File;
use Illuminate\Support\Facades\DB;
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
        if(Gate::allows('is-officer')){
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
            // Get the Organization from which the user is GPOA Admin
            $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
            $organizationID = $userRoles[$userRoleKey]['organization_id'];

            $semesters = upcoming_events::where('upcoming_events.organization_id',$organizationID)
                        ->orderBy('upcoming_event_id', 'desc')
                        ->get();
        
            $semcollection = collect([]);
        
            foreach ($semesters as  $semester) {
                $semcollection->push($semester);
            }
            $newsemcollection = $semcollection->unique('semester');
            $yearcollection = collect([]);
        
            foreach ($semesters as  $semester) {
                $yearcollection->push($semester);
            }
            $newyearcollection = $yearcollection->unique('school_year');
            $filterRange = upcoming_events::where('organization_id',$organizationID)
                ->get();
            $yearRange = collect([]);
    
            foreach ($filterRange as  $range) {
                $yearRange->push($range);
            }
            $newYearRange = $yearRange->unique('school_year');
            $upcoming_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
                            ->where('upcoming_events.organization_id',$organizationID)
                            ->orderBy('upcoming_events.date','asc')
                            ->paginate(5, ['*'], 'upcoming-events');

            return view('officer.events',compact(['upcoming_events','newYearRange','newsemcollection','newyearcollection']));
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
        // Get the Organization from which the user is GPOA Admin
        $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
        $organizationID = $userRoles[$userRoleKey]['organization_id'];
        
       if(Gate::allows('is-officer')){
        $upcoming_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
                        ->where('upcoming_events.completion_status','=','upcoming')
                        ->where('upcoming_events.advisers_approval','=','approved')
                        ->where('upcoming_events.studAffairs_approval','=','approved')
                        ->where('upcoming_events.directors_approval','=','approved')
                        ->where('upcoming_events.organization_id',$organizationID)
                        ->orderBy('upcoming_events.date','asc')
                        ->paginate(5, ['*'], 'upcoming-events');

        $semesters = upcoming_events::where('upcoming_events.advisers_approval','=','approved')
                        ->where('upcoming_events.studAffairs_approval','=','approved')
                        ->where('upcoming_events.organization_id',$organizationID)
                        ->orderBy('upcoming_event_id', 'desc')
                        ->get();
        
        $semcollection = collect([]);
       
        foreach ($semesters as  $semester) {
            $semcollection->push($semester);
        }
        $newsemcollection = $semcollection->unique('semester');
        $yearcollection = collect([]);
       
        foreach ($semesters as  $semester) {
            $yearcollection->push($semester);
        }
        $newyearcollection = $yearcollection->unique('school_year');
        return view('officer.upcoming-events',compact(['upcoming_events','newsemcollection','newyearcollection']));
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
        // dd($request);
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
         // Get the Organization from which the user is GPOA Admin
         $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
         $organizationID = $userRoles[$userRoleKey]['organization_id'];
        if(Gate::allows('is-officer')){
            $semesters = ['1st Semester','2nd Semester'];
            $request->validate([

                // 'head_organization' => ['required','string','exists:App\Models\organization,organization_id'],
                'head_organization' => ['required','string'],
                'title_of_activity' => ['required', 'string', 'max:100'],
                'objectives' => ['required', 'string', 'max:255'],
                'partnerships' => ['required'],
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
                'partnership_status' => ['nullable'],
            ]);
            $getPartnerships = $request['partnerships'];
            $partnerships = implode(',', $getPartnerships);
            // dd($request->partnership_status);
            if($request->has('partnership_status')){
                $eventId = DB::table('upcoming_events')->insertGetId([

                    'organization_id' => $organizationID,
                    'head_organization' => $request['head_organization'],
                    'title' => $request['title_of_activity'],
                    'objectives' => $request['objectives'],
                    'partnerships' => $partnerships,
                    'participants' => $request['participants'],
                    'venue' =>$request['venue'],
                    'projected_budget' =>$request['projected_budget'],
                    'time' => $request['time'],
                    'sponsor' => $request['sponsors'],
                    'date' => $request['date'],
                    'semester' => $request['semester'],
                    'school_year' => $request['school_year'],
                    'fund_source' => $request['fund_sourcing'],
                    'activity_type' => $request['type_of_activity'],
                    'partnership_status' => 'on'
                ]);

                
            }else{
                $eventId = DB::table('upcoming_events')->insertGetId([

                    'organization_id' => $organizationID,
                    'head_organization' => $request['head_organization'],
                    'title' => $request['title_of_activity'],
                    'objectives' => $request['objectives'],
                    'partnerships' => $partnerships,
                    'participants' => $request['participants'],
                    'venue' =>$request['venue'],
                    'projected_budget' =>$request['projected_budget'],
                    'time' => $request['time'],
                    'sponsor' => $request['sponsors'],
                    'date' => $request['date'],
                    'semester' => $request['semester'],
                    'school_year' => $request['school_year'],
                    'fund_source' => $request['fund_sourcing'],
                    'activity_type' => $request['type_of_activity'],
                    'partnership_status' => 'off'
                ]);
            }
            
        
            return redirect(route('officer.showBreakdownForm', $eventId));
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
   
    public function show($id, $orgId)
    {     
        if (Gate::allows('is-officer')) {
            abort_if(! upcoming_events::where('upcoming_event_id', $id)->exists(), 404);

            // Pluck all User Roles
            $userRoleCollection = Auth::user()->roles;

            // Remap User Roles into array with Organization ID
            $userRoles = array();
            foreach ($userRoleCollection as $role) 
            {
                array_push($userRoles, ['role' => $role->role, 'organization_id' => $role->pivot->organization_id]);
            }

            // If User has GPOA Admin role...
            $org = $orgId;
            $memberRoleKey = $this->hasRole($userRoles,'User');
            // Get the Organization from which the user is GPOA Admin
            $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
            $organizationID = $userRoles[$userRoleKey]['organization_id'];
            if($orgId == $organizationID){
                $organizations = organization::all();
                $upcoming_event = upcoming_events::find($id);
                return view('officer.show',compact([
                    'upcoming_event',
                    'organizations',
                    'org'
                ]));
            }
            else{
                abort(403);
            }
        } else {
           abort(403);
        }      
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\upcomming_events  $upcoming_events
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $orgId)
    {
        abort_if(! upcoming_events::where('upcoming_event_id', $id)->exists(), 404);

        if (Gate::allows('is-officer')) {
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
            // Get the Organization from which the user is GPOA Admin
            $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
            $organizationID = $userRoles[$userRoleKey]['organization_id'];
            if($orgId == $organizationID){
                //abort_if(! upcoming_events::where('organization_id', Auth::user()->organization_id), 403);
                $organizations = organization::all();
                $upcoming_events = upcoming_events::find($id);
                
                // dd($upcoming_events);
                $selectedPartnerships = explode(',', $upcoming_events->partnerships);
                return view('officer.edit',compact([
                    'organizations',
                    'upcoming_events',
                    'selectedPartnerships'
                ]));
                }
                else{
                    abort(403);
                }
        } else {
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
    public function update(Request $request, $id, $orgId)
    {   
        if (Gate::allows('is-officer')) {
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
            // Get the Organization from which the user is GPOA Admin
            $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
            $organizationID = $userRoles[$userRoleKey]['organization_id']; 
            if($orgId == $organizationID){
                $semesters = ['1st Semester','2nd Semester'];
                $request->validate([

                    
                    'head_organization' => ['required', 'string', 'max:100'],
                    'title_of_activity' => ['required', 'string', 'max:100'],
                    'objectives' => ['required', 'string', 'max:255'],
                    'partnerships' => ['required'],
                    'participants' => ['required', 'string', 'max:255'],
                    'venue' => ['required', 'string', 'max:255'],
                    'projected_budget' => ['required','integer','max:2147483647'],
                    'sponsors' => ['required','string'],
                    'date' => ['required', 'date'],
                    'time' => ['required'],
                    'type_of_activity' => ['required', 'string'],   
                    'fund_sourcing' => ['required','string'],    
                    'semester' => ['required',Rule::in($semesters)],   
                    'school_year' => ['required'],      
                    'partnership_status' => ['nullable'],
                
                ]);
                $getPartnerships = $request['partnerships'];
                $partnerships = implode(',', $getPartnerships);
                //dd($request);
                if($request->has('partnership_status')){
                    upcoming_events::where('upcoming_event_id',$id)->update([

                        'organization_id' =>  $organizationID,
                        'head_organization' => $request['head_organization'],
                        'title' => $request['title_of_activity'],
                        'objectives' => $request['objectives'],
                        'partnerships' => $partnerships,
                        'participants' => $request['participants'],
                        'venue' =>$request['venue'],
                        'projected_budget' =>$request['projected_budget'],
                        'time' => $request['time'],
                        'sponsor' => $request['sponsors'],
                        'date' => $request['date'],
                        'semester' => $request['semester'],
                        'school_year' => $request['school_year'],
                        'fund_source' => $request['fund_sourcing'],
                        'activity_type' => $request['type_of_activity'],
                        'partnership_status' => 'on'
                    ]);
                }else{
                    upcoming_events::where('upcoming_event_id',$id)->update([

                        'organization_id' =>  $organizationID,
                        'head_organization' => $request['head_organization'],
                        'title' => $request['title_of_activity'],
                        'objectives' => $request['objectives'],
                        'partnerships' => $partnerships,
                        'participants' => $request['participants'],
                        'venue' =>$request['venue'],
                        'projected_budget' =>$request['projected_budget'],
                        'time' => $request['time'],
                        'sponsor' => $request['sponsors'],
                        'date' => $request['date'],
                        'semester' => $request['semester'],
                        'school_year' => $request['school_year'],
                        'fund_source' => $request['fund_sourcing'],
                        'activity_type' => $request['type_of_activity'],
                        'partnership_status' => 'off'
                    ]);
                }
                
                $request->session()->flash('success','Event updated successfully!');
                return redirect(route('officer.events.index'));
            }
            else{
                abort(403);
            }

        } else {
            abort(403);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\upcomming_events  $upcomming_events
     * @return \Illuminate\Http\Response
     */
    public function markasDone($id, Request $request, $orgId)
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

        // Get the Organization from which the user is GPOA Admin
        $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
        $organizationID = $userRoles[$userRoleKey]['organization_id']; 

        if(Gate::allows('is-officer') && $orgId == $organizationID){
            abort_if(! upcoming_events::where('upcoming_event_id', $id)->exists(), 404);
            //$upcoming_event = upcoming_events::find($id);
            $request->validate([

                'title_of_activity' => ['required', 'string', 'max:100'],
                'date' => ['required', 'date'],
                'time' => ['required'],
                
            ]);
        
            $upcoming_events = upcoming_events::where('upcoming_event_id',$id)->update([

                'completion_status' => 'accomplished'

            ]);
            // $upcoming_events = upcoming_events::where('upcoming_event_id',$id)->first();
            // $upcoming_events->accomplished_events()->attach([null]);
            //dd($upcoming_events->accomplished_events);
            $request->session()->flash('success','Event set to accomplished event!');
            return redirect(route('officer.events.index'));
        }
        else{
            abort(403);
        }
    }

    public function generatePDF(Request $request)
    {   
        if (Gate::allows('is-officer')) {
            // Pluck all User Roles
            $userRoleCollection = Auth::user()->roles;
        
            // Remap User Roles into array with Organization ID
            $userRoles = array();
            foreach ($userRoleCollection as $role) 
            {
                array_push($userRoles, ['role' => $role->role, 'organization_id' => $role->pivot->organization_id, 'role_id'=>$role->role_id]);
            }

            // If User has GPOA Admin role...
        
            $memberRoleKey = $this->hasRole($userRoles,'User');
            // Get the Organization from which the user is GPOA Admin
            $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
            $organizationID = $userRoles[$userRoleKey]['organization_id'];
            $role_id =  $userRoles[$userRoleKey]['role_id'];
            
            //Get Organization Details including a single Logo
            $organization = Organization::with('logo')
                    ->where('organization_id', $organizationID)
                    ->first();
            // dd($organization);

            $inputSem = $request->semester;
            $inputYear = $request->school_year;
            $inputMembershipfee = $request->membership_fee;
            $inputCollection = $request->total_collection;

            $request->validate([
                'semester' => ['required'],
                'school_year' => ['required'],
                'membership_fee' => ['required','integer'],
                'total_collection' => ['required','integer'],
            ]);
            
            $upcoming_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
                            ->where('upcoming_events.advisers_approval','=','approved')
                            ->where('upcoming_events.studAffairs_approval','=','approved')
                            ->where('upcoming_events.organization_id',$organizationID)
                            ->where('upcoming_events.semester',$inputSem)
                            ->where('upcoming_events.school_year',$inputYear)
                            ->get();
            
            $president_signature = event_signatures::with('user')
                                ->where('organization_id', $organizationID)
                                ->where('role_id',6)
                                ->first();
            $adviser_signature = event_signatures::with('user')
                                ->where('organization_id', $organizationID)
                                ->where('role_id',9)
                                ->first();
            $admin_signature = event_signatures::with('user')
                                ->where('role_id',1)
                                ->first();    
            $director_signature = event_signatures::with('user')
                                ->where('role_id',10)
                                ->first();
            // dd($director_signature->user->title);

            $pdf = PDF::loadView('officer.pdf-file', compact([
                'upcoming_events', 
                'organization',
                'president_signature',
                'admin_signature',
                'adviser_signature',
                'inputYear',
                'inputSem',
                'inputMembershipfee',
                'inputCollection',
                'director_signature'
            ]))->setPaper('legal', 'landscape');
            
            return $pdf->stream('General-Plan-of-Activities.pdf');
        } else {
            abort(403);
        }
    }

    public function generatePresentationPDF(Request $request)
    {   
        if (Gate::allows('is-officer')) {
            // Pluck all User Roles
            $userRoleCollection = Auth::user()->roles;
        
            // Remap User Roles into array with Organization ID
            $userRoles = array();
            foreach ($userRoleCollection as $role) 
            {
                array_push($userRoles, ['role' => $role->role, 'organization_id' => $role->pivot->organization_id, 'role_id'=>$role->role_id]);
            }

            // If User has GPOA Admin role...
        
            $memberRoleKey = $this->hasRole($userRoles,'User');
            // Get the Organization from which the user is GPOA Admin
            $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
            $organizationID = $userRoles[$userRoleKey]['organization_id'];
            $role_id =  $userRoles[$userRoleKey]['role_id'];
            
            //Get Organization Details including a single Logo
            $organization = Organization::with('logo')
                    ->where('organization_id', $organizationID)
                    ->first();
            // dd($organization);

            $inputSem = $request->semester;
            $inputYear = $request->school_year;
            $inputMembershipfee = $request->membership_fee;
            $inputCollection = $request->total_collection;

            $request->validate([
                'semester' => ['required'],
                'school_year' => ['required'],
                'membership_fee' => ['required','integer'],
                'total_collection' => ['required','integer'],
            ]);
            
            $upcoming_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
                            ->where('upcoming_events.organization_id',$organizationID)
                            ->where('upcoming_events.semester',$inputSem)
                            ->where('upcoming_events.school_year',$inputYear)
                            ->get();
            // dd($upcoming_events);
            $president_signature = event_signatures::with('user')
                                ->where('organization_id', $organizationID)
                                ->where('role_id',6)
                                ->first();
            $director_signature = event_signatures::with('user')
                                ->where('role_id',10)
                                ->first();
            // dd($president_signature);
            $adviser_signature = event_signatures::with('user')
                                ->where('organization_id', $organizationID)
                                ->where('role_id',9)
                                ->first();
            $admin_signature = event_signatures::with('user')
                                ->where('role_id',1)
                                ->first();    
            // dd($president_signature->user->title);

            $pdf = PDF::loadView('officer.presentation-pdf', compact([
                'upcoming_events', 
                'organization',
                'president_signature',
                'admin_signature',
                'adviser_signature',
                'inputYear',
                'inputSem',
                'inputMembershipfee',
                'inputCollection',
                'director_signature'
            ]))->setPaper('legal', 'landscape');
            
            return $pdf->stream('General-Plan-of-Activities.pdf');
        } else {
            abort(403);
        }
    }
    public function profile(){
        if(Gate::allows('is-officer')){
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

            // Get the Organization from which the user is GPOA Admin
            $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
            $organizationID = $userRoles[$userRoleKey]['organization_id']; 

        
            $event_signature = event_signatures::where('event_signatures.user_id',Auth::user()->user_id)->first();
            $courses = course::All();
            $genders= Genders::All();
            // dd($event_signature);
            return view('officer.profile',compact([ 
                'courses',
                'genders',
                'event_signature',
            ]));
        }
    }

    public function updateProfile(Request $request, $id){
        if(Gate::allows('is-officer')){
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

            // Get the Organization from which the user is GPOA Admin
            $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
            $organizationID = $userRoles[$userRoleKey]['organization_id']; 

        
            $data = $request->validate([

                'first_name' => ['required', 'string', 'max:255'],
                'middle_name' => ['nullable','string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'suffix' =>['nullable','string','max:10'],
                'email' => [
                    'required', 
                    'string', 
                    'email', 
                    'max:255',
                    Rule::unique('users')->ignore($id,'user_id')],
                'student_number' => [
                    'required', 
                    'string', 
                    'max:50', 
                    Rule::unique('users')->ignore($id,'user_id')],
                'year_and_section' => ['required', 'string'],
                'course_id' => ['required', 'integer'],
                'mobile_number' => ['required', 'string'], 
                'gender_id' =>['required','integer']
            ]);

            $user = User::where('user_id',$id)->update([
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'],
                'last_name' => $data['last_name'],
                'suffix' => $data['suffix'],
                'email' => $data['email'],  
                'student_number' => $data['student_number'],
                'course_id' => $data['course_id'],
                'gender_id' => $data['gender_id'],
                'year_and_section' => $data['year_and_section'],
                'mobile_number' => $data['mobile_number'],
                
            ]);

            
            $request->session()->flash('success','Successfully update profile!');
            
            return redirect(route('officer.profile'));
        }else{
            abort(403);
        }
    }
    

    public function addSignature(Request $request){
        if(Gate::allows('is-officer')){
            // Pluck all User Roles
            $userRoleCollection = Auth::user()->roles;
            $userRoles = array();
            // Remap User Roles into array with Organization ID
            foreach ($userRoleCollection as $role) 
            {
                array_push($userRoles, ['role' => $role->role, 'organization_id' => $role->pivot->organization_id, 'role_id'=>$role->role_id]);
            }

            // If User has GPOA Admin role...
        
            $memberRoleKey = $this->hasRole($userRoles,'User');
            // Get the Organization from which the user is GPOA Admin
            $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
            $organizationID = $userRoles[$userRoleKey]['organization_id'];
            $role_id =  $userRoles[$userRoleKey]['role_id'];

            
            $request->validate([
                'user_id' =>['required','integer'],
                'signature' => ['required','mimes:png'],
            ]);


            $newImageName= uniqid() . '-' . now()->timestamp . '.' .$request->file('signature')->getClientOriginalExtension();
            $destinationPath = public_path(). '/signatures';
            $request->signature->move($destinationPath, $newImageName);

            $event_signatures = event_signatures::create([
                'role_id' => $role_id,
                'organization_id' => $organizationID,
                'user_id' => $request['user_id'],
                'signature_path' => $newImageName,
            ]);
            
            $request->session()->flash('success','Successfully added signature!');
            
            return redirect(route('officer.profile'));
        }

    }
    public function updateSignature(Request $request, $id){
        if(Gate::allows('is-officer')){
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

            // Get the Organization from which the user is GPOA Admin
            $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
        
       
            //Find if the image exists
            $image = event_signatures::find($id);

            if($request->hasFile('signature')){
                
                $request->validate([
                    'signature' => ['required','mimes:png'],
                ]);
                
                $imagePath = 'signatures/' . $image->signature_path;

                if (File::exists($imagePath)) {

                    File::delete($imagePath);
                } 
                
                $newImageName= uniqid() . '-' . now()->timestamp . '.' .$request->file('signature')->getClientOriginalExtension();
                $request->signature->move(public_path('signatures'), $newImageName);
    
                $event_signatures = event_signatures::where('event_signatures.signature_id',$id)->update([
    
                    'signature_path' => $newImageName,
                ]);

                $request->session()->flash('success','Successfully updated signature!');
                
                return redirect(route('officer.profile'));
        
            }         
        }
        else{
            abort(403);
        }
    }
    public function filterEvents(Request $request){
        if(Gate::allows('is-officer')){
            // Pluck all User Roles
            $userRoleCollection = Auth::user()->roles;

            // Remap User Roles into array with Organization ID
            $userRoles = array();
            foreach ($userRoleCollection as $role) 
            {
                array_push($userRoles, ['role' => $role->role, 'organization_id' => $role->pivot->organization_id]);
            }

            // If User has AR President Admin role...

            
            // Get the Organization from which the user is AR President Admin
            $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
            $organizationID = $userRoles[$userRoleKey]['organization_id'];
            
        
            if(isset($_GET['semester'], $_GET['school_year'])){
               

                $filterRange = upcoming_events::where('organization_id',$organizationID)
                    ->get();
                $yearRange = collect([]);
        
                foreach ($filterRange as  $range) {
                    $yearRange->push($range);
                }
                $newYearRange = $yearRange->unique('school_year');

                $semester = $_GET['semester'];
                $school_year = $_GET['school_year'];
                
                $upcoming_events = DB::table('upcoming_events')
                    ->where([
                        ['upcoming_events.semester','LIKE','%'.$semester.'%'],
                        ['upcoming_events.school_year','LIKE','%'.$school_year.'%'],  
                    ])
                    // ->where('upcoming_events.advisers_approval','=','approved')
                    // ->where('upcoming_events.studAffairs_approval','=','approved')
                    ->orderBy('upcoming_events.date','asc')
                    ->paginate(5, ['*'], 'events');
                return view('officer.filter',compact(['upcoming_events','newYearRange']));
            }else{
                return redirect()->back()->with('error','Record not found! Please make sure to select a semester and school year.');
            }
        }else{
            abort(403);
        }
    }

    public function searchEvent(Request $request){
        if(Gate::allows('is-officer')){
            // Pluck all User Roles
            $userRoleCollection = Auth::user()->roles;

            // Remap User Roles into array with Organization ID
            $userRoles = array();
            foreach ($userRoleCollection as $role) 
            {
                array_push($userRoles, ['role' => $role->role, 'organization_id' => $role->pivot->organization_id]);
            }

            // If User has AR President Admin role...

            
            // Get the Organization from which the user is AR President Admin
            $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
            $organizationID = $userRoles[$userRoleKey]['organization_id'];
            
      
            if(isset($_GET['query'])){
                // Pluck all User Roles
                $userRoleCollection = Auth::user()->roles;

                // Remap User Roles into array with Organization ID
                $userRoles = array();
                foreach ($userRoleCollection as $role) 
                {
                    array_push($userRoles, ['role' => $role->role, 'organization_id' => $role->pivot->organization_id]);
                }
            
                
                // Get the Organization from which the user is Gpoa Admin
                $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
                $organizationID = $userRoles[$userRoleKey]['organization_id'];

                $semesters = upcoming_events::where('upcoming_events.advisers_approval','=','approved')
                            ->where('upcoming_events.studAffairs_approval','=','approved')
                            ->where('upcoming_events.studAffairs_approval','=','approved')
                            ->where('upcoming_events.organization_id',$organizationID)
                            ->orderBy('upcoming_event_id', 'desc')
                            ->get();

                $semcollection = collect([]);

                foreach ($semesters as  $semester) {
                $semcollection->push($semester);
                }
                $newsemcollection = $semcollection->unique('semester');
                $yearcollection = collect([]);

                foreach ($semesters as  $semester) {
                $yearcollection->push($semester);
                }
                $newyearcollection = $yearcollection->unique('school_year');

                $event = $_GET['query'];
    
                $upcoming_events = DB::table('upcoming_events')
                    ->join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
                    ->where('upcoming_events.title','LIKE','%'.$event.'%')
                    ->where('upcoming_events.advisers_approval','=','approved')
                    ->where('upcoming_events.studAffairs_approval','=','approved')
                    ->where('upcoming_events.completion_status','=','upcoming')
                    ->paginate(5, ['*'], 'events');
                return view('officer.search',compact(['upcoming_events','newsemcollection','newyearcollection']));
            }else{
                return redirect()->back()->with('error','Record not found!. Please make sure to type the title of the event properly.');
            }
        }else{
            abort(403);
        }
    }

    public function approvedEvents(){

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
        // Get the Organization from which the user is GPOA Admin
        $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
        $organizationID = $userRoles[$userRoleKey]['organization_id'];
        
        if(Gate::allows('is-officer')){
            $approved_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
                            
                ->where('upcoming_events.advisers_approval','=','approved')
                ->where('upcoming_events.studAffairs_approval','=','approved')
                ->where('upcoming_events.organization_id',$organizationID)
                ->orderBy('upcoming_events.date','asc')
                ->paginate(5, ['*'], 'upcoming-events');

            return view('officer.approved-events',compact('approved_events'));
        }
        else{
            abort(403);
        }
    }
    public function disapprovedEvents(){
        
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
        // Get the Organization from which the user is GPOA Admin
        $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
        $organizationID = $userRoles[$userRoleKey]['organization_id'];
        
        if(Gate::allows('is-officer')){
            $disapproved_events = Disapproved_events::with('user')
                ->join('upcoming_events','upcoming_events.upcoming_event_id','=','disapproved_events.upcoming_event_id')
                ->join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
                ->where('upcoming_events.organization_id',$organizationID)
                // ->join('users','users.user_id','=','disapproved_events.disapproved_by')
                ->orderBy('disapproved_event_id','DESC')
                ->paginate(5, ['*'], 'upcoming-events');
            // dd($disapproved_events);
            $semesters = upcoming_events::where('upcoming_events.advisers_approval','=','approved')
                ->where('upcoming_events.studAffairs_approval','=','approved')
                ->where('upcoming_events.studAffairs_approval','=','approved')
                ->where('upcoming_events.organization_id',$organizationID)
                ->orderBy('upcoming_event_id', 'desc')
                ->get();
            
            $semcollection = collect([]);
            
            foreach ($semesters as  $semester) {
                $semcollection->push($semester);
            }
            $newsemcollection = $semcollection->unique('semester');
            $yearcollection = collect([]);
            
            foreach ($semesters as  $semester) {
                $yearcollection->push($semester);
            }
            $newyearcollection = $yearcollection->unique('school_year');
            return view('officer.disapproved-events',compact(['disapproved_events','newsemcollection','newyearcollection']));
        }
        else{
            abort(403);
        }

    }

    public function partnershipRequests(){
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
        // Get the Organization from which the user is GPOA Admin
        $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
        $organizationID = $userRoles[$userRoleKey]['organization_id'];
        $partnership_requests = Partnerships_Requests::join('upcoming_events','upcoming_events.upcoming_event_id','=','partnership_requests.event_id')
                        ->join('organizations','organizations.organization_id','=','partnership_requests.request_by')
                        ->where('partnership_requests.request_to', $organizationID)
                        ->where('partnership_requests.request_status','=','pending')
                        ->orderBy('partnership_requests.event_id','DESC')
                        ->paginate(5);
        
        return view('officer.partnership_request',compact('partnership_requests'));
    }
    public function partnershipApplications(){
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
        // Get the Organization from which the user is GPOA Admin
        $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
        $organizationID = $userRoles[$userRoleKey]['organization_id'];
        $available_partnerships = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
                                ->where('upcoming_events.partnership_status','=','on')
                                ->where('upcoming_events.organization_id','!=', $organizationID)
                                ->orderBy('upcoming_events.upcoming_event_id','DESC')
                                ->paginate(5);
        return view('officer.partnerships',compact('available_partnerships'));

    }
    public function availablePartnershipDetails($id)
    {     
        if (Gate::allows('is-officer')) {
            abort_if(! upcoming_events::where('upcoming_event_id', $id)->exists(), 404);

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
            // Get the Organization from which the user is GPOA Admin
            $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
            $organizationID = $userRoles[$userRoleKey]['organization_id'];
           
            $organizations = organization::all();
            $upcoming_event = upcoming_events::find($id);
            return view('officer.show',compact([
                'upcoming_event',
                'organizations'
            ]));
          
        } else {
           abort(403);
        }      
    }

    public function applyPartnership($id, Request $request){
        if (Gate::allows('is-officer')) {
            abort_if(! upcoming_events::where('upcoming_event_id', $id)->exists(), 404);
            // Pluck all User Roles
            $userRoleCollection = Auth::user()->roles;

            // Remap User Roles into array with Organization ID
            $userRoles = array();
            foreach ($userRoleCollection as $role) 
            {
                array_push($userRoles, ['role' => $role->role, 'organization_id' => $role->pivot->organization_id]);
            }
            $memberRoleKey = $this->hasRole($userRoles,'User');
            // Get the Organization from which the user is GPOA Admin
            $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
            $organizationID = $userRoles[$userRoleKey]['organization_id'];
            $upcoming_event = upcoming_events::where('upcoming_event_id',$id)
                                ->where('partnership_status','=','on')
                                ->first();
            // dd($upcoming_event);

            $applicationList = Partnerships_Requests::all();

            $applicationExist = false;

            foreach ($applicationList as $application) {

            if ($upcoming_event->upcoming_event_id == $application->event_id && $organizationID == $application->request_by) {

                $applicationExist = true;                 
                return redirect()->back()->with('error', 'Application denied! There is an existing partnership request.');
            }        
            } 
            if ($applicationExist == false) {
                // If User has GPOA Admin role
                $memberRoleKey = $this->hasRole($userRoles,'User');
                // Get the Organization from which the user is GPOA Admin
                $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');

                $organizationID = $userRoles[$userRoleKey]['organization_id'];
                Partnerships_Requests::create([
                    'event_id' => $id,
                    'request_by' => $organizationID,
                    'request_to' => $request['organization_id'],
                ]);
            }
            return redirect()->back()->with('success','Partnership request sent!');
        }else{
            abort(403);
        }
    }
    public function acceptRequest($id){
        if (Gate::allows('is-officer')) {
            abort_if(! Partnerships_Requests::where('event_id', $id)->exists(), 404);
           
            // Pluck all User Roles
            $userRoleCollection = Auth::user()->roles;

            // Remap User Roles into array with Organization ID
            $userRoles = array();
            foreach ($userRoleCollection as $role) 
            {
                array_push($userRoles, ['role' => $role->role, 'organization_id' => $role->pivot->organization_id]);
            }

            // If User has GPOA Admin role
            $memberRoleKey = $this->hasRole($userRoles,'User');
            // Get the Organization from which the user is GPOA Admin
            $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
            $organizationID = $userRoles[$userRoleKey]['organization_id'];
            $event = Partnerships_Requests::join('upcoming_events','upcoming_events.upcoming_event_id','=','partnership_requests.event_id')
                    ->where('partnership_requests.event_id', $id)->get()->toArray();
            // dd($event[0]['request_by']);
            // dd($request->organization_id);
            
            upcoming_events::create([

                'organization_id' => $event[0]['request_by'],
                'head_organization' => $event[0]['head_organization'],
                'title' => $event[0]['title'],
                'objectives' => $event[0]['objectives'],
                'partnerships' => $event[0]['partnerships'],
                'participants' => $event[0]['participants'],
                'venue' =>$event[0]['venue'],
                'projected_budget' =>$event[0]['projected_budget'],
                'time' => $event[0]['time'],
                'sponsor' => $event[0]['sponsor'],
                'date' => $event[0]['date'],
                'semester' => $event[0]['semester'],
                'school_year' => $event[0]['school_year'],
                'fund_source' => $event[0]['fund_source'],
                'activity_type' => $event[0]['activity_type'],
                'partnership_status' => 'off'
            ]);
            // $getPartnersOrg = organization::join('upcoming_events','upcoming_events.organization_id','=','organizations.organization_id')
            //                 ->where('organizations.organization_id',$event[0]['request_by'])
            //                 ->select('organizations.organization_acronym')
            //                 ->first();
            //             dd($getPartnersOrg['organization_acronym']);
            // upcoming_events::where('upcoming_event_id',$id)->update([
            //     'partnerships' => $getPartnersOrg,
            // ]);
            GPOA_Notifications::create([
                'event_id' => $event[0]['upcoming_event_id'],
                'message' => 'Your request has been accepted. Event is now added to your organizations events.',
                'from' => $event[0]['request_to'],
                'to' => $event[0]['request_by']           
            ]);

            Partnerships_Requests::where('event_id', $id)->update([
               'request_status' => 'accepted'
            ]);

            return redirect()->back()->with('success','Partnership request accepted!');
        }else{
            abort(403);
        }
    }
    public function declineRequest($id, Request $request){
        if (Gate::allows('is-officer')) {
            abort_if(! Partnerships_Requests::where('event_id', $id)->exists(), 404);
            // Pluck all User Roles
            $userRoleCollection = Auth::user()->roles;

            // Remap User Roles into array with Organization ID
            $userRoles = array();
            foreach ($userRoleCollection as $role) 
            {
                array_push($userRoles, ['role' => $role->role, 'organization_id' => $role->pivot->organization_id]);
            }

            // If User has GPOA Admin role
            $memberRoleKey = $this->hasRole($userRoles,'User');
            // Get the Organization from which the user is GPOA Admin
            $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
            $organizationID = $userRoles[$userRoleKey]['organization_id'];
            $event = Partnerships_Requests::join('upcoming_events','upcoming_events.upcoming_event_id','=','partnership_requests.event_id')
                    ->where('partnership_requests.event_id', $id)->get()->toArray();
            $request->validate([
                'reason' => ['required','string']
            ]);
            Partnerships_Requests::where('event_id', $id)->update([
               'request_status' => 'declined',
               'reason' => $request['reason']

            ]);
            GPOA_Notifications::create([
                'event_id' => $event[0]['upcoming_event_id'],
                'message' => 'Your request has been declined. You can see the reason/s at the declined partnerships tab.',
                'from' => $event[0]['request_by'],
                'to' => $event[0]['request_to']
            ]);
            
            return redirect()->back()->with('error','Partnership request declined!');
        }else{
            abort(403);
        }
    }

    public function approvedPartnerships(){
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
        // Get the Organization from which the user is GPOA Admin
        $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
        $organizationID = $userRoles[$userRoleKey]['organization_id'];
        $approved_requests = Partnerships_Requests::join('upcoming_events','upcoming_events.upcoming_event_id','=','partnership_requests.event_id')
                            ->join('organizations','organizations.organization_id','=','partnership_requests.request_by')
                            ->where('request_status','=','accepted')
                            ->where('request_by',$organizationID)->paginate(5);
        return view('officer.approved-partnerships',compact('approved_requests'));
    }
    public function disapprovedPartnerships(){
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
         // Get the Organization from which the user is GPOA Admin
         $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
         $organizationID = $userRoles[$userRoleKey]['organization_id'];
         $disapproved_requests = Partnerships_Requests::join('upcoming_events','upcoming_events.upcoming_event_id','=','partnership_requests.event_id')
                             ->join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
                             ->where('request_status','=','declined')
                             ->where('request_by',$organizationID)->paginate(5);
        return view('officer.disapproved-partnerships',compact('disapproved_requests'));

    }

    public function notifications(){
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
        // Get the Organization from which the user is GPOA Admin
        $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
        $organizationID = $userRoles[$userRoleKey]['organization_id'];
        $notifications = GPOA_Notifications::join('upcoming_events','upcoming_events.upcoming_event_id','=','gpoa_notifications.event_id')
                    ->join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
                    // ->join('users','users.user_id','=','gpoa_notifications.user_id')
                    ->where('gpoa_notifications.to',$organizationID)
                    ->paginate(5);
        return view('officer.notifications',compact('notifications'));
    }
    public function showBreakdownForm($id){
        abort_if(! upcoming_events::where('upcoming_event_id', $id)->exists(), 404);

        $event_name = upcoming_events::where('upcoming_events.upcoming_event_id',$id)
            ->select('title')
            ->first();
        $projected_budget = upcoming_events::where('upcoming_events.upcoming_event_id',$id)
            ->select('projected_budget')
            ->first();
        $particulars = Budget_Breakdown::where('event_id', $id)->get();
        // dd($event_name);
        return view('officer.breakdown',[
            'event_id' => $id,
            'projected_budget'=> $projected_budget,
            'event_name' => $event_name,
            'particulars' => $particulars
        ]);
    }

    public function budgetBreakdown(Request $request, $id){

        // dd($request);
        if(Gate::allows('is-officer')){
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
            // Get the Organization from which the user is GPOA Admin
            $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
            $organizationID = $userRoles[$userRoleKey]['organization_id'];

            $this->validate($request, [
                'names' => 'required',
                'names.*' => 'string',
                'amount' => 'required',
                'amount.*' => 'integer'
            ]);
            
            $projected_budget = $request['projected_budget'];
            $total_amount = 0;
            $namesCount = count($request['names']);
            $amountCount = count($request['amount']);

            for ($a=0; $a < $amountCount; $a++) { 

                $amount = $request['amount'][$a];
                $total_amount = $total_amount + $amount;

            }

            if ($total_amount == $projected_budget) {

                if ( $namesCount == $amountCount) {

                    for ($i=0; $i < $namesCount; $i++) {   

                        for ($j=$i; $j < $amountCount; $j++) { 

                            $amount =$request['amount'][$j];
                            $name = $request['names'][$i];
                            Budget_Breakdown::create([
                                'event_id' => $id,
                                'name' => $name,
                                'amount'=> $amount
                            ]);
                            $i++;
                        }

                    }

                }

                $request->session()->flash('success','Successfully added a particular.');     
                return redirect(route('officer.events.index'));

            } else {
                $request->session()->flash('error','Projected budget must be equal to the total amount input.');
                return redirect()->back();
            }
        }else{
            abort(403);
        }
    }
    public function showBudgetBreakdown($id, $org){
    
        if(Gate::allows('is-officer')){
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
            // Get the Organization from which the user is GPOA Admin
            $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
            $organizationID = $userRoles[$userRoleKey]['organization_id'];

            if($org == $organizationID){

                $upcoming_event = upcoming_events::find($id);
                $breakdowns = Budget_Breakdown::join('upcoming_events','upcoming_events.upcoming_event_id','=','budget_breakdowns.event_id')
                            ->where('event_id',$id)
                            ->paginate(5, ['*'], 'budget-breakdown');
                            
                return view('officer.show-breakdown',compact([
                    'upcoming_event',
                    'breakdowns',
                    
                ]));
            }
            else{
                abort(403);
            }
        }else{
            abort(403);
        }
    }

    public function updateBudgetBreakdown(Request $request, $id){
        if(Gate::allows('is-officer')){
            abort_if(! Budget_Breakdown::where('breakdown_id', $id)->exists(), 404);

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
            // Get the Organization from which the user is GPOA Admin
            $userRoleKey = $this->hasRole($userRoles, 'GPOA Admin');
            $organizationID = $userRoles[$userRoleKey]['organization_id'];
            $projected_budget = $request->projected_budget;
            // dd($projected_budget);  
            $budget_count = 0;
            $total_amount = $request['amount'];
            $amount = Budget_Breakdown::where('event_id','=', $request['event_id'])
                    ->where('breakdown_id','!=',$id)
                    ->select('amount')
                    ->get();
            // dd($amount);
            foreach ($amount as $item) {

                $budget_count = $budget_count + $item->amount;

            }
            $total_amount = $total_amount + $budget_count;
            // dd($total_amount);
            if ($total_amount == $projected_budget) {

               Budget_Breakdown::where('breakdown_id',$id)->update([
                    'name' => $request['name'],
                    'amount' => $request['amount']
               ]);

                $request->session()->flash('success','Successfully edited a particular.');     
                return redirect()->back();
            
            } 
            elseif ($total_amount < $projected_budget){
                $request->session()->flash('error','The total amount you input is less than the projected budget. Projected budget must be equal to the total amount input.');
                return redirect()->back();
            }
            else {
                $request->session()->flash('error','The total amount you input is greater than the projected budget. Projected budget must be equal to the total amount input.');
                return redirect()->back();
            }

            
        }else{
            abort(403);
        }
    }
   
}
