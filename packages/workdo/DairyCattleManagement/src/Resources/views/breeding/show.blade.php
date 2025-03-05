<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <table class="table modal-table">
                <tbody>
                    <tr>
                        <th>{{__('Animal Name')}}</th>
                        <td>{{ $breedings->animal_name }}</td>
                    </tr>
                    <tr>

                        <th>{{__('Breeding Date')}}</th>
                        <td>{{ $breedings->breeding_date }}</td>
                    </tr>

                    <tr>
                        <th>{{__('Gestation Period')}}</th>
                        <td>{{ $breedings->gestation}}</td>
                    </tr>
                    <tr>
                        <th>{{__('Expected Due Date')}}</th>
                        <td>{{ $breedings->due_date }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Breeding Status')}}</th>
                        <td>
                            @if ($breedings->breeding_status == 0)
                                <span
                                    class="p-2 px-3 badge fix_badges bg-primary bill_status">{{ __(Workdo\DairyCattleManagement\Entities\Breeding::$breedingstatus[$breedings->breeding_status]) }}</span>
                            @elseif($breedings->breeding_status == 1)
                                <span
                                    class="p-2 px-3 badge fix_badges bg-info bill_status">{{ __(Workdo\DairyCattleManagement\Entities\Breeding::$breedingstatus[$breedings->breeding_status]) }}</span>
                            @elseif($breedings->breeding_status == 2)
                                <span
                                    class="p-2 px-3 badge fix_badges bg-danger bill_status">{{ __(Workdo\DairyCattleManagement\Entities\Breeding::$breedingstatus[$breedings->breeding_status]) }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{__('Breeding Notes')}}</th>
                        <td class="text-wrap">{{ $breedings->note }}</td>
                    </tr>


                </tbody>
            </table>
        </div>
    </div>
</div>
