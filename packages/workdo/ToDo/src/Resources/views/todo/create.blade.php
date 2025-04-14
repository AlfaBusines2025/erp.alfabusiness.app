@php
    $setting = getCompanyAllSetting();
@endphp
{{ Form::open(['route' => 'to-do.store', 'method' => 'post', 'class'=>'needs-validation','novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                {{ Form::label('title', __('Title'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="form-icon-user">
                    {{ Form::text('title', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Title')]) }}
                </div>
            </div>
        </div>
        <div class="col-lg-12 form-group">
            <label class="form-label">{{ __('Duration') }}</label>
            <div class='input-group'>
                <input type='text' class="form-control form-control-light" id="duration" name="duration" required autocomplete="off" placeholder="{{ __('Select date range')}}"/>
                <input type="hidden" name="start_date">
                <input type="hidden" name="due_date">
                <span class="input-group-text"><i class="feather icon-calendar"></i></span>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                {{ Form::label('assigned_to', __('Assigned To'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    <select class="multi-select choices" id="users_list" name="assigned_to[]" multiple="multiple" data-placeholder="{{ __('Select Users ...') }}">
                        @foreach ($users as $key => $user)
                            <option value="{{ $key }}">{{ $user }}</option>
                        @endforeach
                    </select>
                    <p class="text-danger d-none" id="user_validation">{{ __('Users filed is required.') }}</p>
                </div>

            </div>
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('module', __('Module'), ['class' => 'form-label']) }}
            {{ Form::select('module', $module_custumefield, null, ['class' => 'form-control ', 'required' => 'required']) }}
        </div>

        <div class="form-group col-lg-12">
            <label class="form-label">{{ __('Priority') }}</label>
            <select class="form-control form-control-light" name="priority" id="task-priority" required>
                <option value="Low">{{ __('Low') }}</option>
                <option value="Medium">{{ __('Medium') }}</option>
                <option value="High">{{ __('High') }}</option>
            </select>
        </div>

        <div class="col-lg-12">
            <div class="form-group">
                {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Description'), 'rows' => 3, 'maxlength' => 250]) }}
                </div>
            </div>
        </div>
        <div class="row">
            @stack('calendar')
        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary" id="submit">
</div>
{{ Form::close() }}

<link rel="stylesheet" href="{{ asset('packages/workdo/ToDo/src/Resources/assets/libs/bootstrap-daterangepicker/daterangepicker.css') }} ">
<script src="{{ asset('packages/workdo/ToDo/src/Resources/assets/libs/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('packages/workdo/ToDo/src/Resources/assets/libs/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

<script>
    $(function() {
        var start = moment('{{ date('Y-m-d') }}', 'YYYY-MM-DD HH:mm:ss');
        var end = moment('{{ date('Y-m-d') }}', 'YYYY-MM-DD HH:mm:ss');

        function cb(start, end) {
            $("form #duration").val(start.format('MMM D, YY hh:mm A') + ' - ' + end.format(
            'MMM D, YY hh:mm A'));
            $('form input[name="start_date"]').val(start.format('YYYY-MM-DD HH:mm:ss'));
            $('form input[name="due_date"]').val(end.format('YYYY-MM-DD HH:mm:ss'));
        }

        $('form #duration').daterangepicker({
            autoApply: true,
            timePicker: true,
            autoUpdateInput: false,
            startDate: start,
            endDate: end,
            locale: {
                format: 'MMMM D, YYYY hh:mm A',
                applyLabel: "{{ __('Apply') }}",
                cancelLabel: "{{ __('Cancel') }}",
                fromLabel: "{{ __('From') }}",
                toLabel: "{{ __('To') }}",
                daysOfWeek: [
                    "{{ __('Sun') }}",
                    "{{ __('Mon') }}",
                    "{{ __('Tue') }}",
                    "{{ __('Wed') }}",
                    "{{ __('Thu') }}",
                    "{{ __('Fri') }}",
                    "{{ __('Sat') }}"
                ],
                monthNames: [
                    "{{ __('January') }}",
                    "{{ __('February') }}",
                    "{{ __('March') }}",
                    "{{ __('April') }}",
                    "{{ __('May') }}",
                    "{{ __('June') }}",
                    "{{ __('July') }}",
                    "{{ __('August') }}",
                    "{{ __('September') }}",
                    "{{ __('October') }}",
                    "{{ __('November') }}",
                    "{{ __('December') }}"
                ],
            }
        }, cb);
        cb(start, end);
    });

    $(function() {
        $("#submit").click(function() {
            var user = $("#users_list option:selected").length;
            if (user == 0) {
                $('#user_validation').removeClass('d-none')
                return false;
            } else {
                $('#user_validation').addClass('d-none')
            }
        });
    });
</script>
