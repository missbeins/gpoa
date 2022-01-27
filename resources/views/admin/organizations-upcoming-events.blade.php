@extends('layouts.admin')
@section('content')
    
    <div class="mt-3">
        {{-- Title and Breadcrumbs --}}
        <div class="d-flex justify-content-between align-items-center">
           
            {{-- Breadcrumbs --}}
            <nav aria-label="breadcrumb align-items-center">
                <ol class="breadcrumb justify-content-center ">
                    
                    <li class="breadcrumb-item active" aria-current="page">
                    Organization's Events / Upcoming Events
                    </li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <form class="col-md-4 input-group mb-2" style="width:33%" action="{{ route('admin.searchEvents') }}" method="GET">
                    
                <div class="input-group flex-nowrap">
                    <label class="input-group-text" for="inputGroupSelect01">{{ __('Search') }}</label>
                    <input type="text" class="form-control" placeholder="Input the event title.." aria-label="query" aria-describedby="addon-wrapping" name="query">
    
                    @error('query')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <button class="input-group-text btn btn-secondary" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>            
        </div>
        
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-8 mt-1">
                        <h5 class="float-left"> Upcoming Events</h5>
                    </div>                  
                </div>
            </div>
            <div class="card-body table-responsive text-center">        
                @if (isset($upcoming_events))
                    <table class="table table-light table-sm table-striped table-hover table-responsive">
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
                                        <td>{{ $upcoming_event->organization_name }}</td>
                                        <td>{{ $upcoming_event->venue }} / {{ date_format(date_create($upcoming_event->time), 'H : i a')}}</td>
                                        <td>                                            
                                            <a href="{{ route('admin.events.show', [$upcoming_event->upcoming_event_id, $upcoming_event->organization_id]) }}"class="btn btn-secondary btn-sm mt-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Display event details">Details</a>
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