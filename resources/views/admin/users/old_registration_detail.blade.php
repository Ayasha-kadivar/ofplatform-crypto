@extends('admin.layouts.app')

@section('panel')

<div class="card mt-30">
    <div class="card-header">
        <h5 class="card-title mb-0">@lang('Information of') {{ "{$user->firstname} {$user->lastname}" }}</h5>
    </div>
    <div class="card-body">
        <form action="{{route('admin.users.old-profile-update',[$user->id])}}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group ">
                        <label>@lang('First Name')</label>
                        <input class="form-control" type="text" name="firstname" required value="{{$user->firstname}}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-control-label">@lang('Last Name')</label>
                        <input class="form-control" type="text" name="lastname" required value="{{$user->lastname}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-control-label">@lang('Username')</label>
                        <input class="form-control" type="text" name="username" required value="{{$user->username}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>@lang('Email') </label>
                        <input class="form-control" type="email" name="email" value="{{$user->email}}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>@lang('Country') </label>
                        <select name="country_code" class="form-control">
                            @foreach($countries as $key => $country)
                            <option data-mobile_code="{{ $country->dial_code }}" value="{{ $key }}">{{
                                __($country->country) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>@lang('Mobile Number') </label>
                        <div class="input-group ">
                            <span class="input-group-text mobile-code"></span>
                            <input type="number" name="mobile" value="{{ old('mobile') }}" id="mobile"
                                class="form-control checkUser" required>
                        </div>
                    </div>
                </div>
            </div>
            @php
                $addressData = json_decode($user->address);
            @endphp
        
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="form-group ">
                        <label>@lang('Address')</label>
                        <input class="form-control" type="text" name="address" value="{{@$addressData->address }}">
                    </div>
                </div>

                <div class="col-xl-4 col-md-4">
                    <div class="form-group">
                        <label>@lang('City')</label>
                        <input class="form-control" type="text" name="city" value="{{@$addressData->city}}">
                    </div>
                </div>

                <div class="col-xl-4 col-md-4">
                    <div class="form-group ">
                        <label>@lang('State')</label>
                        <input class="form-control" type="text" name="state" value="{{@$addressData->state}}">
                    </div>
                </div>

                <div class="col-xl-4 col-md-4">
                    <div class="form-group ">
                        <label>@lang('Zip/Postal')</label>
                        <input class="form-control" type="text" name="zip" value="{{@$addressData->zip}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>@lang('Balance') </label>
                        <input class="form-control" type="text" name="balance" value="{{$user->balance}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <label>Your sponsor phone number</label>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Country') </label>
                            <select name="sponsor_country_code" class="form-control">
                                @foreach($countries as $key => $country)
                                <option data-mobile_code="{{ $country->dial_code }}" value="{{ $key }}">{{
                                    __($country->country) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Mobile Number') </label>
                            <div class="input-group ">
                                <span class="input-group-text sponsor-mobile-code"></span>
                                <input type="number" name="sponsor_phone" value="{{$user->sponsor_phone}}" id="sponsor_phone"
                                    class="form-control checkUser" required>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
            @php
                $expPackages = explode('||',$user->purchased_packages);
            @endphp
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>@lang('Package Purchases') </label>
                        <input class="form-control" type="text" name="purchased_packages1" value="{{@$expPackages[0]}}">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" name="purchased_packages2" value="{{@$expPackages[1]}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>@lang('Date') </label>
                        <input class="form-control" type="date" name="date" value="{{$user->date}}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>@lang('Number fo Packages') </label>
                        <input class="form-control" type="text" name="number_of_packages" value="{{$user->number_of_packages}}">
                    </div>
                </div>
            </div>   
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label"><input type="checkbox" value="Yes" {{$user->maintenance_fee_paid == 'Yes' ? 'checked' : ''}} id="maintenance_fee_paid" name="maintenance_fee_paid"> @lang('Did you pay 10 BUSD for maintenance?') </label>
                    </div>
                </div>
                <div class="col-md-12" id="google_form_div" {{$user->maintenance_fee_paid == 'Yes' ? 'style="display: block;"' : 'style="display: none;"'}}>
                    <div class="form-group">
                        <label class="form-label"><input type="checkbox" value="Yes" {{$user->google_form == 'Yes' ? 'checked' : ''}} id="google_form" name="google_form"> @lang('Google form') </label>
                    </div>
                </div>  
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label"><input type="checkbox" value="Yes" {{$user->has_hash_id == 'Yes' ? 'checked' : ''}} id="has_hash_id" name="has_hash_id"> @lang('Hash ID') </label>
                    </div>
                </div>   
                <div class="col-md-12" id="has_id_div" {{$user->has_hash_id == 'Yes' ? 'style="display: block;"' : 'style="display: none;"'}}>
                    <div class="form-group">
                        <label class="form-label">@lang('Hash ID')</label>
                        <input type="text" class="form-control form--control h-45" name="hash_id" id="hash_id" value="{{@$user->hash_id}}">
                    </div>
                </div>
            </div>         

            <div class="row">
                <div class="form-group  col-xl-3 col-md-6 col-12">
                    <label>@lang('Admin Approval Status')</label>
                    <input type="checkbox" name="profile_status" value="Approved" @if($user->status=='Approved') checked @endif >
                </div>

                
            </div>


            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="form-group">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

</div>
</div>


@endsection


@push('script')
<script>
    (function($){
    "use strict"
        let mobileElement = $('.mobile-code');
        $('select[name=country_code]').change(function(){
            mobileElement.text(`+${$('select[name=country_code] :selected').data('mobile_code')}`);
        });

        $('select[name=country_code]').val('{{@$user->country_code}}');
        let dialCode        = $('select[name=country_code] :selected').data('mobile_code');
        let mobileNumber    = `{{ $user->mobile }}`;
        mobileNumber        = mobileNumber.replace(dialCode,'');
        $('input[name=mobile]').val(mobileNumber);
        mobileElement.text(`+${dialCode}`);

        $('select[name=sponsor_country_code]').val('{{@$user->sponsor_country_code}}');
        let sponsorMobileElement = $('.sponsor-mobile-code');
        let sponsorDialCode        = $('select[name=sponsor_country_code] :selected').data('mobile_code');
        let sponsorMobileNumber    = `{{ $user->sponsor_phone }}`;
        sponsorMobileNumber = sponsorMobileNumber.replace(sponsorDialCode,'');
        $('input[name=sponsor_phone]').val(sponsorMobileNumber);
        sponsorMobileElement.text(`+${sponsorDialCode}`);

    })(jQuery);
</script>
@endpush