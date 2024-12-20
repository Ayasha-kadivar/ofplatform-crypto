@extends($activeTemplate.'layouts.master')
@section('content')
<div class="pb-60 pt-60">
    <div class="container">

        <div class="row notice"></div>
        
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if ($user->deposit_wallet <= 0 && $user->interest_wallet <= 0)
                    <div class="alert border border--danger" role="alert">
                        <div class="alert__icon d-flex align-items-center text--danger"><i
                                class="fas fa-exclamation-triangle"></i></div>
                        <p class="alert__message">
                            <span class="fw-bold">@lang('Empty Balance')</span><br>
                            <small><i>@lang('Your balance is empty. Please make') <a href="{{ route('user.deposit.index') }}"
                                        class="link-color">@lang('deposit')</a> @lang('for your next investment.')</i></small>
                        </p>
                    </div>
                @endif

                @if ($user->deposits->where('status',1)->count() == 1 && !$user->invests->count())
                    <div class="alert border border--success" role="alert">
                        <div class="alert__icon d-flex align-items-center text--success"><i class="fas fa-check"></i>
                        </div>
                        <p class="alert__message">
                            <span class="fw-bold">@lang('First Deposit')</span><br>
                            <small><i><span class="fw-bold">@lang('Congratulations!')</span> @lang('You\'ve made your first deposit successfully. Go to') <a
                                        href="{{ route('plan') }}" class="link-color">@lang('investment plan')</a>
                                    @lang('page and invest now')</i></small>
                        </p>
                    </div>
                @endif

                @if ($pendingWithdrawals)
                    <div class="alert border border--primary" role="alert">
                        <div class="alert__icon d-flex align-items-center text--primary"><i class="fas fa-spinner"></i>
                        </div>
                        <p class="alert__message">
                            <span class="fw-bold">@lang('Withdrawal Pending')</span><br>
                            <small><i>@lang('Total') {{ showAmount($pendingWithdrawals) }} {{ $general->cur_text }}
                                    @lang('withdrawal request is pending. Please wait for admin approval. The amount will send to the account which you\'ve provided. See') <a href="{{ route('user.withdraw.history') }}"
                                        class="link-color">@lang('withdrawal history')</a></i></small>
                        </p>
                    </div>
                @endif

                @if ($pendingDeposits)
                    <div class="alert border border--primary" role="alert">
                        <div class="alert__icon d-flex align-items-center text--primary"><i class="fas fa-spinner"></i>
                        </div>
                        <p class="alert__message">
                            <span class="fw-bold">@lang('Deposit Pending')</span><br>
                            <small><i>@lang('Total') {{ showAmount($pendingDeposits) }} {{ $general->cur_text }}
                                    @lang('deposit request is pending. Please wait for admin approval. See') <a href="{{ route('user.deposit.history') }}"
                                        class="link-color">@lang('deposit history')</a></i></small>
                        </p>
                    </div>
                @endif

                @if (!$user->ts)
                    <div class="alert border border--warning" role="alert">
                        <div class="alert__icon d-flex align-items-center text--warning"><i
                                class="fas fa-user-lock"></i></div>
                        <p class="alert__message">
                            <span class="fw-bold">@lang('2FA Authentication')</span><br>
                            <small><i>@lang('To keep safe your account, Please enable') <a href="{{ route('user.twofactor') }}"
                                        class="link-color">@lang('2FA')</a> @lang('security').</i>
                                @lang('It will make secure your account and balance.')</small>
                        </p>
                    </div>
                @endif

                @if ($isHoliday)
                    <div class="alert border border--info" role="alert">
                        <div class="alert__icon d-flex align-items-center text--info"><i class="fas fa-toggle-off"></i>
                        </div>
                        <p class="alert__message">
                            <span class="fw-bold">@lang('Holiday')</span><br>
                            <small><i>@lang('Today is holiday on this system. You\'ll not get any interest today from this system. Also you\'re unable to make withdrawal request today.') <br> @lang('The next working day is coming after') <span id="counter"
                                        class="fw-bold text--primary fs--15px"></span></i></small>
                        </p>
                    </div>
                @endif

                @if ($user->kv == 0)
                    <div class="alert border border--info" role="alert">
                        <div class="alert__icon d-flex align-items-center text--info"><i class="fas fa-file-signature"></i>
                        </div>
                        <p class="alert__message">
                            <span class="fw-bold">@lang('KYC Verification Required')</span><br>
                            <small><i>@lang('Please submit the required KYC information to verify yourself. Otherwise, you couldn\'t make any withdrawal requests to the system.') <a href="{{ route('user.kyc.form') }}"
                                        class="link-color">@lang('Click here')</a> @lang('to submit KYC information').</i></small>
                        </p>
                    </div>
                @elseif($user->kv == 2)
                    <div class="alert border border--warning" role="alert">
                        <div class="alert__icon d-flex align-items-center text--warning"><i
                                class="fas fa-user-check"></i></div>
                        <p class="alert__message">
                            <span class="fw-bold">@lang('KYC Verification Pending')</span><br>
                            <small><i>@lang('Your submitted KYC information is pending for admin approval. Please wait till that.') <a href="{{ route('user.kyc.data') }}"
                                        class="link-color">@lang('Click here')</a> @lang('to see your submitted information')</i></small>
                        </p>
                    </div>
                @endif
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-12 mt-lg-0 mt-5">
                <div class="row mb-none-30">
                    <div class="col-xl-4 col-sm-6 mb-30">
                        <div class="d-widget d-flex justify-content-between gap-5">
                            <div class="left-content">
                                <span class="caption">@lang('Deposit Wallet Balance')</span>
                                <h4 class="currency-amount">{{ $general->cur_sym }}{{ getAmount($user->deposit_wallet) }}</h4>
                            </div>
                            <div class="icon ms-auto">
                                <i class="las la-dollar-sign"></i>
                            </div>
                        </div><!-- d-widget-two end -->
                    </div>
                    <div class="col-xl-4 col-sm-6 mb-30">
                        <div class="d-widget d-flex justify-content-between gap-5">
                            <div class="left-content">
                                <span class="caption">@lang('Interest Wallet Balance')</span>
                                <h4 class="currency-amount">
                                    {{ $general->cur_sym }}{{ getAmount($user->interest_wallet) }}</h4>
                            </div>
                            <div class="icon ms-auto">
                                <i class="las la-wallet"></i>
                            </div>
                        </div><!-- d-widget-two end -->
                    </div>
                    <div class="col-xl-4 col-sm-6 mb-30">
                        <div class="d-widget d-flex justify-content-between gap-5">
                            <div class="left-content">
                                <span class="caption">@lang('Total Invest')</span>
                                <h4 class="currency-amount">
                                    {{ $general->cur_sym }}{{ getAmount($totalInvest) }}
                                </h4>
                            </div>
                            <div class="icon ms-auto">
                                <i class="las la-cubes "></i>
                            </div>
                        </div><!-- d-widget-two end -->
                    </div>
                    <div class="col-xl-4 col-sm-6 mb-30">
                        <div class="d-widget d-flex justify-content-between gap-5">
                            <div class="left-content">
                                <span class="caption">@lang('Total Deposit')</span>
                                <h4 class="currency-amount">
                                    {{ $general->cur_sym }}{{ getAmount($user->deposits->where('status',1)->sum('amount')) }}
                                </h4>
                            </div>
                            <div class="icon ms-auto">
                                <i class="las la-credit-card"></i>
                            </div>
                        </div><!-- d-widget-two end -->
                    </div>
                    <div class="col-xl-4 col-sm-6 mb-30">
                        <div class="d-widget d-flex justify-content-between gap-5">
                            <div class="left-content">
                                <span class="caption">@lang('Total Withdraw')</span>
                                <h4 class="currency-amount">
                                    {{ $general->cur_sym }}{{ getAmount($user->withdrawals->where('status',1)->sum('amount')) }}
                                </h4>
                            </div>
                            <div class="icon ms-auto">
                                <i class="las la-cloud-download-alt"></i>
                            </div>
                        </div><!-- d-widget-two end -->
                    </div>
                    <div class="col-xl-4 col-sm-6 mb-30">
                        <div class="d-widget d-flex justify-content-between gap-5">
                            <div class="left-content">
                                <span class="caption">@lang('Referral Earnings')</span>
                                <h4 class="currency-amount">
                                    {{ $general->cur_sym }}{{ showAmount($referral_earnings) }}
                                </h4>
                            </div>
                            <div class="icon ms-auto">
                                <i class="las la-user-friends"></i>
                            </div>
                        </div><!-- d-widget-two end -->
                    </div>
                </div><!-- row end -->
                <div class="row mt-50">
                    <div class="col-lg-12">
                        <div class="table-responsive--md">
                            <table class="table style--two">
                                <thead>
                                    <tr>
                                        <th>@lang('Date')</th>
                                        <th>@lang('Transaction ID')</th>
                                        <th>@lang('Amount')</th>
                                        <th>@lang('Wallet')</th>
                                        <th>@lang('Details')</th>
                                        <th>@lang('Post Balance')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $trx)
                                        <tr>
                                            <td>
                                                {{ showDatetime($trx->created_at,'d/m/Y') }}
                                            </td>
                                            <td><span
                                                    class="text-primary">{{ $trx->trx }}</span></td>

                                            <td>
                                                @if($trx->trx_type == '+')
                                                    <span class="text-success">+
                                                        {{ __($general->cur_sym) }}{{ getAmount($trx->amount) }}</span>
                                                @else
                                                    <span class="text-danger">-
                                                        {{ __($general->cur_sym) }}{{ getAmount($trx->amount) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($trx->wallet_type == 'deposit_wallet')
                                                    <span class="badge bg-info">@lang('Deposit Wallet')</span>
                                                @else
                                                    <span class="badge bg-primary">@lang('Interest Wallet')</span>
                                                @endif
                                            </td>
                                            <td>{{ $trx->details }}</td>
                                            <td><span>
                                                    {{ __($general->cur_sym) }}{{ getAmount($trx->post_balance) }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%" class="text-center">
                                                {{ __('No Transaction Found') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- row end -->
            </div>
        </div>
    </div>
</div>
@endsection
@push('style')
    <style>
        #copyBoard {
            cursor: pointer;
        }

    </style>
@endpush

@push('script')
 <script>
        'use strict';
        (function ($) {
            @if($isHoliday)
                function createCountDown(elementId, sec) {
                    var tms = sec;
                    var x = setInterval(function () {
                        var distance = tms * 1000;
                        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                        var days = `<span>${days}d</span>`;
                        var hours = `<span>${hours}h</span>`;
                        var minutes = `<span>${minutes}m</span>`;
                        var seconds = `<span>${seconds}s</span>`;
                        document.getElementById(elementId).innerHTML = days +' '+ hours + " " + minutes + " " + seconds;
                        if (distance < 0) {
                            clearInterval(x);
                            document.getElementById(elementId).innerHTML = "COMPLETE";
                        }
                        tms--;
                    }, 1000);
                }

                createCountDown('counter', {{\Carbon\Carbon::parse($nextWorkingDay)->diffInSeconds()}});
            @endif
        })(jQuery);
 </script>
@endpush