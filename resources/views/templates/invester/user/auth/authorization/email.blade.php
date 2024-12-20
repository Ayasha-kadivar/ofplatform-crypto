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
                    <form action="{{route('user.verify.email')}}" method="POST" class="submit-form">
                        @csrf
                        <p class="verification-text">@lang('A 6 digit verification code sent to your email address') :  {{ showEmailAddress(auth()->user()->email) }}</p>

                        @include($activeTemplate.'partials.verification_code')

                        <div class="form-group">
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                        </div>

                        <div class="form-group">
                            @lang('If you don\'t get any code')
                            <a href="{{route('user.send.verify.code', 'email')}}" class="fw-bold link-color">@lang('Try again')</a>
                        </div>

                        @if($errors->has('resend'))
                            <small class="text-danger d-block">{{ $errors->first('resend') }}</small>
                        @endif

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Account Section -->

@endsection

@push('script-lib')
    <script src="{{ asset('assets/global/js/disable_paste.js') }}?v=<?php echo strtotime(now()); ?>"></script>
@endpush