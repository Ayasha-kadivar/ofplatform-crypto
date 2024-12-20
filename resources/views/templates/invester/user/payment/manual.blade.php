@extends($activeTemplate.'layouts.master')
@section('content')
<div class="dashboard-inner">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="mb-4">
                <h3 class="mb-2">@lang('Deposit Confirmation')</h3>
                <p class="mb-1">@lang('Send deposit amount to the below information and submit required proof to the system\'s admin. The admin will check the request and will match the submitted proof. After verification, if everything is ok, the admin will approve the request and the amount will be deposited to your Deposit Wallet.')</p>
            </div>
            <div class="card custom--card">
                <div class="card-header card-header-bg">
                    <h5 class="text-center"> <i class="las la-wallet"></i> {{ $data->gateway->name }} @lang('Payment')</h5>
                </div>
                <div class="card-body  ">
                    <form action="{{ route('user.deposit.manual.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <p class="text-center mt-2">@lang('You have requested') <b class="text--success">{{ showAmount($data['amount'])  }} @lang('FT')</b> {{--, @lang('Please pay')
                                    <b class="text--success">{{showAmount($data['final_amo']) }} @lang('USD') </b> @lang('for successful payment')
                                </p> --}}
                                {{-- <p class="text-center mt-2">@lang('You have requested') <b class="text--success">{{ showAmount($data['amount'])  }} {{__($general->cur_text)}}</b> , @lang('Please pay')
                                    <b class="text--success">{{showAmount($data['final_amo']) .' '.$data['method_currency'] }} </b> @lang('for successful payment')
                                </p> --}}

                                <div class="my-4">
                                    <p>@php echo  $data->gateway->description @endphp</p>
                                </div>

                            </div>

                            <x-viser-form identifier="id" identifierValue="{{ $gateway->form_id }}" />

                            <div class="col-md-12">
                            <p style="color:red"><b>
                                Note: We DO NOT accept bscscan screenshot, Please upload the screenshot of the COMPLETED transaction on your wallet (transaction completed confirmation screen OR transaction listed on the transaction list of your wallet) otherwise your deposit will be REJECTED.
                                </b>
                                </p></div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn--base w-100">@lang('Pay Now')</button>
                                </div>
                            </div>
                            <div class="mt-3">
    
                                <br>
                                
                                <br>
                                <p>CF deposit Address (FT):</p>
                                <span class="mb-3" style="font-weight: 800">0x5139724F0BF47604F52735d3447714Eac99711f7</span>
                                <!-- <p>1. BUSD, please use the BEP20 network and address below:</p>
                                <span class="mb-3" style="font-weight: 800">0x5f8c90120a8668dd3e64bad2254c8c4a1505f711</span>

                                <p>2. TRX, please use the TRC20 network and address below:</p>
                                <span class="mb-3" style="font-weight: 800">TByPgDKJiajbFgtq2jZ8AKh46QVLLAC34c</span>

                                <p>3. BTC, please use the Bitcoin network and address below:</p>
                                <span class="mb-3" style="font-weight: 800">13YckxbufPiymWt7wqwUi89v8ywYp8UFpQ</span>
                                
                                <p>4. ETH, please use the ERC20 network and address below:</p>
                                <span class="mb-3" style="font-weight: 800">0x5f8c90120a8668dd3e64bad2254c8c4a1505f711</span>

                                <p>5. BNB, please use the BEP20 network and address below:</p>
                                <span class="mb-3" style="font-weight: 800">0x5f8c90120a8668dd3e64bad2254c8c4a1505f711</span> -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
