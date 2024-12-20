@extends($activeTemplate . 'layouts.app')
@section('panel')
    @php
        $authContent = getContent('authentication.content', true);
    @endphp
    <!-- Account Section -->
    <section class="account-section position-relative">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-9 col-md-10">
                    <a href="{{ route('home') }}" class="text-center d-block mb-3 mb-sm-4 auth-page-logo"><img src="{{ getImage(getFilePath('logoIcon') . '/logo_2.png') }}" alt="logo"></a>
                    <form action="{{ route('user.old-register') }}" method="POST" class="verify-gcaptcha account-form">
                        @csrf
                        <div class="mb-4">
                            <h4 class="mb-2">Old User Registeration Form</h4>
                            <p>Enter Your details of previous accounts and we will verify and accept you in.</p>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('First Name')</label>
                                    <input type="text" class="form-control form--control h-45" name="firstname" value="{{ old('firstname') }}" required>
                                </div>
                            </div>      
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Last Name')</label>
                                    <input type="text" class="form-control form--control h-45" name="lastname" value="{{ old('lastname') }}" required>
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Username')</label>
                                    <input type="text" class="form-control form--control checkOldUser h-45" name="username" value="{{ old('username') }}" required>
                                    <small class="text-danger usernameExist"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('E-Mail Address')</label>
                                    <input type="email" class="form-control form--control checkOldUser h-45" name="email" value="{{ old('email') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
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
                                        <input type="number" name="mobile" value="{{ old('mobile') }}" class="form-control form--control checkOldUser" required>
                                    </div>
                                    <small class="text-danger mobileExist"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
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
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Confirm Password')</label>
                                    <input type="password" class="form-control form--control h-45" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Address')</label>
                                    <input type="text" class="form-control form--control h-45" name="address" id="address" value="{{ old('Address') }}">
                                </div>
                            </div>      
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('State')</label>
                                    <input type="text" class="form-control form--control h-45" name="state" id="state" value="{{ old('Address') }}">
                                </div>
                            </div>                         
                        </div>
                        <div class="row">     
                              
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Zip Code')</label>
                                    <input type="text" class="form-control form--control h-45" name="zipcode" id="zipcode" value="{{ old('Address') }}">
                                </div>
                            </div>  
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('City')</label>
                                    <input type="text" class="form-control form--control h-45" name="city" id="city" value="{{ old('Address') }}">
                                </div>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Balance')</label>
                                    <input type="text" maxlength="5" class="form-control form--control h-45" name="balance" id="balance" value="{{ old('balance') }}">
                                </div>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">@lang('Your sponsor phone number')</label>
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Country')</label>
                                    <select name="sponsor_country_code" class="form--control form-select">
                                        @foreach ($countries as $key => $country)
                                            <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}">
                                                {{ __($country->country) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>  
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Phone')</label>
                                    <div class="input-group ">
                                        <span class="input-group-text sponsor-mobile-code">

                                        </span>
                                        <input type="hidden" name="sponsor_mobile_code">
                                        <input type="hidden" name="sponsor_country_code">
                                        <input type="number" name="sponsor_phone" value="{{ old('sponsor_phone') }}" class="form-control form--control" required>
                                    </div>
                                </div>
                            </div>    
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">@lang('Package Purchases')</label>
                                </div>
                            </div>                                                                                                                                                                                          
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control form--control h-45" name="purchased_packages1" id="purchased_packages1" value="{{ old('balance') }}" required>
                                </div>
                            </div> 
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control form--control h-45" name="purchased_packages2" id="purchased_packages2" value="{{ old('balance') }}">
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Date')</label>
                                    <input type="date" class="form-control form--control h-45" name="date" id="date" value="{{ old('date') }}">
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Number fo Packages')</label>
                                    <input type="text" class="form-control form--control h-45" name="number_of_packages" id="number_of_packages" value="{{ old('number_of_packages') }}">
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label"><input type="checkbox" value="Yes" id="maintenance_fee_paid" name="maintenance_fee_paid"> @lang('Did you pay 10 BUSD for maintenance?') </label>
                                </div>
                            </div>
                            <div class="col-md-12" id="google_form_div" style="display: none;">
                                <div class="form-group">
                                    <label class="form-label"><input type="checkbox" value="Yes" id="google_form" name="google_form"> @lang('Google form') </label>
                                </div>
                            </div>  
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label"><input type="checkbox" value="Yes" id="has_hash_id" name="has_hash_id"> @lang('Hash ID') </label>
                                </div>
                            </div>   
                            <div class="col-md-12" id="has_id_div" style="display: none;">
                                <div class="form-group">
                                    <label class="form-label">@lang('Hash ID')</label>
                                    <input type="text" class="form-control form--control h-45" name="hash_id" id="hash_id" value="{{ old('has_id') }}">
                                </div>
                            </div>                        
                            
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
                            <div class="col-12">

                                <button type="submit" class="btn btn--base w-100">@lang('Create Account')</button>
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

            $('select[name=sponsor_country_code]').change(function() {
                $('input[name=sponsor_mobile_code]').val($('select[name=sponsor_country_code] :selected').data('mobile_code'));
                $('input[name=sponsor_country_code]').val($('select[name=sponsor_country_code] :selected').data('code'));
                $('.sponsor-mobile-code').text('+' + $('select[name=sponsor_country_code] :selected').data('mobile_code'));
            });
            $('input[name=sponsor_mobile_code]').val($('select[name=sponsor_country_code] :selected').data('mobile_code'));
            $('input[name=sponsor_country_code]').val($('select[name=sponsor_country_code] :selected').data('code'));
            $('.sponsor-mobile-code').text('+' + $('select[name=sponsor_country_code] :selected').data('mobile_code'));


            $('.checkOldUser').on('focusout', function(e) {
                var url = '{{ route('user.checkOldUser') }}';
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

        $(document).ready(function(){
            $('#maintenance_fee_paid').click(function() {
                $('#google_form_div').toggle(this.checked);
            });

            $('#has_hash_id').click(function() {
                $('#has_id_div').toggle(this.checked);
            });
        });
    </script>
@endpush