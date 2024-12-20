@extends($activeTemplate . 'layouts.frontend')

@section('content')
    @php
        $loginContent = getContent('login.content', true);
    @endphp
    <section class="signup-page pt-120 pb-120 section-common-bg">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-xl-6 col-lg-6">
                    <div class="sign-in-left d-none d-lg-inline-block">
                        <img class="w-100"
                            src="{{ getImage('assets/images/frontend/login/' . @$loginContent->data_values->image, '660x640') }}"
                            alt="@lang('image')">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6">
                    <form action="{{ route('user.login') }}" method="POST" class="signup-form primary-bg verify-gcaptcha">
                        @csrf
                        <h2 class="signup-form-title">
                            @lang('Sign In Account')
                        </h2>
                        <div class="row gy-4">
                            <div class="col-12">
                                <div class="form-group has-icon">
                                    <label class="form-label">@lang('Username or Email')</label>
                                    <input type="text" name="username" value="{{ old('username') }}"
                                        class="form-control form--control" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group has-icon ">
                                    <label class="form-label">@lang('Password')</label>
                                    <input type="password" class="form-control form--control" name="password" required>
                                </div>
                            </div>
                            <x-captcha />
                            <div class="col-12">
                                <div class="form-group d-flex flex-wrap justify-content-between">
                                    <div class=" custom--checkbox">
                                        <input type="checkbox" class="form-check-input" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>
                                        <label for="remember">@lang('Remember Me')</label>
                                    </div>
                                    <a href="{{ route('user.password.request') }}" class="text--base">
                                        @lang('Forgot Password ?')
                                    </a>
                                </div>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn--outline-base w-100">
                                    @lang('Sign In Account')
                                </button>
                            </div>
                            <div class="col-12">
                                <p class="text-center account-text mb-0"> @lang('Don\'t have an account?')
                                    <a href="{{ route('user.register') }}" class="text--base">
                                        @lang('Create an account')
                                    </a>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
