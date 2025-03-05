{{ Form::model($milkproduct, ['route' => ['milkproduct.update', $milkproduct->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data','class'=>'needs-validation','novalidate']) }}
<div class="modal-body">

    <div class="tab-content tab-bordered">
        <div class="tab-pane fade show active" id="tab-1" role="tabpanel">
            <div class="row">
                <div class="col-6 form-group">
                    {{ Form::label('milk_inventory_id', __('Milk Inventory'), ['class' => 'form-label']) }}<x-required></x-required>
                    <select name="milk_inventory_id" class="form-control" required>
                        <option value="">Select Animal Name</option>
                        @foreach ($animalsGrouped as $milkInventoryId => $animalNamesArray)
                            @foreach ($animalNamesArray as $animalNames)
                                <option value="{{ $milkInventoryId }}"
                                    {{ $milkInventoryId == $milkproduct->milk_inventory_id ? 'selected' : '' }}>
                                    {{ $animalNames }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
                <div class="col-6 form-group">
                    {{ Form::label('name', __('Product Name'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter Name', 'required' => 'required']) }}
                </div>
                <div class="col-6 form-group">
                    {{ Form::label('responsible', __('Responsible'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::text('responsible', null, ['class' => 'form-control', 'placeholder' => 'Enter Responsible', 'required' => 'required']) }}
                </div>
                <div class="col-6 form-group">
                    {{ Form::label('sale_price', __('Sale price'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::number('sale_price', null, ['class' => 'form-control', 'placeholder' => 'Enter Sale price', 'required' => 'required']) }}
                </div>
                <div class="col-6 form-group">
                    {{ Form::label('cost', __('Cost'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::number('cost', null, ['class' => 'form-control', 'placeholder' => 'Enter Cost', 'required' => 'required']) }}
                </div>
                <div class="col-6 form-group">
                    {{ Form::label('quantity_on_hand', __('Quantity On hand'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::number('quantity_on_hand', null, ['class' => 'form-control', 'placeholder' => 'Enter Quantity On hand', 'required' => 'required']) }}
                </div>
                <div class="col-6 form-group">
                    {{ Form::label('forcasted_quantity', __('Forcasted Quantity'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::number('forcasted_quantity', null, ['class' => 'form-control', 'placeholder' => 'Enter Forcasted Quantity', 'required' => 'required']) }}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
</div>

{{ Form::close() }}

<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('select[name="milk_inventory_id"]').change(function() {
            var selectedAnimalId = $(this).val();
            $.ajax({
                url: '{{ route('animal.data.get') }}',
                type: 'GET',
                data: {
                    selectedAnimalId: selectedAnimalId
                },
                success: function(response) {
                    $('input[name="quantity_on_hand"]').val(response.total_milk);
                    $('input[name="forcasted_quantity"]').val(response.total_milk);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
</script>
