@extends($activeTemplate.'layouts.app')
@section('panel')
@php
    $authContent = getContent('authentication.content',true);
@endphp
<!-- Account Section -->
<section class="account-section position-relative">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a href="{{ route('home') }}" class="text-center d-block mb-3 mb-sm-4 auth-page-logo"><img src="{{ getImage(getFilePath('logoIcon').'/logo_2.png') }}" alt="logo"></a>
                <form action="{{ route('user.data.submit') }}" method="POST" class="account-form">
                    @csrf
                    <div class="row">
                        <!-- <div class="form-group col-sm-6">
                            <label class="form-label">@lang('First Name')</label>
                            <input type="text" class="form-control form--control" name="firstname" value="{{ old('firstname') }}" required>
                        </div>

                        <div class="form-group col-sm-6">
                            <label class="form-label">@lang('Last Name')</label>
                            <input type="text" class="form-control form--control" name="lastname" value="{{ old('lastname') }}" required>
                        </div> -->
                        <div class="form-group col-sm-6">
                            <label class="form-label">@lang('Address')</label>
                            <input type="text" class="form-control form--control" name="address" value="{{ old('address') }}">
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="form-label">@lang('State')</label>
                            <input type="text" class="form-control form--control" name="state" value="{{ old('state') }}">
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="form-label">@lang('Zip Code')</label>
                            <input type="text" class="form-control form--control" name="zip" value="{{ old('zip') }}">
                        </div>

                        <div class="form-group col-sm-6">
                            <label class="form-label">@lang('City')</label>
                            <input type="text" class="form-control form--control" name="city" value="{{ old('city') }}">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- Account Section -->
@endsection
