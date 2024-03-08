@php
//$logos = asset(Storage::url('uploads/logo/'));
$logos = \App\Models\Utility::get_file('uploads/logo/');

$company_logo = Utility::getValByName('company_logo');
$company_small_logo = Utility::getValByName('company_small_logo');
$users = \Auth::user();
$profile = asset(Storage::url('uploads/avatar/'));
$currantLang = $users->currentLanguage();
$logo = Utility::get_superadmin_logo();
$emailTemplate = App\Models\EmailTemplate::getemailTemplate();
$mode_setting = \App\Models\Utility::mode_layout();
$lang = Auth::user()->lang;

@endphp
{{-- <nav
class="dash-sidebar light-sidebar {{ isset($mode_setting['is_sidebar_transperent']) && $mode_setting['is_sidebar_transperent'] == 'on' ? 'transprent-bg' : '' }}"> --}}

@if (isset($mode_setting['is_sidebar_transperent']) && $mode_setting['is_sidebar_transperent'] == 'on')
<nav class="dash-sidebar light-sidebar transprent-bg">
    @else
    <nav class="dash-sidebar light-sidebar">
        @endif

        <div class="navbar-wrapper">
            <div class="m-header main-logo"
            style="background-color:#584ED2 !important;border-bottom-right-radius:20px;">

            <a href="{{ route('home') }}" class="b-brand">
                <!-- ========   change your logo hear   ============ -->
                    <!-- <img src="{{ $logos . $logo . '?' . time() }}" alt="{{ env('APP_NAME') }}" class="logo logo-lg"
                        style="height: 40px;" /> -->
                    <!-- <p style="font-weight:bold;">
                {{ $logos}}
            </p> -->
            <!-- <img src="{{asset( '/assets/images/HRMPRO-logos_transparent.png' )}}" alt="{{ env('APP_NAME') }}" class="logo" style="height:7rem !important; width:7rem !important;" /> -->
            <img src="{{asset( '/assets/images/eduhr.png' )}}" alt="{{ env('APP_NAME') }}" class="logo" style="height:7rem !important; width:7rem !important;" />
            <!-- <img src="{{asset( '/assets/uploads/logo/logo-dark.png' )}}" alt="{{ env('APP_NAME') }}" class="logo logo-lg" style="height: 40px;" /> -->
            <!-- <img src="{{asset('assets/images/theme-3.svg')}}" alt="{{ env('APP_NAME') }}" class="logo logo-lg" style="height: 40px;" /> -->
        </a>

    </div>
    <div class="navbar-content">
        <ul class="dash-navbar">

            <!-- dashboard-->
            @if (\Auth::user()->type != 'company')
            <li class="dash-item">
                <a href="{{ route('home') }}" class="dash-link"><span class="dash-micon"><i class="ti ti-home"></i></span><span class="dash-mtext">{{ __('Dashboard') }}</span></a>
            </li>
            @endif
            @if (\Auth::user()->type == 'company')
            <li class="dash-item dash-hasmenu  {{ Request::segment(1) == 'null' ? 'active dash-trigger' : '' }}">
                <a href="#" class="dash-link"><span class="dash-micon"><i class="ti ti-home"></i></span><span class="dash-mtext">{{ __('Dashboard') }}</span><span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                <ul class="dash-submenu ">
                    <li class="dash-item {{ Request::segment(1) == null || Request::segment(1) == 'report' ? ' active dash-trigger' : '' }}">
                        <a class="dash-link" href="{{ route('home') }}">{{ __('Overview') }}</a>
                    </li>

                    @if (Gate::check('Manage Report'))
                    <li class="dash-item dash-hasmenu">
                        <a href="#!" class="dash-link"><span class=""><i class=""></i></span><span class="dash-mtext">{{ __('Report') }}</span><span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="dash-submenu">
                            @can('Manage Report')
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('report.income-expense') }}">{{ __('Income Vs Expense') }}</a>
                            </li>
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('report.p11-report') }}">{{ __('P11 Report') }}</a>
                            </li>

                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('report.monthly.attendance') }}">{{ __('Monthly Attendance') }}</a>
                            </li>

                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('report.leave') }}">{{ __('Leave') }}</a>
                            </li>


                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('report.account.statement') }}">{{ __('Account Statement') }}</a>
                            </li>


                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('report.payroll') }}">{{ __('Payroll') }}</a>
                            </li>


                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('report.timesheet') }}">{{ __('Timesheet') }}</a>
                            </li>
                            @endcan


                        </ul>
                    </li>
                    @endif


                </ul>
            </li>
            @endif
            <!--dashboard-->

            <!-- user-->
            @if (\Auth::user()->type == 'super admin')
            <li class="dash-item">
                <a href="{{ route('user.index') }}" class="dash-link"><span class="dash-micon"><i class="ti ti-user"></i></span><span class="dash-mtext">{{ __('Companies') }}</span></a>
            </li>
            @else
            @if (Gate::check('Manage User') ||
                Gate::check('Manage Role') ||
                Gate::check('Manage Employee Profile') ||
                Gate::check('Manage Employee Last Login'))
            <li class="dash-item dash-hasmenu {{ Request::segment(1) == 'user' || Request::segment(1) == 'roles' || Request::segment(1) == 'lastlogin'
            ? ' active dash-trigger'
            : '' }} ">
            <a href="#!" class="dash-link"><span class="dash-micon"><i class="ti ti-users"></i></span><span class="dash-mtext">{{ __('Staff') }}</span><span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
            <ul class="dash-submenu {{ Request::route()->getName() == 'user.index' || Request::route()->getName() == 'users.create' || Request::route()->getName() == 'user.edit' || Request::route()->getName() == 'lastlogin' ? ' active' : '' }} ">
                @can('Manage User')
                <li class="dash-item {{ Request::segment(1) == 'lastlogin' ? 'active' : '' }} ">
                    <a class="dash-link" href="{{ route('user.index') }}">{{ __('User') }}</a>
                </li>
                @endcan
                @can('Manage Role')
                <li class="dash-item">
                    <a class="dash-link" href="{{ route('roles.index') }}">{{ __('Role') }}</a>
                </li>
                @endcan
                @can('Manage Employee Profile')
                <li class="dash-item">
                    <a class="dash-link" href="{{ route('employee.profile') }}">{{ __('Employee Profile') }}</a>
                </li>
                @endcan
                            {{-- @can('Manage Employee Last Login')
                                    <li class="dash-item">
                                        <a class="dash-link" href="{{ route('lastlogin') }}">{{ __('Last Login') }}</a>
                    </li>
                    @endcan --}}

                </ul>
            </li>
            @endif
            @endif
            <!-- user-->

            <!-- employee-->
            @if (Gate::check('Manage Employee'))
            @if (\Auth::user()->type == 'employee')
            @php
            $employee = App\Models\Employee::where('user_id', \Auth::user()->id)->first();
            @endphp
            <li class="dash-item {{ Request::segment(1) == 'employee' ? 'active' : '' }}">
                <a href="{{ route('employee.show', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) }}" class="dash-link"><span class="dash-micon"><i class="ti ti-user"></i></span><span class="dash-mtext">{{ __('Employee') }}</span></a>
            </li>
            @else
            <li class="dash-item {{ Request::segment(1) == 'employee' ? 'active' : '' }}">
                <a href="{{ route('employee.index') }}" class="dash-link"><span class="dash-micon"><i class="ti ti-user"></i></span><span class="dash-mtext">{{ __('Employee') }}</span></a>
            </li>
            @endif
            <li class="dash-item {{ Request::segment(1) == 'employee-probation' ? 'active' : '' }}">
                <a href="{{ route('employee.probation.index') }}" class="dash-link"><span class="dash-micon"><i class="ti ti-user"></i></span><span class="dash-mtext">{{ __('Probation') }}</span></a>
            </li>
            @endif
            <li class="dash-item {{ Request::segment(1) == 'meet-team' ? 'active' : '' }}">
                <a href="{{ route('employee.meetTeam') }}" class="dash-link"><span class="dash-micon"><i class="ti ti-users"></i></span><span class="dash-mtext">{{ __('Meet Team') }}</span></a>
            </li>

            {{-- <li class="dash-item {{ Request::segment(1) == 'video' ? 'active' : '' }}">
                <a href="{{ route('video.index') }}" class="dash-link"><span class="dash-micon"><i class="ti ti-video"></i></span><span class="dash-mtext">{{ __('Videos') }}</span></a>
            </li> --}}
            
            <!-- employee-->

            <!-- payroll-->
            @if (Gate::check('Manage Set Salary') || Gate::check('Manage Pay Slip'))
            <li class="dash-item dash-hasmenu  {{ Request::segment(1) == 'setsalary' ? 'dash-trigger active' : '' }}">
                <a href="#!" class="dash-link"><span class="dash-micon"><i class="ti ti-receipt"></i></span><span class="dash-mtext">{{ __('Payroll') }}</span><span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                <ul class="dash-submenu ">
                    <li class="dash-item {{ Request::segment(1) == 'setsalary' ? 'active' : '-' }}">
                        <a class="dash-link" href="{{ route('setsalary.index') }}">{{ __('Set Salary') }}</a>
                    </li>
                    <li class="dash-item">
                        <a class="dash-link" href="{{ route('payslip.index') }}">{{ __('Payslip') }}</a>
                    </li>
                        <!-- <li class="dash-item">
                            <a class="dash-link">{{ __('Provident Funds') }}</a>
                        </li>
                        <li class="dash-item">
                            <a class="dash-link">{{ __('Tax Statement') }}</a>
                        </li>
                        <li class="dash-item">
                            <a class="dash-link">{{ __('Leave Encashment') }}</a>
                        </li>
                        <li class="dash-item">
                            <a class="dash-link">{{ __('Pensions') }}</a>
                        </li> -->
                        <li class="dash-item">
                            <a class="dash-link"  href="{{ route('bonus.index') }}">{{ __('Payroll Setup') }}</a>
                        </li>
                    </ul>
                </li>
                @endif
                <!-- payroll-->

                <!-- pension-->
                @if (Gate::check('Manage Pension'))
                <li class="dash-item dash-hasmenu  {{ Request::segment(1) == 'schemes' ? 'dash-trigger active' : '' }}">
                    <a href="#!" class="dash-link"><span class="dash-micon"><i class="ti ti-package"></i></span><span class="dash-mtext">Pension</span><span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                    <ul class="dash-submenu ">
                        <li class="dash-item {{ Request::segment(1) == 'pension-schemes' ? 'active' : '-' }}">
                            <a class="dash-link" href="{{ route('pension-schemes.index') }}">Schemes</a>
                        </li>
                        <li class="dash-item {{ Request::segment(1) == 'pension-opt-ins' ? 'active' : '-' }}">
                            <a class="dash-link" href="{{ route('pension-opt-ins.index') }}">Opt-in</a>
                        </li>
                        <li class="dash-item {{ Request::segment(1) == 'pension-optout' ? 'active' : '-' }}">
                            <a class="dash-link" href="{{ route('pension-optout.index') }}">Opt-out</a>
                        </li>
                    </ul>
                </li>
                @endif
                <!-- pension-->

                @if (\Auth::user()->type == 'employee')
                <li class="dash-item dash-hasmenu {{ Request::segment(1) == 'setsalary' ? 'dash-trigger active' : '' }}">
                    <a href="#!" class="dash-link"><span class="dash-micon"><i class="ti ti-receipt"></i></span><span class="dash-mtext">{{ __('Payroll') }}</span><span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                    <ul class="dash-submenu">
                        <li class="dash-item {{ Request::segment(1) == 'setsalary' ? 'active' : '-' }}">
                            <a class="dash-link" href="{{ route('setsalary.show', \Illuminate\Support\Facades\Crypt::encrypt(\Auth::user()->id)) }}">{{ __('Salary') }}</a>
                        </li>
                        <li class="dash-item">
                            <a class="dash-link" href="{{ route('payslip.index') }}">{{ __('Payslip') }}</a>
                        </li>
                    </ul>
                </li>
                @endif

                <!-- timesheet-->
                @if (Gate::check('Manage Attendance') || Gate::check('Manage Leave') || Gate::check('Manage TimeSheet'))
                <li class="dash-item dash-hasmenu {{ Request::segment(2) == 'leave' ? 'dash-trigger active' : '' }}">
                    <a href="#!" class="dash-link"><span class="dash-micon"><i class="ti ti-clock"></i></span><span class="dash-mtext">{{ __('Timesheet') }}</span><span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                    <ul class="dash-submenu">
                        @can('Manage TimeSheet')
                        <li class="dash-item">
                            <a class="dash-link" href="{{ route('timesheet.index') }}">{{ __('Timesheet') }}</a>
                        </li>
                        @endcan
                        @can('Manage Attendance')
                        <li class="dash-item dash-hasmenu">
                            <a href="#!" class="dash-link"><span class="dash-mtext">{{ __('Attendance') }}</span><span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                            <ul class="dash-submenu">
                                <li class="dash-item">
                                    <a class="dash-link" href="{{ route('attendanceemployee.index') }}">{{ __('Marked Attendance') }}</a>
                                </li>
                                @can('Create Attendance')
                                <li class="dash-item">
                                    <a class="dash-link" href="{{ route('attendanceemployee.bulkattendance') }}">{{ __('Bulk Attendance') }}</a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endif
                <!--timesheet-->

                <!-- Manage Leaves-->
                @if (Gate::check('Manage Leave') || Gate::check('Manage Leave'))
                <li class="dash-item dash-hasmenu">
                    <a href="#!" class="dash-link"><span class="dash-micon"><i class="ti ti-calendar"></i></span><span class="dash-mtext">{{ __('Manage Leave') }}</span><span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                    <ul class="dash-submenu">

                        @can('Manage Leave')
                        <li class="dash-item {{ Request::segment(2) == 'leave' ? ' active' : '' }}">
                            <a class="dash-link" href="{{ route('leave.index') }}">{{ __('Leave Request') }}</a>
                        </li>
                        @endcan

                        @can('Manage Leave')
                        <li class="dash-item {{ Request::segment(2) == 'leave' ? ' active' : '' }}">
                            <a class="dash-link" href="{{ route('carryover.index') }}">{{ __('CarryOver Request') }}</a>
                        </li>
                        @endcan

                        @can('Manage Leave')
                        @if (\Auth::user()->type == 'employee')
                        <li class="dash-item {{ Request::segment(1) == 'leavesummary' || Request::is('employees/leavesummary*') ? 'active' : '' }}">
                            <a class="dash-link" href="{{ route('leavesummary.employee',Auth::user()->employee->id) }}">{{ __('Leave Summary') }}</a>
                        </li>
                        @else
                        <li class="dash-item {{ Request::segment(1) == 'leavesummary' || Request::is('employees/leavesummary*') ? 'active' : '' }}">
                            <a class="dash-link" href="{{ route('leavesummary.employees') }}">{{ __('Leave Summary') }}</a>
                        </li>
                        @endif
                        @endcan

                        <li class="dash-item {{ Request::segment(1) == 'teamleave' ? ' active' : '' }}">
                            <a class="dash-link" href="{{ route('leave.team') }}">{{ __('Team Time Off') }}</a>
                        </li>

                        <li class="dash-item {{ Request::segment(1) == 'holiday' ? ' active' : '' }}">
                            <a class="dash-link" href="{{ route('holiday.index') }}">{{ __('Holidays') }}</a>
                        </li>

                    </ul>
                </li>
                @endif
                <!--performance-->

                <!-- performance-->
                @if (Gate::check('Manage Indicator') || Gate::check('Manage Appraisal') || Gate::check('Manage Goal Tracking'))
                <li class="dash-item dash-hasmenu">
                    <a href="#!" class="dash-link"><span class="dash-micon"><i class="ti ti-3d-cube-sphere"></i></span><span class="dash-mtext">{{ __('Performance') }}</span><span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                    <ul class="dash-submenu">
                        @can('Manage Indicator')
                        <li class="dash-item">
                            <a class="dash-link" href="{{ route('indicator.index') }}">{{ __('Indicator') }}</a>
                        </li>
                        @endcan

                        @can('Manage Appraisal')
                        <li class="dash-item">
                            <a class="dash-link" href="{{ route('appraisal.index') }}">{{ __('Appraisal') }}</a>
                        </li>
                        @endcan

                        @can('Manage Goal Tracking')
                        <li class="dash-item">
                            <a class="dash-link" href="{{ route('goaltracking.index') }}">{{ __('Goal Tracking') }}</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endif
                <!--performance-->

                <!--fianance-->
                @if (Gate::check('Manage Account List') ||
                    Gate::check('Manage Payee') ||
                    Gate::check('Manage Payer') ||
                    Gate::check('Manage Deposit') ||
                    Gate::check('Manage Expense') ||
                    Gate::check('Manage Transfer Balance'))
                    <li class="dash-item dash-hasmenu">
                        <a href="#!" class="dash-link"><span class="dash-micon"><i class="ti ti-wallet"></i></span><span class="dash-mtext">{{ __('Finance') }}</span><span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="dash-submenu">
                            @can('Manage Account List')
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('accountlist.index') }}">{{ __('Account List') }}</a>
                            </li>
                            @endcan
                            @can('View Balance Account List')
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('accountbalance') }}">{{ __('Account Balance') }}</a>
                            </li>
                            @endcan
                            @can('Manage Payee')
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('payees.index') }}">{{ __('Payees') }}</a>
                            </li>
                            @endcan

                            @can('Manage Payer')
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('payer.index') }}">{{ __('Payers') }}</a>
                            </li>
                            @endcan

                            @can('Manage Deposit')
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('deposit.index') }}">{{ __('Deposit') }}</a>
                            </li>
                            @endcan

                            @can('Manage Expense')
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('expense.index') }}">{{ __('Expense') }}</a>
                            </li>
                            @endcan

                            @can('Manage Transfer Balance')
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('transferbalance.index') }}">{{ __('Transfer Balance') }}</a>
                            </li>
                            @endcan

                        </ul>
                    </li>
                    @endif
                    <!-- fianance-->

                    <!--trainning-->
                    @if (Gate::check('Manage Trainer') || Gate::check('Manage Training'))
                    <li class="dash-item dash-hasmenu {{ Request::segment(1) == 'training' ? 'dash-trigger active' : '' }}">
                        <a href="#!" class="dash-link "><span class="dash-micon"><i class="ti ti-school"></i></span><span class="dash-mtext">{{ __('Training') }}</span><span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="dash-submenu">
                            @can('Manage Training')
                            <li class="dash-item {{ Request::segment(1) == 'training' ? ' active' : '' }}">
                                <a class="dash-link" href="{{ route('training.index') }}">{{ __('Training List') }}</a>
                            </li>
                            @endcan

                            @can('Manage Trainer')
                            <li class="dash-item ">
                                <a class="dash-link" href="{{ route('trainer.index') }}">{{ __('Trainer') }}</a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endif

                    <!-- tranning-->


                    <!-- HR-->
                    @if (Gate::check('Manage Awards') ||
                        Gate::check('Manage Transfer') ||
                        Gate::check('Manage Resignation') ||
                        Gate::check('Manage Travels') ||
                        Gate::check('Manage Promotion') ||
                        Gate::check('Manage Complaint') ||
                        Gate::check('Manage Warning') ||
                        Gate::check('Manage Termination') ||
                        Gate::check('Manage Announcement') ||
                        Gate::check('Manage Holiday') ||
                        Gate::check('Manage Holiday'))
                        <li
                        class="dash-item dash-hasmenu {{ Request::segment(1) == 'holiday' ? 'dash-trigger active' : '' }}">
                        <a href="#!" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-user-plus"></i></span><span
                            class="dash-mtext">{{ __('HR Admin Setup') }}</span><span class="dash-arrow"><i
                                data-feather="chevron-right"></i></span></a>
                                <ul class="dash-submenu">
                                    <li class="dash-item {{ Request::segment(1) == 'award' ? 'active' : '' }}">
                                        <a class="dash-link" href="{{ route('award.index') }}">{{ __('Award') }}</a>
                                    </li>
                                    <li class="dash-item">
                                        <a class="dash-link" href="{{ route('transfer.index') }}">{{ __('Transfer') }}</a>
                                    </li>
                        <!-- <li class="dash-item">
                            <a class="dash-link"
                                href="{{ route('resignation.index') }}">{{ __('Resignation') }}</a>
                            </li> -->
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('travel.index') }}">{{ __('Trip') }}</a>
                            </li>
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('promotion.index') }}">{{ __('Promotion') }}</a>
                            </li>
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('complaint.index') }}">{{ __('Complaints') }}</a>
                            </li>
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('warning.index') }}">{{ __('Warning') }}</a>
                            </li>
                        <!-- <li class="dash-item">
                            <a class="dash-link"
                                href="{{ route('termination.index') }}">{{ __('Termination') }}</a>
                            </li> -->
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('announcement.index') }}">{{ __('Announcement') }}</a>
                            </li>
                        <!-- <li class="dash-item {{ Request::segment(1) == 'holiday' ? ' active' : '' }}">
                            <a class="dash-link" href="{{ route('holiday.index') }}">{{ __('Holidays') }}</a>
                        </li> -->
                    </ul>
                </li>
                @endif
                <!-- HR-->

                <!-- recruitment-->
                @if (Gate::check('Manage Job') ||
                    Gate::check('Manage Job Application') ||
                    Gate::check('Manage Job OnBoard') ||
                    Gate::check('Manage Custom Question') ||
                    Gate::check('Manage Interview Schedule') ||
                    Gate::check('Manage Career'))
                    <li class="dash-item dash-hasmenu  {{ Request::segment(1) == 'job' || Request::segment(1) == 'job-application' ? 'dash-trigger active' : '' }} ">
                        <a href="#!" class="dash-link"><span class="dash-micon"><i class="ti ti-license"></i></span><span class="dash-mtext">{{ __('Recruitment') }}</span><span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="dash-submenu">
                            @can('Manage Job')
                            <li class="dash-item {{ Request::route()->getName() == 'job.index' ? 'active' : '-' }}">
                                <a class="dash-link" href="{{ route('job.index') }}">{{ __('Jobs') }}</a>
                            </li>
                            @endcan
                            @can('Manage Job')
                            <li class="dash-item {{ Request::route()->getName() == 'job.create' ? 'active' : '-' }}">
                                <a class="dash-link" href="{{ route('job.create') }}">{{ __('Job Create') }}</a>
                            </li>

                            <li class="dash-item {{ Request::route()->getName() == 'job.template' ? 'active' : '-' }}">
                                <a class="dash-link" href="{{ route('job.template') }}">{{ __('Job Templates') }}</a>
                            </li>
                            @endcan

                            @can('Manage Job Application')
                            <li class="dash-item {{ request()->is('job-application*') ? 'active' : '' }}">
                                <a class="dash-link" href="{{ route('job-application.index') }}">{{ __('Job Application') }}</a>
                            </li>
                            @endcan
                            @can('Manage Job Application')

                            <li class="dash-item {{ request()->is('candidates-job-applications') ? 'active' : '' }}">
                                <a class="dash-link" href="{{ route('job.application.candidate') }}">{{ __('Job Candidate') }}</a>
                            </li>
                            @endcan

                            @can('Manage Job OnBoard')
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('job.on.board') }}">{{ __('Job On-Boarding') }}</a>
                            </li>
                            @endcan

                            @can('Manage Custom Question')
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('custom-question.index') }}">{{ __('Question Templates') }}</a>
                            </li>
                            @endcan

                            @can('Manage Custom Question')
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('custom-question.index') }}">{{ __('Custom Question') }}</a>
                            </li>
                            @endcan

                            @can('Manage Interview Schedule')
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('interview-schedule.index') }}">{{ __('Interview Schedule') }}</a>
                            </li>
                            @endcan

                            @can('Manage Word Count')
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('config-word-count.index') }}">{{ __('Config Word Count') }}</a>
                            </li>
                            @endcan

                            @can('Manage Career')
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('career', [\Auth::user()->creatorId(), $lang]) }}" target="_blank">{{ __('Career') }}</a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endif
                    <!-- recruitment-->

                    <!--Exits-->
            <!-- @if (Gate::check('Manage Job') ||
                    Gate::check('Manage Job Application') ||
                    Gate::check('Manage Job OnBoard') ||
                    Gate::check('Manage Custom Question') ||
                    Gate::check('Manage Interview Schedule') ||
                    Gate::check('Manage Career')) -->
                    <li
                    class="dash-item dash-hasmenu  {{ Request::segment(1) == 'job' || Request::segment(1) == 'job-application' ? 'dash-trigger active' : '' }} ">
                    <a href="#!" class="dash-link"><span class="dash-micon">
                        <i class="ti ti-archive"></i></span><span
                        class="dash-mtext">{{ __('Exits') }}</span><span class="dash-arrow"><i
                            data-feather="chevron-right"></i></span></a>
                            <ul class="dash-submenu">
                                @can('Manage Resignation')
                                <li class="dash-item">
                                    <a class="dash-link" href="{{ route('resignation.index') }}">{{ __('Resignation') }}</a>
                                </li>
                                @endcan
                                @can('Manage Termination')
                                <li class="dash-item">
                                    <a class="dash-link" href="{{ route('termination.index') }}">{{ __('Termination') }}</a>
                                </li>
                                @endcan
                                @can('Manage Retirement')
                                <li class="dash-item {{ request()->is('retirement*') ? 'active' : '' }}">
                                    <a class="dash-link"
                                    href="{{ route('retirement.index') }}">{{ __('Retirement') }}</a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        <!-- @endif -->

                        <!-- Health And Fitness -->
                        <li
                        class="dash-item dash-hasmenu  {{ Request::segment(1) == 'job' || Request::segment(1) == 'job-application' ? 'dash-trigger active' : '' }} ">
                        <a href="#!" class="dash-link"><span class="dash-micon">
                            <i class="ti ti-shield"></i></span><span
                            class="dash-mtext">{{ __('Health And Fitness') }}</span><span class="dash-arrow"><i
                                data-feather="chevron-right"></i></span></a>
                                <ul class="dash-submenu">
                                    @can('Manage Health And Fitness')
                                    <li class="dash-item {{ request()->is('healthassessment*') ? 'active' : '' }}">
                                        <a class="dash-link" href="{{ route('healthassessment.index') }}">{{ __('Health Assessments') }}</a>
                                    </li>
                                    @endcan
                                    @can('Manage Health And Fitness')
                                    <li class="dash-item {{ request()->is('gpnote*') ? 'active' : '' }}">
                                        <a class="dash-link" href="{{ route('gpnote.index') }}">{{ __('GP Notes') }}</a>
                                    </li>
                                    @endcan
                                    @can('Manage Health And Fitness')
                                    <li class="dash-item {{ request()->is('selfcertification*') ? 'active' : '' }}">
                                        <a class="dash-link"
                                        href="{{ route('selfcertification.index') }}">{{ __('Self Certifications') }}</a>
                                    </li>
                                    @endcan
                                </ul>
                            </li>

                            <!--contract-->
                            @can('Manage Contract')
                            <li
                            class="dash-item {{ Request::route()->getName() == 'contract.index' || Request::route()->getName() == 'contract.show' ? 'active' : '' }}">
                            <a href="{{ route('contract.index') }}" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-device-floppy"></i></span><span
                                class="dash-mtext">{{ __('Contracts') }}</span></a>
                                @endcan

                                <!-- {{-- @endcan --}} -->

                                <!-- ticket-->
                                @can('Manage Ticket')
                                <li class="dash-item {{ Request::segment(1) == 'ticket' ? 'active' : '' }}">
                                    <a href="{{ route('ticket.index') }}" class="dash-link"><span class="dash-micon"><i class="ti ti-ticket"></i></span><span class="dash-mtext">{{ __('Ticket') }}</span></a>
                                </li>
                                @endcan

                                <!-- Event-->
                                @can('Manage Event')
                                <li class="dash-item">
                                    <a href="{{ route('event.index') }}" class="dash-link"><span class="dash-micon"><i class="ti ti-calendar-event"></i></span><span class="dash-mtext">{{ __('Event') }}</span></a>
                                </li>
                                @endcan


                                <!--meeting-->
                                @can('Manage Meeting')
                                <li class="dash-item {{ Request::segment(1) == 'meeting' || Request::segment(2) == 'meeting' ? 'active' : '' }}">
                                    <a href="{{ route('meeting.index') }}" class="dash-link"><span class="dash-micon"><i class="ti ti-calendar-time"></i></span><span class="dash-mtext">{{ __('Meeting') }}</span></a>
                                </li>
                                @endcan


                                <!-- Zoom meeting-->
                                @can('Manage Zoom meeting')
                                @if (\Auth::user()->type != 'super admin')
                                <li class="dash-item {{ Request::segment(1) == 'zoommeeting' ? 'active' : '' }}">
                                    <a href="{{ route('zoom-meeting.index') }}" class="dash-link"><span class="dash-micon"><i class="ti ti-video"></i></span><span class="dash-mtext">{{ __('Zoom Meeting') }}</span></a>
                                </li>
                                @endif
                                @endcan

                                <!-- assets-->
                                @if (Gate::check('Manage Assets'))
                                <li class="dash-item">
                                    <a href="{{ route('account-assets.index') }}" class="dash-link"><span class="dash-micon"><i class="ti ti-medical-cross"></i></span><span class="dash-mtext">{{ __('Assets') }}</span></a>
                                </li>
                                @endcan


                                <!-- document-->
                                @if (Gate::check('Manage Document'))
                                <li class="dash-item">
                                    <a href="{{ route('document-upload.index') }}" class="dash-link"><span class="dash-micon"><i class="ti ti-file"></i></span><span class="dash-mtext">{{ __('Document') }}</span></a>
                                </li>
                                @endcan

                                <!-- Eclaim-->
                                @can('Manage Eclaim')
                                <li class="dash-item">
                                    <a href="{{ route('eclaim.index') }}" class="dash-link">
                                        <span class="dash-micon"><i class="ti ti-file"></i></span><span
                                        class="dash-mtext">{{ __('Manage Eclaim') }}
                                    </span>
                                </a>
                            </li>
                            @endcan

                            {{-- Email Template --}}
                            @if (\Auth::user()->type == 'company')
                            <li class="dash-item {{ Request::route()->getName() == 'email_template.show' || Request::segment(1) == 'email_template_lang' || Request::route()->getName() == 'manageemail.lang' ? 'active' : '' }}">
                                <a href="{{ route('manage.email.language', [$emailTemplate->id, \Auth::user()->lang]) }}"
                                    class="dash-link"><span class="dash-micon"><i class="ti ti-template"></i></span><span
                                    class="dash-mtext">{{ __('Email Templates') }}</span></a>
                                </li>
                                @endif
                                <!--company policy-->



                                @if (Gate::check('Manage Company Policy'))
                                <li class="dash-item">
                                    <a href="{{ route('company-policy.index') }}" class="dash-link"><span class="dash-micon"><i class="ti ti-pray"></i></span><span class="dash-mtext">{{ __('Policy & Procedures') }}</span></a>
                                </li>
                                @endcan
                                <!--chats-->
                                @if (\Auth::user()->type != 'super admin')
                                <li class="dash-item {{ Request::segment(1) == 'chats' ? 'active' : '' }}">
                                    <a href="{{ url('chats') }}" class="dash-link"><span class="dash-micon"><i class="ti ti-messages"></i></span><span class="dash-mtext">{{ __('Messenger') }}</span></a>
                                </li>
                                @endif

                                @if (\Auth::user()->type == 'company')
                                <li class="dash-item {{ Request::route()->getName() == 'notification-templates.update' || Request::segment(1) == 'notification-templates' ? 'active' : '' }}">
                                    <a href="{{ route('notification-templates.index') }}" class="dash-link"><span class="dash-micon"><i class="ti ti-bell"></i></span><span class="dash-mtext">{{ __('Notification Template') }}</span></a>
                                </li>
                                @endif

                                @if (\Auth::user()->type == 'super admin')
                                <li class="dash-item ">
                                    <a href="{{ route('plan_request.index') }}" class="dash-link"><span class="dash-micon"><i class="ti ti-arrow-down-right-circle"></i></span><span class="dash-mtext">{{ __('Plan Request') }}</span></a>

                                </li>
                                @endif


                                @if (Auth::user()->type == 'super admin')
                                @if (Gate::check('manage coupon'))
                                <li class="dash-item ">
                                    <a href="{{ route('coupons.index') }}" class="dash-link"><span class="dash-micon"><i class="ti ti-gift"></i></span><span class="dash-mtext">{{ __('Coupon') }}</span></a>

                                </li>
                                @endif
                                @endif
                                @if (\Auth::user()->type == 'super admin')
                                @if (Gate::check('Manage Order'))
                                <li class="dash-item ">
                                    <a href="{{ route('order.index') }}" class="dash-link {{ request()->is('orders*') ? 'active' : '' }}"><span class="dash-micon"><i class="ti ti-shopping-cart"></i></span><span class="dash-mtext">{{ __('Order') }}</span></a>

                                </li>
                                @endif
                                @endif

                                <!--report-->
                <!-- @if (Gate::check('Manage Report'))
