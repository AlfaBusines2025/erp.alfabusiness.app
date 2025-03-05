@extends('layouts.main')
@section('page-title')
    {{ __('Manage Milk Inventory') }}
@endsection
@section('page-breadcrumb')
    {{ __('Milk Inventory') }}
@endsection
@push('css')
    @include('layouts.includes.datatable-css')
@endpush

@section('page-action')
    <div class="d-flex">
        @permission('milkinventory create')
            <a href="{{ route('milkinventory.product.list') }}" class="btn btn-sm btn-primary me-2" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Product List') }}">
                <i class="ti ti-list"></i>
            </a>
        @endpermission
        @permission('milkinventory create')
            <a href="{{ route('milkinventory.create') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        {{ $dataTable->table(['width' => '100%']) }}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    @include('layouts.includes.datatable-js')
    {{ $dataTable->scripts() }}
@endpush

