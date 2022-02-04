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
                    Organization's Events
                    </li>
                   
                </ol>
            </nav>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-8 mt-1">
                        <h5 class="float-left">Organization's Events</h5>
                        
                    </div>
                    <form class="col-md-4 input-group" style="width:33%" action="{{ route('officer.filterEvents') }}" method="get">
                        @csrf
                        <label class="input-group-text" for="inputGroupSelect01">{{ __('Filter') }}</label>
                        <select class="form-control @error('query') is-invalid @enderror" id="inputGroupSelect01" name="semester" required>
                            <option disabled selected>Select semester</option>
                            <option value="1st Semester">1st Semester</option> 
                            <option value="2nd Semester">2nd Semester</option> 
                                                        
                          
                        </select>                        
                                @error('query')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        <select class="form-control @error('query') is-invalid @enderror" id="inputGroupSelect01" name="school_year" required>
                            <option disabled selected>Select School Year</option>
                            @foreach ($newYearRange as $range)
                                <option value="{{ $range->school_year }}">{{ $range->school_year }}</option>                          
                            @endforeach
                        </select>                        
                                @error('query')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        <button class="input-group-text btn-secondary"type="submit">Enter</button>
            
                    </form>
                    <div class="col-md-12">
                      
                        {{-- <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                            Import GPOA
                        </button> --}}
                        <a href="{{ route('officer.events.create') }}" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Start new event">New Event</a>
                        @include('officer.includes.import')
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">        
                @if (isset($upcoming_events))
                    <table class="table table-light table-sm table-striped table-hover table-responsive" id="orgevents">
                        <thead>
                            <tr>
                                <th class="col-sm-2">Date</th>
                                <th class="col-sm-3">Name/Title of Activity</th>
                                <th class="col-sm-2">Head Organization</th>
                                <th class="col-sm-3">Venue & time</th>
                                <th class="col-sm-2">Actions</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @if ($upcoming_events->isNotEmpty())
                                @foreach ($upcoming_events as $upcoming_event)
                                    <tr>
                                        <td>{{ date_format(date_create($upcoming_event->date), 'F d, Y') }}</td>
                                        <td>{{ $upcoming_event->title }}</td>
                                        <td>{{ $upcoming_event->head_organization }}</td>
                                        <td>{{ $upcoming_event->venue }} / {{ date_format(date_create($upcoming_event->time), 'H : i a')}}</td>
                                        <td>
                                          @if ($upcoming_event->advisers_approval == 'approved' && $upcoming_event->studAffairs_approval == 'approved' && $upcoming_event->completion_status == 'upcoming')
                                            <button type="button" class="btn btn-success btn-sm mt-1" data-bs-toggle="modal" data-bs-target="#mark-as-done-form{{ $upcoming_event->upcoming_event_id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Mark event as accomplished">
                                                Mark as done
                                            </button>
                                            @include('officer.includes.markasdone')  
                                          @endif
                                            
                                            <a href="{{ route('officer.events.show', [$upcoming_event->upcoming_event_id , $upcoming_event->organization_id])}}"class="btn btn-secondary btn-sm mt-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Display event details">Details</a>
                                            <a href="{{ route('officer.events.edit', [$upcoming_event->upcoming_event_id , $upcoming_event->organization_id]) }}"class="btn btn-primary btn-sm mt-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Update event">Edit</a>
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
@push('scripts')
    {{-- Import Datatables --}}
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
@endpush

@section('scripts')
    <script type="module">
        // Simple-DataTables
        // https://github.com/fiduswriter/Simple-DataTables
        window.addEventListener('DOMContentLoaded', event => {
            const dataTable = new simpleDatatables.DataTable("#orgevents", {
                perPage: 10,
                searchable: true,
                labels: {
                    placeholder: "Search on current page...",
                    noRows: "No user to display in this page or try in the next page.",
                },
            });
        });
    </script>
@endsection