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
use App\Models\course;
use App\Models\event_signatures;
use App\Models\Genders;
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
                        // ->sortBy(['created_at','desc']);
            //dd($upcoming_events);
            return view('officer.events',compact(['upcoming_events','newYearRange']));
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
                        ->where('upcoming_events.organization_id',$organizationID)
                        ->orderBy('upcoming_events.date','asc')
                        ->paginate(5, ['*'], 'upcoming-events');

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
            $request->session()->flash('success','Successfully added new event!');
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
   
    public function show($id, $orgId)
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
    public function edit($id, $orgId)
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
    public function update(Request $request, $id, $orgId)
    {   // Pluck all User Roles
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
            $semesters = ['1st Semester','2nd Semester'];
            $request->validate([

                
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
                'semester' => ['required',Rule::in($semesters)],   
                'school_year' => ['required'],      
            
            ]);
            
            //dd($request);
            $upcomming_events = upcoming_events::where('upcoming_event_id',$id)->update([

                'organization_id' =>  $organizationID,
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
            $request->session()->flash('success','Event updated successfully!');
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
    public function generatePDF(Request $request)
    {   
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
            'semester' => ['required','integer'],
            'school_year' => ['required','integer'],
            'membership_fee' => ['required','integer'],
            'total_collection' => ['required','integer'],
        ]);
        $upcoming_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
                        ->where('upcoming_events.completion_status','=','upcoming')
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
        // dd($president_signature);
        $adviser_signature = event_signatures::with('user')
                            ->where('organization_id', $organizationID)
                            ->where('role_id',9)
                            ->first();
        $admin_signature = event_signatures::with('user')
                            ->where('role_id',1)
                            ->first();    
        
        $pdf = PDF::loadView('officer.pdf-file', compact([
            'upcoming_events', 
            'organization',
            'president_signature',
            'admin_signature',
            'adviser_signature',
            'inputYear',
            'inputSem',
            'inputMembershipfee',
            'inputCollection'
        ]))->setPaper('legal', 'landscape');
        
        return $pdf->stream('gpoa.pdf');
    }

    public function profile(){
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
            $event_signature = event_signatures::where('event_signatures.user_id',Auth::user()->user_id)->first();
            $courses = course::All();
            $genders= Genders::All();
            return view('officer.profile',compact([ 
                'courses',
                'genders',
                'event_signature',
            ]));
        }
    }

    public function updateProfile(Request $request, $id){
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
            $data = $request->validate([

                'first_name' => ['required', 'string', 'max:255'],
                'middle_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
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

        if(Gate::allows('is-officer')){
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
        
        if(Gate::allows('is-officer')){
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
         
        if(Gate::allows('is-officer')){
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
                    ->where('upcoming_events.advisers_approval','=','approved')
                    ->where('upcoming_events.studAffairs_approval','=','approved')
                    ->orderBy('upcoming_events.date','asc')
                    ->paginate(5, ['*'], 'events');
                return view('officer.filter',compact(['upcoming_events','newYearRange']));
            
            }
        }
    }

    public function searchEvent(Request $request){
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
         
        if(Gate::allows('is-officer')){
            if(isset($_GET['query'])){
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
                    ->where('upcoming_events.title','LIKE','%'.$event.'%')
                    ->where('upcoming_events.advisers_approval','=','approved')
                    ->where('upcoming_events.studAffairs_approval','=','approved')
                    ->where('upcoming_events.completion_status','=','upcoming')
                    ->paginate(5, ['*'], 'events');
                return view('officer.search',compact(['upcoming_events','newsemcollection','newyearcollection']));
            
            }
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
       $upcoming_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
                       
                       ->where('upcoming_events.advisers_approval','=','approved')
                       ->where('upcoming_events.studAffairs_approval','=','approved')
                       ->where('upcoming_events.organization_id',$organizationID)
                       ->orderBy('upcoming_events.date','asc')
                       ->paginate(5, ['*'], 'upcoming-events');

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
       return view('officer.upcoming-events',compact(['upcoming_events','newsemcollection','newyearcollection']));
       }
       else{
           abort(403);
       }
    }
    public function disapprovedEvents(){
        //
    }
}
