<?php

namespace App\Http\Controllers\Adviser;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\upcoming_events;
use App\Models\course;
use App\Models\Disapproved_events;
use App\Models\event_signatures;
use App\Models\Genders;
use App\Models\GPOA_Notifications;
use App\Models\organization;
use App\Models\User;
use File;
use PDF;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        // Get the Organization from which the user is adviser
        $userRoleKey = $this->hasRole($userRoles, 'Adviser');
        $organizationID = $userRoles[$userRoleKey]['organization_id'];

        //check if USER has ADVISER role 
        if(Gate::allows('is-adviser')){
        $upcoming_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
            ->where('upcoming_events.completion_status','=','upcoming')
            ->where('upcoming_events.advisers_approval','=','approved')
            ->where('upcoming_events.studAffairs_approval','=','approved')
            ->where('upcoming_events.directors_approval','=','approved')
            ->where('upcoming_events.organization_id',$organizationID)
            ->orderBy('upcoming_events.date','asc')
            // ->paginate(5, ['*'], 'upcoming-events'); 
            ->get();           
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
        return view('adviser.adviser',compact('upcoming_events','newyearcollection','newsemcollection'));
        
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
        // Get the Organization from which the user is adviser
        $userRoleKey = $this->hasRole($userRoles, 'Adviser');
        $organizationID = $userRoles[$userRoleKey]['organization_id'];
        if(Gate::allows('is-adviser')){
        $upcoming_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
            ->where('upcoming_events.completion_status','=','upcoming')
            ->where('upcoming_events.advisers_approval','=','pending')
            ->where('upcoming_events.organization_id',$organizationID)
            ->orderBy('upcoming_events.date','asc')
            // ->paginate(5, ['*'], 'upcoming-events');   
            ->get();         
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
        // Get the Organization from which the user is adviser
        $userRoleKey = $this->hasRole($userRoles, 'Adviser');
        $organizationID = $userRoles[$userRoleKey]['organization_id'];
        if(Gate::allows('is-adviser')){
            $request->validate([

                'title_of_activity' => ['required', 'string', 'max:100'],
                'date' => ['required', 'date'],
                'time' => ['required'],
                
            ]);
        
            $upcoming_events = upcoming_events::where('upcoming_event_id',$id);
            $upcoming_events->update([

                'advisers_approval' => 'approved',
               
            
            ]);
            GPOA_Notifications::create([
                'event_id' => $id,
                'message' => "Event has been approved by Organization's Adviser.",
                'user_id' => Auth::user()->user_id,
                'to' => $request['organization_id']
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

            Disapproved_events::create([
                'reason' => $request['reason'],
                'upcoming_event_id' => $id,
                'disapproved_by' => Auth::user()->user_id,
            ]);
            GPOA_Notifications::create([
                'event_id' => $id,
                'message' => "Event has been disapproved by Organization's Adviser. Check for the reason/s at the Disapproved Events tab.",            
                'user_id' => Auth::user()->user_id,
                'to' => $request['organization_id']
            ]);
            return redirect(route('adviser.adviser.event-approval'));
        }
        else{
            abort(403);
        }
    }

    public function approveSelected(Request $request){
  

       
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
        // Get the Organization from which the user is adviser
        $userRoleKey = $this->hasRole($userRoles, 'Adviser');
        $organizationID = $userRoles[$userRoleKey]['organization_id'];

        // Get all Keys from Form
        $allKeys= $request->except(['_token']);

        $collectionKeys = Arr::where($allKeys, function ($value, $key) {
            if(Str::startsWith($key, 'eventIds'))
                return $key;
        });

        // Remake array, only keys remain
        $collectionKeys = array_values($collectionKeys);
        // dd($collectionKeys);
        // // Redirect if there is no event/accomplishment selected
        //     if (count($allKeys) == 0) 
        //         return redirect()->action(
        //             [AccomplishmentReportsController::class, 'index'])
        //             ->with('error', 'No Report Selected!');
        
        // foreach ($events as $eve) {
        //     dd($eve->organization_id);
        // }
    
        if(Gate::allows('is-adviser')){
            //approve events
            $upcoming_events = upcoming_events::whereIn('upcoming_event_id',$collectionKeys);
          
            $upcoming_events->update([

                'advisers_approval' => 'approved',
               
            
            ]);

            //create notifications for each event approved
            $events = upcoming_events::whereIn('upcoming_event_id',$collectionKeys)->get();
            foreach ($events as $event ) {
                
                GPOA_Notifications::create([
                    'event_id' => $event->upcoming_event_id,
                    'message' => "Event has been approved by Organization's Adviser.",
                    'user_id' => Auth::user()->user_id,
                    'to' => $event->organization_id
                ]);
                
            }
            return redirect(route('adviser.adviser.event-approval'));
        }
        else{
            abort(403);
        }
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

        if(Gate::allows('is-adviser')){
            $event_signature = event_signatures::where('event_signatures.user_id',Auth::user()->user_id)->first();
            $courses = course::All();
            $genders= Genders::All();
            return view('adviser.profile',compact([ 
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

        if(Gate::allows('is-adviser')){
            $data = $request->validate([

                'first_name' => ['required', 'string', 'max:255'],
                'middle_name' => ['nullable', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'suffix' => ['nullable', 'string'],
                'title' => ['nullable', 'string'],
                'email' => [
                    'required', 
                    'string', 
                    'email', 
                    'max:255',
                    Rule::unique('users')->ignore($id,'user_id')],
               
                // 'course_id' => ['nullable', 'integer'],
                'mobile_number' => ['required', 'string'], 
                'gender_id' =>['required','integer']
            ]);
            // dd($request);
            $user = User::where('user_id',$id)->update([
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'],
                'last_name' => $data['last_name'],
                'suffix' => $data['suffix'],
                'title' => $data['title'],
                'email' => $data['email'],  
                'gender_id' => $data['gender_id'],
                'mobile_number' => $data['mobile_number'],
                
            ]);

            
            $request->session()->flash('success','Successfully updated your profile!');
            
            return redirect(route('adviser.profile'));
        }else{
            abort(403);
        }
    }
    
    public function addSignature(Request $request){
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

        if(Gate::allows('is-adviser')){
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
            
            return redirect(route('adviser.profile'));
        }else{
            abort(403);
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
        
        if(Gate::allows('is-adviser')){
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
                
                return redirect(route('adviser.profile'));
        
            }         
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
        if(Gate::allows('is-adviser')){
            abort_if(! upcoming_events::where('upcoming_event_id', $id)->exists(), 404);
            if ($orgId == $organizationID) {
                $organizations = organization::all();
                $upcoming_event = upcoming_events::find($id);
                return view('adviser.show',compact([
                    'upcoming_event',
                    'organizations'
                ]));
            } else {
                abort(403);
            }
        }
        else{
            abort(403);
        }
    }
    public function searchEvent(Request $request){
        
        if(Gate::allows('is-adviser')){
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
                    ->where('upcoming_events.organization_id',$organizationID)
                    // ->paginate(5, ['*'], 'events');
                    ->get();    
                   
                return view('adviser.search',compact(['upcoming_events','newsemcollection','newyearcollection']));
            
            }else{
                return redirect()->back()->with('error','Input field is empty!');
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
        
        if(Gate::allows('is-adviser')){
            $approved_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
                            
                ->where('upcoming_events.advisers_approval','=','approved')
                // ->where('upcoming_events.studAffairs_approval','=','approved')
                ->where('upcoming_events.organization_id',$organizationID)
                ->orderBy('upcoming_events.date','asc')
                // ->paginate(5, ['*'], 'upcoming-events');
                ->get();    

            return view('adviser.approved-events',compact('approved_events'));
        }
        else{
            abort(403);
        }
    }
    public function disapprovedEvent(){
        
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
        
        if(Gate::allows('is-adviser')){
            $disapproved_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
                ->join('disapproved_events','disapproved_events.upcoming_event_id','=','upcoming_events.upcoming_event_id')
                ->where('upcoming_events.advisers_approval','=','disapproved')
                // ->where('upcoming_events.studAffairs_approval','=','disapproved')
                ->where('upcoming_events.organization_id',$organizationID)
                ->where('disapproved_events.disapproved_by', Auth::user()->user_id)
                ->orderBy('upcoming_events.date','asc')
                // ->paginate(5, ['*'], 'upcoming-events');
                ->get();    

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
            return view('adviser.disapproved-events',compact(['disapproved_events','newsemcollection','newyearcollection']));
        }
        else{
            abort(403);
        }

    }
    
    public function generatePDF(Request $request)
    {   
        if (Gate::allows('is-adviser')) {
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
}
