<div id="googlesignin_setting">
    <div class="card mb-3">
        <form method="POST" action="{{ route('googlesignin.setting') }}" enctype="multipart/form-data"
            accept-charset="UTF-8">
            @csrf
            <div class="card-header p-3">
                <div class="row align-items-center">
                    <div class="col-sm-10 col-9">
                        <h5 class=""> {{ __('Google Setting') }} </h5>
                    </div>
                    <div class="col-sm-2 col-3 text-end">
                        {!! Form::hidden('google_signin_setting_enabled', 'off') !!}
                        <div class="form-check form-switch custom-switch-v1 d-inline-block">
                            {!! Form::checkbox(
                                'google_signin_setting_enabled',
                                'on',
                                isset($settings['google_signin_setting_enabled']) && $settings['google_signin_setting_enabled'] == 'on',
                                ['class' => 'form-check-input', 'id' => 'google_signin_setting_enabled'],
                            ) !!}
                            <label class="custom-control-label form-control-label"
                                for="google_signin_setting_enabled"></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-sm-6 form-group">
                        {!! Form::label('google_client_id', __('Google Client ID'), ['class' => 'form-label']) !!}
                        {!! Form::text('google_client_id', !empty($settings['google_client_id']) ? $settings['google_client_id'] : '', [
                            'class' => 'form-control',
                            'placeholder' => 'Google Client ID',
                            isset($settings['google_signin_setting_enabled']) && $settings['google_signin_setting_enabled'] == 'on'
                                ? ''
                                : ' disabled',
                        ]) !!}
                    </div>
                    <div class="col-sm-6 form-group">
                        {!! Form::label('google_client_secret_key', __('Google Client Secret'), ['class' => 'form-label']) !!}
                        {!! Form::text(
                            'google_client_secret_key',
                            !empty($settings['google_client_secret_key']) ? $settings['google_client_secret_key'] : '',
                            [
                                'class' => 'form-control',
                                'placeholder' => 'Google Client Secret',
                                isset($settings['google_signin_setting_enabled']) && $settings['google_signin_setting_enabled'] == 'on'
                                    ? ''
                                    : ' disabled',
                            ],
                        ) !!}
                    </div>
                    <div class="col-12 form-group">
                        {!! Form::label('google_authorized_url', __('Google Callback URL'), ['class' => 'form-label']) !!}
                        {!! Form::text(
                            'google_authorized_url',
                            !empty($settings['google_authorized_url']) ? $settings['google_authorized_url'] : '',
                            [
                                'class' => 'form-control',
                                'placeholder' => 'Google Callback URL',
                                isset($settings['google_signin_setting_enabled']) && $settings['google_signin_setting_enabled'] == 'on'
                                    ? ''
                                    : ' disabled',
                            ],
                        ) !!}
                    </div>
                    <div class="col-xl-4 col-sm-6">
                        <div class="card">
                            <div class="card-header p-3">
                                {!! Form::label('google_sign_in_image', __('Image'), ['class' => 'form-label h6 mb-0']) !!}
                            </div>
                            <div class="card-body setting-card setting-logo-box p-3">
                                <div class="logo-content img-fluid logo-set-bg  text-center">
                                    @if (!empty($settings['google_sign_in_image']))
                                        <img id="image3"
                                            src="{{ get_file(!empty($settings['google_sign_in_image']) ? (check_file($settings['google_sign_in_image']) ? $settings['google_sign_in_image'] : 'uploads/meta/google_sign_in_image.png') : 'uploads/meta/google_sign_in_image.png') }}{{ '?' . time() }}"
                                            class="img_setting">
                                    @endif
                                </div>
                                <div class="choose-files text-center  mt-3">
                                    <label for="google_sign_in_image">
                                        <div class="bg-primary"> <i
                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                                        <input type="file" class="form-control file" id="google_sign_in_image"
                                            name="google_sign_in_image"
                                            onchange="document.getElementById('image3').src = window.URL.createObjectURL(this.files[0])"
                                            data-filename="google_sign_in_image">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end p-3">
                {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary']) }}
            </div>
        </form>
    </div>
</div>

<script>
    $(document).on('click', '#google_signin_setting_enabled', function() {
        if ($('#google_signin_setting_enabled').prop('checked')) {
            $("#google_client_id").removeAttr("disabled");
            $("#google_client_secret_key").removeAttr("disabled");
            $("#google_authorized_url").removeAttr("disabled");
        } else {
            $('#google_client_id').attr("disabled", "disabled");
            $('#google_client_secret_key').attr("disabled", "disabled");
            $('#google_authorized_url').attr("disabled", "disabled");
        }
    });
</script>
