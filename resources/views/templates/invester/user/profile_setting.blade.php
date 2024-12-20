@extends($activeTemplate.'layouts.master')
@section('content')

    <div class="dashboard-inner">
        <div class="mb-4">
            <h3 class="mb-2 new--color--theme">@lang('Profile')</h3>
        </div>

        <div class="card custom--card">
            <div class="card-body">
                <form class="register" action="" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">@lang('Username') </label>
                                {{--
                                @if($user->launch_nft_owner == 1)
                                    <span>
                                        <span style="font-size: medium;" class="badge badge--primary">Launch NFT Owner</span>
                                    </span>
                                @endif
                                @if($user->vip_user == 1)
                                    <span>
                                        <span style="font-size: medium;" class="badge badge--primary">VIP User</span>
                                    </span>
                                @endif
                                @if($user->vip_user_date != '')
                                    <span>
                                        <span style="font-size: medium;" class="badge badge--warning">Membership Expiry: {{$user->vip_user_date}}</span>
                                    </span>
                                @endif
                                --}}
                                <input type="text" class="form-control form--control checkUser h-45" name="username" value="{{$user->username}}" required maxlength="25" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="form-label">@lang('First Name')</label>
                            <input type="text" class="form-control form--control" name="firstname" value="{{$user->firstname}}" required readonly>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="form-label">@lang('Last Name')</label>
                            <input type="text" class="form-control form--control" name="lastname" value="{{$user->lastname}}" required readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="form-label">@lang('E-mail Address')</label>
                            <input class="form-control form--control" value="{{$user->email}}" readonly>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="form-label">@lang('Mobile Number')</label>
                            <input class="form-control form--control" value="{{$user->mobile}}" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="form-label">@lang('Address')</label>
                            <input type="text" class="form-control form--control" name="address" value="{{@$user->address->address}}">
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="form-label">@lang('State')</label>
                            <input type="text" class="form-control form--control" name="state" value="{{@$user->address->state}}">
                        </div>
                    </div>


                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label class="form-label">@lang('Zip Code')</label>
                            <input type="text" class="form-control form--control" name="zip" value="{{@$user->address->zip}}">
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="form-label">@lang('City')</label>
                            <input type="text" class="form-control form--control" name="city" value="{{@$user->address->city}}">
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="form-label">@lang('Country')</label>
                            <input class="form-control form--control" value="{{@$user->address->country}}" disabled>
                        </div>

                    </div>

                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label class="form-label">@lang('Stellar Wallet Address'): </label>
                            <u><b><h5>{{$user->wallet_data}}</h5></b></u>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="form-label">@lang('Sponsor Username'): </label>
                            <u><b><h5>{{$user->referrer->username ?? null}}</h5></b></u>
                        </div> 
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>


    </div>

@endsection
