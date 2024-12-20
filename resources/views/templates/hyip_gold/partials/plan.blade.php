@foreach ($plans as $k => $plan)
    @php
        $timeName = \App\Models\TimeSetting::where('time', $plan->time)->first();
    @endphp
    <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-6">
        <div class="price-table__item">
            <span class="price-bg-before"></span>
            <div class="price-table__item-parsent">
                <div class="hex contact-meta__hex">
                    <div class="inner-hex"></div>
                    <span class="hex-icon">
                        {{ $plan->interest_type != 1 ? $general->cur_sym : '' }}{{ showAmount($plan->interest) }}{{ $plan->interest_type == 1 ? '%' : '' }}
                    </span>
                </div>
            </div>
            <h4 class="price-plan text-center">
                {{ __($plan->name) }}
            </h4>
            <div class="price-table__item-content text-center">
                <h4 class="price-table__item-content-title">
                    @lang('Every') {{ __($timeName->name) }}
                </h4>
                <div class="price-table__item-content-price">
                    @if ($plan->fixed_amount == 0)
                        <p>@lang('Min :') {{ $general->cur_sym }}{{ showAmount($plan->minimum) }}</p>
                        <p>@lang('Max :') {{ $general->cur_sym }}{{ showAmount($plan->maximum) }}</p>
                    @else
                        <p>@lang('Fiexd :') {{ $general->cur_sym }}{{ showAmount($plan->fixed_amount) }}</p>
                    @endif
                </div>
                <ul class="price-table__item-content-featurs">
                    <li>
                        <i class="lab la-bitcoin"></i>
                        @lang('For')
                        @if ($plan->lifetime == 0)
                            {{ __($plan->repeat_time) }} {{ __($timeName->name) }}
                        @else
                            @lang('Lifetime')
                        @endif
                    </li>
                    <li class="d-block">
                        <i class="lab la-bitcoin"></i>
                        @if ($plan->lifetime == 0)
                            @lang('Total')
                            {{ $plan->interest_type != 1 ? $general->cur_sym : '' }}{{ __($plan->interest * $plan->repeat_time) }}{{ $plan->interest_type == 1 ? '%' : '' }}
                            @if ($plan->capital_back == 1)
                                +
                                <span class="badge badge--success ">
                                    @lang('Capital')
                                </span>
                            @endif
                        @else
                            @lang('Lifetime Earning')
                        @endif

                    </li>
                </ul>
                <div class="price-btn">
                    <a href="javascript:void(0)" class="btn btn--base  investModal" data-bs-toggle="modal"
                        data-plan={{ $plan }} data-bs-target="#investModal">
                        @lang('Invest Now')
                    </a>
                </div>
            </div>
        </div>
    </div>
@endforeach


<div class="modal custom--modal fade" id="investModal">
    <div class="modal-dialog modal-dialog-centered modal-content-bg">
        <div class="modal-content">
            <div class="modal-header">
                @if (auth()->check())
                    <strong class="modal-title " id="ModalLabel">
                        @lang('Confirm to invest on') <span class="planName"></span>
                    </strong>
                @else
                    <strong class="modal-title " id="ModalLabel">
                        @lang('At first sign in your account')
                    </strong>
                @endif
                <span class="close" class="text--base" data-bs-dismiss="modal">
                    <i class="las la-times"></i>
                </span>
            </div>
            <form action="{{ route('user.invest.submit') }}" method="post">
                @csrf
                <input type="hidden" name="plan_id">
                @if (auth()->check())
                    <div class="modal-body">
                        <div class="form-group">
                            <h6 class="text-center investAmountRange"></h6>
                            <p class="text-center mt-1 interestDetails"></p>
                            <p class="text-center interestValidity"></p>

                            <label>@lang('Select Wallet')</label>
                            <select class="form--control  form-select" name="wallet_type" required>
                                <option value="">@lang('Select One')</option>
                                @if (auth()->user()->deposit_wallet > 0)
                                    <option value="deposit_wallet">@lang('Deposit Wallet - ' . $general->cur_sym . showAmount(auth()->user()->deposit_wallet))</option>
                                @endif
                                @if (auth()->user()->interest_wallet > 0)
                                    <option value="interest_wallet">@lang('Interest Wallet -' . $general->cur_sym . showAmount(auth()->user()->interest_wallet))</option>
                                @endif
                                @foreach ($gatewayCurrency as $data)
                                    <option value="{{ $data->id }}" @selected(old('wallet_type') == $data->method_code)
                                        data-gateway="{{ $data }}">{{ $data->name }}</option>
                                @endforeach
                            </select>
                            <code class="gateway-info rate-info d-none">@lang('Rate'): 1 {{ $general->cur_text }}
                                = <span class="rate"></span> <span class="method_currency"></span></code>
                        </div>
                        <div class="form-group">
                            <label>@lang('Invest Amount')</label>
                            <div class="input-group">
                                <input type="number" step="any" class="form-control form--control" name="amount"
                                    required>
                                <span class="input-group-text bg--base">{{ $general->cur_text }}</span>
                            </div>
                            <code class="gateway-info d-none">@lang('Charge'): <span class="charge"></span>
                                {{ $general->cur_text }}. @lang('Total amount'): <span class="total"></span>
                                {{ $general->cur_text }}</code>
                        </div>
                    </div>
                @endif
                <div class="modal-footer">
                    @if (auth()->check())
                        <button type="button" class="btn btn--danger btn-md"
                            data-bs-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn--base btn-md">@lang('Yes')</button>
                    @else
                        <a href="{{ route('user.login') }}"
                            class="btn btn--outline-base btn-md h-45 w-100 text-center">@lang('At first sign in your account')</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>


