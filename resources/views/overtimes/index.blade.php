@extends('layouts.admin')

@section('page-title')
    {{ __('Manage OverTime') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Overtime') }}</li>
@endsection


@section('action-button')
        <a href="#" data-url="{{ route('carryover.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create Overtime entry') }}" data-size="lg" data-bs-toggle="tooltip" title=""
            class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
@endsection

@section('content')
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                {{-- <h5> </h5> --}}
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                            <th>{{ __('Employee') }}</th>
                                <th>{{ __('No. of Days') }}</th>
                                <th>{{ __('No. of Hours') }}</th>
                                <th>{{ __('Rate') }}</th>
                                <th>{{ __('status') }}</th>
                                @if (Gate::check('Edit Termination') || Gate::check('Delete Termination'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                          

                            @foreach ($overtimes as $overtime)
                            <tr>
                            <td>{{ !empty($overtime->employee) ? $overtime->employee->name : '' }}</td>
                            <td>{{ !empty($overtime->number_of_days) ? $overtime->number_of_days : '' }}</td>
                            <td>{{ !empty($overtime->hours) ? $overtime->hours : '' }}</td>
                            <td>{{ !empty($overtime->rate) ? $overtime->rate : '' }}</td>
                            <td>
                                @if ($overtime->status == 'pending')
                                    <div class="badge bg-warning p-2 px-3 rounded">{{ $overtime->status }}</div>
                                @elseif($overtime->status == 'accepted')
                                    <div class="badge bg-success p-2 px-3 rounded">{{ $overtime->status }}</div>
                                @elseif($overtime->status == "rejected")
                                    <div class="badge bg-danger p-2 px-3 rounded">{{ $overtime->status }}</div>
                                @endif
                            </td>
                                <td class="Action">
                                    @if (Gate::check('Edit Termination') || Gate::check('Delete Termination'))
                                        <span>
                                            @can('Edit Retirement') 
                                            <div class="action-btn bg-success ms-2">
                                                <a href="#" class="mx-3 btn btn-sm  align-items-center" data-size="lg"
                                                    data-url="{{ URL::to('carryover/' . $overtime->id . '/action') }}"
                                                    data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                    title="" data-title="{{ __('Leave Action') }}"
                                                    data-bs-original-title="{{ __('CarryOver Leave') }}">
                                                    <i class="ti ti-caret-right text-white"></i>
                                                </a>
                                            </div>
                                            @endcan
                                            @can('Edit Retirement')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-size="lg"
                                                        data-url="{{ URL::to('carryover/' . $carryover->id . '/edit') }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                        title="" data-title="{{ __('Edit Leave CarryOver') }}"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan

                                            @can('Delete Retirement')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['carryover.destroy', $carryover->id], 'id' => 'delete-form-' . $carryover->id]) !!}
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                        aria-label="Delete"><i
                                                            class="ti ti-trash text-white text-white"></i></a>
                                                    </form>
                                                </div>
                                            @endcan
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
