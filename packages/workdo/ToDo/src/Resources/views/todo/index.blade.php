@extends('layouts.main')
@section('page-title')
    {{ __('To Do') }}
@endsection
@section('page-breadcrumb')
    {{ __('To Do') }}
@endsection
@push('css')
    @include('layouts.includes.datatable-css')
@endpush
@section('page-action')
    <div class="d-flex">
        @stack('addButtonHook')
        @permission('todo setup')
            <a class="btn btn-sm me-2 btn-primary" data-bs-toggle="tooltip" href="{{ route('stage.index') }}"
                data-bs-original-title="{{ __('Setup') }}">
                <i class="ti ti-settings"></i>
            </a>
        @endpermission
        @permission('todo manage')
            <a class="btn btn-sm me-2 btn-primary" data-bs-toggle="tooltip" href="{{ route('to-do.board') }}"
                data-bs-original-title="{{ __('Boards') }}">
                <i class="ti ti-table"></i>
            </a>
            <a class="btn btn-sm me-2 btn-primary" data-bs-toggle="tooltip" href="{{ route('to-do.calendar') }}"
                data-bs-original-title="{{ __('Calendar') }}">
                <i class="ti ti-calendar"></i>
            </a>
            @permission('todo create')
            <a class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create To Do') }}"
                data-url="{{ route('to-do.create') }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
            @endpermission
        @endpermission
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
        <div class=" multi-collapse mt-2" id="multiCollapseExample1">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-end">
                        <div class="col-xl-6">
                            <div class="row">
                                <div class="col-xl-6 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                                    <div class="btn-box">
                                        <label for="priorities" class="form-label">{{ __('Priority :') }}</label>
                                        <select class="form-control" id="priorities" name="priorities">
                                            <option value="">{{ __('Select Priority') }}</option>

                                            @foreach($priorities as $key => $priority)
                                                <option value="{{ $priority }}">{{ ucfirst($priority) }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-3 col-md-6 col-sm-12 col-12">
                                    <div class="btn-box">
                                        <label for="stages" class="form-label">{{ __('Stages :') }}</label>
                                        <select class="form-control" id="stages" name="stages">
                                            <option value="">{{ __('Select Stage') }}</option>

                                            @foreach($stages as $stage)
                                                <option value="{{ $stage->name }}">{{ ucfirst($stage->name) }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <h5></h5>
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
