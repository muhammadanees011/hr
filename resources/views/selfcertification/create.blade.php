@php
    $chatgpt = Utility::getValByName('enable_chatgpt');
@endphp

{{ Form::open(['url' => 'selfcertification', 'method' => 'post']) }}
<div class="modal-body">

    @if ($chatgpt == 'on')
    <div class="text-end">
        <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true" data-url="{{ route('generate', ['gpnote']) }}"
            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}"
            data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
        </a>
    </div>
    @endif
    <div class="row">
        <div class="col-md-6 form-group">
            {{ Form::label('employee_id', __('Employee Name'),['class'=>'col-form-label']) }}
            {{ Form::select('employee_id', $employees,null, array('class' => 'form-control select2','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('certification_type', __('Certification Title'),['class'=>'col-form-label']) }}
            {{ Form::text('certification_type', null, array('class' => 'form-control text','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('certification_date', __('Certification Date'),['class'=>'col-form-label']) }}
            {{ Form::date('certification_date', null, array('class' => 'form-control current_date','required'=>'required')) }}
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('details', __('Details'),['class'=>'col-form-label']) }}
                {{ Form::textarea('details', '', array('class' => 'form-control', 'rows' => '3')) }}
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Close')}}</button>
    <button type="submit" class="btn  btn-primary">{{__('Create')}}</button>
   
</div>
{{ Form::close() }}

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