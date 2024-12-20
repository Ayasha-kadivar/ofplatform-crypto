@extends($activeTemplate . 'layouts.app')
@section('panel')
    @php
        $authContent = getContent('authentication.content', true);
    @endphp
    <!-- Account Section -->
    <section class="account-section position-relative" id="tester1">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-7 col-md-8">
                    <a href="{{ route('home') }}" class="text-center d-block mb-3 mb-sm-4 auth-page-logo"><img src="{{ getImage(getFilePath('logoIcon') . '/logo_2.png') }}" alt="logo"></a>
                    <form action="{{ route('user.register') }}" method="POST" class="verify-gcaptcha account-form">
                        @csrf
                        <div class="mb-4">
                            <h4 class="mb-2">{{ __(@$authContent->data_values->register_title) }}</h4>
                            <p>{{ __(@$authContent->data_values->register_subtitle) }}</p>
                        </div>
                        <div class="row">
                            @php
                                $request = app('request');
                                $reference = $request->input('reference') ?: $request->cookie('reference');
                            @endphp
                            
                            @if ($reference)
                                <div class="col-12">
                                    <p>@lang('You\'re referred by') <i class="fw-bold text--base">{{ $reference }}</i></p>
                                    <input type="hidden" class="form-control form--control checkUser h-45" name="reference" id="reference" value="{{ $reference }}">
                                </div>
                            @endif
                        
                            {{-- <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">@lang('Voucher Code (optional):')</label>
                                    <input type="text" class="form-control form--control checkUser h-45" name="reference" id="reference" value="{{@app('request')->input('reference')}}">
                                </div>
                            </div> --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">@lang('Username')</label>
                                    <input type="text" class="form-control form--control checkUser h-45" onkeypress="return /[0-9a-zA-Z]/i.test(event.key)" name="username" value="{{ old('username') }}" required title="6 to 25 characters" pattern=".{6,25}" minLength=6 maxlength="25" >
                                    <p class="text-muted">@lang('Create your username using only letters (uppercase or lowercase) and numbers, no spaces , minimum 6 length')</p>
                                    <small class="text-danger usernameExist"></small>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('First Name')</label>
                                <input type="text" class="form-control form--control" name="firstname" value="{{ old('firstname') }}" required>
                            </div>
    
                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('Last Name')</label>
                                <input type="text" class="form-control form--control" name="lastname" value="{{ old('lastname') }}" required>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">@lang('E-Mail Address')</label>
                                    <input type="email" class="form-control form--control h-45 checkUser" name="email" value="{{ old('email') }}" required maxlength="50">
                                    <small class="text-danger emailExist"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Country')</label>
                                    <select name="country" class="form--control form-select">
                                        @foreach ($countries as $key => $country)
                                            <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}">
                                                {{ __($country->country) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Mobile')</label>
                                    <div class="input-group ">
                                        <span class="input-group-text mobile-code">

                                        </span>
                                        <input type="hidden" name="mobile_code">
                                        <input type="hidden" name="country_code">
                                        <input type="number" name="mobile" value="{{ old('mobile') }}" class="form-control form--control checkUser" required maxlength="15">
                                    </div>
                                    <small class="text-danger mobileExist"></small>
                                </div>
                            </div>
                            
                            <p class="text-muted">@lang('Input your phone number without country code, just your local number, do not include 0 or your country code here')</p>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">@lang('Password')</label>
                                    <input type="password" class="form-control form--control h-45" name="password" required>
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
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">@lang('Confirm Password')</label>
                                    <input type="password" class="form-control form--control h-45" name="password_confirmation" required>
                                </div>
                            </div>
                            <div class="cf-turnstile" data-sitekey="0x4AAAAAAAD-oYalT4Zr0Vn-"></div>
                            @if ($general->agree)
                                @php
                                    $policyPages = getContent('policy_pages.element', false, null, true);
                                @endphp
                                <div class="col-12">
                                    <x-captcha />
                                </div>
                                <div class="col-12">
                                    <div class="d-flex flex-wrap gap-2 justify-content-between">
                                        <div class="form-group custom--checkbox">
                                            <input type="checkbox" id="agree" @checked(old('agree')) name="agree" class="form-check-input" required>
                                            <label for="agree">@lang('I agree with') </label> <span>
                                                @foreach ($policyPages as $policy)
                                                    <a href="{{ route('policy.pages', [slug($policy->data_values->title), $policy->id]) }}" class="link-color">{{ __($policy->data_values->title) }}</a>
                                                    @if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                          
                            <div class="g-recaptcha col-12 mb-1" data-sitekey="6LcQhr4mAAAAABaC0EM8Q9TPmqpEduJZ4l5f_tm_"></div>
                          
                            <div class="col-12">

                                <button id="verify-gcaptcha" type="submit" class="btn btn--base w-100">@lang('Create Account')</button>
                            </div>
                            <div class="col-12 mt-4">
                                <p class="text-center">@lang('Already have an account?') <a href="{{ route('user.login') }}" class="fw-bold text--base">@lang('Login Account')</a></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Account Section -->


    <div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <h6 class="text-center">@lang('You already have an account please Login ')</h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">@lang('Close')</button>
                    <a href="{{ route('user.login') }}" class="btn btn--base">@lang('Login')</a>
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

<script src="https://www.google.com/recaptcha/api.js?render=6LcQhr4mAAAAABaC0EM8Q9TPmqpEduJZ4l5f_tm_"></script>
<script nonce="{{ csp_nonce() }}">
    function onSubmit(token) {
        document.getElementById("verify-gcaptcha").submit();
    }
</script>


    <script nonce="{{ csp_nonce() }}">
        "use strict";
        (function($) {
            @if ($mobileCode)
                $(`option[data-code={{ $mobileCode }}]`).attr('selected', '');
            @endif
            $('select[name=country]').change(function() {
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
                $('input[name=mobile]').val('');
            });
            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
            var vars = {};
            vars['mobile_true'] = vars['email_true'] = vars['username_true'] = true;

            $('.checkUser').on('keyup', function(e) {

                if ($(this).attr('name') == 'username') {
                    $('.usernameExist').text('');
                    $(this).val(function(i, val) {
                        return val.replace(/[^a-zA-Z0-9]/,''); 
                    });
                }
                
                $('#verify-gcaptcha').attr('disabled',false);
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                if ($(this).attr('name') == 'mobile') {
                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                    var data = {
                        mobile: mobile,
                        _token: token
                    }
                    vars[$(this).attr('name')+'_true'] = true;
                }
                if ($(this).attr('name') == 'email') {
                    var data = {
                        email: value,
                        _token: token
                    }
                    vars[$(this).attr('name')+'_true'] = true;
                }
                if ($(this).attr('name') == 'username') {
                    var data = {
                        username: value,
                        _token: token
                    }
                    vars[$(this).attr('name')+'_true'] = true;
                }
                $.post(url, data, function(response) {
                    
                    if (response.type == 'username' && value.length < 6) {
                        $(`.${response.type}Exist`).html('<b>Do not allow less than 6 characthers</b>');
                        vars[response.type+'_true'] = false;
                    }else if(response.type == 'mobile' && value.startsWith("0")){
                        vars[response.type+'_true'] = false;
                        $(`.${response.type}Exist`).html(`<b>Do not allow that number starts with 0</b>`);
                    }else if(response.type == 'email' && !isValidEmail(value)){
                        vars[response.type+'_true'] = false;
                        $('.emailExist').html('<b>Format is not valid (@ missing or domain missing)</b>');
                    }else if (response.data != false && (response.type == 'email' || response.type == 'username')) {
                        vars[response.type+'_true'] = false;
                        //$('#existModalCenter').modal('show');
                        $(`.${response.type}Exist`).html(`<b>${response.type.toUpperCase()} already in use</b>`);
                        //${response.type}_true = false;
                    } else if (response.data != false) {
                        $(`.${response.type}Exist`).html(`<b>${response.type.toUpperCase()} number already in use</b>`);
                        vars[response.type+'_true'] = false;
                        //${response.type}_true = false;
                    } else {
                        $(`.${response.type}Exist`).html('');
                    }       

                    
                    $.each(vars,function(kk,vv){
                        if(vv == false){
                            $('#verify-gcaptcha').attr('disabled',true);
                        }
                    })
                });
            });
            // $('.checkUser').on('focusout', function(e) {
            //     var url = '{{ route('user.checkUser') }}';
            //     var value = $(this).val();
            //     var token = '{{ csrf_token() }}';

            //     if ($(this).attr('name') == 'mobile') {
            //         var mobileCode = $('.mobile-code').text().substr(1);
            //         var mobileNumber = value;
            //         var mobile = `${mobileCode}${mobileNumber}`;
            //         if (mobile.length > 11) {
            //             $('.mobileExist').text('Mobile code should not exceed 11 characters.');
            //             return;
            //         }
            //         var data = {
            //             mobile: mobile,
            //             _token: token
            //         }
            //     }


            //     if ($(this).attr('name') == 'email') {
            //         var data = {
            //             email: value,
            //             _token: token
            //         }

            //         if (value.length > 50 || !isValidEmail(value)) {
            //             let errorMsg = '';
            //             if (value.length > 50) {
            //                 errorMsg += 'Email address should not exceed 50 characters. ';
            //             }
            //             if (!isValidEmail(value)) {
            //                 errorMsg += 'Invalid email format.';
            //             }
            //             $('.emailExist').text(errorMsg);
            //             return;
            //         }
            //     }

            //     if ($(this).attr('name') == 'username') {
            //         if (value.length > 25) {
            //             $('.usernameExist').text('Username should not exceed 25 characters.');
            //             return;
            //         }
            //         var data = {
            //             username: value,
            //             _token: token
            //         }
            //     }


            //     $.post(url, data, function(response) {
            //         if (response.data != false && response.type == 'email') {
            //             $('#existModalCenter').modal('show');
            //         } else if (response.data != false) {
            //             $(`.${response.type}Exist`).text(`${response.type} already exist`);
            //         } else {
            //             $(`.${response.type}Exist`).text('');
            //         }
            //     });
            // });

            function isValidEmail(email) {
                var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                return emailReg.test(email);
            }

        })(jQuery);
    </script>
    <!-- <script src="https://challenges.cloudflare.com/turnstile/v0/api.js?onload=onloadTurnstileCallback"
    nonce="{{ csp_nonce() }}" async defer></script>

<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" nonce="{{ csp_nonce() }}"></script>
<script>
window.onloadTurnstileCallback = function() {
    turnstile.render('#example-container', {
        sitekey: '0x4AAAAAAAGaYwRRFghRefrb',
        callback: function(token) {
            console.log(`Challenge Success ${token}`);
        },
    });
};

// if using synchronous loading, will be called once the DOM is ready
turnstile.ready(onloadTurnstileCallback);
</script> -->
@endpush
