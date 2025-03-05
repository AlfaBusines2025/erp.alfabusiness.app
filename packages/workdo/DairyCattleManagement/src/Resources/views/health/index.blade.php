@extends('layouts.main')
@section('page-title')
    {{ __('Manage Health') }}
@endsection
@section('page-breadcrumb')
    {{ __('Health') }}
@endsection
@push('css')
    @include('layouts.includes.datatable-css')
@endpush
@section('page-action')
    <div>
        @permission('health create')
            <a class="btn btn-sm btn-primary btn-icon " data-bs-toggle="tooltip" data-bs-placement="top"
                title="{{ __('Create') }}" data-ajax-popup="true" data-size="lg" data-title="{{ __('Create Health') }}"
                data-url="{{ route('health.create') }}"><i class="text-white ti ti-plus"></i></a>
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
