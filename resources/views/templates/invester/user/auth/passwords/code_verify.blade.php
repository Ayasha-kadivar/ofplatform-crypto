@extends($activeTemplate.'layouts.app')
@section('panel')

<!-- Account Section -->
<section class="account-section position-relative">
    <div class="container">
        <div class="text-center">
            <a href="{{ route('home') }}" class="d-block mb-3 mb-sm-4 auth-page-logo"><img src="{{ getImage(getFilePath('logoIcon').'/logo_2.png') }}" alt="logo"></a>
        </div>
        <div class="d-flex justify-content-center">
            <div class="verification-code-wrapper">
                <div class="verification-area">
                    <h5 class="pb-3 text-center border-bottom">@lang('Verify Email Address')</h5>
                    <form action="{{ route('user.password.verify.code') }}" method="POST" class="submit-form">
                        @csrf
                        <p class="verification-text">@lang('A 6 digit verification code sent to your email address') :  {{ showEmailAddress($email) }}</p>
                        <input type="hidden" name="email" value="{{ $email }}">

                        @include($activeTemplate.'partials.verification_code')

                        <div class="form-group">
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                        </div>

                        <div class="form-group">
                            @lang('Please check including your Junk/Spam Folder. if not found, you can')
                            <a href="{{ route('user.password.request') }}" class="fw-bold link-color">@lang('Try to send again')</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Account Section -->

@endsection
