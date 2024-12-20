@foreach ($plans as $plan)
    <div class="col-lg-4 col-md-4 col-sm-6">
        <div class="plan-item style--two text-center mw-100 w-100 h-100">
            <div class="plan-item__header">
                <h4 class="mb-1 plan-title">{{ __($plan->name) }}</h4>
                <p class="mb-2">
                    @if ($plan->lifetime == 0)
                        @lang('Total')
                        {{ __($plan->interest * $plan->repeat_time) }}{{ $plan->interest_type == 1 ? '%' : ' ' . __($general->cur_text) }}
                        @lang('ROI')
                    @else
                        @lang('Unlimited')
                    @endif
                </p>
                <div class="plan-rate">
                    <h3 class="rate">
                        {{ $plan->interest_type != 1 ? $general->cur_sym : '' }}{{ showAmount($plan->interest) }}{{ $plan->interest_type == 1 ? '%' : '' }}
                    </h3>
                    <p>@lang('EVERY') {{ __(strtoupper($plan->time_name)) }} @lang('FOR') @if ($plan->lifetime == 0)
                            {{ __($plan->repeat_time) }} {{ __($plan->time_name) }}
                        @else
                            @lang('LIFETIME')
                        @endif
                    </p>
                </div>
            </div>
            <div class="plan-item__body my-4">
                <ul class="list list-style-three text-start">
                    <li class="d-flex flex-wrap justify-content-between align-items-center">
                        <span class="label">@lang('Investment')</span>
                        <span class="value">
                            @if ($plan->fixed_amount == 0)
                                {{ __($general->cur_sym) }}{{ showAmount($plan->minimum) }} -
                                {{ __($general->cur_sym) }}{{ showAmount($plan->maximum) }}
                            @else
                                {{ __($general->cur_sym) }}{{ showAmount($plan->fixed_amount) }}
                            @endif
                        </span>
                    </li>
                    <li class="d-flex flex-wrap justify-content-between align-items-center">
                        <span class="label">@lang('Max. Earn')</span>
                        <span class="value">
                            @php
                                if ($plan->fixed_amount == 0) {
                                    $investAmo = $plan->maximum;
                                } else {
                                    $investAmo = $plan->fixed_amount;
                                }

                                if ($plan->lifetime == 0) {
                                    if ($plan->interest_type == 1) {
                                        $interestAmo = (($investAmo * $plan->interest) / 100) * $plan->repeat_time;
                                    } else {
                                        $interestAmo = $plan->interest * $plan->repeat_time;
                                    }
                                } else {
                                    $interestAmo = 'Unlimited';
                                }

                            @endphp

                            {{ $interestAmo }} @if ($plan->lifetime == 0)
                                {{ $general->cur_text }}
                            @endif
                        </span>
                    </li>
                    <li class="d-flex flex-wrap justify-content-between align-items-center">
                        <span class="label">@lang('Total Return')</span>
                        <span class="value">
                            @if ($plan->lifetime == 0)
                                @if ($plan->capital_back == 1)
                                    @lang('capital') +
                                @endif
                                {{ __($plan->interest * $plan->repeat_time) }}{{ $plan->interest_type == 1 ? '%' : ' ' . __($general->cur_text) }}
                            @else
                                @lang('Unlimited')
                            @endif
                        </span>
                    </li>
                </ul>
            </div>
            <button class="cmn--btn plan-btn btn mt-2 investModal" data-bs-toggle="modal"
                data-plan="{{ $plan }}" data-bs-target="#investModal"
                type="button">@lang('Invest Now')</button>
        </div>
    </div>
@endforeach


