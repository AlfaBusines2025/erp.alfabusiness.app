@extends('layouts.main')
@section('page-title')
    {{ __('Milk Inventory Detail') }}
@endsection
@section('page-breadcrumb')
    {{ __('Milk Inventory Detail') }}
@endsection

@section('page-action')
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body invoice">
                    <div class="invoice-title border-1 border-bottom  pb-3 mb-3">
                        <h3 class="invoice-number d-inline-flex mb-0">{{ 'MIR0000' }}{{ $milkinventory->id }}</h3>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><b>{{ __('Daily Milk Sheet ') }}</b>: {{ implode(', ', $animal) }}</p>
                            <p class="mb-2"><b>{{ __('Date ') }}</b> : {!! !empty($milkinventory->date) ? $milkinventory->date : '--' !!}</p>
                            <p class="mb-0"><b>{{ __('Product ') }}</b> : {!! !empty($milkinventory->product->name) ? $milkinventory->product->name : '--' !!}</p>
                        </div>
                    </div>
                    <div class="invoice-summary mt-3">
                        <div class="invoice-title border-1 border-bottom mb-3 pb-2">
                            <h3 class="h4 mb-0">{{ __('Daily Milk Sheet Record') }}</h3>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped" id="dailymilksheet">
                                <thead>
                                    <tr>
                                        <th class="bg-primary text-white"> {{ __('Animal Name') }}</th>
                                        <th class="bg-primary text-white"> {{ __('Start Date') }}</th>
                                        <th class="bg-primary text-white"> {{ __('End Date') }}</th>
                                        <th class="bg-primary text-white"> {{ __('Morning Milk') }}</th>
                                        <th class="bg-primary text-white"> {{ __('Evening Milk') }}</th>
                                        <th class="bg-primary text-white"> {{ __('Total Milk') }}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dailymilksheets as $dailymilksheet)
                                        <tr class="font-style">
                                            <td>{{ $dailymilksheet->animal_name }}</td>
                                            <td>{{ $dailymilksheet->start_date }}</td>
                                            <td>{{ $dailymilksheet->start_date }}</td>
                                            <td>{{ $dailymilksheet->getAmTotal() }}</td>
                                            <td>{{ $dailymilksheet->getPmTotal() }}</td>
                                            <th>{{ $dailymilksheet->getTotal() }}{{ ' L' }}</th>
                                        </tr>
                                    @endforeach
                                </tbody>
                                @php
                                // Initialize variables to store totals
                                $totalAmTotal = 0;
                                $totalPmTotal = 0;
                                $totalTotal = 0;

                                // Iterate through $dailymilksheets to calculate totals
                                foreach ($dailymilksheets as $dailymilksheet) {
                                    $totalAmTotal += $dailymilksheet->getAmTotal();
                                    $totalPmTotal += $dailymilksheet->getPmTotal();
                                    $totalTotal += $dailymilksheet->getTotal();
                                }
                            @endphp

                                <tfoot>
                                    <tr>
                                        <td colspan="4"></td>
                                        <td>{{ __('Total Morning Milk ') }}</td>
                                        <td><strong>{{ $totalAmTotal }} {{ ' L' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"></td>
                                        <td>{{ __('Total Evening Milk ') }}</td>
                                        <td><strong>{{ $totalPmTotal }} {{ ' L' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"></td>
                                        <td>{{ __('Grand Total') }}</td>
                                        <td><strong>{{ $totalTotal }} {{ ' L' }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
