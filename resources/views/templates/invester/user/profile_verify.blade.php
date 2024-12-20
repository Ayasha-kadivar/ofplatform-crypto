@extends($activeTemplate.'layouts.master')
@section('content')

    <div class="dashboard-inner">
        <div class="mb-4">
            <h3 class="mb-2">@lang('Profile Update')</h3>
        </div>

        <div class="card custom--card">
            <div class="card-body">
                <div class="d-flex justify-content-center">
                    <div class="verification-code-wrapper">
                        <div class="verification-area">
                            <h5 class="pb-3 text-center border-bottom">@lang('Verify Email Address')</h5>
                            <form action="{{route('user.profile.verify.submit')}}" method="POST" class="submit-form">
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
        </div>


    </div>

@endsection