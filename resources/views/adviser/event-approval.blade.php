@extends('layouts.adviser')
@section('content')
    
    <div class="mt-3">
        {{-- Title and Breadcrumbs --}}
        <div class="d-flex justify-content-between align-items-center">
           
            {{-- Breadcrumbs --}}
            <nav aria-label="breadcrumb align-items-center">
                <ol class="breadcrumb justify-content-center ">
                  
                    <li class="breadcrumb-item active" aria-current="page">
                    Organization's Events / Event Approval
                    </li>
                   
                </ol>
            </nav>
        </div>
        <div class="card">
            <div class="card-header" style="background-color: #c62128; color:azure; font-weight: bold;">
                <div class="row">
                    <div class="col-md-8 mt-1">
                        <h5 class="float-left">Organization's Events</h5>
                        
                    </div>
                    {{-- <form class="col-md-4 input-group" style="width:33%" action="" method="get">
                    
                        <label class="input-group-text" for="inputGroupSelect01">{{ __('Filter') }}</label>
                        <select class="form-control @error('query') is-invalid @enderror" id="inputGroupSelect01" name="query">
                            {{-- @foreach ($academic_memberships as $academic_membership)
                                <option value="{{ $academic_membership->academic_membership_id }}">{{ $academic_membership->semester }}({{ $academic_membership->school_year }})</option>                          
                            @endforeach
                        </select>                        
                                @error('query')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        <button class="input-group-text btn-secondary"type="submit">Enter</button>
            
                    </form> --}}
                    
                </div>
            </div>
            @if (isset($errors) && $errors->any())
                <div class="alert alert-danger alert-dismissible mt-2">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                    @foreach ($errors->all() as $error )
                        {{ $error }}
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card-body table-responsive">        
                @if (isset($upcoming_events))
                    <table class="table table-light table-sm table-striped table-hover table-responsive" id="approval">
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
                                        <td>{{ date_format(date_create($upcoming_event->date), 'F d, Y') }}</td>
                                        <td>{{ $upcoming_event->title }}</td>
                                        <td>{{ $upcoming_event->head_organization }}</td>
                                        <td>{{ $upcoming_event->venue }}/{{ $upcoming_event->time }}</td>
                                        <td>                                            
                                            <button type="button" class="btn btn-success btn-sm mt-1" data-bs-toggle="modal" data-bs-target="#approved-event-form{{ $upcoming_event->upcoming_event_id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Approve Event">
                                                Approve
                                            </button> 
                                            @include('adviser.includes.approved')  
                                            <a href="{{ route('adviser.events.show', [$upcoming_event->upcoming_event_id , $upcoming_event->organization_id]) }}"class="btn btn-secondary btn-sm mt-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Display event details">Details</a>

                                            <button type="button" class="btn btn-danger btn-sm mt-1" data-bs-toggle="modal" data-bs-target="#disapproved-event-form{{ $upcoming_event->upcoming_event_id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Approve Event">
                                                Disapprove
                                            </button>   
                                            @include('adviser.includes.disapproved')        
                                                                           

                                        </td>
                                    </tr>
                                @endforeach
                            @else
                            <tr><td class="text-center"colspan="7">No results found!</td></tr>
                            @endif
                        </tbody>
                    </table>
                    {{-- {{ $upcoming_events->links() }} --}}
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
            const dataTable = new simpleDatatables.DataTable("#approval", {
                perPage: 5,
                searchable: true,
                labels: {
                    placeholder: "Search on current page...",
                    noRows: "No user to display in this page or try in the next page.",
                },
            });
        });
    </script>
@endsection