@extends($activeTemplate .'layouts.frontend')
@section('content')
<div class="cmn-section">
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="verification-code-wrapper">
                <div class="verification-area">
                    <form action="{{route('user.go2fa.verify')}}" method="POST" class="submit-form">
                        @csrf

                        @include($activeTemplate.'partials.verification_code')

                        <div class="form--group">
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
