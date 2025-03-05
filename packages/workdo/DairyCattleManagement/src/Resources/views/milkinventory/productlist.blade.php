@extends('layouts.main')
@section('page-title')
    {{ __('Milk Inventory Product List') }}
@endsection
@section('page-breadcrumb')
    {{ __('Milk Inventory Product List') }}
@endsection

@section('page-action')
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('packages/workdo/DairyCattleManagement/src//Resources/views/assets/custom.css') }}"
    id="stylesheet">
@endpush
@section('content')
    <div class="mt-3 col-lg-12">
        <div class="mb-4 milk-sheet">
            <div class="row">
                <div class="col-md-4 col-12">{{ __('Product Name') }}</div>
                <div class="col-md-4 col-12">{{ __('On Hand Quantity') }}</div>
                <div class="col-md-4 col-12">{{ __('Available Quantity') }}</div>
            </div>
        </div>
        <div class="border shadow-none card border-primary">

            <div class="card-body">
                <div id="faq-accordion" class="accordion">
                    @if (count($milkinventorys) > 0 && !empty($milkinventorys))
                        @foreach ($milkinventorys as $k_milkinv => $milkinv)
                            <div class="mb-3 row milk-data accordion-button bg-light" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapse-{{ $k_milkinv }}"
                                aria-expanded="{{ $k_milkinv == 0 ? 'true' : 'false' }}"
                                aria-controls="collapse-{{ $k_milkinv }}">

                                <div class=" col-md-2 col-12">
                                    <svg class="me-3 d-inline-block" xmlns="http://www.w3.org/2000/svg" width="14"
                                        height="15" viewBox="0 0 14 15" fill="none">
                                        <path opacity="0.4"
                                            d="M6.89874 11.3004C9.12126 11.3004 10.923 9.49864 10.923 7.27612C10.923 5.0536 9.12126 3.25189 6.89874 3.25189C4.67622 3.25189 2.87451 5.0536 2.87451 7.27612C2.87451 9.49864 4.67622 11.3004 6.89874 11.3004Z"
                                            fill="#25314C" />
                                        <path
                                            d="M6.89865 8.71332C7.69241 8.71332 8.33588 8.06985 8.33588 7.27609C8.33588 6.48233 7.69241 5.83887 6.89865 5.83887C6.10489 5.83887 5.46143 6.48233 5.46143 7.27609C5.46143 8.06985 6.10489 8.71332 6.89865 8.71332Z"
                                            fill="#25314C" />
                                        <path
                                            d="M12.5038 7.27614C12.5038 7.51184 12.3084 7.70731 12.0727 7.70731H10.8999C10.9171 7.56358 10.9229 7.41986 10.9229 7.27614C10.9229 7.13242 10.9171 6.98869 10.8999 6.84497H12.0727C12.3084 6.84497 12.5038 7.04043 12.5038 7.27614Z"
                                            fill="#25314C" />
                                        <path
                                            d="M2.8744 7.27614C2.8744 7.41986 2.88014 7.56358 2.89739 7.70731H1.72462C1.48892 7.70731 1.29346 7.51184 1.29346 7.27614C1.29346 7.04043 1.48892 6.84497 1.72462 6.84497H2.89739C2.88014 6.98869 2.8744 7.13242 2.8744 7.27614Z"
                                            fill="#25314C" />
                                        <path
                                            d="M7.32986 2.10213V3.27493C7.18614 3.25768 7.04242 3.25191 6.8987 3.25191C6.75497 3.25191 6.61125 3.25768 6.46753 3.27493V2.10213C6.46753 1.86642 6.66299 1.67096 6.8987 1.67096C7.1344 1.67096 7.32986 1.86642 7.32986 2.10213Z"
                                            fill="#25314C" />
                                        <path
                                            d="M7.32986 11.2773V12.4501C7.32986 12.6858 7.1344 12.8813 6.8987 12.8813C6.66299 12.8813 6.46753 12.6858 6.46753 12.4501V11.2773C6.61125 11.2946 6.75497 11.3004 6.8987 11.3004C7.04242 11.3004 7.18614 11.2946 7.32986 11.2773Z"
                                            fill="#25314C" />
                                    </svg>
                                    {{ isset($milkinv->product) ? $milkinv->product->name : '--' }}
                                </div>
                                <div class="col-2"></div>
                                <div class="text-center col-md-4 col-12">
                                    {{ $productTotals[$milkinv->product_id]['total_milk'] }}
                                </div>
                                <div class="col-md-3 col-12 text-end">
                                    {{ $productTotals[$milkinv->product_id]['total_milk'] }}
                                </div>


                                {{-- </div> --}}

                            </div>
                            </h2>
                            <div id="collapse-{{ $k_milkinv }}"
                                class="accordion-collapse collapse @if ($k_milkinv == 0) show @endif"
                                aria-labelledby="heading-{{ $k_milkinv }}" data-bs-parent="#faq-accordion">
                                <div class="accordion-body commulartive-milk">
                                    {{-- Display milkinventory details --}}
                                    <table class="table mb-0">
                                        <thead>
                                            <th>{{ __('') }}</th>
                                            <th>{{ __('Milk Inventory') }}</th>
                                            <th class="text">{{ __('Date') }}</th>
                                            <th class="text">{{ __('Grand Total') }}</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($productTotals[$milkinv->product_id]['milksheet_data'] as $milksheetData)
                                                <tr>
                                                    <td> <svg class="me-3 d-inline-block" xmlns="http://www.w3.org/2000/svg"
                                                            width="14" height="15" viewBox="0 0 14 15"
                                                            fill="none">
                                                            <path opacity="0.4"
                                                                d="M6.89874 11.3004C9.12126 11.3004 10.923 9.49864 10.923 7.27612C10.923 5.0536 9.12126 3.25189 6.89874 3.25189C4.67622 3.25189 2.87451 5.0536 2.87451 7.27612C2.87451 9.49864 4.67622 11.3004 6.89874 11.3004Z"
                                                                fill="#25314C" />
                                                            <path
                                                                d="M6.89865 8.71332C7.69241 8.71332 8.33588 8.06985 8.33588 7.27609C8.33588 6.48233 7.69241 5.83887 6.89865 5.83887C6.10489 5.83887 5.46143 6.48233 5.46143 7.27609C5.46143 8.06985 6.10489 8.71332 6.89865 8.71332Z"
                                                                fill="#25314C" />
                                                            <path
                                                                d="M12.5038 7.27614C12.5038 7.51184 12.3084 7.70731 12.0727 7.70731H10.8999C10.9171 7.56358 10.9229 7.41986 10.9229 7.27614C10.9229 7.13242 10.9171 6.98869 10.8999 6.84497H12.0727C12.3084 6.84497 12.5038 7.04043 12.5038 7.27614Z"
                                                                fill="#25314C" />
                                                            <path
                                                                d="M2.8744 7.27614C2.8744 7.41986 2.88014 7.56358 2.89739 7.70731H1.72462C1.48892 7.70731 1.29346 7.51184 1.29346 7.27614C1.29346 7.04043 1.48892 6.84497 1.72462 6.84497H2.89739C2.88014 6.98869 2.8744 7.13242 2.8744 7.27614Z"
                                                                fill="#25314C" />
                                                            <path
                                                                d="M7.32986 2.10213V3.27493C7.18614 3.25768 7.04242 3.25191 6.8987 3.25191C6.75497 3.25191 6.61125 3.25768 6.46753 3.27493V2.10213C6.46753 1.86642 6.66299 1.67096 6.8987 1.67096C7.1344 1.67096 7.32986 1.86642 7.32986 2.10213Z"
                                                                fill="#25314C" />
                                                            <path
                                                                d="M7.32986 11.2773V12.4501C7.32986 12.6858 7.1344 12.8813 6.8987 12.8813C6.66299 12.8813 6.46753 12.6858 6.46753 12.4501V11.2773C6.61125 11.2946 6.75497 11.3004 6.8987 11.3004C7.04242 11.3004 7.18614 11.2946 7.32986 11.2773Z"
                                                                fill="#25314C" />
                                                        </svg></td>

                                                    <td>{{ 'MIR0000' }}{{ $milksheetData['id'] }}</td>
                                                    <td>{{ $milksheetData['date'] }}</td>
                                                    <td>{{ $milksheetData['grand_total'] }}</td>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <tbody>
                            <tr>
                                <td colspan="5">
                                    <div class="text-center">
                                        <i class="fas fa-folder-open text-primary" style="font-size: 48px;"></i>
                                        <h2>{{ __('Oops') }}...</h2>
                                        <h6>{{ __('No data found') }}.</h6>
                                        <h6>{{ __('Please create new items') }}.</h6>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    @endif


                </div>
            </div>
        </div>
    </div>

@endsection
