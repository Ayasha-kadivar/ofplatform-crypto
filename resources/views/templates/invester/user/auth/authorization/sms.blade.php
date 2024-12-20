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
                    <h5 class="pb-3 text-center border-bottom">@lang('Verify Mobile Number')</h5>
                    <form action="{{route('user.verify.mobile')}}" method="POST" class="submit-form">
                        @csrf

                        <p class="verification-text">@lang('A 6 digit verification code sent to your mobile number') :  +{{ showMobileNumber(auth()->user()->mobile) }}</p>

                        @include($activeTemplate.'partials.verification_code')

                        <div class="mb-3">
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                        </div>
                        <div class="form-group">
                            <p>
                                @lang('If you don\'t get any code'), <a href="{{route('user.send.verify.code', 'phone')}}" class="link-color fw-bold"> @lang('Try again')</a>
                            </p>
                            @if($errors->has('resend'))
                                <br/>
                                <small class="text-danger">{{ $errors->first('resend') }}</small>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Account Section -->

@endsection
