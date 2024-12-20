@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
        $policyPages = getContent('policy_pages.element', false, null, true);
        $registerContent = getContent('register.content', true);
    @endphp

    <div class="signin-wrapper">
        <div class="outset-circle"></div>
        <div class="container">
            <div class="row justify-content-lg-between align-items-center">
                <div class="col-xl-5 col-lg-6">
                    <div class="signin-thumb">
                        <img src="{{ getImage('assets/images/frontend/register/' . @$registerContent->data_values->image) }}" alt="image">
                    </div>
                </div>
                <div class="col-xl-5 col-lg-6">
                    <div class="signin-form-area">
                        <h3 class="title text-capitalize text-shadow mb-30">{{ __($pageTitle) }}</h3>
                        <form class="signin-form verify-gcaptcha" action="{{ route('user.register') }}" method="post">
                            @csrf
                            @if (session()->get('reference') != null)
                                <div class="form-group">
                                    <p>@lang('You\'re referred by') <i class="fw-bold base--color">{{ session()->get('reference') }}</i></p>
                                </div>
                            @endif

                            <div class="form-group">
                                <label class="form-label">@lang('Username')</label>
                                <input type="text" name="username" placeholder="@lang('Username')" class="checkUser" value="{{ old('username') }}" required>
                                <small class="text-danger usernameExist"></small>
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('Email')</label>
                                <input type="email" name="email" placeholder="@lang('Email')" class="checkUser" value="{{ old('email') }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('Country')</label>
                                <select name="country">
                                    @foreach ($countries as $key => $country)
                                        <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}">
                                            {{ __($country->country) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('Mobile')</label>
                                <div class="input-group">
                                    <span class="input-group-text mobile-code">

                                    </span>
                                    <input type="hidden" name="mobile_code">
                                    <input type="hidden" name="country_code">
                                    <input type="tel" name="mobile" value="{{ old('mobile') }}" class="form-control checkUser" required>
                                </div>
                                <small class="text-danger mobileExist"></small>
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('Password')</label>
                                <input type="password" name="password" required>
                                @if ($general->secure_password)
                                    <div class="input-popup">
                                        <p class="error lower">@lang('1 small letter minimum')</p>
                                        <p class="error capital">@lang('1 capital letter minimum')</p>
                                        <p class="error number">@lang('1 number minimum')</p>
                                        <p class="error special">@lang('1 special character minimum')</p>
                                        <p class="error minimum">@lang('6 character password')</p>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('Confirm Password')</label>
                                <input type="password" name="password_confirmation" required>
                            </div>


                            <x-captcha />

                            @if ($general->agree)
                                <div class="form-group">
                                    <input type="checkbox" id="agree" @checked(old('agree')) class="h-auto w-auto" name="agree" required>
                                    <label class="mb-0" for="agree">@lang('I agree with')</label> <span>
                                        @foreach ($policyPages as $policy)
                                            <a href="{{ route('policy.pages', [slug($policy->data_values->title), $policy->id]) }}" class="base--color">{{ __($policy->data_values->title) }}</a>
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </span>
                                </div>
                            @endif

                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-small w-100 btn-primary">@lang('Sign Up')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <h6 class="text-center">@lang('You already have an account please Login ')</h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                    <a href="{{ route('user.login') }}" class="btn btn--base btn-sm text--base">@lang('Login')</a>
                </div>
            </div>
        </div>
    </div>

@endsection

@if ($general->secure_password)
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif
@push('script')
    <script>
        "use strict";
        (function($) {
            @if ($mobileCode)
                $(`option[data-code={{ $mobileCode }}]`).attr('selected', '');
            @endif
            $('select[name=country]').change(function() {
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
            });
            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
            $('.checkUser').on('focusout', function(e) {
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                if ($(this).attr('name') == 'mobile') {
                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                    var data = {
                        mobile: mobile,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'email') {
                    var data = {
                        email: value,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'username') {
                    var data = {
                        username: value,
                        _token: token
                    }
                }
                $.post(url, data, function(response) {
                    if (response.data != false && response.type == 'email') {
                        $('#existModalCenter').modal('show');
                    } else if (response.data != false) {
                        $(`.${response.type}Exist`).text(`${response.type} already exist`);
                    } else {
                        $(`.${response.type}Exist`).text('');
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
