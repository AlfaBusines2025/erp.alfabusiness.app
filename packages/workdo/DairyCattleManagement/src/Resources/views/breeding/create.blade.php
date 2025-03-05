
{{ Form::open(array('url' => 'breeding','enctype'=>'multipart/form-data','class'=>'needs-validation','novalidate')) }}
    <div class="modal-body">
        <div class="tab-content tab-bordered">
            <div class="tab-pane fade show active" id="tab-1" role="tabpanel">
                <div class="row">
                    <div class="col-6 form-group">
                        {{ Form::label('animal_id', __('Animal Name'),['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::select('animal_id', $animal, null, array('class' => 'form-control','placeholder'=>'Enter Name','required'=>'required')) }}

                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('breeding_date', __('Breeding Date'),['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::date('breeding_date', null, array('class' => 'form-control','placeholder'=>'Enter Breeding Date','required'=>'required' ,'min'=> date("Y-m-d"))) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('gestation', __('Gestation Period'),['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::number('gestation', null, array('class' => 'form-control','placeholder'=>'Enter Gestation Period','required'=>'required')) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('due_date', __('Expected Due Date'),['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::date('due_date', null, array('class' => 'form-control','placeholder'=>'Enter Expected Due Date','required'=>'required')) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('breeding_status', __('Breeding Status'),['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::select('breeding_status', $breedingstatus, null, array('class' => 'form-control','required'=>'required')) }}

                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('note', __('Breeding Notes'),['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::text('note', null, array('class' => 'form-control','placeholder'=>'Enter Note','required'=>'required')) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('Cancel')}}</button>
        <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
    </div>

{{ Form::close() }}

