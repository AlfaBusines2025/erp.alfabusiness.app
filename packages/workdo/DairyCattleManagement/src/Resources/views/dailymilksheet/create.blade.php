
{{ Form::open(array('url' => 'dailymilksheet','enctype'=>'multipart/form-data','class'=>'needs-validation','novalidate')) }}
    <div class="modal-body">
        <div class="tab-content tab-bordered">
            <div class="tab-pane fade show active" id="tab-1" role="tabpanel">
                <div class="row">
                    <div class="col-6 form-group">
                        {{ Form::label('animal_id', __('Animal Name'),['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::select('animal_id', $animal, null, array('class' => 'form-control','placeholder'=>'Enter Name','required'=>'required')) }}

                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('start_date', __('Start Date'),['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::date('start_date', null, array('class' => 'form-control','placeholder'=>'Enter Start Date','required'=>'required')) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('end_date', __('End Date'),['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::date('end_date', null, array('class' => 'form-control','placeholder'=>'Enter End Date','required'=>'required')) }}
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

