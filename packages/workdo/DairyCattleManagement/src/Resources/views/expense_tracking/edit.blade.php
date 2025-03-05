
{{ Form::model($expense_tracking, array('route' => array('expense_tracking.update', $expense_tracking->id), 'method' => 'PUT','enctype'=>'multipart/form-data','class'=>'needs-validation','novalidate')) }}

    <div class="modal-body">
        <div class="row">
            <div class="col-12 form-group">
                {{ Form::label('category', __('Category'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::text('category', null, array('class' => 'form-control','placeholder'=>'Enter Category','required'=>'required')) }}
            </div>
            <div class="col-12 form-group">
                {{ Form::label('amount', __('Amount'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('amount', null, ['class' => 'form-control', 'placeholder' => 'Enter Amount', 'required' => 'required']) }}
            </div>
            <div class="col-12 form-group">
                {{ Form::label('expense_date', __('Expense Date'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::date('expense_date', null, ['class' => 'form-control', 'placeholder' => 'Select Expense Date', 'required' => 'required']) }}
            </div>
            <div class="form-group col-12">
                {{Form::label('description',__('Description'),array('class' => 'form-label')) }}<x-required></x-required>
                {{Form::textarea('description',null,array('class' => 'form-control','rows' => 3,'required' => 'required','placeholder'=>'Enter Description'))}}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('Cancel')}}</button>
        <button type="submit" class="btn btn-primary">{{__('Update')}}</button>
    </div>

{{ Form::close() }}
