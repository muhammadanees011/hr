
@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Exit Procedure') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Exit Procedure') }}</li>
@endsection

@section('action-button')
    @can('Create Exit Procedure')
        <a href="#" data-url="{{ route('exitprocedure.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create New Exit Procedure') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection

@section('content')
        <div class="col-3">
            @include('layouts.hrm_setup')
        </div>
        <div class="col-9">
            <div class="card">
                <div class="card-body table-border-style">

                    <div class="table-responsive">
                <table class="table" id="pc-dt-simple">
                    <thead>
                        <tr>
                            <th>{{ __('Exit Procedure Type') }}</th>
                            <th width="200px">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($exitprocedures as $exitprocedure)
                            <tr>
                                <td>{{ $exitprocedure->name }}</td>
                                <td class="Action">
                                    <span>
                                        @can('Edit Exit Procedure')
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                    data-url="{{ URL::to('exitprocedure/' . $exitprocedure->id . '/edit') }}"
                                                    data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                    data-title="{{ __('Edit ExitProcedure') }}"
                                                    data-bs-original-title="{{ __('Edit') }}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                        @endcan

                                        @can('Delete Exit Procedure')
                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['exitprocedure.destroy', $exitprocedure->id], 'id' => 'delete-form-' . $exitprocedure->id]) !!}
                                                <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                    data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                    aria-label="Delete"><i
                                                        class="ti ti-trash text-white text-white"></i></a>
                                                </form>
                                            </div>
                                        @endcan
                                    </span>
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
    