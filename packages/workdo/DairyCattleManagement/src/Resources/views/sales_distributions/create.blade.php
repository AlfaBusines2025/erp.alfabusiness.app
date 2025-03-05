{{ Form::open(array('url' => 'sales_distribution', 'method' => 'post','class'=>'needs-validation','novalidate')) }}
    <div class="modal-body">
        <div class="row">
            <div class="col-12 form-group">
                {{ Form::label('customer_id', __('Customer'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::select('customer_id', $customers, null, array('class' => 'form-control','required'=>'required','placeholder'=>'Select Customer')) }}
            </div>
            <div class="col-12 form-group">
                {{ Form::label('milk_product_id', __('Milk Product'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::select('milk_product_id', $products, null, array('class' => 'form-control','required'=>'required','placeholder'=>'Select Milk Product')) }}
            </div>
            <div class="col-12 form-group">
                {{ Form::label('quantity', __('Quantity'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::number('quantity', null, array('class' => 'form-control','placeholder'=>'Enter Quantity','required'=>'required')) }}
            </div>
            <div class="col-12 form-group">
                {{ Form::label('total_price', __('Total Price'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::number('total_price', null, array('class' => 'form-control','placeholder'=>'Enter Total Price','required'=>'required')) }}
            </div>
            <div class="col-12 form-group">
                {{ Form::label('sale_date', __('Sale Date'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::date('sale_date', null, ['class' => 'form-control', 'placeholder' => 'Select Sale Date', 'required' => 'required']) }}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('Close')}}</button>
        <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
    </div>
{{ Form::close() }}
