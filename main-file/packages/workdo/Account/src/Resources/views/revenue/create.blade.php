{{ Form::open(array('url' => 'revenue','enctype' => 'multipart/form-data', 'class'=>'needs-validation', 'novalidate')) }}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn',['template_module' => 'revenues','module'=>'Account'])
        @endif
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('date', __('Date'),['class'=>'form-label']) }}<x-required></x-required>
            <div class="form-icon-user">
                {{Form::date('date',null,array('class'=>'form-control ','required'=>'required','placeholder' => 'Select Date'))}}
            </div>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('amount', __('Amount'),['class'=>'form-label']) }}<x-required></x-required>
            <div class="form-icon-user">
                {{ Form::number('amount',null, array('class' => 'form-control','required'=>'required','placeholder'=>'Enter Amount','step'=>'0.01','min'=>'0')) }}
            </div>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('account_id', __('Account'),['class'=>'form-label']) }}<x-required></x-required>
            {{ Form::select('account_id',$accounts,null, array('class' => 'form-control `','required'=>'required','placeholder' => 'Select Account')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('customer_id', __('Customer'),['class'=>'form-label']) }}<x-required></x-required>
            {{ Form::select('customer_id', $customers,null, array('class' => 'form-control `','required'=>'required','placeholder' => 'Select Customer')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('category_id', __('Category'),['class'=>'form-label']) }}<x-required></x-required>
            {{ Form::select('category_id', $categories,null, array('class' => 'form-control `','required'=>'required','placeholder' => 'Select Category')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('reference', __('Reference'),['class'=>'form-label']) }}<x-required></x-required>
            <div class="form-icon-user">
                {{ Form::text('reference',null, array('class' => 'form-control','placeholder'=>'Enter Reference','required'=>'required')) }}
            </div>
        </div>
        <div class="form-group  col-md-12">
            {{ Form::label('description', __('Description'),['class'=>'form-label']) }}<x-required></x-required>
            {{ Form::textarea('description',null, array('class' => 'form-control','rows'=>3,'required'=>'required','placeholder'=>'Enter Description')) }}
        </div>
        <div class="form-group">
            {{ Form::label('add_receipt', __('Payment Receipt'), ['class' => 'form-label']) }}
            <div class="choose-files ">
                <label for="add_receipt">
                    <div class=" bg-primary "> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                    <input type="file" class="ms-2 form-control file" name="add_receipt" id="add_receipt" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])" data-filename="add_receipt">
                    <img id="blah" width="50%" class="mt-3">
                </label>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}


