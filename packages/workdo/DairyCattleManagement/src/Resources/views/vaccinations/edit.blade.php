
{{ Form::model($vaccination, array('route' => array('vaccinations.update', $vaccination->id), 'method' => 'PUT','enctype'=>'multipart/form-data','class'=>'needs-validation','novalidate')) }}
    <div class="modal-body">
        <div class="row">
            <div class="col-12 form-group">
                {{ Form::label('animal_id', __('Animal'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::select('animal_id', $animals, null, array('class' => 'form-control','required'=>'required','placeholder'=>'Select Animal')) }}
            </div>
            <div class="col-12 form-group">
                {{ Form::label('vaccination_name', __('Name'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::text('vaccination_name', null, array('class' => 'form-control','placeholder'=>'Enter Name','required'=>'required')) }}
            </div>
            <div class="col-12 form-group">
                {{ Form::label('vaccination_date', __('Date'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::date('vaccination_date', null, ['class' => 'form-control', 'placeholder' => 'Select Date', 'required' => 'required']) }}
            </div>
            <div class="col-12 form-group">
                {{ Form::label('next_due_date', __('Next Due Date'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::date('next_due_date', null, ['class' => 'form-control', 'placeholder' => 'Select Next Due Date', 'required' => 'required']) }}
            </div>
            <div class="col-12 form-group">
                {{ Form::label('veterinarian', __('Veterinarian'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::text('veterinarian', null, array('class' => 'form-control','placeholder'=>'Enter Veterinarian','required'=>'required')) }}
            </div>
            <div class="form-group col-12">
                {{Form::label('notes',__('Notes'),array('class' => 'form-label')) }}<x-required></x-required>
                {{Form::textarea('notes',null,array('class' => 'form-control','rows' => 3,'required' => 'required','placeholder'=>'Enter Notes'))}}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('Cancel')}}</button>
        <button type="submit" class="btn btn-primary">{{__('Update')}}</button>
    </div>

{{ Form::close() }}

