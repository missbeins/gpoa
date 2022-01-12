@extends('layouts.officer')
@section('content')
    
    <div class="mt-3">
        {{-- Title and Breadcrumbs --}}
        <div class="d-flex justify-content-between align-items-center">
           
            {{-- Breadcrumbs --}}
            <nav aria-label="breadcrumb align-items-center">
                <ol class="breadcrumb justify-content-center ">
                    {{-- <li class="breadcrumb-item">
                        <a href="{{route('officer.officer.home')}}" class="text-decoration-none">Home</a>
                    </li> --}}
                    <li class="breadcrumb-item active" aria-current="page">
                    Organization's Events / Upcoming Events
                    </li>
                   
                </ol>
            </nav>
        </div>
        <div class="row">
            <form class="col-md-4 input-group mb-2" style="width:33%" action="" method="get">
                    
                <label class="input-group-text" for="inputGroupSelect01">{{ __('Search') }}</label>
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
                <a class="input-group-text btn btn-secondary"type="submit"><i class="fas fa-search"></i></a>
    
            </form>
            <a href="{{ route('officer.print-pdf') }}" class="col-md-1 mb-2 btn btn-danger">PDF</a>
        </div>
        
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-8 mt-1">
                        <h5 class="float-left"> Upcoming Events</h5>
                        
                    </div>
                    
                    <form class="col-md-4 input-group" style="width:33%" action="" method="get">
                    
                        <label class="input-group-text" for="inputGroupSelect01">{{ __('Semester') }}</label>
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
                                <th class="col-sm-1">Date</th>
                                <th class="col-sm-2">Name/Title of Activity</th>
                                <th class="col-sm-3">Head Organization</th>
                                <th class="col-sm-1">Venue & time</th>
                                <th class="col-sm-2">Actions</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @if ($upcoming_events->isNotEmpty())
                                @foreach ($upcoming_events as $upcoming_event)
                                    <tr>
                                        <td>{{ $upcoming_event->date }}</td>
                                        <td>{{ $upcoming_event->title }}</td>
                                        <td>{{ $upcoming_event->organization_name }}</td>
                                        <td>{{ $upcoming_event->venue }}/{{ $upcoming_event->time }}</td>
                                        <td>                                            
                                            <a href="{{ route('officer.events.show', $upcoming_event->upcoming_event_id) }}"class="btn btn-secondary btn-sm mt-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Display event details">Details</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                            <tr><td colspan="7">No results found!</td></tr>
                            @endif
                        </tbody>
                    </table>
                    {{ $upcoming_events->links() }}
                @endif
            </div>
        </div>
    </div>

@endsection