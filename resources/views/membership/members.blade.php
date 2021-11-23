@extends('layouts.dashboard')
@section('content')
    <div class="row my-5">
        <h3 class="fs-4 mb-3">Members</h3>
        <div class="col">
            <table class="table bg-white rounded shadow-sm table-striped table-hover">
                <thead>
                    <tr></tr>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Firstname</th>
                        <th scope="col">Lastname</th>
                        <th scope="col">Middlename</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Course</th>
                        <th scope="col">Year / Section</th>
                        <th scope="col">Action</th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">#</th>
                        <td>Penny</td>
                        <td>Wise</td>
                        <td>Co</td>
                        <td>Male</td>
                        <td>Bacheor of Science in Information Technology </td>
                        <td>BSIT 3-1</td>
                        <td>
                            <a class="btn btn-primary">Renew</a>
                            <a class="btn btn-danger">Decline</a>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">#</th>
                        <td>Penny</td>
                        <td>Wise</td>
                        <td>Co</td>
                        <td>Male</td>
                        <td>Bacheor of Science in Information Technology </td>
                        <td>BSIT 3-1</td>
                        <td>
                            <a class="btn btn-primary">Accept</a>
                            <a class="btn btn-danger">Decline</a>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">#</th>
                        <td>Penny</td>
                        <td>Wise</td>
                        <td>Co</td>
                        <td>Male</td>
                        <td>Bacheor of Science in Information Technology </td>
                        <td>BSIT 3-1</td>
                        <td>
                            <a class="btn btn-primary">Accept</a>
                            <a class="btn btn-danger">Decline</a>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">#</th>
                        <td>Penny</td>
                        <td>Wise</td>
                        <td>Co</td>
                        <td>Male</td>
                        <td>Bacheor of Science in Information Technology </td>
                        <td>BSIT 3-1</td>
                        <td>
                            <a class="btn btn-primary">Accept</a>
                            <a class="btn btn-danger">Decline</a>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">#</th>
                        <td>Penny</td>
                        <td>Wise</td>
                        <td>Co</td>
                        <td>Male</td>
                        <td>Bacheor of Science in Information Technology </td>
                        <td>BSIT 3-1</td>
                        <td>
                            <a class="btn btn-primary">Accept</a>
                            <a class="btn btn-danger">Decline</a>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">#</th>
                        <td>Penny</td>
                        <td>Wise</td>
                        <td>Co</td>
                        <td>Male</td>
                        <td>Bacheor of Science in Information Technology </td>
                        <td>BSIT 3-1</td>
                        <td>
                            <a class="btn btn-primary">Accept</a>
                            <a class="btn btn-danger">Decline</a>
                        </td>
                    </tr>

                    {{-- @foreach ($members as $item)
                        <tr>
                            <td>{{ $item->iteration }}</td>
                            <td>{{ $item->FIRSTNAME }}</td>
                            <td>{{ $item->LASTNAME }}</td>
                            <td>{{ $item->MIDDLENAME }}</td>
                            <td>{{ $item->AGE }}</td>
                            <td>{{ $item->DATE_OF_BIRTH }}</td>
                            <td>{{ $item->GENDER }}</td>
                            <td>{{ $item->COURSE }}</td>
                            <td>{{ $item->YEAR_SECTION }}</td>

                            <td>

                                <div class="dropdown">
                                    <button class="btn btn-danger btn-sm dropdown-toggle" type="button" id="actionDropdown"
                                        data-mdb-toggle="dropdown" aria-expanded="false">
                                        Action
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="actionDropdown">

                                        <li><a class="dropdown-item"
                                                href="{{ url('memberships/' . $item->id . '/edit') }}">Edit</a>
                                        </li> Edit
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form action="{{ url('memberships/' . $item->id) }}" method="post">

                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item">Delete</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach --}}

                </tbody>
            </table>
        </div>
    </div>
@endsection
