@extends('layouts.main')
@section('page-title')
    {{ __('To Do') }}
@endsection
@section('page-breadcrumb')
    {{ __('To Do') }}
@endsection
@section('page-action')
    <div class="d-flex">
        @stack('addButtonHook')
        @permission('todo setup')
            <a class="btn btn-sm btn-primary me-2" data-bs-toggle="tooltip" href="{{ route('stage.index') }}"
                data-bs-original-title="{{ __('Setup') }}">
                <i class="ti ti-settings"></i>
            </a>
        @endpermission

        @permission('todo manage')
            <a class="btn btn-sm btn-primary me-2" data-bs-toggle="tooltip" href="{{ route('to-do.index') }}"
                data-bs-original-title="{{ __('List') }}">
                <i class="ti ti-list"></i>
            </a>
            <a class="btn btn-sm btn-primary me-2" data-bs-toggle="tooltip" href="{{ route('to-do.board') }}"
                data-bs-original-title="{{ __('Boards') }}">
                <i class="ti ti-table"></i>
            </a>
            <a class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create To Do') }}"
                data-url="{{ route('to-do.create') }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission

    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('packages/workdo/ToDo/src/Resources/assets/css/main.css') }}">
@endpush
@section('content')
    <div class="row">

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Calendar') }}</h5>
                </div>
                <div class="card-body">
                    <div id='calendar' class='calendar'></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">

            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">{{ __('To Dos List') }}</h4>
                    <ul class="event-cards list-group list-group-flush mt-3 w-100">
                        @forelse ($events as $event)
                            <li class="list-group-item card mb-3">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-auto mb-3 mb-sm-0">
                                        <div class="d-flex align-items-center">
                                            <div class="theme-avtar bg-primary badge">
                                                <i class="ti ti-calendar-event"></i>
                                            </div>
                                            <div class="ms-3">
                                                <h6 class="card-text small text-primary">{{ $event->title }}
                                                </h6>
                                                <div class="card-text small text-dark">{{ __('Priority :') }}
                                                    @if ($event->priority == 'Low')
                                                        <div class="badge bg-success p-1 px-3">
                                                            {{ $event->priority }}
                                                        </div>
                                                    @elseif($event->priority == 'Medium')
                                                        <div class="badge bg-warning p-1 px-3">
                                                            {{ $event->priority }}
                                                        </div>
                                                    @elseif($event->priority == 'High')
                                                        <div class="badge bg-danger p-1 px-3">
                                                            {{ $event->priority }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="card-text small text-dark">{{ __('Module :') }}
                                                    <div class="badge bg-secondary p-1 px-3">
                                                        {{ !empty($event->sub_module) ? $event->sub_module : '----' }}
                                                    </div>
                                                </div>
                                                <div class="card-text small text-dark">{{ __('Start Date :') }}
                                                    {{ company_date_formate($event->start_date) }}
                                                </div>
                                                <div class="card-text small text-dark">{{ __('End Date :') }}
                                                    {{ company_date_formate($event->due_date) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <div class="text-center">
                                <h6>{{ __('There is no To Dos in this month') }}</h6>
                            </div>
                        @endforelse

                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('packages/workdo/ToDo/src/Resources/assets/js/main.min.js') }}"></script>
    <script type="text/javascript">
        (function() {
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
                slotDuration: '00:10:00',
                navLinks: true,
                droppable: true,
                selectable: true,
                selectMirror: true,
                editable: true,
                dayMaxEvents: true,
                handleWindowResize: true,
                events: {!! $arrSchedule !!},
            });
            calendar.render();
        })();
    </script>
    <script>
        $(document).on('click', '.todo-show', function(e) {
            e.preventDefault();
            var event = $(this);
            var title = $(this).find('.fc-event-title-container .fc-event-title').html();
            var size = 'lg';
            var url = $(this).attr('href');
            $("#commonModal .modal-title").html(title);
            $("#commonModal .modal-dialog").addClass('modal-' + size);
            $.ajax({
                url: url,
                success: function(data) {
                    $('#commonModal .body').html(data);
                    $("#commonModal").modal('show');
                    common_bind();
                    select2();
                },
                error: function(data) {
                    data = data.responseJSON;
                    toastrs('Error', data.error, 'error')
                }
            });
        });
    </script>
@endpush
