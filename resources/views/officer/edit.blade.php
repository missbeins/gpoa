@extends('layouts.officer')
@section('content')

<div class="container mt-5">
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
                           Update Event
                        </li>
                    
                    </ol>
                </nav>
            </div>
            <div class="card">
                <div class="card-header">{{ __('Update Event') }}</div>
                <div class="card-body">
                    <form class="row g-3" method="POST" action="{{ route('officer.events.update', [$upcoming_events->upcoming_event_id , $upcoming_events->organization_id]) }}">
                        @csrf
                        @method('PUT')
                        <div class="col-md-4 mb-2">
                            <label for="head_organization" class="form-label">{{ __('Head Organization') }}</label>
                            {{-- <input id="head_organization" type="text" class="form-control @error('head_organization') is-invalid @enderror" name="head_organization"
                            value="{{ old('head_organization') }}@isset($upcoming_events){{ $upcoming_events->head_organization }}@endisset" required
                            autocomplete="head_organization" autofocus> --}}
                            <select class="form-control" id="head_organization" name="head_organization" required>
                                @foreach ($organizations as $organization)
                                    {{-- <option value="{{ $organization->organization_acronym }}">
                                        {{ $organization->organization_name }}
                                    </option> --}}
                                    <option value="{{ $organization->organization_acronym }}" @isset($upcoming_events){{ $organization->organization_acronym == $upcoming_events->head_organization ? 'selected' : '' }} @endisset>
                                        {{ $organization->organization_name }}
                                    </option>
                                    
                                @endforeach
                            </select>
                            @error('head_organization')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="title_of_activity" class="form-label">{{ __('Title of Event') }}</label>
                            <input id="title_of_activity" type="text" class="form-control @error('title_of_activity') is-invalid @enderror" name="title_of_activity"
                                value="@isset($upcoming_events){{ $upcoming_events->title}}@endisset" required
                                autocomplete="title_of_activity" autofocus>

                            @error('title_of_activity')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="objectives" class="form-label">{{ __('Objectives') }}</label>
                            
                            <input id="objectives" type="text" class="form-control @error('objectives') is-invalid @enderror"
                            name="objectives"
                            value="@isset($upcoming_events){{ $upcoming_events->objectives }}@endisset" required>

                            @error('objectives')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>     
                        <div class="col-md-3 mb-2">
                            <label for="partnerships" class="form-label">{{ __('Partnership(s)') }}</label>
                            {{-- <input id="partnerships" type="text" class="form-control @error('partnership') is-invalid @enderror" name="partnerships"
                                value="@isset($upcoming_events){{ $upcoming_events->partnerships }}@endisset" required
                                autocomplete="partnerships" autofocus> --}}
                            <select class="form-control" id="partnerships" name="partnerships[]" multiple>
                                <option value="All Organizations">All Organizations</option>
                                @foreach ($organizations as $organization)
                                   
                                    {{-- <option value="{{ $organization->organization_acronym }}" @isset($upcoming_events){{ $organization->organization_acronym == $upcoming_events->head_organization ? 'selected' : '' }} @endisset>
                                        {{ $organization->organization_name }}
                                    </option> --}}
                                    <option value="{{ $organization->organization_acronym }}" @if(in_array($organization->organization_acronym,$selectedPartnerships)) selected @endif> {{ $organization->organization_name }}</option>
                                @endforeach
                            </select>
                            @error('partnerships')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="participants" class="form-label">{{ __('Participant(s)') }}</label>
                            <input id="participants" type="text" class="form-control @error('participants') is-invalid @enderror" name="participants"
                                value="@isset($upcoming_events){{ $upcoming_events->participants }}@endisset" required
                                autocomplete="participants" autofocus>

                            @error('participants')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-2">
                            <label for="venue" class="form-label">{{ __('Venue') }}</label>
                            <input id="venue" type="text" class="form-control @error('venue') is-invalid @enderror"
                                name="venue" value="@isset($upcoming_events){{ $upcoming_events->venue }}@endisset"
                                required autocomplete="venue" autofocus>
                            @error('venue')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="projected_budget" class="form-label">{{ __('Projected Budget') }}</label>
                            <input id="projected_budget" type="number" class="form-control @error('projected_budget') is-invalid @enderror"
                                name="projected_budget" value="@isset($upcoming_events){{ $upcoming_events->projected_budget }}@endisset"
                                required autocomplete="student_number" autofocus>
                            @error('projected_budget')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="sponsors" class="form-label">{{ __('Sponsors') }}</label>
                            <input id="sponsors" type="text" class="form-control @error('sponsors') is-invalid @enderror" name="sponsors"
                                value="@isset($upcoming_events){{ $upcoming_events->sponsor }}@endisset" required autocomplete="sponsors">

                                @error('sponsors')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="fund_sourcing" class="form-label">{{ __('Fund Sourcing') }}</label>
                            <input id="fund_sourcing" type="text" class="form-control @error('fund_sourcing') is-invalid @enderror" name="fund_sourcing"
                                value="@isset($upcoming_events){{ $upcoming_events->fund_source }}@endisset" required autocomplete="fund_sourcing">

                                @error('fund_sourcing')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="col-md-3 mb-2">
                            <label for="date" class="form-label">{{ __('Date') }}</label>
                            <input id="date" type="date" class="form-control @error('date') is-invalid @enderror"
                                name="date" value="@isset($upcoming_events){{ $upcoming_events->date }}@endisset"
                                required>
                            @error('date')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="time" class="form-label">{{ __('Time') }}</label>
                            <input id="time" type="time" class="form-control @error('time') is-invalid @enderror"
                                name="time"
                                value="@isset($upcoming_events){{ $upcoming_events->time }}@endisset" required>

                            @error('time')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="type_of_activity" class="form-label">{{ __('Type of Activity') }}</label>
                            <input id="type_of_activity" type="text" class="form-control @error('type_of_activity') is-invalid @enderror"
                                name="type_of_activity"
                                value="@isset($upcoming_events){{ $upcoming_events->activity_type }}@endisset" required>

                            @error('type_of_activity')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>     
                        <div class="col-md-4 mb-2">
                            <label for="semester" class="form-label">{{ __('Semester') }}</label>
                            <select name="semester" class="form-control @error('semester') is-invalid @enderror">

                                <option @isset($upcoming_events){{ $upcoming_events->semester == '1st Semester' ? 'selected' : '' }} @endisset>1st Semester</option>
                                <option @isset($upcoming_events){{ $upcoming_events->semester == '2nd Semester' ? 'selected' : '' }} @endisset>2nd Semester</option>
                               
                            </select>
                            @error('semester')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="school_year" class="form-label">{{ __('School Year') }}</label>
                            <input id="school_year" type="text" class="form-control @error('school_year') is-invalid @enderror"
                                name="school_year"
                                value="@isset($upcoming_events){{ $upcoming_events->school_year }}@endisset" required>

                            @error('school_year')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>         
                            
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
    
    @push('scripts')
        {{-- Javascript Imports --}}
        
        {{-- <script src="{{ asset('js/app.js') }}"></script> --}}
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @endpush

       
<script>
  $(document).ready(function() {
      // Select2 Multiple
      $('#partnerships').select2({
          placeholder: "Select",
          allowClear: true
      });

  });

</script>
@endsection