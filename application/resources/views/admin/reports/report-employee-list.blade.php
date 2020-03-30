@extends('layouts.default')
    @section('meta')
    <title>{{__('admin.Reports')}} | {{__('admin.Smart Timesheet')}}</title>
    <meta name="description" content="smart timesheet reports, view reports, and export or download reports.">
    @endsection

    @section('content')
    
    <div class="container-fluid">
        <div class="row">
            <h2 class="page-title">{{__('admin.Employee List Report')}}
                <a href="{{ url('export/report/employees') }}" class="ui basic button mini offsettop5 btn-export float-right"><i class="ui icon download"></i> {{__('admin.Export to CSV')}}</a>
                <a href="{{ url('reports') }}" class="ui basic blue button mini offsettop5 float-right"><i class="ui icon chevron left"></i>{{__('admin.Return')}}</a>
            </h2>
        </div>

        <div class="row">
            <div class="box box-success">
                <div class="box-body">
                    <table width="100%" class="table table-striped table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>{{__('admin.Employee Name')}}</th>
                                <th>{{__('admin.Age')}}</th>
                                <th>{{__('admin.Gender')}}</th>
                                <th>{{__('admin.Civil Status')}}</th>
                                <th>{{__('admin.Mobile Number')}}</th>
                                <th>E-mail</th>
                                <th>{{__('admin.Employment Type')}}</th>
                                <th>{{__('admin.Employment Status')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($empList)
                            @foreach ($empList as $et)
                                <tr>
                                    <td>{{ $et->id }}</td>
                                    <td>{{ $et->lastname }}, {{ $et->firstname }} {{ $et->mi }}</td>
                                    <td>{{ $et->age }}</td>
                                    <td>{{ $et->gender }}</td>
                                    <td>{{ $et->civilstatus }}</td>
                                    <td>{{ $et->mobileno }}</td>
                                    <td>{{ $et->emailaddress }}</td>
                                    <td>{{ $et->employmenttype }}</td>
                                    <td>{{ $et->employmentstatus }}</td>
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
        $('#dataTables-example').DataTable({responsive: true,pageLength: 15,lengthChange: false,searching: true,});
    });
    </script>
    @endsection 