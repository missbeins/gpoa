@extends('layouts.admin')
@section('content')
<div class="mt-3">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-8 mt-1">
                    <h5 class="float-left">Student Organization's Events</h5>
                </div>
                <form class="col-md-4 input-group" style="width:30%" action="" method="get">
                
                    <label class="input-group-text" for="inputGroupSelect01">{{ __('Filter') }}</label>
                    <select class="form-control @error('query') is-invalid @enderror" id="inputGroupSelect01" name="query">
                        {{-- @foreach ($academic_memberships as $academic_membership)
                            <option value="{{ $academic_membership->academic_membership_id }}">{{ $academic_membership->semester }}({{ $academic_membership->school_year }})</option>                          
                        @endforeach --}}
                    </select>                        
                            @error('query')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    <button class="input-group-text btn-secondary"type="submit">Enter</button>
        
                </form>
            </div>
        </div>
        <div class="card-body table-responsive text-center">        
            @if (isset($upcoming_events))
                <table class="table table-light table-sm table-striped table-hover table-responsive">
                    <thead>
                        <tr>
                            <th class="col-sm-1">Name/Title of Activity</th>
                            <th class="col-sm-2">Head Organization</th>
                            <th class="col-sm-1">Participant(s)</th>
                            <th class="col-sm-1">Partnerships</th>
                            <th class="col-sm-1">Date</th>
                            <th class="col-sm-1">Venue & time</th>
                            <th class="col-sm-2">Semester & School Year</th>
           
                            
                        </tr>
                    </thead>
                    <tbody>
                        @if ($upcoming_events->isNotEmpty())
                            @foreach ($upcoming_events as $upcoming_event)
                                <tr>
                                    <td>{{ $upcoming_event->title }}</td>
                                    <td>{{ $upcoming_event->organization_name }}</td>
                                    <td>{{ $upcoming_event->participants }}</td>
                                    <td>{{ $upcoming_event->partnerships }}</td>
                                    <td>{{ $upcoming_event->date }}</td>
                                    <td>{{ $upcoming_event->venue }}/{{ $upcoming_event->time }}</td>
                                    <td>{{ $upcoming_event->semester }}({{ $upcoming_event->school_year }})</td>
                                
                                </tr>
                            @endforeach
                        @else
                        <tr><td colspan="6">No results found!</td></tr>
                        @endif
                    </tbody>
                </table>
                {{ $upcoming_events->links() }}
            @endif
        </div>
    </div>
</div>
@endsection