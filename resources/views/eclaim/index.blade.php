@extends('layouts.admin')

@section('page-title')
   {{ __('Manage Eclaim') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Eclaim') }}</li>
@endsection

@section('action-button')
   @can('Create Eclaim')
        <a href="#" data-url="{{ route('eclaim.create') }}" data-ajax-popup="true"
            data-title="{{ __('Request A New Eclaim') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection

@section('content')
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style">

                    <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>{{ __('Eclaim Type') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th width="200px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($eclaims as $eclaim)
                                <tr>
                                    <td>{{ $eclaim->claimType->title }}</td>
                                    <td>{{env('CURRENCY_SYMBOL') ?? '£'}}{{ number_format($eclaim->amount, 2) }}</td>
                                    <td>{{ $eclaim->description }}</td>
                                    <td class="Action">
                                        <span>
                                            @if (\Auth::user()->type == 'employee' && $eclaim->status=="pending")
                                                @can('Edit Eclaim')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                            data-url="{{ URL::to('eclaim/' . $eclaim->id . '/edit') }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                            data-title="{{ __('Edit Eclaim') }}"
                                                            data-bs-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan

                                                @can('Delete Eclaim')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['eclaim.destroy', $eclaim->id], 'id' => 'delete-form-' . $eclaim->id]) !!}
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                            data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                            aria-label="Delete"><i
                                                                class="ti ti-trash text-white text-white"></i></a>
                                                        </form>
                                                    </div>
                                                @endcan
                                            @endif

                                            @can('Manage Eclaim')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm bg-info  align-items-center"
                                                        data-url="{{ URL::to('eclaim/' . $eclaim->id . '/receipt') }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                        data-title="{{ __('Eclaim Receipt') }}"
                                                        data-bs-original-title="{{ __('View Receipt') }}">
                                                        <i class="ti ti-eye text-white"></i>
                                                    </a>
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

