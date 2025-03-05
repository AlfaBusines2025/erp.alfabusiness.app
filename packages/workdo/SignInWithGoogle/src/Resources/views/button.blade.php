    @if (!empty($settings['google_sign_in_image']))
        <a href="{{ route('login.google') }}" class="btn btn-secondary btn-block d-flex align-items-center justify-content-center mt-2 login_button text-white">
            <img src="{{ get_file($settings['google_sign_in_image']) }}" alt="Google Logo" class="me-2" style="width: 24px; height: 24px;">
            {{ __('Sign-In with Google') }}
        </a>
    @else
        <a class="btn btn-secondary btn-block mt-2 login_button text-white" style="padding:10px;" href="{{ route('login.google') }}">{{__('Sign-In with Google')}}<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="48" height="48">
            <path fill="#ffffff" d="M44.5 20H24v8.5h11.7c-1.3 3.7-4.5 6.5-8.7 6.5-5 0-9-4-9-9s4-9 9-9c2.4 0 4.6 1 6.3 2.6l6.3-6.3C36.3 9.7 30.5 7 24 7c-9.4 0-17 7.6-17 17s7.6 17 17 17c8.6 0 16-6.3 17-15v-6z"/>
          </svg>
        </a>
    @endif
