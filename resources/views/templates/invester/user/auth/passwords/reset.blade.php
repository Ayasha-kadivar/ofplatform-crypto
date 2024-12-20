@extends($activeTemplate.'layouts.app')
@section('panel')

<!-- Account Section -->
<section class="account-section position-relative">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-7 col-md-8">
                <div class="text-center">
                    <a href="{{ route('home') }}" class="d-block mb-3 mb-sm-4 auth-page-logo"><img src="{{ getImage(getFilePath('logoIcon').'/logo_2.png') }}" alt="logo"></a>
                </div>
                <form action="{{ route('user.password.update') }}" method="POST" class="account-form">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="mb-4">
                        <h4 class="mb-2">{{ __($pageTitle) }}</h4>
                        <p>@lang('Your account is verified successfully. Now you can change your password. Please enter a strong password and don\'t share it with anyone.')</p>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">@lang('Password')</label>
                                <input type="password" class="form-control form--control h-45" name="password" required>
                                @if($general->secure_password)
                                    <div class="input-popup">
                                        <p class="error lower">@lang('1 small letter minimum')</p>
                                        <p class="error capital">@lang('1 capital letter minimum')</p>
                                        <p class="error number">@lang('1 number minimum')</p>
                                        <p class="error special">@lang('1 special character minimum')</p>
                                        <p class="error minimum">@lang('6 character password')</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">@lang('Confirm Password')</label>
                                <input type="password" class="form-control form--control h-45" name="password_confirmation" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- Account Section -->

@endsection

@if($general->secure_password)
@push('script-lib')
<script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
@endpush
@endif
@push('script')
<script>
    (function ($) {
        "use strict";
            $('input[name=password]').on('input',function(){
                secure_password($(this));
            });

            $('[name=password]').focus(function () {
                $(this).closest('.form-group').addClass('hover-input-popup');
            });

            $('[name=password]').focusout(function () {
                $(this).closest('.form-group').removeClass('hover-input-popup');
            });
    })(jQuery);
</script>
@endpush
