@extends('layouts.admin')
@section('page-title')
    {{ __('P11 Report') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Timesheet') }}</li>
@endsection


@section('content')
    {{-- <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
        <div class=" mt-2 " id="multiCollapseExample1" style="">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => ['timesheet.index'], 'method' => 'get', 'id' => 'timesheet_filter']) }}
                    <div class="d-flex align-items-center justify-content-end">
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                            <div class="btn-box">
                                {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}
                                {{ Form::text('start_date', isset($_GET['start_date']) ? $_GET['start_date'] : '', ['class' => 'month-btn form-control d_week current_date', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                            <div class="btn-box">
                                {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}
                                {{ Form::text('end_date', isset($_GET['end_date']) ? $_GET['end_date'] : '', ['class' => 'month-btn form-control d_week current_date', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        @if (\Auth::user()->type == 'employee')
                            <!-- {!! Form::hidden('employee', !empty($employeesList) ? $employeesList->id : 0, ['id' => 'employee_id']) !!} -->
                        @else
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                                <div class="btn-box">
                                    {{ Form::label('employee', __('Employee'), ['class' => 'form-label']) }}
                                    <!-- {{ Form::select('employee', $employeesList, isset($_GET['employee']) ? $_GET['employee'] : '', ['class' => 'form-control select ', 'id' => 'employee_id']) }} -->
                                </div>
                            </div>
                        @endif
                        <div class="col-auto float-end ms-2 mt-4">
                            <a href="#" class="btn btn-sm btn-primary"
                                onclick="document.getElementById('timesheet_filter').submit(); return false;"
                                data-bs-toggle="tooltip" title="" data-bs-original-title="apply">
                                <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                            </a>
                            <a href="{{ route('timesheet.index') }}" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                title="" data-bs-original-title="Reset">
                                <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                            </a>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div> --}}

        <div class="col-sm-12">
            <div class="mt-2" id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => ['timesheet.index'], 'method' => 'get', 'id' => 'timesheet_filter']) }}
                        <div class="row align-items-center justify-content-end">
                            <div class="col-xl-10">
                                <div class="row">
                                    
                                </div>
                            </div>
                            <div class="col-auto">
                                
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>

    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    @if (\Auth::user()->type != 'employee')
                                    <th>{{ __('Employee Id') }}</th>
                                    <th>{{ __('Employee Name') }}</th>
                                    @endif
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Requested Date') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                            @foreach ($currentMonthRecords as $records)
                                    <tr>
                                        @if (\Auth::user()->type != 'employee')
                                            <td>{{ !empty($records->employee) ? $records->employee->id : '' }}</td>
                                            <td>{{ !empty($records->employee) ? $records->employee->name : '' }}</td>
                                        @endif
                                        <td>{{env('CURRENCY_SYMBOL') ?? '£'}}{{ $records->amount }}</td>
                                        <td>{{ \Auth::user()->dateFormat($records->created_at) }}</td>
                                
                                        <td class="Action">


                                        </td>
                                    </tr>
                                @endforeach



                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('script-page')
        <script>
            $(document).ready(function() {
                var now = new Date();
                var month = (now.getMonth() + 1);
                var day = now.getDate();
                if (month < 10) month = "0" + month;
                if (day < 10) day = "0" + day;
                var today = now.getFullYear() + '-' + month + '-' + day;
                $('.current_date').val(today);
            });
        </script>
    @endpush
