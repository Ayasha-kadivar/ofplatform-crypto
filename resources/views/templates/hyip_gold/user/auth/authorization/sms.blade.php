@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class=" section-common-bg pt-160 pb-140 ">
        <div class="container">
            <div class="d-flex justify-content-center">
                <div class="verification-code-wrapper">
                    <div class="verification-area">
                        <form action="{{ route('user.verify.mobile') }}" method="POST" class="submit-form">
                            @csrf
                            <p class="verification-text">@lang('A 6 digit verification code sent to your mobile number') : +{{ showMobileNumber(auth()->user()->mobile) }}
                            </p>
                            @include($activeTemplate . 'partials.verification_code')
                            <div class="mb-3">
                                <button type="submit" class="btn btn--outline-base w-100">@lang('Submit')</button>
                            </div>
                            <div class="form-group">
                                <p>
                                    @lang('If you don\'t get any code'), <a href="{{ route('user.send.verify.code', 'phone') }}"
                                        class="forget-pass text--base"> @lang('Try again')</a>
                                </p>
                                @if ($errors->has('resend'))
                                    <br />
                                    <small class="text-danger">{{ $errors->first('resend') }}</small>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
