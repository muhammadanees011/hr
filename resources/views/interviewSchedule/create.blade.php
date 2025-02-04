@php
    $setting = App\Models\Utility::settings();
    $chatgpt = Utility::getValByName('enable_chatgpt');
@endphp
{{ Form::open(['url' => 'interview-schedule', 'method' => 'post']) }}
<div class="modal-body">

    @if ($chatgpt == 'on')
    <div class="text-end">
        <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true" data-url="{{ route('generate', ['interview-schedule']) }}"
            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}"
            data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
        </a>
    </div>
    @endif

    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('candidate', __('Interview To'), ['class' => 'col-form-label']) }}
            {{ Form::select('candidate', $candidates, null, ['class' => 'form-control select2', 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('employee', __('Interviewer'), ['class' => 'col-form-label']) }}
            {{ Form::select('employee', $employees, null, ['class' => 'form-control select2']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('date', __('Interview Date'), ['class' => 'col-form-label']) }}
            {{ Form::text('date', null, ['class' => 'form-control d_week current_date', 'autocomplete' => 'off', 'id' => 'currentDate']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('time', __('Interview Time'), ['class' => 'col-form-label']) }}
            {{ Form::time('time', null, ['class' => 'form-control ', 'id' => 'currentTime']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('destination', __('Destination'), ['class' => 'col-form-label']) }}
            {{ Form::select('destination', $destination, null, ['class' => 'form-control select2', 'required' => 'required', 'id' => 'destination', 'onchange' => 'onDestinationChange()']) }}
        </div>
        <div class="form-group ">
            {{ Form::label('comment', __('Comment'), ['class' => 'col-form-label']) }}
            {{ Form::textarea('comment', null, ['class' => 'form-control', 'rows' => '3']) }}
        </div>
        <div id="address-field" class="form-group" style="display: none;">
            {{ Form::label('address', __('Address'), ['class' => 'col-form-label']) }}
            {{ Form::textarea('address', null, ['class' => 'form-control', 'rows' => '4']) }}
        </div>
        @if (isset($setting['is_enabled']) && $setting['is_enabled'] == 'on')
            <div class="form-group col-md-6">
                {{ Form::label('synchronize_type', __('Synchroniz in Google Calendar ?'), ['class' => 'form-label']) }}
                <div class=" form-switch">
                    <input type="checkbox" class="form-check-input mt-2" name="synchronize_type" id="switch-shadow"
                        value="google_calender">
                    <label class="form-check-label" for="switch-shadow"></label>
                </div>
            </div>
        @endif
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">

</div>
{{ Form::close() }}

@if ($candidate != 0)
    <script>
        $('select#candidate').val({{ $candidate }}).trigger('change');
    </script>
@endif

<script>
    // Function to handle onchange event of the destination dropdown
    function onDestinationChange() {
        // Get the selected value of the destination dropdown
        var selectedDestination = document.getElementById('destination').value; 
        
        // Get the address field
        var addressField = document.getElementById('address-field');
        
        // Check if "On Site" is selected
        if (selectedDestination === '1') { // Assuming '1' is the value for "On Site"
            // Show the address field
            addressField.style.display = 'block';
        } else {
            // Hide the address field
            addressField.style.display = 'none';
        }
    }

</script>





<script>
    const getTwoDigits = (value) => value < 10 ? `0${value}` : value;

    const formatDate = (date) => {
        const day = getTwoDigits(date.getDate());
        const month = getTwoDigits(date.getMonth() + 1); // add 1 since getMonth returns 0-11 for the months
        const year = date.getFullYear();

        return `${year}-${month}-${day}`;
    }

    const formatTime = (date) => {
        const hours = getTwoDigits(date.getHours());
        const mins = getTwoDigits(date.getMinutes());

        return `${hours}:${mins}`;
    }

    const date = new Date();
    document.getElementById('currentDate').value = formatDate(date);
    document.getElementById('currentTime').value = formatTime(date);
</script>
