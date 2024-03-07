@extends('layouts.admin')

@section('page-title')
   {{ __('Manage Videos') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Video') }}</li>
@endsection

@section('action-button')
   @can('Create Eclaim')
        <a href="#" data-url="{{ route('video.create') }}" data-ajax-popup="true"
            data-title="{{ __('Add a New Video') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
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
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Source') }}</th>
                                <th>{{ __('Video Link') }}</th>
                                <th>{{ __('File Name') }}</th>
                                <th width="200px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($videos as $video)
                                <tr>
                                <td>{{ $video->title }}</td>
                                    <td>{{ $video->source_type }}</td>
                                    <td>{{ $video->video_link ?? "NA" }}</td>
                                    <td>{{ $video->video_file ?? "NA" }}</td>
                                    
                                    <td class="Action">
                                        <span>
                                            @can('Edit Eclaim')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                    data-url="{{ URL::to('video/' . $video->id . '/edit') }}"
                                                    
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan

                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['video.destroy', $video->id], 'id' => 'delete-form-' . $video->id]) !!}
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                        aria-label="Delete"><i
                                                            class="ti ti-trash text-white text-white"></i></a>
                                                    </form>
                                                </div>


                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm bg-info  align-items-center"
                                                    
                                                    data-url="{{ URL::to('video/' . $video->id . '/show-video') }}"    
                                                    data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                        data-title="{{ __('Video') }}"
                                                        
                                                        data-bs-original-title="{{ __('View Video') }}">
                                                        <i class="ti ti-eye text-white"></i>
                                                    </a>
                                                </div>

                                                 
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

