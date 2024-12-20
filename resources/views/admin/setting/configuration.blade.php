@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="" method="post">
                @csrf
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-0">@lang('Balance Transfer')</p>
                                <p class="mb-0">
                                    <small>@lang('If you enable this module, users will be able to transfer the balance to each other. A fixed and a percent charge can be configured for this module from the') <a href="{{ route('admin.setting.index') }}">@lang('General Setting').</a></small>
                                </p>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="35" data-on="@lang('Enable')" data-off="@lang('Disable')" name="b_transfer" @if($general->b_transfer) checked @endif>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-0">@lang('User Registration')</p>
                                <p class="mb-0">
                                    <small>@lang('If you disable this module, no one can register on this system')</small>
                                </p>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="35" data-on="@lang('Enable')" data-off="@lang('Disable')" name="registration" @if($general->registration) checked @endif>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-0">@lang('Registration Bonus')</p>
                                <p class="mb-0">
                                    <small>@lang('If you enable this module, users will get an amount to their deposit wallet after completing registration, according to the') <span class="fw-bold">@lang('Registration Bonus')</span> @lang('value set from the') <a href="{{ route('admin.setting.index') }}">@lang('General Setting')</a>.</small>
                                </p>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="35" data-on="@lang('Enable')" data-off="@lang('Disable')" name="signup_bonus_control" @if($general->signup_bonus_control) checked @endif>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-0">@lang('Promotional Tool')</p>
                                <p class="mb-0">
                                    <small>@lang('If you enable this module, users will be able to copy some HTML code which contain his/her referral link and an image added by you.') <a href="">@lang('Click here')</a> @lang('to add promotional tool').</small>
                                </p>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="35" data-on="@lang('Enable')" data-off="@lang('Disable')" name="promotional_tool" @if($general->promotional_tool) checked @endif>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-0">@lang('Withdrawal on Holiday')</p>
                                <p class="mb-0">
                                    <small>@lang('If you enable it, that means the system\'s users will be able to make withdrawal requests on holiday. Otherwise, they have to wait for the next working days')</small>
                                </p>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="35" data-on="@lang('Enable')" data-off="@lang('Disable')" name="holiday_withdraw" @if($general->holiday_withdraw) checked @endif>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-0">@lang('Force SSL')</p>
                                <p class="mb-0">
                                    <small>@lang('By enabling') <span class="fw-bold">@lang("Force SSL (Secure Sockets Layer)")</span> @lang('the system will force a visitor that he/she must have to visit in secure mode. Otherwise, the site will be loaded in secure mode.')</small>
                                </p>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="35" data-on="@lang('Enable')" data-off="@lang('Disable')" name="force_ssl" @if($general->force_ssl) checked @endif>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-0">@lang('Agree Policy')</p>
                                <p class="mb-0">
                                    <small>@lang('If you enable this module, that means a user must have to agree with your system\'s') <a href="{{ route('admin.frontend.sections','policy_pages') }}">@lang('policies')</a> @lang('during registration.')</small>
                                </p>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="35" data-on="@lang('Enable')" data-off="@lang('Disable')" name="agree" @if($general->agree) checked @endif>
                            </div>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-0">@lang('Force Secure Password')</p>
                                <p class="mb-0">
                                    <small>@lang('By enabling this module, a user must set a secure password while signing up or changing the password.')</small>
                                </p>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="35" data-on="@lang('Enable')" data-off="@lang('Disable')" name="secure_password" @if($general->secure_password) checked @endif>
                            </div>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-0">@lang('KYC Verification')</p>
                                <p class="mb-0">
                                    <small>@lang('If you enable') <span class="fw-bold">@lang('KYC (Know Your Client)')</span> @lang('module, users must have to submit') <a href="{{ route('admin.kyc.setting') }}">@lang('the required data')</a>. @lang('Otherwise, any money out transaction will be prevented by this system.')</small>
                                </p>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="35" data-on="@lang('Enable')" data-off="@lang('Disable')" name="kv" @if($general->kv) checked @endif>
                            </div>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-0">@lang('Email Verification')</p>
                                <p class="mb-0">
                                    <small>
                                        @lang('If you enable') <span class="fw-bold">@lang('Email Verification')</span>, @lang('users have to verify their email to access the dashboard. A 6-digit verification code will be sent to their email to be verified.')
                                        <br>
                                        <span class="fw-bold"><i>@lang('Note'):</i></span> <i>@lang('Make sure that the') <span class="fw-bold">@lang('Email Notification') </span> @lang('module is enabled')</i>
                                    </small>
                                </p>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="35" data-on="@lang('Enable')" data-off="@lang('Disable')" name="ev" @if($general->ev) checked @endif>
                            </div>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-0">@lang('Email Notification')</p>
                                <p class="mb-0">
                                    <small>@lang('If you enable this module, the system will send emails to users where needed. Otherwise, no email will be sent.') <code>@lang('So be sure before disabling this module that, the system doesn\'t need to send any emails.')</code></small>
                                </p>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="35" data-on="@lang('Enable')" data-off="@lang('Disable')" name="en" @if($general->en) checked @endif>
                            </div>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-0">@lang('Mobile Verification')</p>
                                <p class="mb-0">
                                    <small>
                                        @lang('If you enable') <span class="fw-bold">@lang('Mobile Verification')</span>, @lang('users have to verify their mobile to access the dashboard. A 6-digit verification code will be sent to their mobile to be verified.')
                                        <br>
                                        <span class="fw-bold"><i>@lang('Note'):</i></span> <i>@lang('Make sure that the') <span class="fw-bold">@lang('SMS Notification') </span> @lang('module is enabled')</i>
                                    </small>
                                </p>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="35" data-on="@lang('Enable')" data-off="@lang('Disable')" name="sv" @if($general->sv) checked @endif>
                            </div>
                        </li>


                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-0">@lang('SMS Notification')</p>
                                <p class="mb-0">
                                    <small>@lang('If you enable this module, the system will send SMS to users where needed. Otherwise, no SMS will be sent.') <code>@lang('So be sure before disabling this module that, the system doesn\'t need to send any SMS.')</code></small>
                                </p>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="35" data-on="@lang('Enable')" data-off="@lang('Disable')" name="sn" @if($general->sn) checked @endif>
                            </div>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-0">@lang('Multi Language')</p>
                                <p class="mb-0">
                                    <small>
                                        @lang('If you enable') <span class="fw-bold">@lang('Multi Language')</span>, @lang('users can switch site languages that you added in the ')<a href="{{ route('admin.language.manage') }}">@lang('Language')</a>@lang(' module').
                                    </small>
                                </p>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="35" data-on="@lang('Enable')" data-off="@lang('Disable')" name="language_switch" @if($general->language_switch) checked @endif>
                            </div>
                        </li>

                        <li class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                            <div>
                                <p class="fw-bold mb-0">@lang('Push Notification')</p>
                                <p class="mb-0">
                                    <small>
                                        @lang('If you enable this module, the system will send push notifications to users. Otherwise, no push notification will be sent.')
                                        <a href="{{ route('admin.setting.notification.push') }}">@lang('Setting here')</a><a>
                                    </a></small><a>
                                </a></p><a>
                            </a></div><a>
                            <div class="form-group">
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="35" data-on="@lang('Enable')" data-off="@lang('Disable')" name="push_notify" @if($general->push_notify) checked @endif>
                            </div>
                        </a></li>

                    </ul>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('style')
    <style>
        .toggle.btn-lg{
            height: 37px !important;
            min-height: 37px !important;
        }
        .toggle-handle{
            width: 25px !important;
            padding: 0;
        }
        .form-group{
            width: 125px;
            margin-bottom: 0;
        }
    </style>
@endpush