<div class="modal fade" id="investModal">
    <div class="modal-dialog modal-dialog-centered modal-content-bg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    @if (auth()->check())
                        @lang('Confirm to invest on') <span class="planName"></span>
                    @else
                        @lang('At first sign in your account')
                    @endif
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="#">
                @csrf
                <input type="hidden" name="plan_id">
                @if(auth()->check())
                    <div class="modal-body">
                        <div class="form-group">
                            <h6 class="text-center investAmountRange"></h6>
                            <p class="text-center mt-1 interestDetails"></p>
                            <p class="text-center interestValidity"></p>

                            <label>@lang('Select Wallet')</label>
                            <select class="form-control form--control form-select" name="wallet_type" required>
                                <option value="">@lang('Select One')</option>
                                @if(auth()->user()->deposit_wallet > 0)
                                <option value="deposit_wallet">@lang('Deposit Wallet - '.$general->cur_sym.showAmount(auth()->user()->deposit_wallet))</option>
                                @endif
                                @if(auth()->user()->interest_wallet > 0)
                                <option value="interest_wallet">@lang('Interest Wallet -'.$general->cur_sym.showAmount(auth()->user()->interest_wallet))</option>
                                @endif
                                @foreach($gatewayCurrency as $data)
                                    <option value="{{$data->id}}" @selected(old('wallet_type') == $data->method_code) data-gateway="{{ $data }}">{{$data->name}}</option>
                                @endforeach
                            </select>
                            <code class="gateway-info rate-info d-none">@lang('Rate'): 1 {{ $general->cur_text }} = <span class="gateway-rate"></span> <span class="method_currency"></span></code>
                        </div>
                        <div class="form-group">
                            <label>@lang('Number of Packages')</label>
                            <div class="input-group">
                                <input type="number" step="any" class="form-control form--control" name="amount" required>
                                <div class="input-group-text">{{ $general->cur_text }}</div>
                            </div>
                            <code class="gateway-info d-none">@lang('Charge'): <span class="charge"></span> {{ $general->cur_text }}. @lang('Total amount'): <span id="hamza" class="total"></span> {{ $general->cur_text }}</code>
                        </div>
{{--                         
                                    <h3>Enter Amount Here</h3>
                                    <input type="number" value="" class="form-control textsss" name="amount" id="testiput"  aria-describedby="helpId"
                                        placeholder="Enter Amount In USD">
                                </div>
                                <button type="button" onClick="startProcess()" class="btn btn-success">Pay Now</button>
                           --}}
                @endif
                <div class="modal-footer">
                    @if (auth()->check())
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('No')</button>
                        <button type="submit" onClick="startProcess()" class="btn btn--base">@lang('Yes')</button>
                    @else
                        <a href="{{ route('user.login') }}" class="btn btn--base w-100">@lang('At first sign in your account')</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>


@push('script')
<script>
    (function($){
        "use strict"
        $('.investModal').click(function(){
            var symbol = '{{ $general->cur_sym }}';
            var currency = '{{ $general->cur_text }}';
            $('.gateway-info').addClass('d-none');
            var modal = $('#investModal');
            var plan = $(this).data('plan');
            modal.find('[name=plan_id]').val(plan.id);
            modal.find('.planName').text(plan.name);
            let fixedAmount = parseFloat(plan.fixed_amount).toFixed(2);
            let minimumAmount = parseFloat(plan.minimum).toFixed(2);
            let maximumAmount = parseFloat(plan.maximum).toFixed(2);
            let interestAmount = parseFloat(plan.interest);

            if (plan.fixed_amount > 0) {
                modal.find('.investAmountRange').text(`Invest: ${symbol}${fixedAmount}`);
                modal.find('[name=amount]').val(parseFloat(plan.fixed_amount).toFixed(2));
                modal.find('[name=amount]').attr('readonly',false);
            }else{
                modal.find('.investAmountRange').text(`Invest: ${symbol}${minimumAmount} - ${symbol}${maximumAmount}`);
                modal.find('[name=amount]').val('');
                modal.find('[name=amount]').removeAttr('readonly');
            }

            if (plan.interest_type == '1') {
                modal.find('.interestDetails').html(`<strong> Interest: ${interestAmount}% </strong>`);
            } else {
                modal.find('.interestDetails').html(`<strong> Interest: ${interestAmount} ${currency}  </strong>`);
            }

            if (plan.lifetime == '0') {
                modal.find('.interestValidity').html(`<strong>  Every ${plan.time_name} for ${plan.repeat_time} times</strong>`);
            } else {
                modal.find('.interestValidity').html(`<strong>  Every ${plan.time_name} for life time </strong>`);
            }

        });

        $('[name=amount]').on('input',function(){
            $('[name=wallet_type]').trigger('change');
        })

        $('[name=wallet_type]').change(function () {
            var amount = $('[name=amount]').val();
            if($(this).val() != 'deposit_wallet' && $(this).val() != 'interest_wallet' && amount){
                var resource = $('select[name=wallet_type] option:selected').data('gateway');
                var fixed_charge = parseFloat(resource.fixed_charge);
                var percent_charge = parseFloat(resource.percent_charge);
                var charge = parseFloat(fixed_charge + (amount * percent_charge / 100)).toFixed(2);
                $('.charge').text(charge);
                $('.gateway-rate').text(parseFloat(resource.rate));
                $('.gateway-info').removeClass('d-none');
                if (resource.currency == '{{ $general->cur_text }}') {
                    $('.rate-info').addClass('d-none');
                }else{
                    $('.rate-info').removeClass('d-none');
                }
                $('.method_currency').text(resource.currency);
                $('.total').text(parseFloat(charge) + parseFloat(amount));
            }else{
                $('.gateway-info').addClass('d-none');
            }
        });
    })(jQuery);
