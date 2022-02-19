@extends('layouts.adviser')

@section('content')
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
      <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
    </symbol>
</svg>
    <div class="row justify-content-center">
         {{-- Title and Breadcrumbs --}}
         <div class="d-flex justify-content-between align-items-center">
           
            {{-- Breadcrumbs --}}
            <nav aria-label="breadcrumb align-items-center">
                <ol class="breadcrumb justify-content-center ">
                    <li class="breadcrumb-item">
                        <a href="{{route('adviser.adviser.home')}}" class="text-decoration-none">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                    Profile / Update Profile
                    </li>
                   
                </ol>
            </nav>
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
        <div class="col-md-12">
            <form action="{{ route('adviser.update-profile', Auth::user()->user_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header" style="background-color: #c62128; color:azure; font-weight: bold;">{{ __('Update Profile') }}
                        <button type="submit" class="btn btn-success btn-sm float-end">Save</button>
                    </div>
                    <div class="card-body">
                            <div class="row g-2 mb-2">
                                <div class="col-md">
                                    <label for="first_name">First Name<span class="text-danger">*</span></label>
                                    <div class="form-floating" id="first_name">
                                        <input type="text" class="form-control" id="floatingInputGrid" required value="{{ Auth::user()->first_name }}" name="first_name">
                                        <label for="floatingInputGrid">Input your Firstname</label>
                                    </div>
                                </div>
                               
                                <div class="col-md">
                                    <label for="middle_name">Middle Name</label>
                                    <div class="form-floating" id="middle_name">
                                        <input type="text" class="form-control" id="floatingInputGrid"  value="{{ Auth::user()->middle_name }}" name="middle_name">
                                        <label for="floatingInputGrid">Input your Middlename</label></label>
                                    </div>
                                </div>
                                
                                <div class="col-md">
                                    <label for="last_name">Last Name<span class="text-danger">*</span></label>
                                    <div class="form-floating" id="last_name">
                                        <input type="text" class="form-control" id="floatingInputGrid" required value="{{ Auth::user()->last_name }}" name="last_name">
                                        <label for="floatingInputGrid">Input your Lastname</label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <label for="suffix">Suffix</label>
                                    <div class="form-floating" id="suffix">
                                        <input type="text" class="form-control" id="floatingInputGrid" value="{{ Auth::user()->suffix }}" name="suffix">
                                        <label for="floatingInputGrid">Input your Suffix</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-2 mb-2">
                                {{-- <div class="col-md-6">
                                    <label for="course">Course</label>
                                    <div class="form-floating" id="course">
                                        <select name="course_id" class="form-control" id="course_id" aria-label="Floating label select example">
                                            <option disabled selected>Select course</option>
                                            @foreach ($courses as $course)
                                                <option value="{{ $course->course_id }}" @isset($course){{ $course->course_id == auth()->user()->course_id ? 'selected' : '' }}@endisset>
                                                 
                                                    {{ $course->course_name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="course_id">Select Course</label>
                                    </div>
                                </div> --}}
                                <div class="col-md-3">
                                    <label for="email">Email<span class="text-danger">*</span></label>
                                    <div class="form-floating" id="email">
                                        <input type="email" class="form-control" id="floatingInputGrid" required value="{{ Auth::user()->email }}" name="email">
                                        <label for="floatingInputGrid">Input your Email</label>
                                    </div>
                                </div>
                                {{-- <div class="col-md-4">
                                    <label for="studnum">Student Number</label>
                                    <div class="form-floating" id="studnum">
                                        <input type="text" class="form-control" id="floatingInputGrid" required value="{{ Auth::user()->student_number }}" name="student_number">
                                        <label for="floatingInputGrid">Input your Student Number</label></label>
                                    </div>
                                </div> --}}
                                <div class="col-md-3">
                                    <label for="contact">Contact<span class="text-danger">*</span></label>
                                    <div class="form-floating" id="contact">
                                        <input type="text" class="form-control" id="floatingInputGrid" value="{{ Auth::user()->mobile_number }}" name="mobile_number" required>
                                        <label for="floatingInputGrid">Input your Contact Number</label></label>
                                    </div>
                                </div>
                         
                                {{-- <div class="col-md">
                                    <label for="year_n_section">Year and Section</label>
                                    <div class="form-floating" id="year_n_section">
                                        <input type="text" class="form-control" id="floatingInputGrid" value="{{ Auth::user()->year_and_section }}" name="year_and_section">
                                        <label for="floatingInputGrid">Input your Year and Section</label>
                                    </div>
                                </div> --}}
                               
                                <div class="col-md-3">
                                    <label for="gender">Gender</label>
                                    <div class="form-floating" id="gender">
                                        <select class="form-select" id="gender_id" aria-label="Floating label select example" name="gender_id">
                                            @foreach ($genders as $gender)
                                            <option value="{{ $gender->gender_id }}" {{ $gender->gender_id  == auth()->user()->gender_id ? 'selected' : '' }}>
                                                {{ $gender->gender }}</option>
                                            @endforeach
                                    </select>
                                        </select>
                                        <label for="gender_id">Input your Gender</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="title">Title</label>
                                    <div class="form-floating" id="contact">
                                        <input type="text" class="form-control" id="floatingInputGrid"value="{{ Auth::user()->title }}" name="title">
                                        <label for="floatingInputGrid">Input your Title(Ms/Mr/Mrs/Dr)</label></label>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </form>
           
            <div class="card mt-2">
                <div class="card-header" style="background-color: #c62128; color:azure; font-weight: bold;">{{ __('Signature') }}</div>
                <div class="card-body">
                        <label for="signature">My signature</label>
                        @if (empty($event_signature->signature_path))
                           <input type="text" name="" id="signature" readonly value=" No signature has been uploaded. Please upload your signature." style="width: 50%">
                           <button type="button" class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Add
                            </button>
        
                            @include('adviser.includes.addsignature')
                        @else
                            <img src="{{ asset('signatures/'. $event_signature->signature_path) }}" alt="signature" style="width: 100px; height:100px; margin-left:10px;">
                        
                            <button type="button" class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#exampleModal{{ $event_signature->signature_id }}">
                                Update
                            </button>
                            @include('adviser.includes.updatesignature')   
                        @endif          
                </div>
            </div>
        </div>
    </div>
@endsection
