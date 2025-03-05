@extends('layouts.main')
@section('page-title')
{{ __('Create Milk Inventory') }}
@endsection
@section('page-breadcrumb')
{{ __('Milk Inventory') }}
@endsection

@section('page-action')
@endsection

@section('content')
<div class="row">
    {{ Form::open(array('route' => 'milkinventory.store','method' =>'post','class'=>'needs-validation','novalidate')) }}
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                            <div class="form-group" id="customer-box">
                                {{ Form::label('daily_milksheet_id', __('Daily milk Sheet'), ['class' => 'form-label']) }}
                                {{ Form::select('daily_milksheet_id[]', $animals, null, ['class' => 'form-control daily_milksheet_id multi-select choices', 'data-toggle' => 'select2', 'multiple' => 'multiple', 'id' => 'daily_milksheet_id','data-placeholder' => 'Select Daily milk sheet']) }}
                            </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}<x-required></x-required>
                            <div class="form-icon-user">
                                {{ Form::date('date', date('Y-m-d'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Select Date']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('product_id', __('Product'), ['class' => 'form-label']) }}<x-required></x-required>
                            <div class="form-icon-user">
                                {{ Form::select('product_id', $product, null, array('class' => 'form-control','placeholder'=>'Enter Product Name' , 'required' => 'required')) }}
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="grand_total" id="grand_total" value="">

                </div>
            </div>
        </div>
    </div>
    <div class="col-12 section_div">
        <h5 class="mb-4 h4 d-inline-block font-weight-400">{{ __('Daily Milk Sheet Record') }}</h5>
        <div class="card ">
            <div class="mt-2 card-body table-border-style">
                <div class="table-responsive">
                    <table class="table mb-0 table-custom-style" id="milk_table">
                        <thead>
                            <tr>
                                <th>{{ __('Animal Name') }}</th>
                                <th>{{ __('Start Date') }}</th>
                                <th>{{ __('End Date') }}</th>
                                <th>{{ __('Morning Milk') }}</th>
                                <th>{{ __('Evening Milk') }}</th>
                                <th>{{ __('Total Milk') }} (L)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{ __('Cancel') }}" onclick="location.href = {{ route('milkinventory.index') }}';"
            class="btn btn-light me-2">
        <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
    </div>
    {{ Form::close() }}
</div>

@endsection
@push('scripts')

<script>
    $(document).on('change', '.daily_milksheet_id', function()
    {
         var animal_id = $(this).val();
         var sum = [];
             $.ajax({
                 type: 'post',
                 url: "{{ route('milk.section.get') }}",
                 data: {
                     _token: "{{ csrf_token() }}                                                                                                 ",
                     animal_id: animal_id,
                 },
                 beforeSend: function() {
                     $("#loader").removeClass('d-none');
                 },
                 success: function(response) {
                     $('#milk_table tbody').empty();



                     $.each(response, function(index, record) {
                        var sumValue = parseInt(record.amTotal) + parseInt(record.pmTotal);
                // console.log(sumValue);
                        sum.push(sumValue);
                         var tr = $('<tr>');
                         tr.append('<td>' + record.milksheetrecord.animal_name + '</td>');
                         tr.append('<td>' + record.milksheetrecord.start_date + '</td>');
                         tr.append('<td>' + record.milksheetrecord.end_date + '</td>');
                         tr.append('<td>' + record.amTotal + '</td>');
                         tr.append('<td>' + record.pmTotal + '</td>');
                         tr.append('<td>' + record.total + '</td>');

                         $('#milk_table tbody').append(tr);
                     });


                     total = 0;
                     $.each(sum, function() { total += parseFloat(this) || 0; });
                     var tr = $('<tr>');
                     tr.append('<td colspan="4"></td>');
                     tr.append('<th>Grand Total</th>');
                     tr.append('<td>' + total + '</td>');
                     $('#milk_table tbody').append(tr);

                     $('#grand_total').val(total);
                 },

             });
         });
</script>

@endpush

