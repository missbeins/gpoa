@extends('layouts.officer')

@section('content')
    <div class="row justify-content-center">
         {{-- Title and Breadcrumbs --}}
         <div class="d-flex justify-content-between align-items-center">
           
            {{-- Breadcrumbs --}}
            <nav aria-label="breadcrumb align-items-center">
                <ol class="breadcrumb justify-content-center ">
                    <li class="breadcrumb-item">
                        <a href="{{route('officer.officer.home')}}" class="text-decoration-none">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                    Profile / Update Profile
                    </li>
                   
                </ol>
            </nav>
        </div>
        <div class="col-md-12">
            <form action="{{ route('officer.update-profile', Auth::user()->user_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">{{ __('Update Profile') }}
                        <button type="submit" class="btn btn-success btn-sm float-end">Save</button>
                    </div>
                    <div class="card-body">
                            <div class="row g-2 mb-2">
                                <div class="col-md">
                                    <label for="first_name">First Name</label>
                                    <div class="form-floating" id="first_name">
                                        <input type="text" class="form-control" id="floatingInputGrid" placeholder="name@example.com" value="{{ Auth::user()->first_name }}" name="first_name">
                                        <label for="floatingInputGrid">Input your Firstname</label>
                                    </div>
                                    @error('first_name')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                               
                                <div class="col-md">
                                    <label for="middle_name">Middle Name</label>
                                    <div class="form-floating" id="middle_name">
                                        <input type="text" class="form-control" id="floatingInputGrid" placeholder="name@example.com" value="{{ Auth::user()->middle_name }}" name="middle_name">
                                        <label for="floatingInputGrid">Input your Middlename</label></label>
                                    </div>
                                    @error('middle_name')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                                
                                <div class="col-md">
                                    <label for="last_name">Last Name</label>
                                    <div class="form-floating" id="last_name">
                                        <input type="text" class="form-control" id="floatingInputGrid" placeholder="name@example.com" value="{{ Auth::user()->last_name }}" name="last_name">
                                        <label for="floatingInputGrid">Input your Lastname</label>
                                    </div>
                                    @error('last_name')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                                <div class="col-md">
                                    <label for="suffix">Suffix</label>
                                    <div class="form-floating" id="suffix">
                                        <input type="text" class="form-control" id="floatingInputGrid" value="{{ Auth::user()->suffix }}" name="suffix">
                                        <label for="floatingInputGrid">Input your suffix</label>
                                    </div>
                                    @error('suffix')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-md-6">
                                    <label for="course">Course</label>
                                    <div class="form-floating" id="course">
                                        <select name="course_id" class="form-control" id="course_id" aria-label="Floating label select example">
                                            @foreach ($courses as $course)
                                                <option value="{{ $course->course_id }}"
                                                    {{ $course->course_id == auth()->user()->course_id ? 'selected' : '' }}
                                                    @error('course_id') is-invalid @enderror>
                                                    {{ $course->course_name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="course_id">Select Course</label>
                                    </div>
                                    @error('course_id')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="email">Email</label>
                                    <div class="form-floating" id="email">
                                        <input type="email" class="form-control" id="floatingInputGrid" placeholder="name@example.com" value="{{ Auth::user()->email }}" name="email">
                                        <label for="floatingInputGrid">Input your Email</label>
                                    </div>
                                    @error('email')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="studnum">Student Number</label>
                                    <div class="form-floating" id="studnum">
                                        <input type="text" class="form-control" id="floatingInputGrid" value="{{ Auth::user()->student_number }}" name="student_number" pattern="[0-9]{4}-[0-9]{5}-[A-Z]{2}-[0]{1}">
                                        <label for="floatingInputGrid">Input your Student Number</label>
                                        <small>Format: 2019-00000-TG-0</small>
                                    </div>
                                    @error('student_number')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-md">
                                    <label for="year_and_section">Year and Section</label>
                                    <div class="form-floating" id="year_n_section">
                                        <input type="text" class="form-control" id="floatingInputGrid" value="{{ Auth::user()->year_and_section }}" name="year_and_section" pattern="[0-5]{1}-[0-9]{1}">
                                        <label for="floatingInputGrid">Input your Year and Section</label>
                                        <small>Format: 1-1</small>
                                    </div>
                                    @error('year_and_section')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md">
                                    <label for="contact">Contact</label>
                                    <div class="form-floating" id="contact">
                                        <input type="text" class="form-control" id="floatingInputGrid"value="{{ Auth::user()->mobile_number }}" name="mobile_number">
                                        <label for="floatingInputGrid">Input your Contact Number</label></label>
                                    </div>
                                    @error('mobile_number')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md">
                                    <label for="gender">Gender</label>
                                    <div class="form-floating" id="gender">
                                        <select class="form-select" id="gender_id" aria-label="Floating label select example" name="gender_id">
                                            @foreach ($genders as $gender)
                                                <option value="{{ $gender->gender_id }}" {{ $gender->gender_id  == auth()->user()->gender_id ? 'selected' : '' }}>
                                                    {{ $gender->gender }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="gender_id">Input your Gender</label>
                                    </div>
                                    @error('gender_id')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                    </div>
                </div>
            </form>
            <div class="card mt-2">
                <div class="card-header">{{ __('Signature') }}</div>
                <div class="card-body">
                        <label for="signature">My signature</label>
                        @if (empty($event_signature))
                           <input type="text" name="" id="signature" readonly value=" No signature has been uploaded. Please upload your signature." style="width: 50%">
                           <button type="button" class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Add
                            </button>
        
                            @include('officer.includes.addsignature')
                        @else
                            <img src="{{ asset('signatures/'. $event_signature->signature_path) }}" alt="signature" style="width: 100px; height:100px; margin-left:10px;">
                        
                            <button type="button" class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#exampleModal{{ $event_signature->signature_id }}">
                                Update
                            </button>
                            @include('officer.includes.updatesignature')   
                        @endif          
                </div>
            </div>
        </div>
    </div>
@endsection
