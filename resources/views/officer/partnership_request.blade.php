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
                    Organization's Events / Partnership Requests
                    </li>
                   
                </ol>
            </nav>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-8 mt-1">
                        <h5 class="float-left">Partnership Requests</h5>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">        
                @if (isset($partnership_requests))
                    <table class="table table-light table-sm table-striped table-hover table-responsive" id="partnership_requests">
                        <thead>
                            <tr>
                                
                                <th class="col-sm-1">#</th></th>
                                <th class="col-sm-4">Name/Title of Activity</th>
                                <th class="col-sm-4">Request By</th>
                                <th class="col-sm-2">Actions</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @if ($partnership_requests->isNotEmpty())
                                @foreach ($partnership_requests as $request)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $request->title }}</td>
                                        <td>{{ $request->organization_name }}</td>
                                        <td>                                             
                                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#accept-partnership{{ $request->event_id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="accept partnership">
                                                <i class="fas fa-thumbs-up me-2"></i>Accept
                                            </button>
                                            @include('officer.includes.accept-partnership')      
                                              
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#decline-partnership{{ $request->event_id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="decline partnership">
                                                <i class="fas fa-thumbs-down me-2"></i>Decline
                                            </button>
                                            @include('officer.includes.decline-partnership')      
                                        </td>
                                     
                                    </tr>
                                @endforeach
                            @else
                            <tr class="text-center"><td colspan="7">No results found!</td></tr>
                            @endif
                        </tbody>
                    </table>
                    {{-- {{ $partnership_requests->links() }} --}}
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
            const dataTable = new simpleDatatables.DataTable("#partnership_requests", {
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