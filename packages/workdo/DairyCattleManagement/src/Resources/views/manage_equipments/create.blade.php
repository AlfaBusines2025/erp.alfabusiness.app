{{ Form::open(array('url' => 'dairy-equipments', 'method' => 'post','class'=>'needs-validation','novalidate')) }}
    <div class="modal-body">
        <div class="row">
            <div class="col-12 form-group">
                {{ Form::label('name', __('Name'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::text('name', null, array('class' => 'form-control','placeholder'=>'Enter Name','required'=>'required')) }}
            </div>
            <div class="form-group col-12">
                {{Form::label('description',__('Description'),array('class' => 'form-label')) }}<x-required></x-required>
                {{Form::textarea('description',null,array('class' => 'form-control','rows' => 3,'required' => 'required','placeholder'=>'Enter Description'))}}
            </div>
            <div class="col-12 form-group">
                {{ Form::label('purchase_date', __('Purchase Date'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::date('purchase_date', null, ['class' => 'form-control', 'placeholder' => 'Select Purchase Date', 'required' => 'required']) }}
            </div>
            <div class="col-12 form-group">
                {{ Form::label('maintenance_schedule', __('Maintenance Schedule'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::select('maintenance_schedule', $schedules, null, array('class' => 'form-control','required'=>'required','placeholder'=>'Select Schedule')) }}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('Cancel')}}</button>
        <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
    </div>
{{ Form::close() }}
