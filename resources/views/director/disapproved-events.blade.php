@extends('layouts.director')
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
                    Organization's Events / Reports / Disapproved Events
                    </li>
                   
                </ol>
            </nav>
        </div>      
        <div class="card">
            <div class="card-header"  style="background-color: #c62128; color:azure; font-weight: bold;">
                <div class="row">
                    <div class="col-md-8 mt-1">
                        <h5 class="float-left"> Disapproved Events</h5>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">        
                @if (isset($disapproved_events))
                    <table class="table table-light table-sm table-striped table-hover table-responsive" id="disapprovedevents">
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
                            @if ($disapproved_events->isNotEmpty())
                                @foreach ($disapproved_events as $disapproved_event)
                                    <tr>
                                        <td>{{ date_format(date_create($disapproved_event->date), 'F d, Y') }}</td>
                                        <td>{{ $disapproved_event->title }}</td>
                                        <td>{{ $disapproved_event->head_organization }}</td>
                                        <td>{{ $disapproved_event->venue }} / {{ date_format(date_create($disapproved_event->time), 'H : i a')}}</td>
                                        <td>                                            
                                            <a href="{{ route('director.events.show', [$disapproved_event->upcoming_event_id, $disapproved_event->organization_id]) }}"class="btn btn-secondary btn-sm mt-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Display event details">Details</a>
                                            <button type="button" class="btn btn-warning btn-sm mt-1" data-bs-toggle="modal" data-bs-target="#reason{{ $disapproved_event->upcoming_event_id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="View Reasons">
                                                Reason/s
                                            </button>
                                            @include('director.includes.reason')
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                            <tr><td colspan="7">No results found!</td></tr>
                            @endif
                        </tbody>
                    </table>
                    {{ $disapproved_events->links() }}
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
            const dataTable = new simpleDatatables.DataTable("#disapprovedevents", {
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