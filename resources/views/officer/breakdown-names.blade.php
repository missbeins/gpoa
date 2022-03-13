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
            @endif
            <div class="card">
                <div class="card-header"><h5>{{ __('Budget Breakdown') }}</h5></div>
                <div class="card-body">
                    <div class="text-center">
                        <h5>Event: {{ $event_name['title'] }}</h5>
                        <h5>Projected budget: ₱ {{ $projected_budget['projected_budget']}}</h5>
                    </div>
                    <form class="row g-3" method="POST" action="{{ route('officer.budgetBreakdown',$event_id) }}">
                        @csrf
                        <h6>Particulars</h6>
                        <div class="input-group hdtuto control-group lst increment" >
    
                            <input type="text" id="name[]" name="name[]" class="form-control" placeholder="Name" required>
                            
                            <input type="number" id="amount[]" name="amount[]" class="form-control" placeholder="Amount" required>
                            
                            <div class="input-group-btn"> 
                              <button class="btn btn-success" type="button"><i class="fas fa-plus"></i> Add</button>
                            </div>
                        </div>
                        <div class="clone">
                            <div class="hdtuto control-group lst input-group" style="margin-top:10px">
                                <input type="text" id="name[]" name="name[]" class="form-control" placeholder="Name" required>
                                
                                <input type="number" id="amount[]" name="amount[]" class="form-control" placeholder="Amount" required>
                               
                                <div class="input-group-btn"> 
                                    <button class="btn btn-danger" type="button"><i class="fas fa-minus"></i> Remove</button>
                                </div>
                            </div>
                        </div>       
                            
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