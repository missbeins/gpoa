@extends('layouts.officer')
@section('content')
<div class="container">
    
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
                            Events / Budget Breakdown
                        </li>
                    
                    </ol>
                </nav>
            </div>
           
            <div class="card">
                <div class="card-header "><h5>{{ __('Budget Breakdown') }}</h5>
                </div>
                <div class="card-body table-responsive">
                    <div class="text-center">
                        <h5>Event: {{ $upcoming_event->title }}</h5>
                        <h5>Projected budget: ₱ {{ $upcoming_event->projected_budget}}</h5>
                    </div>
                    @if (isset($breakdowns))
                        <table class="table table-light table-sm table-striped table-hover table-responsive" id="breakdowns">
                            <thead>
                                <tr>
                                    <th class="col-sm-5">Names</th>
                                    <th class="col-sm-5">Amounts</th>
                                    <th class="col-md-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($breakdowns->isNotEmpty())
                                    @foreach ($breakdowns as $breakdown)
                                        <tr>
                                            <td>{{ $breakdown->name }}</td>
                                            <td>{{ $breakdown->amount }}</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#edit{{ $breakdown->breakdown_id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Modify particular">
                                                    <i class="fas fa-edit me-2"></i>Edit
                                                </button>

                                                @include('officer.includes.edit-breakdown')
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                <tr class="text-center"><td colspan="7">No results found!</td></tr>
                                @endif
                            </tbody>
                        </table>
                        {{ $breakdowns->links() }}
                    @endif
                </div>
            </div>
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
            const dataTable = new simpleDatatables.DataTable("#breakdowns", {
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