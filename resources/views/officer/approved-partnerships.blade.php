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
                    Organization's Events / Reports / Accepted Partnerships
                    </li>
                   
                </ol>
            </nav>
        </div>      
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-8 mt-1">
                        <h5 class="float-left">Accepted Partnerships</h5>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">        
                @if (isset($approved_requests))
                    <table class="table table-light table-sm table-striped table-hover table-responsive" id="approvedevents">
                        <thead>
                            <tr>
                                <th class="col-sm-1">#</th>
                                <th class="col-sm-3">Name/Title of Activity</th>
                                <th class="col-sm-2">Approved by</th>
                                <th class="col-sm-2">Actions</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @if ($approved_requests->isNotEmpty())
                                @foreach ($approved_requests as $approved_request)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $approved_request->title }}</td>
                                        <td>{{ $approved_request->organization_name }}</td>
                                        <td>                                            
                                            <a href="{{ route('officer.events.show', [$approved_request->event_id, $approved_request->request_by]) }}"class="btn btn-secondary btn-sm mt-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Display event details">Details</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                            <tr class="text-center"><td colspan="7">No results found!</td></tr>
                            @endif
                        </tbody>
                    </table>
                    {{ $approved_requests->links() }}
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
            const dataTable = new simpleDatatables.DataTable("#approvedevents", {
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