@push('script')
    <script>
        (function($) {
            "use strict"
            $('.investModal').click(function() {
                var symbol = '{{ $general->cur_sym }}';
                var currency = '{{ $general->cur_text }}';
                $('.gateway-info').addClass('d-none');
                var modal = $('#investModal');
                var plan = $(this).data('plan');
                modal.find('.planName').text(plan.name)
                modal.find('[name=plan_id]').val(plan.id);
                let fixedAmount = parseFloat(plan.fixed_amount).toFixed(2);
                let minimumAmount = parseFloat(plan.minimum).toFixed(2);
                let maximumAmount = parseFloat(plan.maximum).toFixed(2);
                let interestAmount = parseFloat(plan.interest);

                if (plan.fixed_amount > 0) {
                    modal.find('.investAmountRange').text(`Invest: ${symbol}${fixedAmount}`);
                    modal.find('[name=amount]').val(fixedAmount);
                    modal.find('[name=amount]').attr('readonly', true);
                } else {
                    modal.find('.investAmountRange').text(
                        `Invest: ${symbol}${minimumAmount} - ${symbol}${maximumAmount}`);
                    modal.find('[name=amount]').val('');
                    modal.find('[name=amount]').removeAttr('readonly');
                }

                if (plan.interest_type == '1') {
                    modal.find('.interestDetails').html(`<strong> Interest: ${interestAmount}% </strong>`);
                } else {
                    modal.find('.interestDetails').html(
                        `<strong> Interest: ${interestAmount} ${currency}  </strong>`);
                }
                if (plan.lifetime_status == '0') {
                    modal.find('.interestValidity').html(
                        `<strong>  Per ${plan.time} hours ,  ${plan.repeat_time} times</strong>`);
                } else {
                    modal.find('.interestValidity').html(
                        `<strong>  Per ${plan.time} hours,  life time </strong>`);
                }

            });

            $('[name=amount]').on('input', function() {
                $('[name=wallet_type]').trigger('change');
            })

            $('[name=wallet_type]').change(function() {
                var amount = $('[name=amount]').val();
                if ($(this).val() != 'deposit_wallet' && $(this).val() != 'interest_wallet' && amount) {
                    var resource = $('select[name=wallet_type] option:selected').data('gateway');
                    var fixed_charge = parseFloat(resource.fixed_charge);
                    var percent_charge = parseFloat(resource.percent_charge);
                    var charge = parseFloat(fixed_charge + (amount * percent_charge / 100)).toFixed(2);
                    $('.charge').text(charge);
                    $('.rate').text(parseFloat(resource.rate));
                    $('.gateway-info').removeClass('d-none');
                    if (resource.currency == '{{ $general->cur_text }}') {
                        $('.rate-info').addClass('d-none');
                    } else {
                        $('.rate-info').removeClass('d-none');
                    }
                    $('.method_currency').text(resource.currency);
                    $('.total').text(parseFloat(charge) + parseFloat(amount));
                } else {
                    $('.gateway-info').addClass('d-none');
                }
            });
        })(jQuery);
    </script>
@endpush
