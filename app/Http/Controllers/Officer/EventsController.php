<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Imports\UpcomingEventsImport;
use App\Models\organization;
use App\Models\upcoming_events;
use App\Models\Accomplished_Events;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EventsController extends Controller


{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $upcoming_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')->paginate(5, ['*'], 'upcoming-events');
        return view('officer.events',compact('upcoming_events'));
    }
    
    /**
     * Display a listing of the resource of upcoming events.
     *
     * @return \Illuminate\Http\Response
     */
    public function upcomingEvents()
    {
        $upcoming_events = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')
                        ->where('upcoming_events.status','=','upcoming')
                        ->paginate(5, ['*'], 'upcoming-events');
        return view('officer.upcoming-events',compact('upcoming_events'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organizations = organization::all();
        return view('officer.create',compact('organizations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $semesters = ['1st Semester','2nd Semester'];
        $request->validate([

            'organization' => ['required','integer','exists:App\Models\organization,organization_id'],
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
        
        // dd($request);
        $upcomming_events = upcoming_events::create([

            'organization_id' => $request['organization'],
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\upcomming_events  $upcomming_events
     * @return \Illuminate\Http\Response
     */
   
    public function show(upcoming_events $upcoming_events)
    {
        //$upcoming_event = upcoming_events::join('organizations','organizations.organization_id','=','upcoming_events.organization_id')->paginate(5, ['*'], 'upcoming-events');
        $organizations = organization::all();
        return view('officer.show',compact('upcoming_events','organizations'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\upcomming_events  $upcoming_events
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\upcomming_events  $upcomming_events
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([

            'organization' => ['required'],
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

            'organization_id' => $request['organization'],
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\upcomming_events  $upcomming_events
     * @return \Illuminate\Http\Response
     */
    public function markasDone($id, Request $request)
    {

        //$upcoming_event = upcoming_events::find($id);
        $request->validate([

            'title_of_activity' => ['required', 'string', 'max:100'],
            'date' => ['required', 'date'],
            'time' => ['required'],
             
        ]);
       
        $upcoming_events = upcoming_events::where('upcoming_event_id',$id)->update([

            'status' => 'accomplished'

        ]);
        $upcoming_events = upcoming_events::where('upcoming_event_id',$id)->first();
        $upcoming_events->accomplished_events()->attach([null]);
        //dd($upcoming_events->accomplished_events);
        return redirect(route('officer.events.index'));
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
}
