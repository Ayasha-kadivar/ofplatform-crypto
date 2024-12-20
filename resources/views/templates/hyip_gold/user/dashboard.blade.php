@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if ($user->deposit_wallet <= 0 && $user->interest_wallet <= 0)
                <div class="alert border border--danger" role="alert">
                    <div class="alert__icon d-flex align-items-center text--danger">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <p class="alert__message">
                        <span class="fw-bold">@lang('Empty Balance')</span><br>
                        <small>
                            <i>@lang('Your balance is empty. Please make')
                                <a href="{{ route('user.deposit.index') }}" class="text--base">
                                    @lang('deposit')
                                </a>
                                @lang('for your next investment.')
                            </i>
                        </small>
                    </p>
                </div>
            @endif

            @if ($user->deposits->where('status', 1)->count() == 1 && !$user->invests->count())
                <div class="alert border border--success" role="alert">
                    <div class="alert__icon d-flex align-items-center text--success">
                        <i class="fas fa-check"></i>
                    </div>
                    <p class="alert__message">
                        <span class="fw-bold">@lang('First Deposit')</span><br>
                        <small>
                            <i>
                                <span class="fw-bold">@lang('Congratulations!')</span>
                                @lang('You\'ve made your first deposit successfully. Go to')
                                <a href="{{ route('plan') }}" class="text--base">
                                    @lang('investment plan')
                                </a>
                                @lang('page and invest now')
                            </i>
                        </small>
                    </p>
                </div>
            @endif

            @if ($pendingWithdrawals)
                <div class="alert border border--primary" role="alert">
                    <div class="alert__icon d-flex align-items-center text--primary"><i class="fas fa-spinner"></i>
                    </div>
                    <p class="alert__message">
                        <span class="fw-bold">@lang('Withdrawal Pending')</span><br>
                        <small><i>@lang('Total') {{ showAmount($pendingWithdrawals) }} {{ __($general->cur_text) }}
                                @lang('withdrawal request is pending. Please wait for admin approval. The amount will send to the account which you\'ve provided. See') <a href="{{ route('user.withdraw.history') }}"
                                    class="text--base">@lang('withdrawal history')</a></i></small>
                    </p>
                </div>
            @endif

            @if ($pendingDeposits)
                <div class="alert border border--primary" role="alert">
                    <div class="alert__icon d-flex align-items-center text--primary"><i class="fas fa-spinner"></i>
                    </div>
                    <p class="alert__message">
                        <span class="fw-bold">@lang('Deposit Pending')</span><br>
                        <small><i>@lang('Total') {{ showAmount($pendingDeposits) }} {{ __($general->cur_text) }}
                                @lang('deposit request is pending. Please wait for admin approval. See') <a href="{{ route('user.deposit.history') }}"
                                    class="text--base">@lang('deposit history')</a></i></small>
                    </p>
                </div>
            @endif

            @if (!$user->ts)
                <div class="alert border border--warning" role="alert">
                    <div class="alert__icon d-flex align-items-center text--warning">
                        <i class="fas fa-user-lock"></i>
                    </div>
                    <p class="alert__message">
                        <span class="fw-bold">@lang('2FA Authentication')</span><br>
                        <small><i>@lang('To keep safe your account, Please enable') <a href="{{ route('user.twofactor') }}"
                                    class="text--base">@lang('2FA')</a> @lang('security').</i>
                            @lang('It will make secure your account and balance.')</small>
                    </p>
                </div>
            @endif

            @if ($isHoliday)
                <div class="alert border border--info" role="alert">
                    <div class="alert__icon d-flex align-items-center text--info">
                        <i class="fas fa-toggle-off"></i>
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
                                    class="text--base">@lang('Click here')</a> @lang('to submit KYC information').</i></small>
                    </p>
                </div>
            @elseif($user->kv == 2)
                <div class="alert border border--warning" role="alert">
                    <div class="alert__icon d-flex align-items-center text--warning"><i class="fas fa-user-check"></i></div>
                    <p class="alert__message">
                        <span class="fw-bold">@lang('KYC Verification Pending')</span><br>
                        <small><i>@lang('Your submitted KYC information is pending for admin approval. Please wait till that.') <a href="{{ route('user.kyc.data') }}"
                                    class="text--base">@lang('Click here')</a> @lang('to see your submitted information')</i></small>
                    </p>
                </div>
            @endif
        </div>
    </div>

    <div class="row gy-4">
        <div class="col-xxl-4 col-sm-6">
            <div class="card-item">
                <div class="card-item-body d-flex justify-content-between">
                    <div class="card-item-body-left">
                        <i class="las la-dollar-sign"></i>
                        <p>@lang('Deposit Wallet Balance')</p>
                        <h4>{{ showAmount($user->deposit_wallet) }} {{ __($general->cur_text) }}</h4>
                    </div>
                    <div class="card-item-body-right">
                        <a class="btn btn--outline-base btn--back-bg btn-dashboard"
                            href="{{ route('user.deposit.history') }}">@lang('View All')</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-4 col-sm-6">
            <div class="card-item">
                <div class="card-item-body d-flex justify-content-between">
                    <div class="card-item-body-left">
                        <i class="fas fa-coins"></i>
                        <p>@lang('Investment Wallet Balance')</p>
                        <h4>{{ showAmount($user->interest_wallet) }} {{ __($general->cur_text) }}</h4>
                    </div>
                    <div class="card-item-body-right">
                        <a class="btn btn--outline-base btn-dashboard btn--back-bg"
                            href="{{ route('user.transactions') }}?remark=interest">@lang('View All')</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-4 col-sm-6">
            <div class="card-item">
                <div class="card-item-body d-flex justify-content-between">
                    <div class="card-item-body-left">
                        <i class="fas fa-chart-area"></i>
                        <p>@lang('Total Invest') </p>
                        <h4>{{ showAmount($totalInvest) }} {{ __($general->cur_text) }}</h4>
                    </div>
                    <div class="card-item-body-right">
                        <a class="btn btn--outline-base btn--back-bg btn-dashboard"
                            href="{{ route('user.invest.statistics') }}">@lang('View All')</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-4 col-sm-6">
            <div class="card-item">
                <div class="card-item-body d-flex justify-content-between">
                    <div class="card-item-body-left">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <p>@lang('Total Deposit') </p>
                        <h4>{{ showAmount($user->deposits->where('status', 1)->sum('amount')) }}
                            {{ __($general->cur_text) }}
                        </h4>
                    </div>
                    <div class="card-item-body-right">
                        <a class="btn btn--outline-base btn--back-bg btn-dashboard"
                            href="{{ route('user.deposit.history') }}">@lang('View All')</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-4 col-sm-6 ">
            <div class="card-item">
                <div class="card-item-body d-flex justify-content-between">
                    <div class="card-item-body-left">
                        <i class="las la-cloud-download-alt"></i>
                        <p>@lang('Total Withdraw')</p>

                        <h4>{{ showAmount($user->withdrawals->where('status', 1)->sum('amount')) }}
                            {{ __($general->cur_text) }}</h4>
                    </div>
                    <div class="card-item-body-right">
                        <a class="btn btn--outline-base btn--back-bg btn-dashboard"
                            href="{{ route('user.withdraw.history') }}">@lang('View All')</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-4 col-sm-6">
            <div class="card-item">
                <div class="card-item-body d-flex justify-content-between">
                    <div class="card-item-body-left">
                        <i class="fas fa-wallet"></i>
                        <p>@lang('Referral Earning')</p>
                        <h4>{{ showAmount(0.0) }} {{ __($general->cur_text) }}</h4>
                    </div>
                    <div class="card-item-body-right">
                        <a class="btn btn--outline-base btn--back-bg btn-dashboard"
                            href="{{ route('user.transactions') }}?remark=referral_commission">@lang('View All')</a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="dashboard-table pt-60">
        <h2 class="dashboard-table-title mb-30">@lang('My Transaction')</h2>
        <table class="table transection__table table--responsive--xl">
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
                            {{ showDatetime($trx->created_at, 'd/m/Y') }}
                        </td>
                        <td><span class="text-primary">{{ $trx->trx }}</span></td>

                        <td>
                            @if ($trx->trx_type == '+')
                                <span class="text-success">+
                                    {{ $general->cur_sym }}{{ showAmount($trx->amount) }}</span>
                            @else
                                <span class="text-danger">-
                                    {{ $general->cur_sym }}{{ showAmount($trx->amount) }}</span>
                            @endif
                        </td>
                        <td>
                            @if ($trx->wallet_type == 'deposit_wallet')
                                <span class="badge badge--info">@lang('Deposit Wallet')</span>
                            @else
                                <span class="badge badge--primary">@lang('Interest Wallet')</span>
                            @endif
                        </td>
                        <td>{{ $trx->details }}</td>
                        <td><span>
                                {{ $general->cur_sym }}{{ showAmount($trx->post_balance) }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%" class="text-center">
                            @lang('No Transaction Found')
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    </div><!-- row end -->
@endsection
