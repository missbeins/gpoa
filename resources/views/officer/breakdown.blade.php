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
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Sorry!</strong> There were more problems with your input.<br><br>
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
            @endif
            
            {{-- @if(session('success'))
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
                    <form class="row g-3" method="POST" action="{{ route('officer.budgetBreakdown',$event_id) }}">
                        @csrf
                        <input type="hidden" name="projected_budget" value="{{ $projected_budget['projected_budget'] }}">
                        <h6>Particulars</h6>
                        {{-- @foreach ($particulars as  $particular)
                            <div class="col-md-6 mb-2">
                                <label for="name" class="form-label">{{ __('Name') }}</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                    value="{{ old('name') }}" required
                                    autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div> 
                            <div class="col-md-6 mb-2">
                                <label for="amount" class="form-label">{{ __('Amount') }}</label>
                                <input id="amount" type="number" class="form-control @error('amount') is-invalid @enderror" name="amount"
                                    value="{{ old('amount') }}" required
                                    autocomplete="amount" autofocus>

                                @error('amount')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        @endforeach
                        <div class="col-md-6 mb-2">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                value="{{ old('name') }}" required
                                autocomplete="name" autofocus>

                            @error('name')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div> 
                        <div class="col-md-6 mb-2">
                            <label for="amount" class="form-label">{{ __('Amount') }}</label>
                            <input id="amount" type="number" class="form-control @error('amount') is-invalid @enderror" name="amount"
                                value="{{ old('amount') }}" required
                                autocomplete="amount" autofocus>

                            @error('amount')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div> --}}
                        <div class="input-group hdtuto control-group lst increment" >
                            <input type="text" name="names[]" class=" form-control" placeholder="name">
                            <input type="number" name="amount[]" class=" form-control" placeholder="amount">
                            <div class="input-group-btn"> 
                                <button class="btn btn-success" type="button"><i class="fldemo glyphicon glyphicon-plus"></i>Add</button>
                            </div>
                        </div>
                        <div class="clone">
                            <div class="hdtuto control-group input-group" style="margin-top:10px">
                                <input type="text" name="names[]" class=" form-control" placeholder="name">
                                <input type="number" name="amount[]" class=" form-control" placeholder="amount">
                                <div class="input-group-btn"> 
                                <button class="btn btn-danger" type="button"><i class="fldemo glyphicon glyphicon-remove"></i> Remove</button>
                                </div>
                            </div>
                        </div>
                    
                        <button type="submit" class="btn btn-primary" style="margin-top:10px">Submit</button>
  
                        
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

<script type="text/javascript">
    $(document).ready(function() {
      $(".btn-success").click(function(){ 
          var lsthmtl = $(".clone").html();
          $(".increment").after(lsthmtl);
      });
      $("body").on("click",".btn-danger",function(){ 
          $(this).parents(".hdtuto").remove();
      });
    });
</script>
@endsection
