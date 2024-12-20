@extends($activeTemplate.'layouts.master')
@section('content')
<style>
    .form-label{
        font-weight: 600;
    }
</style>
<div class="dashboard-inner">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="mb-4">
                <h3 class="mb-2 new--color--theme">@lang('Deposit FT')</h3>
                {{-- <p>@lang('Add funds using our system\'s gateway. The deposited amount will be credited to the deposit wallet. You\'ll just make investments from this wallet.')</p> --}}
                <p>@lang('Kidly follow instructions bellow to deposit FT from your decentralized wallet to Deposit wallet on platform')</p>
            </div>
            <div class="text-end mb-3">
                <a href="{{ route('user.deposit.history') }}" class="btn btn--secondary btn--smd"><i class="las la-long-arrow-alt-left"></i> @lang('Deposit History')</a>
            </div>
            <form action="{{route('user.deposit.insert')}}" method="post">
                @csrf
                <input type="hidden" name="method_code">
                <input type="hidden" name="currency">
                <div class="card">
                    <div class="card-body">

                        <div class="row">

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">@lang('Payment Method')</label>
                                    <select class="form-select form-control form--control" name="gateway" required>
                                        @foreach($gatewayCurrency as $data)
                                        <option value="{{$data->method_code}}" @selected(request('gateway') == $data->method_code) data-gateway="{{ $data }}">{{$data->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                              <h5>Kindly send your FT to following wallet</h5><br>


                              <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                  <button class="accordion-button display-address-type" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                      Manual FT Address
                                  </button>
                                </h2>
                                <div id="flush-collapseOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                  <div class="accordion-body"><b><h6 class="display-address-id">0x5139724F0BF47604F52735d3447714Eac99711f7</h6></b></div>
                                </div>
                              </div>

                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">@lang('Paste transaction HASH ID in field bellow')</label>
                                    <div class="input-group">
                                        <input type="text" name="deposit_hash" class="form-control form--control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label" style="width: 100%;">@lang("Amount (separate only decimals with . - don't use , in field bellow)") <span class='dis-amt' style="font-size:x-large;font-weight:600;color:green;float: right;"></span></label>
                                    <div class="input-group">
                                        <input type="number" step="any" name="amount" class="form-control form--control" value="{{ request('amount') }}" autocomplete="off" required>
                                        {{-- <span class="input-group-text">{{ $general->cur_text }}</span> --}}
                                        <span class="input-group-text">FT</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 preview-details d-none" style="display:none">
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>@lang('Limit')</span>
                                    <span><span class="min fw-bold">0</span> {{__($general->cur_text)}} - <span class="max fw-bold">0</span> {{__($general->cur_text)}}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>@lang('Charge')</span>
                                    <span><span class="charge fw-bold">0</span> {{__($general->cur_text)}}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>@lang('Payable')</span> <span><span class="payable fw-bold"> 0</span> {{__($general->cur_text)}}</span>
                                </li>
                                <li class="list-group-item justify-content-between d-none rate-element">

                                </li>
                                <li class="list-group-item justify-content-between d-none in-site-cur">
                                    <span>@lang('In') <span class="method_currency"></span></span>
                                    <span class="final_amo fw-bold">0</span>
                                </li>
                                <li class="list-group-item justify-content-center crypto_currency d-none">
                                    <span>@lang('Conversion with') <span class="method_currency"></span> @lang('and final value will Show on next step')</span>
                                </li>
                            </ul>
                        </div>
                        {{-- <a href="#" class="btn btn--base w-100 mt-3">@lang('Pay with Credit Card')</a> --}}
                        <button type="submit" class="btn btn--base w-100 mt-3">@lang('Deposit Now')</button>
                        {{-- <div class="mt-3">
                            <br>
                            <br>
                            <p>CF deposit Address (FT):</p>
                            <span class="mb-3" style="font-weight: 800">0x5139724F0BF47604F52735d3447714Eac99711f7</span>

                            <!-- <p>1. BUSD, please use the BEP20 network and address below:</p>
                            <span class="mb-3" style="font-weight: 800">0x5139724F0BF47604F52735d3447714Eac99711f7</span>

                            <p>2. BNB, please use the BEP20 network and address below:</p>
                            <span class="mb-3" style="font-weight: 800">0x5139724F0BF47604F52735d3447714Eac99711f7</span>

                            <p>3. USDT, please use the BEP20 network and address below:</p>
                            <span class="mb-3" style="font-weight: 800">0x5139724F0BF47604F52735d3447714Eac99711f7</span>

                            <p>4. ETH, please use the ERC20 network and address below:</p>
                            <span class="mb-3" style="font-weight: 800">0x5139724F0BF47604F52735d3447714Eac99711f7</span>

                            <p>5. USDT, please use the ERC20 network and address below:</p>
                            <span class="mb-3" style="font-weight: 800">0x5139724F0BF47604F52735d3447714Eac99711f7</span> -->
                        </div> --}}
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('script')
    <script>

        $('body').on('keyup', '#amount', function() {
            const numbers = $(this).val();
            if(numbers){
                const options = {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
                };
                const formatted = Number(numbers).toLocaleString('en', options);
                $('.dis-amt').html(formatted+ ' FT');
            }else{
                $('.dis-amt').html('');
            }

        });

        (function ($) {
            "use strict";
            $('select[name=gateway]').change(function(){
                if(!$('select[name=gateway]').val()){
                    $('.preview-details').addClass('d-none');
                    return false;
                }
                var resource = $('select[name=gateway] option:selected').data('gateway');
                var fixed_charge = parseFloat(resource.fixed_charge);
                var percent_charge = parseFloat(resource.percent_charge);
                var rate = parseFloat({{$general->price_ft}})
                if(resource.method.crypto == 1){
                    var toFixedDigit = 8;
                    $('.crypto_currency').removeClass('d-none');
                }else{
                    var toFixedDigit = 2;
                    $('.crypto_currency').addClass('d-none');
                }
                $('.min').text(parseFloat(resource.min_amount).toFixed(2));
                $('.max').text(parseFloat(resource.max_amount).toFixed(2));
                var amount = parseFloat($('input[name=amount]').val());
                if (!amount) {
                    amount = 0;
                }
                if(amount <= 0){
                    $('.preview-details').addClass('d-none');
                    return false;
                }
                $('.preview-details').removeClass('d-none');
                var charge = parseFloat(fixed_charge + ((amount * parseFloat(rate)) * percent_charge / 100)).toFixed(2);
                $('.charge').text(charge);
                var payable = parseFloat(((parseFloat(amount) * parseFloat(rate)) + parseFloat(charge))).toFixed(2);
                $('.payable').text(payable);
                // var final_amo = (parseFloat((parseFloat(amount) + parseFloat(charge)))*rate).toFixed(toFixedDigit);
                var final_amo = (parseFloat((parseFloat(amount)))*rate).toFixed(toFixedDigit);
                $('.final_amo').text(payable);
                if (resource.currency != '{{ $general->cur_text }}') {
                    var rateElement = `<span class="fw-bold">@lang('Conversion Rate')</span> <span><span  class="fw-bold">1 ${resource.currency} = <span class="rate">${rate}</span>  <span class="method_currency">{{__($general->cur_text)}}</span></span></span>`;
                    $('.rate-element').html(rateElement)
                    $('.rate-element').removeClass('d-none');
                    $('.in-site-cur').removeClass('d-none');
                    $('.rate-element').addClass('d-flex');
                    $('.in-site-cur').addClass('d-flex');
                }else{
                    $('.rate-element').html('')
                    $('.rate-element').addClass('d-none');
                    $('.in-site-cur').addClass('d-none');
                    $('.rate-element').removeClass('d-flex');
                    $('.in-site-cur').removeClass('d-flex');
                }
                $('.method_currency').text("{{__($general->cur_text)}}");
                $('input[name=currency]').val(resource.currency);
                $('input[name=method_code]').val(resource.method_code);
                $('input[name=amount]').on('input');
            });
            $('input[name=amount]').on('input',function(){
                $('select[name=gateway]').change();
                $('.amount').text(parseFloat($(this).val()).toFixed(2));
            });
        })(jQuery);
    </script>
@endpush
