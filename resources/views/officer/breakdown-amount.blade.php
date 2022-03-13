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
                            Add New Events / Budget Breakdown
                        </li>
                    
                    </ol>
                </nav>
            </div>
            {{-- @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Sorry!</strong> There were more problems with your HTML input.<br><br>
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
            @endif
            
            @if(session('success'))
            <div class="alert alert-success">
            {{ session('success') }}
            </div> 
            @endif --}}
            <div class="card">
                <div class="card-header"><h5>{{ __('Budget Breakdown') }}</h5></div>
                <div class="card-body">
                    <div class="text-center">
                        <h5>Event: {{ $event_name['title'] }}</h5>
                        <h5>Projected budget: ₱ {{ $projected_budget['projected_budget']}}</h5>
                    </div>
                    <form class="row g-3" method="POST" action="{{ route('officer.budgetBreakdownAmount',$event_id) }}">
                        @csrf
                        <input type="hidden" name="projected_budget" value="{{ $projected_budget['projected_budget'] }}">
                        <h6>Particulars</h6>
                        @foreach ($breakdown_names as $name)
                            <input type="text" value="{{ $name->name }}" readonly>
                            <input type="number" placeholder="Add amount" name="amount[]"required>
                        @endforeach
                            
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
