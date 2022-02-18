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
                    Organization's Events / Partnerships
                    </li>
                   
                </ol>
            </nav>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-8 mt-1">
                        <h5 class="float-left">Available Partnership</h5>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">        
                @if (isset($available_partnerships))
                    <table class="table table-light table-sm table-striped table-hover table-responsive" id="available_partnerships">
                        <thead>
                            <tr>
                                <th class="col-sm-1">#</th></th>
                                <th class="col-sm-4">Name/Title of Activity</th>
                                <th class="col-sm-4">Head Organization</th>
                                <th class="col-sm-2">Actions</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @if ($available_partnerships->isNotEmpty())
                                @foreach ($available_partnerships as $available_partnership)
                                @if ()
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $available_partnership->title }}</td>
                                        <td>{{ $available_partnership->head_organization }}</td>
                                        <td>        
                                            <a href="{{ route('officer.availablePartnershipDetails',$available_partnership->upcoming_event_id) }}" class="btn btn-secondary btn-sm">Details</a>
                                            
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#apply{{ $available_partnership->upcoming_event_id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="View Reasons">
                                                Apply
                                            </button>
                                            @include('officer.includes.apply')                                   
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $available_partnership->title }}</td>
                                        <td>{{ $available_partnership->head_organization }}</td>
                                        <td>        
                                            <a href="{{ route('officer.availablePartnershipDetails',$available_partnership->upcoming_event_id) }}" class="btn btn-secondary btn-sm">Details</a>
                                            
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#apply{{ $available_partnership->upcoming_event_id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="View Reasons">
                                                Apply
                                            </button>
                                            @include('officer.includes.apply')                                   
                                        </td>
                                    </tr>
                                @endif
                                    
                                @endforeach
                            @else
                            <tr class="text-center"><td colspan="7">No results found!</td></tr>
                            @endif
                        </tbody>
                    </table>
                    {{ $available_partnerships->links() }}
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
            const dataTable = new simpleDatatables.DataTable("#available_partnerships", {
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