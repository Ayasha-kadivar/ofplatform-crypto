@extends($activeTemplate.'layouts.app')
@section('panel')

<section class="account-section position-relative">
    <div class="container">
        <div class="text-center">
            <a href="{{ route('home') }}" class="d-block mb-3 mb-sm-4 auth-page-logo"><img src="{{ getImage(getFilePath('logoIcon').'/logo_2.png') }}" alt="logo"></a>
        </div>
        <div class="d-flex justify-content-center">
            <div class="verification-code-wrapper">
                <div class="verification-area">
                    <h5 class="pb-3 text-center border-bottom">@lang('2FA Verification')</h5>
                    <form action="{{route('user.go2fa.verify')}}" method="POST" class="submit-form">
                        @csrf
                        @include($activeTemplate.'partials.verification_code')

                        <div class="form-group">
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
