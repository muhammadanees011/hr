@php
    $chatgpt = Utility::getValByName('enable_chatgpt');
@endphp

{{ Form::open(['url' => 'overtime', 'method' => 'post']) }}
<div class="modal-body">

    @if ($chatgpt == 'on')
    <div class="text-end">
        <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true" data-url="{{ route('generate', ['healthassessment']) }}"
            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}"
            data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
        </a>
    </div>
    @endif

    <div class="row">
    <div class="form-group col-md-6">
            {{ Form::label('title', __('Overtime Title*'), ['class' => 'col-form-label']) }}
            {{ Form::text('title', null, ['class' => 'form-control ', 'required' => 'required','placeholder'=>'Enter Title']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('days', __('No. of Days'), ['class' => 'col-form-label']) }}
            {{ Form::number('days', null, ['class' => 'form-control text', 'autocomplete' => 'off' ,'required' => 'required']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('hours', __('No. of Hours'), ['class' => 'col-form-label']) }}
            {{ Form::number('hours', null, ['class' => 'form-control text', 'autocomplete' => 'off' ,'required' => 'required']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('rate', __('Hourly Rate'), ['class' => 'col-form-label']) }}
            {{ Form::number('rate', null, ['class' => 'form-control text', 'autocomplete' => 'off' ,'required' => 'required']) }}
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