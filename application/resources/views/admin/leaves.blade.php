@extends('layouts.default')
    @section('meta')
    <title>{{ __('admin.Leaves of Absence') }} | {{ __('admin.Smart Timesheet') }}</title>
    <meta name="description" content="smart timesheet leave of absence, view all employee leaves of absence, edit, comment, and approve or deny leave requests.">
    @endsection 

    @section('content')

    <div class="container-fluid">
        <div class="row">
            <h2 class="page-title">{{ __('admin.Leaves of Absence') }}</h2>
        </div>

        <div class="row">
            <div class="box box-success">
                <div class="box-body">
                    <table width="100%" class="table table-striped table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>ID #</th>
                                <th>{{ __('admin.Employee') }}</th>
                                <th>{{ __('admin.Description') }}</th>
                                <th>{{ __('admin.Leave From') }}</th>
                                <th>{{ __('admin.Leave To') }}</th>
                                <th>{{ __('admin.Return Date') }}</th>
                                <th>{{ __('admin.Status') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($leaves)
                            @foreach ($leaves as $data)
                            <tr>
                                <td>{{ $data->idno }}</td>
                                <td>{{ $data->employee }}</td>
                                <td>{{ $data->type }}</td>
                                <td>@php echo e(date('D, M d, Y', strtotime($data->leavefrom))) @endphp</td>
                                <td>@php echo e(date('D, M d, Y', strtotime($data->leaveto))) @endphp</td>
                                <td>@php echo e(date('M d, Y', strtotime($data->returndate))) @endphp</td>
                                <td><span class="">{{ $data->status }}</span></td>
                                <td class="align-right">
                                    <a href="{{ url('leaves/edit/'.$data->id) }}" class="ui circular basic icon button tiny"><i class="icon edit outline"></i></a>
                                    <a href="{{ url('leaves/delete/'.$data->id) }}" class="ui circular basic icon button tiny"><i class="icon trash alternate outlin"></i></a>
                                
                                    @isset($data->comment)
                                        <button class="ui circular basic icon button tiny uppercase" data-tooltip='{{ $data->comment }}' data-variation='wide' data-position='top right'><i class="ui icon comment alternate"></i></button>
                                    @endisset
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
        $('#dataTables-example').DataTable({responsive: true,pageLength: 15,lengthChange: false,searching: true,});        
    });
    </script>

    @endsection