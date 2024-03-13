{{ Form::open(['url' => 'flexi-time', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">

    <div class="row">
        @if (\Auth::user()->type != 'employee')
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('employee_id', __('Employeess'), ['class' => 'col-form-label']) }}
                    {{ Form::select('employee_id', $employees, null, ['class' => 'form-control select2', 'id' => 'employee_id', 'placeholder' => __('Select Employee')]) }}
                </div>
            </div>
        @else
            {{-- @foreach ($employees as $employee) --}}
            {!! Form::hidden('employee_id', !empty($employees) ? $employees->id : 0, ['id' => 'employee_id']) !!}
            {{-- @endforeach --}}
        @endif
        <div class="form-group col-md-6">
        {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}
                                {{ Form::text('start_date', $startDate, ['class' => 'month-btn form-control d_week current_date start_date', 'autocomplete' => 'off']) }}
        </div>
        <div class="form-group col-md-6">
        {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}
                                {{ Form::text('end_date', $startDate, ['class' => 'month-btn form-control d_week current_date end_date', 'autocomplete' => 'off']) }}
        </div>
</div>
    <div class="row">
    <div class="form-group col-md-6">
    <div class="form-group">
        {{ Form::label('start_time', __('Start Time'), ['class' => 'col-form-label']) }}
        <select class="form-control" name="start_time">
            @for ($i = 1; $i <= 12; $i++)
                <option value="{{ sprintf('%02d', $i) }}:00 AM">{{ sprintf('%02d', $i) }}:00 AM</option>
                <option value="{{ sprintf('%02d', $i) }}:30 AM">{{ sprintf('%02d', $i) }}:30 AM</option>
            @endfor
            @for ($i = 1; $i <= 12; $i++)
                <option value="{{ sprintf('%02d', $i) }}:00 PM">{{ sprintf('%02d', $i) }}:00 PM</option>
                <option value="{{ sprintf('%02d', $i) }}:30 PM">{{ sprintf('%02d', $i) }}:30 PM</option>
            @endfor
        </select>
    </div>
</div>
<div class="form-group col-md-6">
    <div class="form-group">
        {{ Form::label('end_time', __('End Time'), ['class' => 'col-form-label']) }}
        <select class="form-control" name="end_time">
            @for ($i = 1; $i <= 12; $i++)
                <option value="{{ sprintf('%02d', $i) }}:00 AM">{{ sprintf('%02d', $i) }}:00 AM</option>
                <option value="{{ sprintf('%02d', $i) }}:30 AM">{{ sprintf('%02d', $i) }}:30 AM</option>
            @endfor
            @for ($i = 1; $i <= 12; $i++)
                <option value="{{ sprintf('%02d', $i) }}:00 PM">{{ sprintf('%02d', $i) }}:00 PM</option>
                <option value="{{ sprintf('%02d', $i) }}:30 PM">{{ sprintf('%02d', $i) }}:30 PM</option>
            @endfor
        </select>
    </div>
</div>
    </div>

    <div class="form-group col-md-12">
            {{ Form::label('remark', __('Remark'), ['class' => 'col-form-label']) }}
            {{ Form::text('remark', null, ['class' => 'form-control ', 'required' => 'required','placeholder'=>'Enter Remark']) }}
        </div>

     


    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
