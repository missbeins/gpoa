<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Membership') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">


</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->

        <div id="sidebar-wrapper">
            {{-- <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom">

                @can('is-admin')
                    <a class="second-text brand" href="{{ route('membership.admin.users.index') }}"><i
                            class="fas fa-swatchbook me-2"></i>Membership</a>
                @elsecan('is-student')
                    <a class="second-text brand" href="{{ route('membership.user.my-organizations') }}"><i
                            class="fas fa-swatchbook me-2"></i>Membership</a>
                @endcan
            </div> --}}
            <div class="list-group list-group-flush my-3" id="myList">
                {{-- @can('is-admin') --}}
                    <a href=""
                        class="list-group-item list-group-item-action second-text fw-bold"><i
                            class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
                    <a href=""
                        class="list-group-item list-group-item-action  second-text fw-bold "><i
                            class="fas fa-users me-2"></i>Members</a>
                    <a href=""
                        class="list-group-item list-group-item-action second-text fw-bold"><i
                            class="fas fa-money-check me-2"></i>Membership Fees</a>
                    <a href=""
                        class="list-group-item list-group-item-action second-text fw-bold"><i
                            class="fas fa-address-book me-2"></i>Applications</a>
                    {{-- <a href="#" class="list-group-item list-group-item-action second-text fw-bold"><i
                            class="fas fa-paperclip me-2"></i>Reports</a> --}}

                {{-- @elsecan('is-student') --}}
                    <a href=""
                        class="list-group-item list-group-item-action  second-text fw-bold "><i
                            class="fas fa-address-card me-2"></i>My Organizations</a>
                    <a href=""
                        class="list-group-item list-group-item-action second-text fw-bold"><i
                            class="fas fa-money-check me-2"></i>My Memberships</a>
                    <a href=""
                        class="list-group-item list-group-item-action second-text fw-bold"><i
                            class="fas fa-address-book me-2"></i>My Applications</a>
                {{-- @endcan --}}

                <a class=" list-group-item list-group-item-action second-text fw-bold" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                document.getElementById('logout-form').submit();"><i class="fas fa-power-off me-2"></i>
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>

        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent py-4 px-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-align-left primary-text fs-4 me-3" id="menu-toggle"></i>
                    <h2 class="fs-2 m-0">Dashboard</h2>
                </div>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle second-text fw-bold" href="#" id="navbarDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{-- {{ auth()->user()->last_name }}, {{ auth()->user()->first_name }}
                                {{ auth()->user()->middle_name }} --}}
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @can('is-admin')
                                    <li><a class="dropdown-item" href=""><i
                                                class="fas fa-user me-2"></i>Profile</a></li>
                                @endcan
                                <li><a class="dropdown-item" href=""><i
                                            class="fas fa-user-lock me-2"></i>Change Password</a></li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();"><i class="fas fa-power-off me-2"></i>
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            {{-- Table --}}
            <div class="container-fluid">
                @include('alerts.alerts')
                @yield('content')
            </div>

        </div>
    </div>
    <!-- /#page-content-wrapper -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
    <script>
        var el = document.getElementById("wrapper");
        var toggleButton = document.getElementById("menu-toggle");

        toggleButton.onclick = function() {
            el.classList.toggle("toggled");
        };
    </script>
    <script>
        // Get the container element
        var btnContainer = document.getElementById("myList");

        // Get all buttons with class="btn" inside the container
        var btns = btnContainer.getElementsByClassName("list-group-item");

        // Loop through the buttons and add the active class to the current/clicked button
        for (var i = 0; i < btns.length; i++) {
            btns[i].addEventListener("click", function() {
                var current = document.getElementsByClassName("active");
                current[0].className = current[0].className.replace(" active", "");
                this.className += " active";
            });
        }
    </script>
</body>

</html>
