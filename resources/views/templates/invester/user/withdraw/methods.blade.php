@extends($activeTemplate.'layouts.master')
@section('content')
@if($isHoliday)

<script>
    "use strict"
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
</script>

@push('style')
    <style>
        .counter-area span {
            font-size: 45px;
            font-weight: bold;
            margin: 0px 7px;
            font-family: 'Lora', serif;
        }
    </style>
@endpush

@endif
<div class="dashboard-inner">
    <div class="row justify-content-center">
        <div class="col-md-8 mb-5">
            <div class="mb-4">
                <h3 class="mb-2">@lang('Withdraw Funds')</h3>
                <p>@lang('The fund will be withdrawal only from Deposit Wallet. So make sure that you\'ve sufficient balance to the Deposit wallet. ')</p>
                <p> You can withdraw maximum <b>{{auth()->user()->deposit_wallet}}</b> from your wallet</p>
            </div>
            <div class="row">
                <div class="col-6">
                    {{-- <button type="button" class="btn btn--secondary btn--smd" id="manualBtn">Manual</button>
                    <button type="button" class="btn btn--secondary btn--smd" id="metamaskBtn">Metamask</button> --}}
                </div>
                <div class="col-6">
                    <div class="text-end mb-4">
                        <a href="{{ route('user.withdraw.history') }}" class="btn btn--secondary btn--smd"><i class="las la-long-arrow-alt-left"></i> @lang('Withdraw History')</a>
                    </div>
                </div>
            </div>

            @if($isHoliday && !$general->holiday_withdraw)
            <div class="text-center counter-area mt-5">
                <h3 class="text--base">@lang('Next Working Day')</h3>
                <div id="counter"></div>
                <script>createCountDown('counter', {{\Carbon\Carbon::parse($nextWorkingDay)->diffInSeconds()}});</script>
            </div>
            @else
            <div class="card custom--card" id="manual" style="">
                <div class="card-body">
                    <form action="{{route('user.withdraw.money')}}" method="post">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="form-label">@lang('Manual Method')</label>
                            <select class="form-control form--control form-select" name="method_code" required disabled>
                                <option value="">@lang('Select Gateway')</option>
                                @foreach($withdrawMethod as $data)
                                    <option value="{{ $data->id }}" data-resource="{{$data}}" selected>  {{__($data->name)}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">@lang('Amount')</label>
                            <div class="input-group">
                                <input type="number" step="any" name="amount" value="{{ old('amount') }}" class="form-control form--control" required>
                                <span class="input-group-text">{{ $general->cur_text }}</span>
                            </div>
                        </div>
                        <div class="mt-3 preview-details d-none">
                            <ul class="list-group text-center">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>@lang('Limit')</span>
                                    <span><span class="min fw-bold">0</span> {{__($general->cur_text)}} - <span class="max fw-bold">0</span> {{__($general->cur_text)}}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>@lang('Charge')</span>
                                    <span><span class="charge fw-bold">0</span> {{__($general->cur_text)}}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>@lang('Receivable')</span> <span><span class="receivable fw-bold"> 0</span> {{__($general->cur_text)}} </span>
                                </li>
                                <li class="list-group-item d-none justify-content-between rate-element">

                                </li>
                                <li class="list-group-item d-none justify-content-between in-site-cur">
                                    <span>@lang('In') <span class="base-currency"></span></span>
                                    <strong class="final_amo">0</strong>
                                </li>
                            </ul>
                        </div>
                        <button type="submit" class="btn btn--base w-100 mt-3">@lang('Submit')</button>
                    </form>
                </div>
            </div>

            <div class="card custom--card" id="metamask" style="display: none;">
                <div class="card-body">
                    <form action="" method="post">
                        @csrf
                        <div class="form-group mb-3">
                            {{-- <h6>Withdraw in Metamask</h6> --}}
                            <label class="form-label">@lang('Withdraw in Metamask')</label>
                            <select class="form-control form--control form-select" name="withdraw_type" id="withdraw_type" required disabled>
                                <option value="">@lang('Select Gateway')</option>
                                <option value="metamask" selected>Metamask</option>
                            </select>
                        </div>
                        {{-- <div class="form-group">
                            <label class="form-label">@lang('Wallet Address')</label>
                            <div class="input-group">
                                <input type="text" step="any" name="wallet_amount"  class="form-control form--control" required>
                            </div>
                        </div> --}}
                        <div class="form-group">
                            <label class="form-label">@lang('Amount')</label>
                            <div class="input-group">
                                <input type="number" step="any" name="withdraw_amount" id="withdraw_amount" value="" class="form-control form--control" required>
                                <span class="input-group-text">{{ $general->cur_text }}</span>
                            </div>
                        </div>
                        <div class="mt-3 preview-details d-none">
                            <ul class="list-group text-center">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>@lang('Limit')</span>
                                    <span><span class="min fw-bold">0</span> {{__($general->cur_text)}} - <span class="max fw-bold">0</span> {{__($general->cur_text)}}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>@lang('Charge')</span>
                                    <span><span class="charge fw-bold">0</span> {{__($general->cur_text)}}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>@lang('Receivable')</span> <span><span class="receivable fw-bold"> 0</span> {{__($general->cur_text)}} </span>
                                </li>
                                <li class="list-group-item d-none justify-content-between rate-element">

                                </li>
                                <li class="list-group-item d-none justify-content-between in-site-cur">
                                    <span>@lang('In') <span class="base-currency"></span></span>
                                    <strong class="final_amo">0</strong>
                                </li>
                            </ul>
                        </div>
                        {{-- <button type="submit" class="btn btn--base w-100 mt-3">@lang('Submit')</button> --}}
                    </form>
                    <button id="connect-metamask"  class="btn btn--base w-100 mt-3">@lang('Submit')</button>
                </div>
            </div>
            @endif
        </div>
        {{-- <div class="row mt-5">
            <div class="mb-4">
                <h3 class="mb-2">@lang('Deposit in Pools')</h3>
                <p>@lang('The fund will be withdrawal only from Deposit Wallet. So make sure that you\'ve sufficient balance to the interest wallet. ')</p>
                <p> You can deposit maximum <b>{{auth()->user()->deposit_wallet}}</b> from your wallet</p>
            </div>
            <div class="text-end mb-4">
                <a href="{{ route('user.withdraw.history') }}" class="btn btn--secondary btn--smd"><i class="las la-long-arrow-alt-left"></i> @lang('Withdraw History')</a>
            </div>
            <div class="col-md-3">
                @if($isHoliday && !$general->holiday_withdraw)
                <div class="text-center counter-area mt-5">
                    <h3 class="text--base">@lang('Next Working Day')</h3>
                    <div id="counter"></div>
                    <script>createCountDown('counter', {{\Carbon\Carbon::parse($nextWorkingDay)->diffInSeconds()}});</script>
                </div>
                @else
                <div class="card custom--card">
                    <div class="card-body">
                        <form action="{{route('depositPool1')}}" method="post">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="form-label">@lang('Rewards Cube')</label>
                            </div>
                            <div class="form-group">
                                <label class="form-label">@lang('Amount')</label>
                                <div class="input-group">
                                    <input type="number" step="any" name="amount" value="{{ old('amount') }}" class="form-control form--control" required>
                                    <span class="input-group-text">{{ $general->cur_text }}</span>
                                </div>
                            </div>
                            <div class="mt-3 preview-details d-none">
                                <ul class="list-group text-center">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>@lang('Limit')</span>
                                        <span><span class="min fw-bold">0</span> {{__($general->cur_text)}} - <span class="max fw-bold">0</span> {{__($general->cur_text)}}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>@lang('Charge')</span>
                                        <span><span class="charge fw-bold">0</span> {{__($general->cur_text)}}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>@lang('Receivable')</span> <span><span class="receivable fw-bold"> 0</span> {{__($general->cur_text)}} </span>
                                    </li>
                                    <li class="list-group-item d-none justify-content-between rate-element">
    
                                    </li>
                                    <li class="list-group-item d-none justify-content-between in-site-cur">
                                        <span>@lang('In') <span class="base-currency"></span></span>
                                        <strong class="final_amo">0</strong>
                                    </li>
                                </ul>
                            </div>
                            <button type="submit" class="btn btn--base w-100 mt-3 ">@lang('Submit')</button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-md-3">
                @if($isHoliday && !$general->holiday_withdraw)
                <div class="text-center counter-area mt-5">
                    <h3 class="text--base">@lang('Next Working Day')</h3>
                    <div id="counter"></div>
                    <script>createCountDown('counter', {{\Carbon\Carbon::parse($nextWorkingDay)->diffInSeconds()}});</script>
                </div>
                @else
                <div class="card custom--card">
                    <div class="card-body">
                        <form action="{{route('depositPool2')}}" method="post">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="form-label">@lang('Vouchers Cube')</label>
                            </div>
                            <div class="form-group">
                                <label class="form-label">@lang('Amount')</label>
                                <div class="input-group">
                                    <input type="number" step="any" name="amount" value="{{ old('amount') }}" class="form-control form--control" required>
                                    <span class="input-group-text">{{ $general->cur_text }}</span>
                                </div>
                            </div>
                            <div class="mt-3 preview-details d-none">
                                <ul class="list-group text-center">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>@lang('Limit')</span>
                                        <span><span class="min fw-bold">0</span> {{__($general->cur_text)}} - <span class="max fw-bold">0</span> {{__($general->cur_text)}}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>@lang('Charge')</span>
                                        <span><span class="charge fw-bold">0</span> {{__($general->cur_text)}}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>@lang('Receivable')</span> <span><span class="receivable fw-bold"> 0</span> {{__($general->cur_text)}} </span>
                                    </li>
                                    <li class="list-group-item d-none justify-content-between rate-element">
    
                                    </li>
                                    <li class="list-group-item d-none justify-content-between in-site-cur">
                                        <span>@lang('In') <span class="base-currency"></span></span>
                                        <strong class="final_amo">0</strong>
                                    </li>
                                </ul>
                            </div>
                            <button type="submit" class="btn btn--base w-100 mt-3">@lang('Submit')</button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-md-3">
                @if($isHoliday && !$general->holiday_withdraw)
                <div class="text-center counter-area mt-5">
                    <h3 class="text--base">@lang('Next Working Day')</h3>
                    <div id="counter"></div>
                    <script>createCountDown('counter', {{\Carbon\Carbon::parse($nextWorkingDay)->diffInSeconds()}});</script>
                </div>
                @else
                <div class="card custom--card">
                    <div class="card-body">
                        <form action="{{route('depositPool3')}}" method="post">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="form-label">@lang('Staking Cube')</label>
                            </div>
                            <div class="form-group">
                                <label class="form-label">@lang('Amount')</label>
                                <div class="input-group">
                                    <input type="number" step="any" name="amount" value="{{ old('amount') }}" class="form-control form--control" required>
                                    <span class="input-group-text">{{ $general->cur_text }}</span>
                                </div>
                            </div>
                            <div class="mt-3 preview-details d-none">
                                <ul class="list-group text-center">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>@lang('Limit')</span>
                                        <span><span class="min fw-bold">0</span> {{__($general->cur_text)}} - <span class="max fw-bold">0</span> {{__($general->cur_text)}}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>@lang('Charge')</span>
                                        <span><span class="charge fw-bold">0</span> {{__($general->cur_text)}}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>@lang('Receivable')</span> <span><span class="receivable fw-bold"> 0</span> {{__($general->cur_text)}} </span>
                                    </li>
                                    <li class="list-group-item d-none justify-content-between rate-element">
    
                                    </li>
                                    <li class="list-group-item d-none justify-content-between in-site-cur">
                                        <span>@lang('In') <span class="base-currency"></span></span>
                                        <strong class="final_amo">0</strong>
                                    </li>
                                </ul>
                            </div>
                            <button type="submit" class="btn btn--base w-100 mt-3">@lang('Submit')</button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-md-3">
                @if($isHoliday && !$general->holiday_withdraw)
                <div class="text-center counter-area mt-5">
                    <h3 class="text--base">@lang('Next Working Day')</h3>
                    <div id="counter"></div>
                    <script>createCountDown('counter', {{\Carbon\Carbon::parse($nextWorkingDay)->diffInSeconds()}});</script>
                </div>
                @else
                <div class="card custom--card">
                    <div class="card-body">
                        <form action="{{route('depositPool4')}}" method="post">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="form-label">@lang('NFTs Cube')</label>
                            </div>
                            <div class="form-group">
                                <label class="form-label">@lang('Amount')</label>
                                <div class="input-group">
                                    <input type="number" step="any" name="amount" value="{{ old('amount') }}" class="form-control form--control" required>
                                    <span class="input-group-text">{{ $general->cur_text }}</span>
                                </div>
                            </div>
                            <div class="mt-3 preview-details d-none">
                                <ul class="list-group text-center">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>@lang('Limit')</span>
                                        <span><span class="min fw-bold">0</span> {{__($general->cur_text)}} - <span class="max fw-bold">0</span> {{__($general->cur_text)}}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>@lang('Charge')</span>
                                        <span><span class="charge fw-bold">0</span> {{__($general->cur_text)}}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>@lang('Receivable')</span> <span><span class="receivable fw-bold"> 0</span> {{__($general->cur_text)}} </span>
                                    </li>
                                    <li class="list-group-item d-none justify-content-between rate-element">
    
                                    </li>
                                    <li class="list-group-item d-none justify-content-between in-site-cur">
                                        <span>@lang('In') <span class="base-currency"></span></span>
                                        <strong class="final_amo">0</strong>
                                    </li>
                                </ul>
                            </div>
                            <button type="submit" class="btn btn--base w-100 mt-3">@lang('Submit')</button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div> --}}
    </div>
</div>
@endsection

@push('script')
<script>
    (function ($) {
        "use strict";
        $('select[name=method_code]').change(function(){
            if(!$('select[name=method_code]').val()){
                $('.preview-details').addClass('d-none');
                return false;
            }
            var resource = $('select[name=method_code] option:selected').data('resource');
            var fixed_charge = parseFloat(resource.fixed_charge);
            var percent_charge = parseFloat(resource.percent_charge);
            var rate = parseFloat(resource.rate)
            var toFixedDigit = 2;
            $('.min').text(parseFloat(resource.min_limit).toFixed(2));
            $('.max').text(parseFloat(resource.max_limit).toFixed(2));
            var amount = parseFloat($('input[name=amount]').val());
            if (!amount) {
                amount = 0;
            }
            if(amount <= 0){
                $('.preview-details').addClass('d-none');
                return false;
            }
            $('.preview-details').removeClass('d-none');

            var charge = parseFloat(fixed_charge + (amount * percent_charge / 100)).toFixed(2);
            $('.charge').text(charge);
            if (resource.currency != '{{ $general->cur_text }}') {
                var rateElement = `<span>@lang('Conversion Rate')</span> <span class="fw-bold">1 {{__($general->cur_text)}} = <span class="rate">${rate}</span>  <span class="base-currency">${resource.currency}</span></span>`;
                $('.rate-element').html(rateElement);
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
            var receivable = parseFloat((parseFloat(amount) - parseFloat(charge))).toFixed(2);
            $('.receivable').text(receivable);
            var final_amo = parseFloat(parseFloat(receivable)*rate).toFixed(toFixedDigit);
            $('.final_amo').text(final_amo);
            $('.base-currency').text(resource.currency);
            $('.method_currency').text(resource.currency);
            $('input[name=amount]').on('input');
        });
        $('input[name=amount]').on('input',function(){
            var data = $('select[name=method_code]').change();
            $('.amount').text(parseFloat($(this).val()).toFixed(2));
        });
    })(jQuery);
</script>
<script>
    document.getElementById('connect-metamasks').addEventListener('click', async function() {
  try {
    await window.ethereum.enable();
    // Reload the page to update the list of available accounts
    console.log(window.ethereum.selectedAddress);
  } catch (error) {
    console.error(error);
  }
});

</script>
    <script>
        (async function() {
          const _web3 = new Web3(window.ethereum);
          const abi1 = [{"inputs":[{"internalType":"address","name":"_tokenAddress","type":"address"}],"stateMutability":"nonpayable","type":"constructor"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"previousOwner","type":"address"},{"indexed":true,"internalType":"address","name":"newOwner","type":"address"}],"name":"OwnershipTransferred","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"user","type":"address"},{"indexed":false,"internalType":"uint256","name":"amount","type":"uint256"}],"name":"Withdrawn","type":"event"},{"inputs":[{"internalType":"address","name":"userAddress","type":"address"}],"name":"getWithdrawnBalance","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"owner","outputs":[{"internalType":"address","name":"","type":"address"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"renounceOwnership","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[],"name":"tokenAddress","outputs":[{"internalType":"address","name":"","type":"address"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"totalWithdrawned","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"address","name":"newOwner","type":"address"}],"name":"transferOwnership","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"withdraw","outputs":[],"stateMutability":"nonpayable","type":"function"}];
          const contractWithdraw = new _web3.eth.Contract(
            abi1, "0xa586a913AD04dED28874885eA89f6981dee10807"
          );
          document.getElementById("connect-metamask").addEventListener("click", async function() {
  try {
    await window.ethereum.enable();
    console.log(window.ethereum.selectedAddress);

    // let sendFt = document.getElementById("newftPrice").innerHTML;
    let sendFt = document.getElementById("withdraw_amount").value;
    const userAddress = window.ethereum.selectedAddress;
    const depositWalletAmount = {{ auth()->user()->deposit_wallet }};

    // check if the withdrawal amount is less than the deposit wallet amount
    if (sendFt > depositWalletAmount) {
    // display an error message to the user
        alert("You do not have enough funds in your deposit wallet");
    } else {
        const amount = sendFt;
        console.log(userAddress, amount, depositWalletAmount);
        let receipt;
        await contractWithdraw.methods.withdraw(
        _web3.utils.toWei(amount.toString(), "ether")
        )
        .send({
            from: userAddress,
            // value: _web3.utils.toWei(amount.toString(), "ether")
        }).then(function(result) {
            receipt = JSON.stringify(result);
            console.log("mazhar")
            console.log(receipt);
            console.log(document.getElementById("withdraw_type").value,);

            // Saving the receipt information to the server using ajax
            // const rentedNft = document.getElementById("rent").value;
            // console.log("rentedNft");

            $.ajax({
            type: "POST",
            url: "{{route('user.withdraw.metawithdraw')}}",
            data: {
                "_token": "{{ csrf_token() }}",
                    "metamask_info": receipt,
                    "withdraw_type": document.getElementById("withdraw_type").value,
                    "user_id": {{ Auth::id() }},
                    "withdraw_amount": document.getElementById("withdraw_amount").value,
            },
            success: function(data) {
                window.location.href = "{{ route('user.withdraw.history')}}";
            },
            error: function(error) {
                console.error("Error while saving the receipt information: ", error);
            }
            });
        });
    }
    }catch (error) {
    console.error(error);
  }
});


        })();
</script>
<script>
    document.getElementById("manualBtn").addEventListener("click", function() {
      document.getElementById("manual").style.display = "block";
      document.getElementById("metamask").style.display = "none";
    });
    document.getElementById("metamaskBtn").addEventListener("click", function() {
      document.getElementById("manual").style.display = "none";
      document.getElementById("metamask").style.display = "block";
    });
  </script>
@endpush
