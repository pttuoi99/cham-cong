<!doctype html>
<!--
* Smart Timesheet: Time and Attendance Management System
* Email: official.smarttimesheet@gmail.com
* Version: 3.0
* Author: Brian Luna
* Copyright 2018 Brian Luna
* Website: https://github.com/brianluna/smarttimesheet
-->
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
        <meta name="viewport" content="width=device-width" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        @yield('meta')

        <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/bootstrap/css/bootstrap.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/semantic-ui/semantic.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/DataTables/datatables.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/style.css') }}">
        
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="{{ asset('/assets/js/html5shiv.js') }}></script>
            <script src="{{ asset('/assets/js/respond.min.js') }}"></script>
        <![endif]-->

        @yield('styles')
    </head>
    <body>

        <div class="wrapper">
        
        <nav id="sidebar" class="active">
            <div class="sidebar-header bg-lightblue">
                <div class="logo">
                <a href="/" class="simple-text">
                    <img src="{{ asset('/assets/images/img/logo-small.png') }}">
                </a>
                </div>
            </div>

            <ul class="list-unstyled components">
                <li class="">
                    <a href="{{ url('dashboard') }}">
                        <i class="ui icon sliders horizontal"></i>
                    <p>{{__('admin.Dashboard')}}</p>
                    </a>
                </li>

                <li class="">
                    <a href="{{ url('employees') }}">
                        <i class="ui icon users"></i>
                        <p>{{__('admin.Employees')}}</p>
                    </a>
                </li>
                    
                <li class="">
                    <a href="{{ url('attendance') }}">
                        <i class="ui icon clock outline"></i>
                        <p>{{__('admin.Attendances')}}</p>
                    </a>
                </li>

                <li class="">
                    <a href="{{ url('schedules') }}">
                        <i class="ui icon calendar alternate outline"></i>
                        <p>{{__('admin.Schedules')}}</p>
                    </a>
                </li>
                
                <li class="">
                    <a href="{{ url('leaves') }}">
                        <i class="ui icon calendar plus outline"></i>
                        <p>{{__('admin.Leave')}}</p>
                    </a>
                </li>
                <li class="">
                    <a href="{{ url('reports') }}">
                        <i class="ui icon chart bar outline"></i>
                        <p>{{__('admin.Reports')}}</p>
                    </a>
                </li>
                <li>
                    <a href="{{ url('users') }}">
                        <i class="ui icon user circle outline"></i>
                        <p>{{__('admin.Users')}}</p>
                    </a>
                </li>
                <li>
                    <a href="{{ url('settings') }}">
                        <i class="ui icon cog"></i>
                        <p>{{__('admin.Settings')}}</p>
                    </a>
                </li>
            </ul>
        </nav>

        <div id="body" class="active">
            <nav class="navbar navbar-expand-lg navbar-light bg-lightblue">
                <div class="container-fluid">

                    <button type="button" id="slidesidebar" class="ui icon button btn-light-outline">
                        <i class="ui icon bars"></i> Menu
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto navmenu">
                            <li class="nav-item">
                                <div class="ui pointing link dropdown item" tabindex="0">
                                    <i class="ui icon th"></i> <span class="navmenutext">{{__('admin.Quick Access')}}</span>
                                    <i class="dropdown icon"></i>
                                    <div class="menu" tabindex="-1">
                                      <a href="{{ url('employees/new') }}" class="item"><i class="ui icon user plus"></i> {{__('admin.Add Employee')}}</a>
                                      <a href="{{ url('clock') }}" target="_blank" class="item"><i class="ui icon clock outline"></i> {{__('admin.Clock In/Out')}}</a>
                                      <div class="divider"></div>
                                      <a href="{{ url('fields/company') }}" class="item"><i class="ui icon university"></i> {{__('admin.Company')}}</a>
                                      <a href="{{ url('fields/department') }}" class="item"><i class="ui icon cubes"></i> {{__('admin.Department')}}</a>
                                      <a href="{{ url('fields/jobtitle') }}" class="item"><i class="ui icon pencil alternate"></i> {{__('admin.Job Title')}}</a>
                                      <a href="{{ url('fields/leavetype') }}" class="item"><i class="ui icon calendar alternate outline"></i> {{__('admin.Leave Type')}}</a>
                                    </div>
                              </div>
                            </li>
                            <li class="nav-item">
                               <div class="ui pointing link dropdown item" tabindex="0">
                                    <i class="ui icon user outline"></i> <span class="navmenutext">@isset(Auth::user()->name) {{ Auth::user()->name }} @endisset</span>
                                    <i class="dropdown icon"></i>
                                    <div class="menu" tabindex="-1">
                                      <a href="{{ url('update-profile') }}" class="item"><i class="ui icon user"></i> {{__('admin.Update Account')}}</a>
                                      <a href="{{ url('update-password') }}" class="item"><i class="ui icon lock"></i> {{__('admin.Change Password')}}</a>
                                      <a href="{{ url('personal/dashboard') }}" target="_blank" class="item"><i class="ui icon sign-in"></i> {{__('admin.Switch to MyAccount')}}</a>
                                      <div class="divider"></div>
                                      <a href="{{ url('logout') }}" class="item"><i class="ui icon power"></i> {{__('admin.Logout')}}</a>
                                    </div>
                                </div>
                            </li>
                            @if(session('locale') == "en")
                                <li class="nav-item">
                                    <div class="ui pointing link item" tabindex="0">
                                        <a href="{{ url('lang/vi') }}"><img width="25px" src="{{ asset('/assets/images/img/vn-flag.png') }}" title="Tiếng Việt"></a>
                                    </div>
                                </li>
                            @endif

                            @if(session('locale') == "vi")
                                <li class="nav-item">
                                    <div class="ui pointing link item" tabindex="0">
                                        <a href="{{ url('lang/en') }}"><img width="25px" src="{{ asset('/assets/images/img/usa-flag.png') }}" title="English"></a>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="{{ asset('/assets/vendor/jquery/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/semantic-ui/semantic.min.js') }}"></script>
    <script src="{{ asset('/assets/js/bootstrap-notify.js') }}"></script>
    <script src="{{ asset('/assets/vendor/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('/assets/js/script.js') }}"></script>
    @if ($success = Session::get('success'))
    <script>
        $(document).ready(function() {
            $.notify({
                icon: 'ui icon check',
                message: "{{ $success }}"},
                {type: 'success',timer: 400}
            );
        });
    </script>
    @endif

    @if ($error = Session::get('error'))
    <script>
        $(document).ready(function() {
            $.notify({
                icon: 'ui icon times',
                message: "{{ $error }}"},
                {type: 'danger',timer: 400});
        });
    </script>
    @endif

    @yield('scripts')

    </body>
</html>