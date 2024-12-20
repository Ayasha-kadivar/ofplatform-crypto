@extends($activeTemplate.'layouts.app')
@section('panel')
@php
$authContent = getContent('authentication.content',true);
@endphp
<!-- Account Section -->
<section class="account-section position-relative" id="example-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-7 col-md-8">
                <a href="{{ route('home') }}" class="text-center d-block mb-3 mb-sm-4 auth-page-logo"><img
                        src="{{ getImage(getFilePath('logoIcon').'/logo_2.png') }}" alt="logo"></a>
                <form action="{{ route('user.login') }}" method="POST" class="verify-gcaptcha account-form">
                    @csrf
                    <div class="mb-4">
                        <h4 class="mb-2">{{ __(@$authContent->data_values->login_title) }}</h4>
                        <p>{{ __(@$authContent->data_values->login_subtitle) }}</p>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Email/Username</label>
                                <input type="text" name="username" class="form-control form--control h-45"
                                    maxlength="100" required>
                                <!-- <p class="text-muted">@lang('Please do not include (+) or (00) at the start of your
                                    mobile number.')</p> -->
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>@lang('Password')</label>
                                <input type="password" name="password" class="form-control form--control h-45">
                            </div>
                        </div>
                        {{-- <div class="col-12">
                            <div class="form-group">
                                <label>@lang('Provide Email For Verification')</label>
                                <input type="text" name="email" class="form-control form--control h-45" required
                                    maxlength="50">
                            </div>
                        </div> --}}
                        <div class="col-12">
                            <div class="d-flex flex-wrap gap-2 justify-content-between">
                                <div class="form-group custom--checkbox">
                                    <input type="checkbox" id="remember" name="remember" class="form-check-input">
                                    <label for="remember">@lang('Keep me Logged in')</label>
                                </div>
                                <a href="{{ route('user.password.request') }}" class="text--base fw-bold">@lang('Forgot
                                    Password?')</a>
                            </div>
                        </div>
                        {{-- <div class="cf-turnstile" data-sitekey="0x4AAAAAAAD-oYalT4Zr0Vn-"></div>
                        <div class="col-12">
                            <x-captcha />
                        </div> --}}
                        <div class="col-12">
                            <button type="submit" class="btn btn--base w-100">@lang('Login Account')</button>
                        </div>
                        <div class="col-12 mt-4">
                            <p class="text-center">@lang('Don\'t have any account?') <a
                                    href="{{ route('user.register') }}" class="fw-bold text--base">@lang('Create
                                    Account')</a></p>
                            {{-- <p class="text-center">@lang('Do you have old registration account?') <a href="{{ route('user.old-register') }}"
                            class="fw-bold text--base">@lang('Old Registration Account')</a></p> --}}
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- <script src="https://challenges.cloudflare.com/turnstile/v0/api.js?onload=onloadTurnstileCallback"
    nonce="{{ csp_nonce() }}" async defer></script>

<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" nonce="{{ csp_nonce() }}"></script> -->
<script>
// window.onloadTurnstileCallback = function() {
//     turnstile.render('#example-container', {
//         sitekey: '0x4AAAAAAAGV0xKg82j6bxEu',
//         callback: function(token) {
//             console.log(`Challenge Success ${token}`);
//         },
//     });
// };

// if using synchronous loading, will be called once the DOM is ready
// turnstile.ready(onloadTurnstileCallback);
</script>
<!-- Account Section -->
@endsection