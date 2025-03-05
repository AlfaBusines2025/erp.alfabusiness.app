<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <table class="table modal-table">
                <tbody>
                    <tr>
                        <th>{{__('Product Name')}}</th>
                        <td>{{ $milkproducts->name }}</td>
                    </tr>
                    <tr>

                        <th>{{__('Responsible')}}</th>
                        <td>{{ $milkproducts->responsible }}</td>
                    </tr>

                    <tr>
                        <th>{{__('Sale price')}}</th>
                        <td>{{ currency_format_with_sym($milkproducts->sale_price) }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Cost')}}</th>
                        <td>{{ currency_format_with_sym($milkproducts->cost) }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Quantity On hand')}}</th>
                        <td class="text-wrap">{{ $milkproducts->quantity_on_hand }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Forcasted Quantity')}}</th>
                        <td class="text-wrap">{{ $milkproducts->forcasted_quantity }}</td>
                    </tr>


                </tbody>
            </table>
        </div>
    </div>
</div>
