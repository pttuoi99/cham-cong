@extends('layouts.default')
    @section('meta')
    <title>{{ __('admin.Dashboard') }} | {{ __('admin.Smart Timesheet') }}</title>
    <meta name="description" content="smart timesheet dashboard, view recent attendance, recent leaves of absence, and newest employees">
    @endsection

    @section('content')

        <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
            <h2 class="page-title">{{ __('admin.Dashboard') }}</h2>
            </div>    
        </div>

        <div class="text-center mb-4">
            <button type="button" class="btn btn-primary">
                <a href="{{url('clock')}}" target="_blank" class="item"><i class="ui icon clock outline"></i> Clock In/Out</a>
            </button>
        </div>

        <div class="row">

            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="ui icon user circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ __('admin.EMPLOYEES') }}</span>
                        <div class="progress-group">
                            <div class="progress sm">
                                <div class="progress-bar progress-bar-aqua" style="width: 100%"></div>
                            </div>
                            <div class="stats_d">
                                <table style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td>{{ __('admin.Regular') }}</td>
                                            <td>@isset($emp_typeR) {{ $emp_typeR }} @endisset</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('admin.Trainee') }}</td>
                                            <td>@isset($emp_typeT) {{ $emp_typeT }} @endisset</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="ui icon clock outline"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ __('admin.ATTENDANCES') }}</span>
                        <div class="progress-group">
                            <div class="progress sm">
                                <div class="progress-bar progress-bar-green" style="width: 100%"></div>
                            </div>
                            <div class="stats_d">
                                <table style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td>{{ __('admin.Online') }}</td>
                                            <td>@isset($is_online_now) {{ $is_online_now }} @endisset</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('admin.Offline') }}</td>
                                            <td>@isset($is_offline_now) {{ $is_offline_now }} @endisset</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-orange"><i class="ui icon home"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ __('admin.LEAVES OF ABSENCE') }}</span>
                        <div class="progress-group">
                            <div class="progress sm">
                                <div class="progress-bar progress-bar-orange" style="width: 100%"></div>
                            </div>
                            <div class="stats_d">
                                <table style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td>{{ __('admin.Approved') }}</td>
                                            <td>@isset($emp_leaves_approve) {{ $emp_leaves_approve }} @endisset</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('admin.Pending') }}</td>
                                            <td>@isset($emp_leaves_pending) {{ $emp_leaves_pending }} @endisset</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-4">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ __('admin.Newest Employees') }}</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                    <table class="table responsive nobordertop">
                        <thead>
                            <tr>
                                <th class="text-left">{{ __('admin.Name') }}</th>
                                <th class="text-left">{{ __('admin.Position') }}</th>
                                <th class="text-left">{{ __('admin.Start Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($emp_all_type)
                                @foreach ($emp_all_type as $data)
                                <tr>
                                    <td class="text-left name-title">{{ $data->lastname }}, {{ $data->firstname }}</td>
                                    <td class="text-left">{{ $data->jobposition }}</td>
                                    <td class="text-left">@php echo e(date('M d, Y', strtotime($data->startdate))) @endphp</td>
                                </tr>
                                @endforeach
                            @endisset
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ __('admin.Recent Attendances') }}</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table responsive nobordertop">
                        <thead>
                            <tr>
                                <th class="text-left">{{ __('admin.Name') }}</th>
                                <th class="text-left">{{ __('admin.Type') }}</th>
                                <th class="text-left">{{ __('admin.Time') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($a)
                            @foreach($a as $v)
    
                            @if($v->timein != null && $v->timeout == null)
                            <tr>
                                <td class="name-title">{{ $v->employee }}</td>
                                <td>{{ __('admin.Time-In') }}</td>
                                <td>@php echo e(date('h:i:s A', strtotime($v->timein))) @endphp</td>
                            </tr>
                            @endif
                            
                            @if($v->timein != null && $v->timeout != null)
                            <tr>
                                <td class="name-title">{{ $v->employee }}</td>
                                <td>{{ __('admin.Time-Out') }}</td>
                                <td>@php echo e(date('h:i:s A', strtotime($v->timeout))) @endphp</td>
                            </tr>
                            @endif

                            @if($v->timein != null && $v->timeout != null)
                            <tr>
                                <td class="name-title">{{ $v->employee }}</td>
                                <td>{{ __('admin.Time-In') }}</td>
                                <td>@php echo e(date('h:i:s A', strtotime($v->timein))) @endphp</td>
                            </tr>
                            @endif

                            @endforeach
                            @endisset
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        
            <div class="col-md-4">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ __('admin.Recent Leaves of Absence') }}</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                    <table class="table responsive nobordertop">
                        <thead>
                            <tr>
                                <th class="text-left">{{ __('admin.Name') }}</th>
                                <th class="text-left">{{ __('admin.Date') }}</th>
                            </tr>
                        </thead>
                            <tbody>
                                @isset($emp_approved_leave)
                                @foreach ($emp_approved_leave as $leaves)
                                <tr>
                                    <td class="text-left name-title">{{ $leaves->employee }}</td>
                                    <td class="text-left">@php echo e(date('M d, Y', strtotime($leaves->leavefrom))) @endphp</td>
                                </tr>
                                @endforeach
                                @endisset
                            </tbody>
                    </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @endsection
    
