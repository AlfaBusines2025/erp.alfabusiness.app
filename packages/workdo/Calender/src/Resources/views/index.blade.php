@extends('layouts.main')

@section('page-title')
    {{ __('Calendar') }}
@endsection

@section('page-breadcrumb')
    {{ __('Calendar') }}
@endsection

@push('email_template_end_menu')
    @include('calender::calander_menu')
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('packages/workdo/Calender/src/Resources/assets/css/main.css') }}">
@endpush

@php
    $setting = getCompanyAllSetting();
@endphp

@section('content')
    <div class="row">
        <div class="mt-2" id="multiCollapseExample1">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => ['calender.index'], 'method' => 'GET', 'id' => 'calender_submit']) }}
                    <div class="d-flex align-items-center justify-content-end">
                        <div class="col-xl-10 col-md-10">
                            <div class="row align-items-center justify-content-end">
                                @if (module_is_active('Calender') &&
                                        Auth::user()->isAbleTo('calander show') &&
                                        isset($setting['google_calendar_enable']) &&
                                        $setting['google_calendar_enable'] == 'on' &&
                                        isset($setting['google_calender_id']))
                                    <div class="row col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 mr-2">
                                        {{ Form::label('calender type', __('Calendar Type'), ['class' => 'text-type mb-2']) }}
                                        <div class="radio-check">
                                            <div class="form-check form-check-inline form-group">
                                                <input type="radio" id="local_calender" value="local_calendar"
                                                    name="calendar_type" class="form-check-input code"
                                                    {{ !isset($_GET['calendar_type']) || $_GET['calendar_type'] == 'local_calendar' ? "checked='checked'" : '' }}>
                                                <label class="custom-control-label"
                                                    for="local_calender">{{ __('Local Calendar') }}</label>
                                            </div>
                                            <div class="form-check form-check-inline form-group">
                                                <input type="radio" id="google_calender" value="google_calendar"
                                                    name="calendar_type" class="form-check-input code"
                                                    {{ isset($_GET['calendar_type']) && $_GET['calendar_type'] == 'google_calendar' ? "checked='checked'" : '' }}>
                                                <label class="custom-control-label"
                                                    for="google_calender">{{ __('Google Calendar') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 mr-2">
                                    <div class="btn-box">
                                        {{ Form::label('type', __('Type'), ['class' => 'text-type']) }}
                                        {{ Form::select('type', ['' => 'Select Type'] + $type, isset($_GET['type']) ? $_GET['type'] : null, ['class' => 'form-control', 'id' => 'type']) }}
                                    </div>
                                </div>
                                <div class="col-auto float-end ms-2 mt-4 d-flex">
                                    <a class="btn btn-sm btn-primary me-2"
                                        onclick="document.getElementById('calender_submit').submit(); return false;"
                                        data-bs-toggle="tooltip" title="{{ __('Search') }}"
                                        data-original-title="{{ __('apply') }}">
                                        <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                    </a>
                                    <a href="{{ route('calender.index') }}" class="btn btn-sm btn-danger"
                                        data-bs-toggle="tooltip" title="{{ __('Reset') }}"
                                        data-original-title="{{ __('Reset') }}">
                                        <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off"></i></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-6">
                            @if (module_is_active('Calender') &&
                                    Auth::user()->isAbleTo('calander show') &&
                                    isset($setting['google_calendar_enable']) &&
                                    $setting['google_calendar_enable'] == 'on' &&
                                    isset($setting['google_calender_id']))
                                <h5>{{ !isset($_GET['calendar_type']) || $_GET['calendar_type'] == 'local_calendar' ? __('Local Calendar') : __('Google Calendar') }}
                                </h5>
                            @else
                                <h5>{{ __('Calendar') }} </h5>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id='calendar' class='calendar'></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('packages/workdo/Calender/src/Resources/assets/js/main.min.js') }}"></script>

    <script>
        (function() {
            var storedView = localStorage.getItem('calendarView') || 'dayGridMonth';

            var etitle;
            var etype;
            var etypeclass;
            var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    timeGridDay: "{{ __('Day') }}",
                    timeGridWeek: "{{ __('Week') }}",
                    dayGridMonth: "{{ __('Month') }}"
                },
                themeSystem: 'bootstrap',
                navLinks: true,
                droppable: true,
                selectable: true,
                selectMirror: true,
                editable: true,
                dayMaxEvents: true,
                handleWindowResize: true,
                events: {!! json_encode($events) !!},
                initialView: storedView, // Set initial view from local storage
                viewDidMount: function(info) {
                    // Store the selected view in local storage whenever it changes
                    localStorage.setItem('calendarView', info.view.type);
                }
            });
            calendar.render();
        })();
    </script>
@endpush
