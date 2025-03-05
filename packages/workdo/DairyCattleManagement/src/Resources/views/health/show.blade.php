<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <table class="table modal-table">
                <tbody>
                    <tr>
                        <th>{{__('Animal Name')}}</th>
                        <td>{{ $health->animal_name }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Veterinarian')}}</th>
                        <td>{{ $health->veterinarian }}</td>
                    </tr>

                    <tr>
                        <th>{{__('Duration')}}</th>
                        <td>{{ $durationText = \Workdo\DairyCattleManagement\Entities\Health::$duration[$health->duration]}}</td>
                    </tr>
                    <tr>
                        <th>{{__('Date')}}</th>
                        <td>{{ $health->date }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Next Checkup Date')}}</th>
                        <td>{{ $health->checkup_date }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Diagnosis')}}</th>
                        <td>{{ $health->diagnosis }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Treatment')}}</th>
                        <td class="text-wrap">{{ $health->treatment }}</td>
                    </tr>
                    <tr>
                        <th>{{__('price')}}</th>
                        <td>{{ $health->price}}</td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
