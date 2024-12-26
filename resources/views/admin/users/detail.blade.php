@extends('admin.layouts.app')
@section('panel')
    <style>
        input.datepicker-ban-expirydate[type="date"] {
        position: relative;
        }

        input.datepicker-ban-expirydate[type="date"]::-webkit-calendar-picker-indicator {
        position: absolute;
        top: 0;
        right: 0;
        width: 100%;
        height: 100%;
        padding: 0;
        color: transparent;
        background: transparent;
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="row gy-4">

                <div class="col-xxl-4 col-sm-6">
                    <div class="widget-two style--two box--shadow2 b-radius--5 bg--19">
                        <div class="widget-two__icon b-radius--5 bg--primary">
                            <i class="las la-money-bill-wave-alt"></i>
                        </div>
                        <div class="widget-two__content">
                            <h3 class="text-white">{{ showAmount($user->deposit_ft) }} FT</h3>
                            <p class="text-white">@lang('Deposit Wallet')</p>
                        </div>
                        <a href="{{ route('admin.report.transaction') }}?search={{ $user->username }}" class="widget-two__btn">@lang('View All')</a>
                    </div>
                </div>
                <!-- dashboard-w1 end -->

                <div class="col-xxl-4 col-sm-6">
                    <div class="widget-two style--two box--shadow2 b-radius--5 bg--19">
                        <div class="widget-two__icon b-radius--5 bg--primary">
                            <i class="las la-money-bill-wave-alt"></i>
                        </div>
                        <div class="widget-two__content">
                            <h3 class="text-white">{{ $general->cur_sym }}{{ showAmount($user->interest_wallet) }}</h3>
                            <p class="text-white">@lang('Interest Wallet')</p>
                        </div>
                        <a href="{{ route('admin.report.transaction') }}?search={{ $user->username }}" class="widget-two__btn">@lang('View All')</a>
                    </div>
                </div>
                <!-- dashboard-w1 end -->

                <div class="col-xxl-4 col-sm-6">
                    <div class="widget-two style--two box--shadow2 b-radius--5 bg--19">
                        <div class="widget-two__icon b-radius--5 bg--primary">
                            <i class="las la-money-bill-wave-alt"></i>
                        </div>
                        <div class="widget-two__content">
                            <h3 class="text-white">{{ $general->cur_sym }}{{ showAmount($user->pool_2) }}</h3>
                            <p class="text-white">@lang('Vouchers Cube')</p>
                        </div>
                        <a href="{{ route('admin.report.transaction') }}?search={{ $user->username }}" class="widget-two__btn">@lang('View All')</a>
                    </div>
                </div>
                <!-- dashboard-w1 end -->

                <div class="col-xxl-4 col-sm-6">
                    <div class="widget-two style--two box--shadow2 b-radius--5 bg--19">
                        <div class="widget-two__icon b-radius--5 bg--primary">
                            <i class="las la-money-bill-wave-alt"></i>
                        </div>
                        <div class="widget-two__content">
                            <h3 class="text-white">{{ showAmount($user->pool_3) }} FT</h3>
                            <p class="text-white">@lang('Staking Cube')</p>
                        </div>
                        <a href="{{ route('admin.report.transaction') }}?search={{ $user->username }}" class="widget-two__btn">@lang('View All')</a>
                    </div>
                </div>
                <!-- dashboard-w1 end -->

                <div class="col-xxl-4 col-sm-6">
                    <div class="widget-two style--two box--shadow2 b-radius--5 bg--19">
                        <div class="widget-two__icon b-radius--5 bg--primary">
                            <i class="las la-money-bill-wave-alt"></i>
                        </div>
                        <div class="widget-two__content">
                            <h3 class="text-white">{{ $general->cur_sym }}{{ showAmount($user->pool_4) }}</h3>
                            <p class="text-white">@lang('NFTs Cube')</p>
                        </div>
                        <a href="{{ route('admin.report.transaction') }}?search={{ $user->username }}" class="widget-two__btn">@lang('View All')</a>
                    </div>
                </div>
                <!-- dashboard-w1 end -->

                <div class="col-xxl-4 col-sm-6">
                    <div class="widget-two style--two box--shadow2 b-radius--5 bg--primary">
                        <div class="widget-two__icon b-radius--5 bg--primary">
                            <i class="las la-wallet"></i>
                        </div>
                        <div class="widget-two__content">
                            <h3 class="text-white">{{ $general->cur_sym }}{{ showAmount($totalDeposit) }}</h3>
                            <p class="text-white">@lang('Deposits')</p>
                        </div>
                        <a href="{{ route('admin.deposit.list') }}?search={{ $user->username }}" class="widget-two__btn">@lang('View All')</a>
                    </div>
                </div>
                <!-- dashboard-w1 end -->

                <div class="col-xxl-4 col-sm-6">
                    <div class="widget-two style--two box--shadow2 b-radius--5 bg--1">
                        <div class="widget-two__icon b-radius--5 bg--primary">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="widget-two__content">
                            <h3 class="text-white">{{ $general->cur_sym }}{{ showAmount($totalWithdrawals) }}</h3>
                            <p class="text-white">@lang('Withdrawals')</p>
                        </div>
                        <a href="{{ route('admin.withdraw.log') }}?search={{ $user->username }}" class="widget-two__btn">@lang('View All')</a>
                    </div>
                </div>
                <!-- dashboard-w1 end -->

                <div class="col-xxl-4 col-sm-6">
                    <div class="widget-two style--two box--shadow2 b-radius--5 bg--17">
                        <div class="widget-two__icon b-radius--5 bg--primary">
                            <i class="las la-exchange-alt"></i>
                        </div>
                        <div class="widget-two__content">
                            <h3 class="text-white">{{ $totalTransaction }}</h3>
                            <p class="text-white">@lang('Transactions')</p>
                        </div>
                        <a href="{{ route('admin.report.transaction') }}?search={{ $user->username }}" class="widget-two__btn">@lang('View All')</a>
                    </div>
                </div>
                
                <!-- dashboard-w1 end -->
                <div class="col-xxl-4 col-sm-6">
                    <div class="widget-two style--two box--shadow2 b-radius--5 bg--18">
                        <div class="widget-two__icon b-radius--5 bg--primary">
                            <i class="las la-ticket-alt"></i>
                        </div>
                        <div class="widget-two__content">
                            <h3 class="text-white">{{ $general->cur_sym }}{{ showAmount($user->affiliate_reward) }}</h3>
                            <p class="text-white">@lang('Affiliate Rewards')</p>
                        </div>
                        <a href="{{ route('admin.report.transaction') }}?search={{ $user->username }}" class="widget-two__btn">@lang('View All')</a>
                    </div>
                </div>
                <!-- dashboard-w1 end -->

            </div>

            @if((auth('admin')->user()->id == 29 || auth('admin')->user()->id == 19 || auth('admin')->user()->role_status == 0))
            <div class="d-flex flex-wrap gap-3 mt-4">
                @if (auth('admin')->check() && auth('admin')->user()->role_status == 0)
                    @php
                        $allowedUserIds = [1, 3, 7, 8, 17, 30]; // Specify the user IDs you want to give access to
                    @endphp

                    @if (in_array(auth('admin')->user()->id, $allowedUserIds))
                        <div class="flex-fill">
                            <button data-bs-toggle="modal" data-bs-target="#addSubModal" class="btn btn--success btn--shadow w-100 btn-lg bal-btn" data-act="add">
                                <i class="las la-plus-circle"></i> @lang('Balance')
                            </button>
                        </div>

                        <div class="flex-fill">
                            <button data-bs-toggle="modal" data-bs-target="#addSubModal" class="btn btn--danger btn--shadow w-100 btn-lg bal-btn" data-act="sub">
                                <i class="las la-minus-circle"></i> @lang('Balance')
                            </button>
                        </div>
                        
                        @if($user->status == 1)
                        <div class="flex-fill">
                            <button data-bs-toggle="modal" data-bs-target="#addFamilyNFTModal" class="btn btn--warning btn--shadow w-100 btn-lg bal-btn" data-act="add">
                                <i class="las la-plus-circle"></i> @lang('Add FamilyNFT')
                            </button>
                        </div>
                        <div class="flex-fill">
                            <button onclick="openRemoveNFTPage({{ $user->id }})" class="btn btn--danger btn--shadow w-100 btn-lg bal-btn">
                                <i class="las la-minus-circle"></i> @lang('Remove FamilyNFT')
                            </button>
                        </div>
                        {{-- <div class="flex-fill">
                            <button data-bs-toggle="modal" data-bs-target="#removeFamilyNFTModal" class="btn btn--danger btn--shadow w-100 btn-lg bal-btn" data-act="add">
                                <i class="las la-minus-circle"></i> @lang('Remove FamilyNFT')
                            </button>
                        </div> --}}
                        @endif
                    @else
                    @endif
                @endif

                <div class="flex-fill">
                    <a href="{{route('admin.report.login.history')}}?search={{ $user->username }}" class="btn btn--primary btn--shadow w-100 btn-lg">
                        <i class="las la-list-alt"></i>@lang('Logins')
                    </a>
                </div>

                <div class="flex-fill">
                    <a href="{{ route('admin.users.notification.log',$user->id) }}" class="btn btn--secondary btn--shadow w-100 btn-lg">
                        <i class="las la-bell"></i>@lang('Notifications')
                    </a>
                </div>

                <div class="flex-fill">
                    <a href="{{route('admin.users.login',$user->id)}}" target="_blank" class="btn btn--primary btn--gradi btn--shadow w-100 btn-lg">
                        <i class="las la-sign-in-alt"></i>@lang('Login as User')
                    </a>
                </div>

                @if($user->kyc_data)
                <div class="flex-fill">
                    <a href="{{ route('admin.users.kyc.details', $user->id) }}" target="_blank" class="btn btn--dark btn--shadow w-100 btn-lg">
                        <i class="las la-user-check"></i>@lang('KYC Data')
                    </a>
                </div>
                @endif

                
                <div class="flex-fill">
                    @if($user->status == 1)
                    <button type="button" class="btn btn--warning btn--gradi btn--shadow w-100 btn-lg userStatus" data-bs-toggle="modal" data-bs-target="#userStatusModal">
                        <i class="las la-ban"></i>@lang('Ban User')
                    </button>
                    @else
                    <button type="button" class="btn btn--success btn--gradi btn--shadow w-100 btn-lg userStatus" data-bs-toggle="modal" data-bs-target="#userStatusModal">
                        <i class="las la-undo"></i>@lang('Unban User')
                    </button>
                    @endif
                </div>
                <div class="flex-fill">
                    <form action="{{ route('admin.users.destroy', ['id' => $user->id]) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn--danger btn--shadow w-100 btn-lg del_user" confirm_txt="Are you sure you want to delete this user?">
                            <i class="las la-trash"></i>@lang('Delete')
                        </button>
                    </form>
                </div>
            </div>
            @endif


            <div class="card mt-30">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Information of') {{$user->fullname}}</h5>
                </div>
                <div class="card-body">
                    <form action="{{route('admin.users.update',[$user->id])}}" method="POST"
                          enctype="multipart/form-data">
                        @csrf

                        @if((auth('admin')->user()->id == 29 || auth('admin')->user()->id == 19 || auth('admin')->user()->role_status == 0))
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Username') </label>
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
                                    <input class="form-control" type="text" name="username" value="{{$user->username}}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        @lang('Sponsor Email') 

                                        @if((auth('admin')->user()->id == 29 || auth('admin')->user()->id == 19 || auth('admin')->user()->role_status == 0))
                                        

                                            <span data-bs-toggle="modal" data-bs-target="#updateSponsorEmail" style="cursor:pointer;color:blue"> - Update</span>

                                        @endif
                                    </label>
                                    <input class="form-control" type="text" name="referrer_email" value="{{ $referrerEmail }}  ({{$referrerUserName}})" readonly>
                                </div>
                            </div>
                        </div>

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
                        @else 
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Username') </label>
                                        @if($user->launch_nft_owner == 1)
                                        <span>
                                            <span style="font-size: medium;" class="badge badge--primary">Launch NFT Owner</span>
                                        </span>
                                        @endif
                                        <br>
                                        @if($user->vip_user == 1)
                                        <span>
                                            <span style="font-size: medium;" class="badge badge--primary">VIP User</span>
                                        </span>
                                        <br>
                                        @endif
                                        <span>{{$user->username}}</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            @lang('Sponsor Email') 
                                        </label><br>
                                        <span>{{ $referrerEmail }}  ({{$referrerUserName}})</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label>@lang('First Name')</label><br>
                                        <span>{{$user->firstname}}</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">@lang('Last Name')</label><br>
                                        <span>{{$user->lastname}}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Email') </label>
                                    <input class="form-control" type="email" name="email" value="{{$user->email}}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Mobile Number') </label>
                                    <div class="input-group ">
                                        <span class="input-group-text mobile-code"></span>
                                        <input type="number" name="mobile" value="{{ old('mobile') }}" id="mobile" class="form-control checkUser" required>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row mt-4">
                            @if((auth('admin')->user()->id == 29 || auth('admin')->user()->id == 19 || auth('admin')->user()->role_status == 0))
                            
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label>@lang('Create New Password') <span style="color:red">*  Use combination of lower/upper case, numeric, special character and un-trivial</span></label><br>
                                        <input class="form-control" type="text" name="password" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label>@lang('Address')</label>
                                        <input class="form-control" type="text" name="address" value="{{@$user->address->address}}">
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <div class="form-group">
                                        <label>@lang('City')</label>
                                        <input class="form-control" type="text" name="city" value="{{@$user->address->city}}">
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <div class="form-group ">
                                        <label>@lang('State')</label>
                                        <input class="form-control" type="text" name="state" value="{{@$user->address->state}}">
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <div class="form-group ">
                                        <label>@lang('Zip/Postal')</label>
                                        <input class="form-control" type="text" name="zip" value="{{@$user->address->zip}}">
                                    </div>
                                </div>

                            @else

                                <div class="col-md-12">
                                    <div class="form-group ">
                                        <label>@lang('Address')</label><br>
                                        <span>{{@$user->address->address}}</span>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <div class="form-group">
                                        <label>@lang('City')</label><br>
                                        <span>{{@$user->address->city}}</span>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <div class="form-group ">
                                        <label>@lang('State')</label><br>
                                        <span>{{@$user->address->state}}</span>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <div class="form-group ">
                                        <label>@lang('Zip/Postal')</label><br>
                                        <span>{{@$user->address->zip}}</span>
                                    </div>
                                </div>

                            @endif

                            
                            <div class="col-xl-3 col-md-6">
                                <div class="form-group ">
                                    <label>@lang('Country')</label>
                                    <select name="country" class="form-control">
                                        @foreach($countries as $key => $country)
                                            <option data-mobile_code="{{ $country->dial_code }}" value="{{ $key }}">{{ __($country->country) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        @if (auth('admin')->check() && (auth('admin')->user()->role_status == 0 || auth('admin')->user()->id == 29 || auth('admin')->user()->id == 19 ))
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('Stellar Wallet Address')</label>
                                <input type="text" maxlength="56" name="wallet_data" class="form-control form--control" value="{{@$user->wallet_data}}" >
                            </div>
                        </div>
                        @else
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('Stellar Wallet Address'): </label>
                                <u><b><h5>{{@$user->wallet_data}}</h5></b></u>
                            </div> 
                        </div>
                        @endif


                        <div class="row">
                            @if((auth('admin')->user()->id == 29 || auth('admin')->user()->id == 19 || auth('admin')->user()->role_status == 0))
                                <div class="form-group  col-xl-3 col-md-6 col-12">
                                    <label>@lang('Email Verification')</label>
                                    <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                        data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="ev"
                                        @if($user->ev) checked @endif>

                                </div>

                                <div class="form-group  col-xl-3 col-md-6 col-12">
                                    <label>@lang('Mobile Verification')</label>
                                    <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                        data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="sv"
                                        @if($user->sv) checked @endif>

                                </div>
                            @else
                                @if($user->ev)
                                <div class="form-group  col-xl-3 col-md-6 col-12">
                                    <label>@lang('Email Verification')</label><br>
                                    <span>@if($user->ev) Verified @else Unverified @endif</span>
                                </div>
                                @endif
                                @if($user->sv)
                                <div class="form-group  col-xl-3 col-md-6 col-12">
                                    <label>@lang('Mobile Verification')</label><br>
                                    <span>@if($user->sv) Verified @else Unverified @endif</span>
                                </div>
                                @endif
                            @endif
                            <div class="form-group col-xl-3 col-md- col-12">
                                <label>@lang('2FA Verification') </label>
                                <input type="checkbox" data-width="100%" data-height="50" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disable')" name="ts" @if($user->ts) checked @endif>
                            </div>
                            @if((auth('admin')->user()->id == 29 || auth('admin')->user()->id == 19 || auth('admin')->user()->role_status == 0))
                                <div class="form-group col-xl-3 col-md- col-12">
                                    <label>@lang('KYC') </label>
                                    <input type="checkbox" data-width="100%" data-height="50" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="kv" @if($user->kv == 1) checked @endif>
                                </div>
                            @else
                                <div class="form-group col-xl-3 col-md- col-12">
                                    <label>@lang('KYC') </label><br>
                                    <span>@if($user->kv == 1) Verified @else Unverified @endif</span>
                                </div>                            
                            @endif
                        </div>

                        @if((auth('admin')->user()->id == 29 || auth('admin')->user()->id == 19 || auth('admin')->user()->role_status == 0))
                            <div class="row">
                                <div class="form-group col-xl-3 col-md- col-12">
                                    <label>@lang('Launch NFT Owner') </label>
                                    <input type="checkbox" data-width="100%" data-height="50" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')" name="launch_nft_owner" @if($user->launch_nft_owner == 1) checked @endif>
                                </div>

                                <div class="form-group col-xl-3 col-md- col-12">
                                    <label>@lang('VIP User') </label>
                                    <input type="checkbox" data-width="100%" data-height="50" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')" name="vip_user" id="vip_user" @if($user->vip_user == 1) checked @endif>
                                </div>

                                <div id="VipDateVarify" <?php if($user->vip_user == 1): ?> style="display: block;" <?php else: ?>style="display: none;" <?php endif; ?> class="form-group col-xl-3 col-md- col-12">
                                        <div>
                                            <label>@lang('VIP Membership Expiry Date')</label>
                                            <input name="vip_user_date" id="vip_user_date" type="text" data-multiple-dates-separator=" - " data-language="en" class="datepicker-expirydate form-control" data-position='bottom right' placeholder="VIP Membership Expiry Date" readonly <?php if(isset($user->vip_user_date) && !empty($user->vip_user_date)): ?> value="{{ \Carbon\Carbon::parse($user->vip_user_date)->format('Y-m-d') }}" <?php endif; ?> >
                                        </div>
                                </div>

                                <div class="form-group col-xl-3 col-md- col-12">
                                    <label>@lang('Maintenance Fees') </label>
                                    <input type="checkbox" data-width="100%" data-height="50" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" id="fee_status" name="fee_status" @if($user->fee_status == 2) checked @endif>
                                </div>
                                <div class="form-group col-xl-3 col-md- col-12">
                                        <div id="ExpiryDateVarify" <?php if($user->fee_status == 2): ?> style="display: block;" <?php else: ?>style="display: none;" <?php endif; ?>>
                                            <label>@lang('Expiry Date')</label>
                                            <input name="maintenance_expiration_date" type="text" data-multiple-dates-separator=" - " data-language="en" class="datepicker-expirydate form-control" data-position='bottom right' placeholder="Expiry Date" readonly <?php if(isset($user->maintenance_expiration_date) && !empty($user->maintenance_expiration_date)): ?> value="{{ \Carbon\Carbon::parse($user->maintenance_expiration_date)->format('Y-m-d') }}" <?php endif; ?> >
                                        </div>
                                </div>
                                <div class="form-group col-xl-3 col-md- col-12">
                                    <label>@lang('Account deactivated?') </label>
                                    <input type="checkbox" data-width="100%" data-height="50" data-onstyle="-danger" data-offstyle="-success" data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')" id="is_block" name="is_block" @if($user->is_block == 1) checked @endif>
                                </div>
                                <div class="form-group col-xl-3 col-md- col-12" id="MaintenanceNote" <?php if($user->fee_status == 2): ?> style="display: block;" <?php else: ?>style="display: none;" <?php endif; ?>>
                                    <label>@lang('Note')</label>
                                    <textarea class="form-control" id="maintenance_note" name="maintenance_note" maxlength="145">{{$user->maintenance_note }}</textarea>
                                </div>


                                

                                <div class="form-group col-xl-3 col-md- col-12">
                                    <label>@lang('Assembly User') </label>
                                    <input type="checkbox" data-width="100%" data-height="50" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')" name="assembly_user" id="assembly_user" @if($user->assembly_user == 1) checked @endif>
                                </div>

                                <div id="AssemblyDateVarify" <?php if($user->assembly_user == 1): ?> style="display: block;" <?php else: ?>style="display: none;" <?php endif; ?> class="form-group col-xl-3 col-md- col-12">
                                        <div>
                                            <label>@lang('Assembly Expiry Date')</label>
                                            <input name="assembly_user_date" id="assembly_user_date" type="text" data-multiple-dates-separator=" - " data-language="en" class="datepicker-expirydate form-control" data-position='bottom right' placeholder="Assembly Expiry Date" readonly <?php if(isset($user->assembly_user_date) && !empty($user->assembly_user_date)): ?> value="{{ \Carbon\Carbon::parse($user->assembly_user_date)->format('Y-m-d') }}" <?php endif; ?> >
                                        </div>
                                </div>

                            </div>
                        @else
                            <div class="row">
                                <div class="form-group col-xl-3 col-md-3 col-12">
                                    <label>@lang('Launch NFT Owner') </label><br>
                                    <span>@if($user->launch_nft_owner == 1) Yes @else No @endif</span>
                                </div>

                                <div class="form-group col-xl-3 col-md-3 col-12">
                                    <label>@lang('VIP User') </label><br>
                                    <span>@if($user->vip_user == 1) Yes @else No @endif</span>
                                </div>

                                @if($user->fee_status == 2)
                                <div class="form-group col-xl-3 col-md-3 col-12">
                                        <label>@lang('Maintenance Fees') </label><br>
                                        <span>@if($user->fee_status == 2) Verified @else Unverified @endif</span>
                                </div>
                                <div class="form-group col-xl-3 col-md-3 col-12">
                                        <div id="ExpiryDateVarify" <?php if($user->fee_status == 2): ?> style="display: block;" <?php else: ?>style="display: none;" <?php endif; ?>>
                                            <label>@lang('Expiry Date')</label><br>
                                            <span>{{ \Carbon\Carbon::parse($user->maintenance_expiration_date)->format('Y-m-d') }}</span>
                                        </div>
                                </div>
                                @endif     
                                <div class="form-group col-xl-3 col-md- col-12">
                                    <label>@lang('Account deactivated?') </label><br>
                                    <span> @if($user->is_block == 1) Yes @else No @endif</span>
                                </div>          
                            </div>             
                        @endif

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

    {{-- Add Sub Balance MODAL --}}
    <div id="addSubModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span class="type"></span> <span>@lang('Balance')</span></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{route('admin.users.add.sub.balance',$user->id)}}" method="POST">
                    @csrf
                    <input type="hidden" name="act">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Amount')</label>
                            <div class="input-group">
                                <input type="number" step="any" name="amount" class="form-control" placeholder="@lang('Please provide positive amount')" required>
                                <div class="input-group-text" id="currency_type">{{ __($general->cur_text) }}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Wallet Type')</label>
                            <select id="wallet_type" name="wallet_type" class="form-control" required>
                                <option value="" hidden>@lang('Select One')</option>
                                <option value="deposit_wallet">@lang('Deposit Wallet')</option>
                                <option value="interest_wallet">@lang('Reward Cube')</option>
                                <option value="pool_2">@lang('Voucher Cube')</option>
                                <option value="pool_3">@lang('Staking Cube')</option>
                                <option value="pool_4">@lang('NFTs Cube')</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Remark')</label>
                            <textarea class="form-control" placeholder="@lang('Remark')" name="remark" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- Add Family NFT MODAL --}}
    <div id="addFamilyNFTModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></span> <span>@lang('Add FamilyNFT')</span></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{route('admin.users.add.familynft',$user->id)}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Amount')</label>
                            <div class="input-group">
                                <input type="number" step="any" name="amount" class="form-control" placeholder="@lang('Please enter amount')" min="1" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="clickable-from btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Remove Family NFT MODAL --}}
    {{-- <div id="removeFamilyNFTModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></span> <span>@lang('Remove FamilyNFT')</span></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{route('admin.users.remove.familynft',$user->id)}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang("NFT's")</label>
                            <div class="input-group">
                                <input type="number" step="any" name="nfts" class="form-control" placeholder="@lang('Remove NFT Qty')" min="1" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="clickable-from btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}


    <div id="updateSponsorEmail" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></span> <span>@lang('Update Sponsor')</span></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{route('admin.users.update.sponsor',$user->id)}}" id="updateSponsorForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Search & Select New Sponsor Email / Username')</label>
                            <div class="input-group">
                                <select id="sponsorSearch" name="sponsorSearch" class="sponsorSearch" style="width: 100%;" required></select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="userStatusModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @if($user->status == 1)
                        <span>@lang('Ban User')</span>
                        @else
                        <span>@lang('Unban User')</span>
                        @endif
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{route('admin.users.status',$user->id)}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        @if($user->status == 1)
                        <h6 class="mb-2">@lang('If you ban this user he/she won\'t able to access his/her dashboard.')</h6>
                        <div class="form-group">
                            <input type="radio" name="type_ban" id="type_ban_permenant" onclick="ShowHideDarePickDiv()" checked value="permanent"> Permanent
                            <input type="radio" name="type_ban" id="type_ban_temp" onclick="ShowHideDarePickDiv()" value="temporary"> Temporary
                        </div>
                        <div class="form-group" id="tiilBanVarify" style="display: none;">
                            <label>@lang('Till Ban Date')</label>
                            <input name="till_ban_date" id="till_ban_date" type="date" data-multiple-dates-separator=" - " data-language="en" class="datepicker-ban-expirydate form-control" data-position='bottom right' min="<?php echo date("Y-m-d"); ?>">
                        </div>
                        <div class="form-group">
                            <label>@lang('Reason')</label>
                            <textarea class="form-control" name="reason" rows="4" required></textarea>
                        </div>
                        @else
                        <p><span>@lang('Ban reason was'):</span></p>
                        <p>{{ $user->ban_reason }}</p>
                        <h4 class="text-center mt-3">@lang('Are you sure to unban this user?')</h4>
                        @endif
                    </div>
                    <div class="modal-footer">
                        @if($user->status == 1)
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                        @else
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{asset('assets/admin/css/vendor/datepicker.min.css')}}">
@endpush

@push('script')
<script>
         function openRemoveNFTPage(userId) {
        // Correct route name with the "admin" prefix
        var url = "{{ route('admin.users.remove.familynft', ':id') }}".replace(':id', userId);
        window.open(url, '_blank'); // Open the URL in a new tab
    }
    (function($){
    "use strict"
        $('.bal-btn').click(function(){
            var act = $(this).data('act');
            $('#addSubModal').find('input[name=act]').val(act);
            if (act == 'add') {
                $('.type').text('Add');
            }else{
                $('.type').text('Subtract');
            }
        });
        
        let mobileElement = $('.mobile-code');
        $('select[name=country]').change(function(){
            mobileElement.text(`+${$('select[name=country] :selected').data('mobile_code')}`);
        });

        $('select[name=country]').val('{{@$user->country_code}}');
        let dialCode        = $('select[name=country] :selected').data('mobile_code');
        let mobileNumber    = `{{ $user->mobile }}`;
        mobileNumber        = mobileNumber.replace(dialCode,'');
        $('input[name=mobile]').val(mobileNumber);
        mobileElement.text(`+${dialCode}`);


        $('button[type=submit]').click(function() {
            var reportValidity = $(this).closest("form")[0].reportValidity();
            if(reportValidity){
                $(this).attr('disabled', 'disabled');
                $(this).parents('form').submit();
            }
        });


        $('.del_user').on('click',function(){
            var confirm_msg = $(this).attr('confirm_txt');
            if (confirm(confirm_msg)) {
                $(this).parents('form').submit();
            } else {
                return false;
            }
        });


        $("#sponsorSearch").select2({
            minimumInputLength: 3,
            dropdownParent: $("#updateSponsorEmail"),
            ajax: {
            url: "{{route('admin.users.allUserSearch')}}",
            dataType: 'json',
            data: (params) => {
                return {
                q: params.term,
                }
            },
            processResults: (data, params) => {
                const results = data.map(item => {
                return {
                    id: item.id,
                    text: item.email,
                };
                });
                return {
                results: results,
                }
            },
            },
        });

        $('#fee_status').change(function () {
            if ($(this).is(':checked')) {
                $('#maintenance_expiration_date').val('');
                $('#maintenance_note').val('');
                $("#ExpiryDateVarify").show();
                $("#MaintenanceNote").show();
                //$("#ExpiryDateVarified").hide();
            } else {
                $("#ExpiryDateVarify").hide();
                $("#MaintenanceNote").hide();
                //$("#ExpiryDateVarified").hide();
            }
        });

        $('#vip_user').change(function () {
            if ($(this).is(':checked')) {
                $("#vip_user_date").val('');
                $("#VipDateVarify").show();
            } else {
                $("#VipDateVarify").hide();
            }
        });

        $('#assembly_user').change(function () {
            if ($(this).is(':checked')) {
                $("#assembly_user_date").val('');
                $("#AssemblyDateVarify").show();
            } else {
                $("#AssemblyDateVarify").hide();
            }
        });

        $('#wallet_type').on('change', function() {
            if($(this).val() == 'deposit_wallet') {
                $('#currency_type').html('FT');
            } else {
                $('#currency_type').html('{{ __($general->cur_text) }}');
            }
        });

    })(jQuery);

    function ShowHideDarePickDiv() {
        var valueofban = $('input[name=type_ban]:checked').val();
        if(valueofban == 'temporary'){
            $("#tiilBanVarify").show();
        }else if(valueofban == 'permanent'){
            $("#tiilBanVarify").hide();
        }
    }
</script>
@endpush

@push('script-lib')
  <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
  <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush
@push('script')
  <script>
    (function($){
        $('.datepicker-expirydate').datepicker({
            dateFormat: 'yyyy-mm-dd',
            timeFormat: 'hh:ii aa',
        });
        
    })(jQuery)
    
  </script>
@endpush