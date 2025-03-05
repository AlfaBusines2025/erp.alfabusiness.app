
{{ Form::model($birth_record, array('route' => array('birth_records.update', $birth_record->id), 'method' => 'PUT','enctype'=>'multipart/form-data','class'=>'needs-validation','novalidate')) }}
    <div class="modal-body">
        <div class="row">
            <div class="col-12 form-group">
                {{ Form::label('animal_id', __('Animal'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::select('animal_id', $animals, null, array('class' => 'form-control','required'=>'required','placeholder'=>'Select Animal')) }}
            </div>
            <div class="col-12 form-group">
                {{ Form::label('birth_date', __('Birth Date'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::date('birth_date', null, ['class' => 'form-control', 'placeholder' => 'Select Birth Date', 'required' => 'required']) }}
            </div>
            <div class="form-group col-12">
                {{ Form::label('gender', __('Gender'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="row ms-1">
                    <div class="form-check col-md-6">
                        <input class="form-check-input gender" type="radio" name="gender" value="male" @if($birth_record->gender == 'male') checked @endif id="gender">
                        <label class="form-check-label" for="gender">
                            {{ __('Male') }}
                        </label>
                    </div>
                    <div class="form-check col-md-6">
                        <input class="form-check-input gender" type="radio" name="gender" value="female" @if($birth_record->gender == 'female') checked @endif id="gender">
                        <label class="form-check-label" for="gender">
                            {{ __('Female') }}
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-12 form-group">
                {{ Form::label('weight_at_birth', __('Weight at Birth'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::number('weight_at_birth', null, array('class' => 'form-control','placeholder'=>'Enter Weight','required'=>'required','step'=>'0.01')) }}
            </div>
            <div class="col-12 form-group">
                {{ Form::label('health_status', __('Health Status'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::select('health_status', $healthStatusOptions, null, array('class' => 'form-control','placeholder'=>'Select Health Status','required'=>'required')) }}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('Cancel')}}</button>
        <button type="submit" class="btn btn-primary">{{__('Update')}}</button>
    </div>

{{ Form::close() }}

