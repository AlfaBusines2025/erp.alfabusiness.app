@extends('layouts.main')
@section('page-title')
    {{ __('Manage Milk Product') }}
@endsection
@section('page-breadcrumb')
    {{ __('Milk Product') }}
@endsection
@push('css')
    @include('layouts.includes.datatable-css')
@endpush

@section('page-action')
    <div>
        @permission('milkproduct create')
            <a class="btn btn-sm btn-primary btn-icon " data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Create') }}"
                data-ajax-popup="true" data-size="lg" data-title="{{ __('Create Milk Product') }}"
                data-url="{{ route('milkproduct.create') }}"><i class="text-white ti ti-plus"></i></a>
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

