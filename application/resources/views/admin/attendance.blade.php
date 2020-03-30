@extends('layouts.default')
    @section('meta')
    <title>{{ __('admin.Attendances') }} | {{ __('admin.Smart Timesheet') }}</title>
    <meta name="description" content="smart timesheet attendance, view all employee attendances, clock-in, edit, and delete attendances.">
    @endsection
    
    @section('content')

    <div class="container-fluid">
        <div class="row">
            <h2 class="page-title">{{ __('admin.Attendances') }}
                <a href="{{ url('clock') }}" class="ui positive button mini offsettop5 float-right"><i class="ui icon clock"></i>Clock In/Out</a>
            </h2>
        </div>  

        <div class="row">
            <div class="box box-success">
                <div class="box-body">
                    <table width="100%" class="table table-striped table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>ID #</th>
                                <th>{{ __('admin.Date') }}</th>
                                <th>{{ __('admin.Employee') }}</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>{{ __('admin.Total Hours') }}</th>
                                <th>{{ __('admin.Status') }} (In / Out)</th>
                                @isset($clock_comment)
                                    @if($clock_comment == 1)
                                        <th>{{ __('admin.Comment') }}</th>
                                    @endif
                                @endisset
                                <th>IP (In / Out)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($data)
                            @foreach ($data as $d)
                            <tr>
                                <td>{{ $d->idno }}</td>
                                <td>{{ $d->date }}</td>
                                <td>{{ $d->employee }}</td>
                                <td>@php $IN = date('h:i:s A', strtotime($d->timein)); echo $IN; @endphp</td>
                                <td>
                                    @isset($d->timeout)
                                        @php 
                                            $OUT = date('h:i:s A', strtotime($d->timeout));
                                        @endphp
                                        @if($d->timeout != NULL)
                                            {{ $OUT }}
                                        @endif
                                    @endisset
                                </td>
                                <td class="">
                                    @isset($d->totalhours)
                                        @if($d->totalhours != null) 
                                            @php
                                                if(stripos($d->totalhours, ".") === false) {
                                                    $h = $d->totalhours;
                                                } else {
                                                    $HM = explode('.', $d->totalhours); 
                                                    $h = $HM[0]; 
                                                    $m = $HM[1];
                                                }
                                            @endphp
                                        @endif
                                        @if($d->totalhours != null)
                                            @if(stripos($d->totalhours, ".") === false) 
                                                {{ $h }} hr
                                            @else 
                                                {{ $h }} hr {{ $m }} minutes
                                            @endif
                                        @endif
                                    @endisset
                                </td>
                                <td>
                                    @if($d->status_timein != null OR $d->status_timeout != null) 
                                        <span class="@if($d->status_timein == 'Late Arrival') orange @else blue @endif">{{ $d->status_timein }}</span> / 
                                        
                                        @isset($d->status_timeout) 
                                            <span class="@if($d->status_timeout == 'Early Departure') red @else green @endif">
                                                {{ $d->status_timeout }}
                                            </span> 
                                        @endisset
                                    @else
                                        <span class="blue">{{ $d->status_timein }}</span>
                                    @endif 
                                </td>
                                @isset($clock_comment)
                                    @if($clock_comment == 1)
                                        <td>{{ $d->comment }}</td>
                                    @endif
                                @endisset
                                <td>
                                    @if($d->ipin OR $d->ipout) 
                                        <span>{{ $d->ipin }}</span> / 
                                        
                                        @isset($d->ipout) 
                                            <span>{{ $d->ipout }}</span>
                                        @endisset
                                    @else
                                        <span>{{ $d->ipin }}</span>
                                    @endif 
                                </td>
                                <td class="align-right">
                                    <a href="{{ url('/attendance/edit/'.$d->id) }}" class="ui circular basic icon button tiny"><i class="edit outline icon"></i></a>
                                    <a href="{{ url('/attendance/delete/'.$d->id) }}" class="ui circular basic icon button tiny"><i class="trash alternate outline icon"></i></a>
                                </td>
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div>

    @endsection

    @section('scripts')
    <script type="text/javascript">
    $(document).ready(function() {
        $('#dataTables-example').DataTable({responsive: true,pageLength: 15,lengthChange: false,searching: true,sorting: false,});
    });
    </script> 

    @endsection