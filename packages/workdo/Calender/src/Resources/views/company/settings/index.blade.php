@php
    $company_setting = getCompanyAllSetting();
@endphp

<div class="" id="google_calendar_sidenav">
    <div class="card">
        {{ Form::open(['url' => route('google.calender.settings'), 'enctype' => 'multipart/form-data']) }}
        <div class="card-header p-3">
            <div class="row align-items-center">
                <div class="col-sm-10 col-9">
                    <h5 class="">{{ __('Google Calendar') }}</h5>
                    <small>{{ __('These details will be used to collect your Google Calendar events.') }}</small>
                </div>
                <div class="col-sm-2 col-3 text-end">
                    <div class="form-check form-switch custom-switch-v1 float-end">
                        <input type="checkbox" name="google_calendar_enable" class="form-check-input input-primary"
                            id="google_calendar_enable"{{ (isset($company_setting['google_calendar_enable']) ? $company_setting['google_calendar_enable'] : 'off') == 'on' ? ' checked ' : '' }}>
                        <label class="form-check-label" for="google_calendar_enable"></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-3 pb-0">
            <div class="row">
                <div class="col-md-6 col-sm-12 form-group">
                    {{ Form::label('Google calendar id', __('Google Calendar Id'), ['class' => 'form-label']) }}
                    <input type="text" class="form-control is_google_calender_on"
                        value="{{ isset($company_setting['google_calender_id']) ? $company_setting['google_calender_id'] : '' }}"
                        name="google_calender_id" placeholder="Google Calendar Id"
                        {{ (isset($company_setting['google_calendar_enable']) ? $company_setting['google_calendar_enable'] : 'off') == 'on' ? '' : ' disabled' }}>
                </div>
                <div class="col-md-6 col-sm-12 form-group">
                    {{ Form::label('Google calendar json file', __('Google Calendar json File'), ['class' => 'form-label']) }}
                    <input type="file" class="form-control is_google_calender_on" accept=".json"
                        name="google_calender_json_file" id="file"
                        {{ (isset($company_setting['google_calendar_enable']) ? $company_setting['google_calendar_enable'] : 'off') == 'on' ? '' : ' disabled' }}>
                </div>
            </div>
        </div>
        <div class="card-footer p-3">
            <div class="row row-gap">
                <div class="col-sm-6">
                    @if (company_setting('google_calendar_enable') == 'on')
                        @if (check_file(company_setting('google_calender_json_file')))
                            <label for="file" class="form-label">{{ __('Download Calendar Json File') }}</label>
                            <a href="{{ get_file(company_setting('google_calender_json_file')) }}"
                                class="btn btn-primary" download>
                                <i class="ti ti-download"></i>
                            </a>
                        @endif
                    @endif
                </div>
                <div class="col-sm-6 text-sm-end">
                    <input class="btn btn-print-invoice btn-primary" type="submit" value="{{ __('Save Changes') }}">
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>
<script>
    $(document).on('click', '#google_calendar_enable', function() {
        if ($('#google_calendar_enable').prop('checked')) {
            $(".is_google_calender_on").removeAttr("disabled");
        } else {
            $('.is_google_calender_on').attr("disabled", "disabled");
        }
    });
</script>
