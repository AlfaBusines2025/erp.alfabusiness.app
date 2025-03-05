@extends('layouts.main')
@section('page-title')
    {{ __('Daily Milk Details') }}
@endsection

@section('page-breadcrumb')
{{ __('Daily Milk Details') }}
@endsection

@section('page-action')
@endsection

@push('css')
    <style>
        #card-element {
            border: 1px solid #a3afbb !important;
            border-radius: 10px !important;
            padding: 10px !important;
        }
    </style>
@endpush


@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="row row-gap invoice-title border-1 border-bottom  pb-3 mb-3">
                    <div class="col-sm-4  col-12">
                        <h2 class="h3 mb-0">{{ (!empty($dailymilksheets->animal_name) ? $dailymilksheets->animal_name : '--') }}</h2>
                    </div>
                    <div class="col-sm-8  col-12">
                        <div class="d-flex invoice-date flex-wrap align-items-center justify-content-end gap-md-3 gap-1">
                            <p class="mb-0"><strong>{{ __('Start Date ') }}</strong> : {!! !empty($dailymilksheets->start_date) ? $dailymilksheets->start_date : '--' !!}</p>
                            <p class="mb-0"><strong>{{ __('End Date ') }}</strong> : {!! !empty($dailymilksheets->end_date) ? $dailymilksheets->end_date : '--' !!}</p>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <h5 class="card-header-title mb-3">{{ __('Animal Milk Record') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-striped overflow-hidden rounded" id="dailymilksheet">
                            <thead>
                                <tr>
                                    <th class="bg-primary text-white"> {{ __('Date') }}</th>
                                    <th class="bg-primary text-white"> {{ __('Animal Name') }}</th>
                                    <th class="bg-primary text-white"> {{ __('Morning Milk') }}</th>
                                    <th class="bg-primary text-white"> {{ __('Evening milk') }}</th>
                                    <th class="bg-primary text-white"> {{ __('Total Milk') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($animalmilks as $animalmilk)
                                    <tr>
                                        <td class="border-0">{{ $animalmilk->date }}</td>
                                        <td class="border-0">{{ $animalmilk->animal_name }}</td>
                                        <td class="border-0">{{ $animalmilk->morning_milk }}</td>
                                        <td class="border-0">{{ $animalmilk->evening_milk }}</td>
                                        <td class="border-0"><strong>{{ $animalmilk->morning_milk + $animalmilk->evening_milk }}{{ ' L' }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="border-0" colspan="3"></td>
                                    <td class="border-0">{{ __('Total Morning Milk ') }}</td>
                                    <td class="border-0"><strong>{{ $dailymilksheet->getAmTotal() }} {{ ' L' }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="border-0" colspan="3"></td>
                                    <td class="border-0">{{ __('Total Evening Milk') }}</td>
                                    <td class="border-0"><strong>{{ $dailymilksheet->getPmTotal() }} {{ ' L' }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="border-0" colspan="3"></td>
                                    <td class="border-0">{{ __('Grand Total') }}</td>
                                    <td class="border-0"><strong>{{ $dailymilksheet->getTotal() }} {{ ' L' }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
@push('scripts')
<script>
    $("#paymentModals").click(function(){
        $("#paymentModal").modal('show');
        $("ul li a").removeClass("active");
        $(".tab-pane").removeClass("active show");
        $("ul li:first a:first").addClass("active");
        $(".tab-pane:first").addClass("active show");
    });
</script>
@endpush