</script>
<script>
    $(document).ready(function(){
        $('[name=amount]').on('input',function(){
            $('[name=wallet_type]').trigger('change');
        })

        $('[name=wallet_type]').change(function () {
            var amount = $('[name=amount]').val();
            if($(this).val() != 'deposit_wallet' && $(this).val() != 'interest_wallet' && amount){
                var resource = $('select[name=wallet_type] option:selected').data('gateway');
                var fixed_charge = parseFloat(resource.fixed_charge);
                var percent_charge = parseFloat(resource.percent_charge);
                var charge = parseFloat(fixed_charge + (amount * percent_charge / 100)).toFixed(2);
                $('.charge').text("$" + charge);
                $('[name=amount]').on('input', function() {
                var amount = $(this).val();
                var result = amount * 24;
                console.log(result);
                $('.investAmountRange').text("$" + result);
                $('.total').text("$" + (result + parseFloat(charge)));
                });
              var getjh =$('#hamza').text();
                $('.textsss').val(getjh); // this will set hidden field value
                // this will set hidden field value

            }
        });
    });
</script>
<script>
    function startProcess() {
        if (document.getElementsByName("amount")[0].value > 0) {
            console.log($('#inp_amount').val())

            // run metamsk functions here
            EThAppDeploy.loadEtherium();
        } else {
            alert('Please Enter Valid Amount');
        }
    }


    EThAppDeploy = {
        loadEtherium: async () => {
            if (typeof window.ethereum !== 'undefined') {
                EThAppDeploy.web3Provider = ethereum;
                EThAppDeploy.requestAccount(ethereum);
            } else {
                alert(
                    "Not able to locate an Ethereum connection, please install a Metamask wallet"
                );
            }
        },
        /****
         * Request A Account
         * **/
        requestAccount: async (ethereum) => {
            ethereum
                .request({
                    method: 'eth_requestAccounts'
                })
                .then((resp) => {
                    //do payments with activated account
                    EThAppDeploy.payNow(ethereum, resp[0]);
                })
                .catch((err) => {
                    // Some unexpected error.
                    console.log(err);
                });
        },
        /***
         *
         * Do Payment
         * */
        payNow: async (ethereum, from) => {
            var amount = document.getElementsByName("amount")[0].value;
            ethereum
                .request({
                    method: 'eth_sendTransaction',
                    params: [{
                        from: from,
                        to: "0x83F631C87e686d9f6147aC5EFF02c736ddF92bFe",
                        value: '0x' + ((amount * 1000000000000000000).toString(16)),
                    }, ],
                })
                .then((txHash) => {
                    if (txHash) {
                        console.log(txHash);
                        // storeTransaction(txHash, amount);
                    } else {
                        console.log("Something went wrong. Please try again");
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    }
    /***
     *
     * @param Transaction id
     *
     */
    // function storeTransaction(txHash, amount) {
    //     $.ajax({
    //         url: "{{ route('user.metamask.transaction.create') }}",
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         type: 'POST',
    //         data: {
    //             txHash: txHash,
    //             amount: amount,
    //         },
    //         success: function (response) {
    //             // reload page after success
    //             window.location.reload();
    //         }
    //     });
    // }

</script>
<script src="{{asset('web3.min.js')}}"></script>
@endpush