<li class="dash-item dash-hasmenu">
<a href="#!" class="dash-link"><span class="dash-micon"><i
class="ti ti-list"></i></span><span
class="dash-mtext">{{ __('Report') }}</span><span
class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
<ul class="dash-submenu">
@can('Manage Report')
<li class="dash-item">
<a class="dash-link"
href="{{ route('report.income-expense') }}">{{ __('Income Vs Expense') }}</a>
</li>

                            <li class="dash-item">
                                <a class="dash-link"
                                href="{{ route('report.monthly.attendance') }}">{{ __('Monthly Attendance') }}</a>
                            </li>

                            <li class="dash-item">
                                <a class="dash-link"
                                href="{{ route('report.leave') }}">{{ __('Leave') }}</a>
                            </li>


                            <li class="dash-item">
                                <a class="dash-link"
                                href="{{ route('report.account.statement') }}">{{ __('Account Statement') }}</a>
                            </li>


                            <li class="dash-item">
                                <a class="dash-link"
                                href="{{ route('report.payroll') }}">{{ __('Payroll') }}</a>
                            </li>


                            <li class="dash-item">
                                <a class="dash-link"
                                href="{{ route('report.timesheet') }}">{{ __('Timesheet') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @endif


<!--constant-->
@if (Gate::check('Manage Department') ||
    Gate::check('Manage Designation') ||
    Gate::check('Manage Document Type') ||
    Gate::check('Manage Branch') ||
    Gate::check('Manage Award Type') ||
    Gate::check('Manage Termination Types') ||
    Gate::check('Manage Payslip Type') ||
    Gate::check('Manage Allowance Option') ||
    Gate::check('Manage Loan Options') ||
    Gate::check('Manage Deduction Options') ||
    Gate::check('Manage Expense Type') ||
    Gate::check('Manage Income Type') ||
    Gate::check('Manage Payment Type') ||
    Gate::check('Manage Leave Type') ||
    Gate::check('Manage Training Type') ||
    Gate::check('Manage Job Category') ||
    Gate::check('Manage Job Stage'))
    <li class="dash-item dash-hasmenu {{ Request::route()->getName() == 'branch.index' ||Request::route()->getName() == 'department.index' ||Request::route()->getName() == 'designation.index' ||Request::route()->getName() == 'leavetype.index' ||Request::route()->getName() == 'document.index' ||Request::route()->getName() == 'paysliptype.index' ||Request::route()->getName() == 'allowanceoption.index' ||Request::route()->getName() == 'loanoption.index' ||Request::route()->getName() == 'deductionoption.index' ||Request::route()->getName() == 'goaltype.index' ||Request::route()->getName() == 'trainingtype.index' ||Request::route()->getName() == 'awardtype.index' ||Request::route()->getName() == 'terminationtype.index' ||Request::route()->getName() == 'job-category.index' ||Request::route()->getName() == 'job-stage.index' ||Request::route()->getName() == 'performanceType.index' ||Request::route()->getName() == 'competencies.index' ||Request::route()->getName() == 'expensetype.index' ||Request::route()->getName() == 'incometype.index' ||Request::route()->getName() == 'paymenttype.index' ||Request::route()->getName() == 'contract_type.index'? ' active': '' }}">
        <a href="{{ route('branch.index') }}" class="dash-link"><span class="dash-micon"><i class="ti ti-table"></i></span><span class="dash-mtext">{{ __('HRM System Setup') }}</span></a>
    </li>
                <!-- <ul class="dash-submenu">
@can('Manage Branch')
<li class="dash-item {{ request()->is('branch*') ? 'active' : '' }}">
<a class="dash-link"
href="{{ route('branch.index') }}">{{ __('Branch') }}</a>
</li>
@endcan
@can('Manage Department')
<li class="dash-item {{ request()->is('department*') ? 'active' : '' }}">
<a class="dash-link"
href="{{ route('department.index') }}">{{ __('Department') }}</a>
</li>
@endcan
@can('Manage Designation')
<li class="dash-item {{ request()->is('designation*') ? 'active' : '' }}">
<a class="dash-link"
href="{{ route('designation.index') }}">{{ __('Designation') }}</a>
</li>
@endcan
@can('Manage Document Type')
<li class="dash-item {{ request()->is('document*') ? 'active' : '' }}">
<a class="dash-link"
href="{{ route('document.index') }}">{{ __('Document Type') }}</a>
</li>
@endcan

@can('Manage Award Type')
<li class="dash-item {{ request()->is('awardtype*') ? 'active' : '' }}">
<a class="dash-link"
href="{{ route('awardtype.index') }}">{{ __('Award Type') }}</a>
</li>
@endcan
@can('Manage Termination Types')
<li
class="dash-item {{ request()->is('terminationtype*') ? 'active' : '' }}">
<a class="dash-link"
href="{{ route('terminationtype.index') }}">{{ __('Termination Type') }}</a>
</li>
@endcan
@can('Manage Payslip Type')
<li class="dash-item {{ request()->is('paysliptype*') ? 'active' : '' }}">
<a class="dash-link"
href="{{ route('paysliptype.index') }}">{{ __('Payslip Type') }}</a>
</li>
@endcan
@can('Manage Allowance Option')
<li
class="dash-item {{ request()->is('allowanceoption*') ? 'active' : '' }}">
<a class="dash-link"
href="{{ route('allowanceoption.index') }}">{{ __('Allowance Option') }}</a>
</li>
@endcan
@can('Manage Loan Option')
<li class="dash-item {{ request()->is('loanoption*') ? 'active' : '' }}">
<a class="dash-link"
href="{{ route('loanoption.index') }}">{{ __('Loan Option') }}</a>
</li>
@endcan
@can('Manage Deduction Option')
<li
class="dash-item {{ request()->is('deductionoption*') ? 'active' : '' }}">
<a class="dash-link"
href="{{ route('deductionoption.index') }}">{{ __('Deduction Option') }}</a>
</li>
@endcan
@can('Manage Expense Type')
<li class="dash-item {{ request()->is('expensetype*') ? 'active' : '' }}">
<a class="dash-link"
href="{{ route('expensetype.index') }}">{{ __('Expense Type') }}</a>
</li>
@endcan
@can('Manage Income Type')
<li class="dash-item {{ request()->is('incometype*') ? 'active' : '' }}">
<a class="dash-link"
href="{{ route('incometype.index') }}">{{ __('Income Type') }}</a>
</li>
@endcan
@can('Manage Payment Type')
<li class="dash-item {{ request()->is('paymenttype*') ? 'active' : '' }}">
<a class="dash-link"
href="{{ route('paymenttype.index') }}">{{ __('Payment Type') }}</a>
</li>
@endcan
@can('Manage Leave Type')
<li class="dash-item {{ request()->is('leavetype*') ? 'active' : '' }}">
<a class="dash-link"
href="{{ route('leavetype.index') }}">{{ __('Leave Type') }}</a>
</li>
@endcan
@can('Manage Termination Type')
<li
class="dash-item {{ request()->is('terminationtype*') ? 'active' : '' }}">
<a class="dash-link"
href="{{ route('terminationtype.index') }}">{{ __('Termination Type') }}</a>
</li>
@endcan
@can('Manage Goal Type')
<li class="dash-item {{ request()->is('goaltype*') ? 'active' : '' }}">
<a class="dash-link"
href="{{ route('goaltype.index') }}">{{ __('Goal Type') }}</a>
</li>
@endcan
@can('Manage Training Type')
<li class="dash-item {{ request()->is('trainingtype*') ? 'active' : '' }}">
<a class="dash-link"
href="{{ route('trainingtype.index') }}">{{ __('Training Type') }}</a>
</li>
@endcan
@can('Manage Job Category')
<li class="dash-item {{ request()->is('job-category*') ? 'active' : '' }}">
<a class="dash-link"
href="{{ route('job-category.index') }}">{{ __('Job Category') }}</a>
</li>
@endcan
@can('Manage Job Stage')
<li class="dash-item {{ request()->is('job-stage*') ? 'active' : '' }}">
<a class="dash-link"
href="{{ route('job-stage.index') }}">{{ __('Job Stage') }}</a>
</li>
@endcan

<li
class="dash-item {{ request()->is('performanceType*') ? 'active' : '' }}">
<a class="dash-link"
href="{{ route('performanceType.index') }}">{{ __('Performance Type') }}</a>
</li>

@can('Manage Competencies')
<li class="dash-item {{ request()->is('competencies*') ? 'active' : '' }}">

<a class="dash-link"
href="{{ route('competencies.index') }}">{{ __('Competencies') }}</a>
</li>
@endcan
</ul>
</li> -->
@endif
<!--constant-->

@if (\Auth::user()->type == 'company')
@include('landingpage::menu.landingpage')
@endif

@if (Gate::check('Manage Company Settings') || Gate::check('Manage System Settings'))
<li class="dash-item ">
    <a href="{{ route('settings.index') }}" class="dash-link"><span class="dash-micon"><i class="ti ti-settings"></i></span><span class="dash-mtext">{{ __('System Setting') }}</span></a>

</li>
@endif

</ul>

</div>
</div>
</nav>
