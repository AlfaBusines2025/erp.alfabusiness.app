<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <table class="table modal-table">
                <tbody>
                    <tr>
                        <th>{{__('Animal Name')}}</th>
                        <td>{{ $weight->animal_name }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Date Recorded')}}</th>
                        <td>{{ $weight->date }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Age')}}</th>
                        <td>{{ $weight->age}}</td>
                    </tr>
                    <tr>
                        <th>{{__('Weight(Kgs)')}}</th>
                        <td>{{ $weight->weight }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
