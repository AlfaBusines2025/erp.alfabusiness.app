{{ Form::model($weight, array('route' => array('weight.update', $weight->id), 'method' => 'PUT','enctype'=>'multipart/form-data','class'=>'needs-validation','novalidate')) }}

<div class="modal-body">
        <div class="tab-content tab-bordered">
            <div class="tab-pane fade show active" id="tab-1" role="tabpanel">
                <div class="row">
                    <div class="col-6 form-group">
                        {{ Form::label('animal_id', __('Animal Name'),['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::select('animal_id', $animal, null, array('class' => 'form-control','placeholder'=>'Enter Name','required'=>'required')) }}

                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('date', __('Date Recorded'),['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::date('date', null, array('class' => 'form-control','placeholder'=>'Enter Date','required'=>'required')) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('age', __('Age'),['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::number('age', null, array('class' => 'form-control','placeholder'=>'Enter Age','required'=>'required')) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('weight', __('weight(Kgs)'),['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::number('weight', null, array('class' => 'form-control','placeholder'=>'Enter weight','required'=>'required')) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('Cancel')}}</button>
        <button type="submit" class="btn btn-primary">{{__('Update')}}</button>
    </div>

{{ Form::close() }}

