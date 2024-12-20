@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
    $loginContent = getContent('login.content', true);
    @endphp

    <div class="signin-wrapper">
        <div class="outset-circle"></div>
        <div class="container">
            <div class="row justify-content-lg-between align-items-center">
                <div class="col-xl-5 col-lg-6">
                    <div class="signin-thumb">
                        <img src="{{ getImage('assets/images/frontend/login/' . @$loginContent->data_values->image) }}" alt="image">
                    </div>
                </div>
                <div class="col-xl-5 col-lg-6">
                    <div class="signin-form-area">
                        <h3 class="title text-capitalize text-shadow mb-30">{{ __($pageTitle) }}</h3>
                        <form class="signin-form verify-gcaptcha" action="{{ route('user.login') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">@lang('Username')</label>
                                <input  type="text" name="username" id="signin_name" placeholder="@lang('Username or Email')" value="{{ old('username') }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('Password')</label>
                                <input type="password" name="password" id="signin_pass" placeholder="@lang('Password')" required autocomplete="current-password" required>
                            </div>

                            <x-captcha />

                            <div class="custom--checkbox mb-3">
                                <input class="w-auto h-auto" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="mb-0" for="remember">
                                    @lang('Remember Me')
                                </label>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-small w-100 btn-primary">{{ trans('Sign In') }}</button>
                            </div>
                            <p>{{ trans('Forgot Your Password?') }}
                                <a href="{{ route('user.password.request') }}" class="label-text base--color">{{ trans('Reset Now') }}</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
