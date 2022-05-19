<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
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
use Illuminate\Support\Facades\DB;

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
    public function index(){
       //check if USER has SUPER ADMIN role 
       if(Gate::allows('is-superadmin')){
            
            $semesters = upcoming_events::where('upcoming_events.advisers_approval','=','approved')
                ->where('upcoming_events.studAffairs_approval','=','approved')
                ->where('upcoming_events.directors_approval','=','approved')
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
            $upcoming_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
                ->where('upcoming_events.completion_status','=','upcoming')
                ->where('upcoming_events.advisers_approval','=','approved')
                ->where('upcoming_events.studAffairs_approval','=','approved')
                ->where('upcoming_events.directors_approval','=','approved')
                ->orderBy('upcoming_events.date','asc')
                // ->paginate(5, ['*'], 'upcoming-events');    
                ->get();    
            return view('admin.admin',compact(['upcoming_events','newsemcollection','newyearcollection']));
        }
        else{
            abort(403);
        }
    }

    public function viewOrganizationevents($OrgId){
       //check if USER has SUPER ADMIN role 
       if(Gate::allows('is-superadmin')){
            $upcoming_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
                ->where('upcoming_events.completion_status','=','upcoming')
                ->where('upcoming_events.advisers_approval','=','approved')
                ->where('upcoming_events.studAffairs_approval','=','approved')
                ->where('upcoming_events.directors_approval','=','approved')
                ->where('upcoming_events.organization_id',$OrgId)
                ->orderBy('upcoming_events.date','asc')
                // ->paginate(5, ['*'], 'upcoming-events');  
                ->get();          
            return view('admin.organizations-upcoming-events',compact('upcoming_events'));
        }
        else{
            abort(403);
        }
    }
    public function showAllPendingApproval(){
        //check if USER has SUPER ADMIN role 
        if(Gate::allows('is-superadmin')){
                         
            $upcoming_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
                ->where('upcoming_events.completion_status','=','upcoming')
                ->where('upcoming_events.advisers_approval','=','approved')
                ->where('upcoming_events.studAffairs_approval','=','pending')
                ->orderBy('upcoming_events.date','asc')
                // ->paginate(5, ['*'], 'all-pending-approval');
                ->get();                
             return view('admin.all-pending-approval',compact('upcoming_events'));
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
    public function eventApproval($OrgId)
    {
        //check if USER has SUPER ADMIN role 
        if(Gate::allows('is-superadmin')){
            $upcoming_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
                ->where('upcoming_events.completion_status','=','upcoming')
                ->where('upcoming_events.advisers_approval','=','approved')
                ->where('upcoming_events.studAffairs_approval','=','pending')
                ->where('upcoming_events.organization_id',$OrgId)
                ->orderBy('upcoming_events.date','asc')
                // ->paginate(5, ['*'], 'upcoming-events');        
                ->get();    
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
                'organization_id' =>['required','integer'],
                'title_of_activity' => ['required', 'string', 'max:100'],
                'date' => ['required', 'date'],
                'time' => ['required'],
                
            ]);
        
            $upcoming_events = upcoming_events::where('upcoming_event_id',$id);
            $upcoming_events->update([

                'studAffairs_approval' => 'approved'

            ]);
            GPOA_Notifications::create([
                'event_id' => $id,
                'message' => 'Event has been approved by Head of Academic affairs.',
                'user_id' => Auth::user()->user_id,
                'to' => $request['organization_id']
            ]);
            
            return redirect()->back();
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
                'organization_id' =>['required','integer'],
                'title_of_activity' => ['required', 'string', 'max:100'],
                'date' => ['required', 'date'],
                'time' => ['required'],
            ]);
        
            $upcoming_events = upcoming_events::where('upcoming_event_id',$id)->update([

                'studAffairs_approval' => 'disapproved'

            ]);
            Disapproved_events::create([
                'reason' => $request['reason'],
                'upcoming_event_id' => $id,
                'disapproved_by' => Auth::user()->user_id,
            ]);
            GPOA_Notifications::create([
                'event_id' => $id,
                'message' => 'Event has been disapproved by Head of Academic affairs. Check for the reason at the Disapproved Events tab',
                'user_id' => Auth::user()->user_id,
                'to' => $request['organization_id']
            ]);
           return redirect()->back();
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

        if(Gate::allows('is-superadmin')){
            $event_signature = event_signatures::where('event_signatures.user_id',Auth::user()->user_id)->first();
            $courses = course::All();
            $genders= Genders::All();
            return view('admin.profile',compact([ 
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

        if(Gate::allows('is-superadmin')){
            $data = $request->validate([

                'first_name' => ['required', 'string', 'max:255'],
                'middle_name' => ['nullable', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'suffix' =>['nullable','string'],
                'email' => [
                    'required', 
                    'string', 
                    'email', 
                    'max:255',
                    Rule::unique('users')->ignore($id,'user_id')],
                'mobile_number' => ['required', 'string'], 
                'gender_id' =>['required','integer'],
                'title' =>['nullable','string']
            ]);

            $user = User::where('user_id',$id)->update([
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'],
                'last_name' => $data['last_name'],
                'suffix' => $data['suffix'],
                'email' => $data['email'],  
                'gender_id' => $data['gender_id'],
                'mobile_number' => $data['mobile_number'],
                'title' =>$data['title']
                
            ]);

            
            $request->session()->flash('success','Successfully update profile!');
            
            return redirect(route('admin.profile'));
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
        
        if(Gate::allows('is-superadmin')){
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
            
            return redirect(route('admin.profile'));
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
        
        if(Gate::allows('is-superadmin')){
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
                
                return redirect(route('admin.profile'));
        
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
   
    public function show($id, $OrgId)
    {   
        abort_if(! upcoming_events::where('upcoming_event_id', $id)->exists(), 404);
        abort_if(! organization::where('organization_id', $OrgId)->exists(), 404);
        // abort_if(! upcoming_events::where('upcoming_event_id', $id)->exists() || organization::where('organization_id', $OrgId)->exists(), 404);
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
        if(Gate::allows('is-superadmin')){
            $organizations = organization::all();
            $upcoming_event = upcoming_events::find($id);
            return view('admin.show',compact([
                'upcoming_event',
                'organizations'
            ]));
        }else{
            abort(404);
        }
    }
    public function searchEvent(Request $request){
        // $request->validate([
        //     'query' => ['required']
        // ]);
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
         
        if(Gate::allows('is-superadmin')){
            if(isset($_GET['query'])){

                $semesters = upcoming_events::where('upcoming_events.advisers_approval','=','approved')
                            ->where('upcoming_events.studAffairs_approval','=','approved')
                            ->where('upcoming_events.studAffairs_approval','=','approved')
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
                    ->orderBy('upcoming_events.date','asc')
                    // ->paginate(5, ['*'], 'events');
                    ->get();    
                return view('admin.search',compact(['upcoming_events','newsemcollection','newyearcollection']));
            
            }else{
                return redirect()->back()->with('error','Input field is empty!');
            }
        }else{
            abort(403);
        }
        
    }
    public function generatePDF(Request $request)
    {   
        if (Gate::allows('is-superadmin')) {
            $semesters = ['1st Semester','2nd Semester'];
            $request->validate([
                'semester' => ['required',Rule::in($semesters)],
                'school_year' => ['required'],
                'membership_fee' => ['required','integer'],
                'total_collection' => ['required','integer'],
            ]);
            $organizationID = $request->organization_id;
            //Get Organization Details including a single Logo
            
            // dd($organization);

            $inputSem = $request->semester;
            $inputYear = $request->school_year;
            $inputMembershipfee = $request->membership_fee;
            $inputCollection = $request->total_collection;

            $organization = Organization::with('logo')
            ->where('organization_id', $organizationID)
            ->first();
            
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
            //dd($president_signature, $admin_signature, $adviser_signature);
            $pdf = PDF::loadView('admin.gpoa-pdf-file', compact([
                'upcoming_events', 
                'organization',
                'president_signature',
                'admin_signature',
                'adviser_signature',
                'director_signature',
                'inputYear',
                'inputSem',
                'inputMembershipfee',
                'inputCollection'
            ]))->setPaper('legal', 'landscape');
            
            return $pdf->stream('General-Plan-of-Activities.pdf');
        } else {
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
        
        if(Gate::allows('is-superadmin')){
            $approved_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
                            
                ->where('upcoming_events.advisers_approval','=','approved')
                ->where('upcoming_events.studAffairs_approval','=','approved')
                ->orderBy('upcoming_events.date','asc')
                // ->paginate(5, ['*'], 'upcoming-events');
                ->get();    

            return view('admin.approved-events',compact('approved_events'));
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
        
        if(Gate::allows('is-superadmin')){
            $disapproved_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
                ->join('disapproved_events','disapproved_events.upcoming_event_id','=','upcoming_events.upcoming_event_id')
                // ->where('upcoming_events.advisers_approval','=','disapproved')
                ->where('upcoming_events.studAffairs_approval','=','disapproved')
                ->where('disapproved_events.disapproved_by', Auth::user()->user_id)
                ->orderBy('upcoming_events.date','asc')
                // ->paginate(5, ['*'], 'upcoming-events');
                ->get();    

            $semesters = upcoming_events::where('upcoming_events.advisers_approval','=','approved')
                ->where('upcoming_events.studAffairs_approval','=','approved')
                ->where('upcoming_events.studAffairs_approval','=','approved')
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
            return view('admin.disapproved-events',compact(['disapproved_events','newsemcollection','newyearcollection']));
        }
        else{
            abort(403);
        }

    }
}
