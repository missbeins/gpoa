@extends('layouts.officer')
@section('content')

<div class="container mt-1">
    <div class="row justify-content-center">
        <div class="col-md-12">
            {{-- Title and Breadcrumbs --}}
            <div class="d-flex justify-content-between align-items-center">
            
                {{-- Breadcrumbs --}}
                <nav aria-label="breadcrumb align-items-center">
                    <ol class="breadcrumb justify-content-center ">
                        <li class="breadcrumb-item">
                            <a href="{{route('officer.events.index')}}" class="text-decoration-none">Organization's Events</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                           Event Details
                        </li>
                    
                    </ol>
                </nav>
            </div>
            <div class="card">
                <div class="card-header"  style="background-color: #c62128; color:azure; font-weight: bold;">{{ __('Event Details') }}</div>
                <div class="card-body">
                    <form class="row g-3">

                        <div class="col-lg-12 mb-1">
                            <label for="title_of_activity" class="form-label">{{ __('Title of Event') }}</label>
                            <input id="title_of_activity" type="text" class="form-control @error('title_of_activity') is-invalid @enderror" name="title_of_activity"
                                value="{{ old('title_of_activity') }} @isset($upcoming_event){{ $upcoming_event->title }}@endisset" required
                                autocomplete="title_of_activity" autofocus readonly>

                            @error('title_of_activity')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-1">
                            <label for="objectives" class="form-label">{{ __('Objectives') }}</label>
                            
                            <textarea id="objectives" row="3"type="text" class="form-control @error('objectives') is-invalid @enderror" name="objectives" 
                            value="{{ old('objectives') }}" required readonly>@isset($upcoming_event){{ $upcoming_event->objectives }}@endisset</textarea>

                            @error('objectives')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>   
                        <div class="col-md-4 mb-1">
                            <label for="organization" class="form-label">{{ __('Head Organization') }}</label>
                            <input id="head_organization" type="text" class="form-control @error('partnership') is-invalid @enderror" name="head_organization"
                            value="{{ old('head_organization') }}@isset($upcoming_event){{ $upcoming_event->head_organization }}@endisset" required
                            autocomplete="head_organization" autofocus readonly>

                            @error('head_organization')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror  
                        </div>
                        <div class="col-md-4 mb-1">
                            <label for="partnerships" class="form-label">{{ __('Partnership(s)') }}</label>
                            <input id="partnerships" type="text" class="form-control @error('partnership') is-invalid @enderror" name="partnerships"
                                value="{{ old('partnerships') }}@isset($upcoming_event){{ $upcoming_event->partnerships }}@endisset" required
                                autocomplete="partnerships" autofocus readonly>

                            @error('partnership')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-1">
                            <label for="participants" class="form-label">{{ __('Participant(s)') }}</label>
                            <input id="participants" type="text" class="form-control @error('participants') is-invalid @enderror" name="participants"
                                value="{{ old('participants') }}@isset($upcoming_event){{ $upcoming_event->participants }}@endisset" required
                                autocomplete="participants" autofocus readonly>

                            @error('participants')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-1">
                            <label for="venue" class="form-label">{{ __('Venue') }}</label>
                            <input id="venue" type="text" class="form-control @error('venue') is-invalid @enderror"
                                name="venue" value="{{ old('venue') }}@isset($upcoming_event){{ $upcoming_event->venue }}@endisset"
                                required autocomplete="venue" autofocus readonly>
                            @error('venue')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-1">
                            <label for="projected_budget" class="form-label">{{ __('Projected Budget(₱)') }}</label>
                            <input id="projected_budget" type="number" class="form-control @error('projected_budget') is-invalid @enderror"
                                name="projected_budget" value="{{ old('projected_budget') }}@isset($upcoming_event){{ $upcoming_event->projected_budget }}@endisset"
                                required autocomplete="student_number" autofocus readonly>
                            @error('projected_budget')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-1">
                            <label for="sponsors" class="form-label">{{ __('Sponsors') }}</label>
                            <input id="sponsors" type="text" class="form-control @error('sponsors') is-invalid @enderror" name="sponsors"
                                value="{{ old('sponsors') }}@isset($upcoming_event){{ $upcoming_event->sponsor }}@endisset" readonly required autocomplete="sponsors">

                                @error('sponsors')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>
                        <div class="col-md-3 mb-1">
                            <label for="fund_sourcing" class="form-label">{{ __('Fund Sourcing') }}</label>
                            <input id="fund_sourcing" type="text" class="form-control @error('fund_sourcing') is-invalid @enderror" name="fund_sourcing"
                                value="{{ old('fund_sourcing') }}@isset($upcoming_event){{ $upcoming_event->fund_source }}@endisset" readonly required autocomplete="fund_sourcing">

                                @error('fund_sourcing')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="col-md-3 mb-1">
                            <label for="date" class="form-label">{{ __('Date') }}</label>
                            <input id="date" type="date" class="form-control @error('date') is-invalid @enderror"
                                name="date" value="{{ old('date') }}@isset($upcoming_event){{ $upcoming_event->date }}@endisset"
                                required readonly>
                            @error('date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-1">
                            <label for="time" class="form-label">{{ __('Time') }}</label>
                            <input id="time" type="time" class="form-control @error('time') is-invalid @enderror"
                                name="time"
                                value="{{ old('time') }}@isset($upcoming_event){{ $upcoming_event->time }}@endisset" required readonly>

                            @error('time')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-1">
                            <label for="type_of_activity" class="form-label">{{ __('Type of Activity') }}</label>
                            <input id="type_of_activity" type="text" class="form-control @error('type_of_activity') is-invalid @enderror"
                                name="type_of_activity"
                                value="{{ old('type_of_activity') }}@isset($upcoming_event){{ $upcoming_event->activity_type }}@endisset"readonly required>

                            @error('type_of_activity')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>     
                        
                        <div class="col-md-3">
                            <label for="school_year" class="form-label">{{ __('School Year / Semester') }}</label>
                            <input id="school_year" type="text" class="form-control @error('school_year') is-invalid @enderror"
                                name="school_year"
                                value="{{ old('school_year') }}@isset($upcoming_event){{ $upcoming_event->school_year }} / {{  $upcoming_event->semester }}@endisset" readonly required>

                            @error('school_year')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>  
                        <hr>
                        <div class="col-md-12">
                            <span class="text-danger">Note:</span>
                            @if ( $upcoming_event->partnership_status=='on')
                                <span class="text-danger"> The event is open for partnerships.</span>
                            @else
                                <span class="text-danger"> The event is not open for partnerships.</span>

                            @endif
                            
                            @if ( $upcoming_event->status=='accomplished')
                                <span class="text-danger">The event is already accomplished.</span>
                            @endif
                    
                            
                        </div>           
                            
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection