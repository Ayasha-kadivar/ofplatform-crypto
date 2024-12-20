@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="blog-area-details section-common-bg ">
        <div class="container">
            <div class="row justify-content-center  pb-120 pt-120">
                <div class="col-md-8 col-lg-7 col-xl-5">
                    <div class="card custom--card">
                        <div class="card-body">
                            <div class="mb-4">
                                <p>@lang('To recover your account please provide your email or username to find your account.')</p>
                            </div>
                            <form method="POST" action="{{ route('user.password.email') }}">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="form-label">@lang('Email or Username')</label>
                                    <input type="text" class="form-control form--control" name="value"
                                        value="{{ old('value') }}" required autofocus="off">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn--outline-base w-100">@lang('Submit')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section />
    @endsection
