{{ Form::open(array('url' => 'feeds_schedule', 'method' => 'post','class'=>'needs-validation','novalidate')) }}
    <div class="modal-body">
        <div class="row">
            <div class="col-12 form-group">
                {{ Form::label('animal_id', __('Animal'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::select('animal_id', $animals, null, array('class' => 'form-control','required'=>'required','placeholder'=>'Select Animal')) }}
            </div>
            <div class="col-12 form-group">
                {{ Form::label('feed_type_id', __('Feed Type'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::select('feed_type_id', $feed_types, null, array('class' => 'form-control','required'=>'required','placeholder'=>'Select Feed Type')) }}
            </div>
            <div class="col-12 form-group">
                {{ Form::label('quantity', __('Quantity'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::number('quantity', null, array('class' => 'form-control','placeholder'=>'Enter Quantity','required'=>'required')) }}
            </div>
            <div class="col-12 form-group">
                {{ Form::label('scheduled_time', __('Scheduled Time'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::input('datetime-local', 'scheduled_time', null, ['class' => 'form-control', 'placeholder' => 'Select Date & Time', 'required' => 'required']) }}
            </div>
            <div class="col-12 form-group">
                {{ Form::label('consumption_end', __('Consumption End'), ['class' => 'form-label']) }}
                {{ Form::input('datetime-local', 'consumption_end', null, ['class' => 'form-control', 'placeholder' => 'Select End Date & Time']) }}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
    </div>
{{ Form::close() }}
