@extends('layouts.admin')
@section('page-title')
{{ __('Manage Job') }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item">{{ __('Manage Job') }}</li>
@endsection


@push('script-page')
<script>
    $('.copy_link').click(function(e) {
        e.preventDefault();
        var copyText = $(this).attr('href');

        document.addEventListener('copy', function(e) {
            e.clipboardData.setData('text/plain', copyText);
            e.preventDefault();
        }, true);

        document.execCommand('copy');
        show_toastr('Success', 'Url copied to clipboard', 'success');
    });
</script>
@endpush
@section('action-button')

@can('Create Job')
<a href="{{ route('job.create') }}" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Job') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
    <i class="ti ti-plus"></i>
</a>
@endcan
@endsection
@section('content')

<div class="col-lg-4 col-md-6">
    <div class="card">
        <div class="card-body">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto mb-3 mb-sm-0">
                    <div class="d-flex align-items-center">
                        <div class="theme-avtar bg-primary">
                            <i class="ti ti-cast"></i>
                        </div>
                        <div class="ms-3">
                            <small class="text-muted">{{__('Total')}}</small>
                            <h6 class="m-0">{{__('Jobs')}}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-auto text-end">
                    <h4 class="m-0">{{$data['total']}}</h4>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-4 col-md-6">
    <div class="card">
        <div class="card-body">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto mb-3 mb-sm-0">
                    <div class="d-flex align-items-center">
                        <div class="theme-avtar bg-info">
                            <i class="ti ti-cast"></i>
                        </div>
                        <div class="ms-3">
                            <small class="text-muted">{{__('Active')}}</small>
                            <h6 class="m-0">{{__('Jobs')}}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-auto text-end">
                    <h4 class="m-0">{{$data['active']}}</h4>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-4 col-md-6">
    <div class="card">
        <div class="card-body">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto mb-3 mb-sm-0">
                    <div class="d-flex align-items-center">
                        <div class="theme-avtar bg-warning">
                            <i class="ti ti-cast"></i>
                        </div>
                        <div class="ms-3">
                            <small class="text-muted">{{__('Inactive')}}</small>
                            <h6 class="m-0">{{__('Jobs')}}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-auto text-end">
                    <h4 class="m-0">{{$data['in_active']}}</h4>
                </div>
            </div>
        </div>
    </div>
</div>





<div class="col-xl-12">
    <div class="card">
        <div class="card-header card-body table-border-style">
            {{-- <h5> </h5> --}}
            <div class="table-responsive">
                <table class="table" id="pc-dt-simple">
                    <thead>
                        <tr>
                            <th>{{ __('Branch') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Start Date') }}</th>
                            <th>{{ __('End Date') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Created At') }}</th>
                            @if (Gate::check('Edit Job') || Gate::check('Delete Job') || Gate::check('Show Job'))
                            <th width="200px">{{ __('Action') }}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jobs as $job)
                        <tr>
                            <td>{{ !empty($job->branch) ? $job->branches->name : __('All') }}</td>
                            <td>{{ $job->title }}</td>
                            <td>{{ \Auth::user()->dateFormat($job->start_date) }}</td>
                            <td>{{ \Auth::user()->dateFormat($job->end_date) }}</td>
                            <td>
                                @if ($job->status == 'active')
                                <span class="badge bg-success p-2 px-3 rounded status-badge">{{ App\Models\Job::$status[$job->status] }}</span>
                                @else
                                <span class="badge bg-danger p-2 px-3 rounded status-badge">{{ App\Models\Job::$status[$job->status] }}</span>
                                @endif
                            </td>
                            <td>{{ \Auth::user()->dateFormat($job->created_at) }}</td>
                            <td class="Action">
                                @if (Gate::check('Edit Job') || Gate::check('Delete Job') || Gate::check('Show Job'))
                                <span>
                                    <div class="action-btn bg-secondary ms-2">
                                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Job Copy') }}">
                                            <i class="ti ti-copy text-white"></i>
                                        </a>
                                    </div>

                                    @can('Show Job')
                                    <div class="action-btn bg-warning ms-2">
                                        <a href="{{ route('job.show', $job->id) }}" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Job Detail') }}">
                                            <i class="ti ti-eye text-white"></i>
                                        </a>
                                    </div>
                                    @endcan


                                    @can('Edit Job')
                                    <div class="action-btn bg-info ms-2">
                                        <a href="{{ route('job.edit', $job->id) }}" class="mx-3 btn btn-sm  align-items-center" data-url="" data-ajax-popup="true" data-title="{{ __('Edit Job') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit') }}">
                                            <i class="ti ti-pencil text-white"></i>
                                        </a>
                                    </div>
                                    @endcan

                                    @can('Delete Job')
                                    <div class="action-btn bg-danger ms-2">
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['job.destroy', $job->id], 'id' => 'delete-form-' . $job->id]) !!}
                                        <a href="#!" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('Delete') }}">
                                            <i class="ti ti-trash text-white"></i></a>
                                        {!! Form::close() !!}
                                    </div>
                                    @endcan

                                    <div class="dropdown action-btn bg-primary ms-2">
                                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="jobPlatformsDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ti ti-share text-white"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="jobPlatformsDropdown">
                                            <a class="dropdown-item" href="#">LinkedIn</a>
                                            <a class="dropdown-item" href="#">Glassdoor</a>
                                            <a class="dropdown-item" href="#">Indeed</a>
                                        </div>
                                    </div>
                                </span>
                                @endif
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