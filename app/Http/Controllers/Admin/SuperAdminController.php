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
            ->where('status','=','upcoming')
            ->paginate(5, ['*'], 'upcoming-events');        
        return view('admin.admin',compact('upcoming_events'));
        
    }
}
