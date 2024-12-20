@extends($activeTemplate.'layouts.master')
@section('content')

    <div class="dashboard-inner">
        <div class="row justify-content-center">
            <div class="@if(!auth()->user()->ts) col-md-12 @else col-md-8 @endif">
                <div class="mb-4">
                    <h3 class="mb-2 new--color--theme">@lang('Two Factor Authentication')</h3>
                    @if(!auth()->user()->ts)
                    <p>@lang('Your account will be more secure if you use this feature. A 6-digit verification code from your Android Google Authenticator app must be entered whenever someone tries to log in to the account. So that the system could verify that, this is you. Additionally, the payout procedure will require this verification.')</p>
                    @else
                    <p>@lang('If you think that you don\'t need the 2FA verification, You\'ve a way to disable it. But before disabling this, the system warns you that your account could be at a security risk. The System recommendation is to enable 2FA security.')</p>
                    @endif
                </div>
                <div class="row gy-4">

                    @if(!auth()->user()->ts)
                    <div class="col-md-6">
                        <div class="card custom--card">
                            <div class="card-header">
                                <h5 class="mb-0">@lang('Add Your Account')</h5>
                            </div>

                            <div class="card-body">
                                <h6 class="mb-3">
                                    @lang('Use the QR code or setup key on your Google Authenticator app to add your account. ')
                                </h6>

                                <div class="form-group mb-3 mx-auto text-center">
                                    <img class="mx-auto" src="{{$qrCodeUrl}}">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">@lang('Setup Key')</label>
                                    <div class="copy-link">
                                        <input type="text" class="copyURL" value="{{$secret}}" readonly>
                                        <span class="copyBoard" id="copyBoard"><i class="las la-copy"></i> <strong class="copyText">@lang('Copy')</strong></span>
                                    </div>
                                </div>

                                <label><i class="fa fa-info-circle"></i> @lang('Help')</label>
                                <p>@lang('Google Authenticator is a multifactor app for mobile devices. It generates timed codes used during the 2-step verification process. To use Google Authenticator, install the Google Authenticator application on your mobile device.') <a class="text--base" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank">@lang('Download')</a></p>
                            </div>
                        </div>
                    </div>

                    @endif

                    <div class="@if(!auth()->user()->ts) col-md-6 @else col-md-12 @endif">

                        @if(auth()->user()->ts)
                            <div class="card custom--card">
                                <div class="card-header">
                                    <h5 class="mb-0">@lang('Disable 2FA Security')</h5>
                                </div>
                                <form action="{{route('user.twofactor.disable')}}" method="POST">
                                    <div class="card-body">
                                        @csrf
                                        <input type="hidden" name="key" value="{{$secret}}">
                                        <div class="form-group mb-3">
                                            <label class="form-label">@lang('Google Authenticatior OTP')</label>
                                            <input type="text" class="form-control form--control" name="code" required>
                                        </div>
                                        <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="card custom--card">
                                <div class="card-header">
                                    <h5 class="mb-0">@lang('Enable 2FA Security')</h5>
                                </div>
                                <form action="{{ route('user.twofactor.enable') }}" method="POST">
                                    <div class="card-body">
                                        @csrf
                                        <input type="hidden" name="key" value="{{$secret}}">
                                        <div class="form-group mb-3">
                                            <label class="form-label">@lang('Google Authenticatior OTP')</label>
                                            <input type="text" class="form-control form--control" name="code" required>
                                        </div>
                                        <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection



@push('script')
    <script>
        (function($){
            "use strict";
            $('#copyBoard').click(function(){
                var copyText = document.getElementsByClassName("copyURL");
                copyText = copyText[0];
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                /*For mobile devices*/
                document.execCommand("copy");
                $('.copyText').text('Copied');
                setTimeout(() => {
                    $('.copyText').text('Copy');
                }, 2000);
            });
        })(jQuery);
    </script>
@endpush
