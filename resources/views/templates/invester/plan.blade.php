@extends("$activeTemplate.layouts.$layout")

@php
$price_ft = App\Models\GeneralSetting::first();
@endphp
@section('content')
    <div class="bg--light bg--new-theme">
        <div class="dashboard-inner {{ $layout == 'frontend' ? 'container pt-120 pb-120' : ''  }}">
            <div class="mb-4">
                <div class="row mb-4">
                    <div class="col-lg-8">
                        <h3 class="mb-2 new--color--theme">@lang('Cubes Balance')</h3>
                    </div>
                </div>
                <div class="row gy-4">
                    {{-- @include($activeTemplate.'partials.plan', ['plans' => $plans]) --}}
                    {{-- @if(isset($myPoolData)) --}}
                    @if(true)
                    <div class="col-sm-6 px-3">
                        <div class="card text-center shadow border--radius--new" style="height: 540px;">
                          <div class="card-body">
                            <h4 class="card-title new--label--theme--card--cube">Rewards Cube</h4>
                            <p class="card-text">Balance withdrawable on 1st and 15th of month</p>
                            {{-- <h2>${{($myPoolData['one_nft_price']*$myPoolData['rented_nft']) + $user->interest_wallet}}</h2> --}}
                            <span class="currency--size--new--theme">USD</span>
                            <h2>{{ number_format($final_iwallet_amount, 2) }}</h2>
                            {{-- <p class="card-text">Profit will start after 9 days and will expire on the 90th day.</p> --}}
                            {{-- <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>Total NFT Rented</span>
                                </div>
                                <div class="col-sm-6">
                                    <p>{{$myPoolData['rented_nft']}}</p>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>Price in usd</span>
                                </div>
                                <div class="col-sm-6">
                                    <p>${{$myPoolData['one_nft_price']*$myPoolData['rented_nft']}}</p>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>Price in Ft</span>
                                </div>
                                <div class="col-sm-6">
                                    <p>{{round(($myPoolData['one_nft_price']*$myPoolData['rented_nft'])/$myPoolData['ft_price'],2)}}ft</p>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>Rent generated</span>
                                </div>
                                <div class="col-sm-6">
                                    <p>0</p>
                                </div>
                            </div>     
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>Start date</span>
                                </div>
                                <div class="col-sm-6">
                                    <p>{{ date('d F, Y', strtotime($myPoolData['buying_date'])) }}</p>
                                </div>
                            </div>   
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>End date</span>
                                </div>
                                <div class="col-sm-6">
                                    <p>
                                        @php
                                            $date = new DateTime($myPoolData['buying_date']);
                                            $date->add(new DateInterval('P90D'));
                                            echo $date->format('d F, Y');
                                        @endphp                                        
                                    </p>
                                </div>
                            </div>    
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>Next withdrawal Date</span>
                                </div>
                                <div class="col-sm-6">
                                    <p>
                                        @php
                                            $date = new DateTime($myPoolData['buying_date']);
                                            $date->add(new DateInterval('P9D'));
                                            echo $date->format('d F, Y');
                                        @endphp
                                </div>
                            </div>                                                                                                          --}}
                            {{-- @php
                                $currentDay = date('j');
                                $currentMonth = date('n');
                                $currentYear = date('Y');
                                $user = auth()->user();
                            @endphp
                            
                            @if(($user->launch_nft_owner == 0 && ($currentDay == 1 || $currentDay == 15) && (($currentMonth >= 11 && $currentYear == 2023) || ($currentYear > 2023))) || ($user->launch_nft_owner == 1 && ($currentDay == 1 || $currentDay == 2 || $currentDay == 15)))
                                <a href="{{ route('user.withdraw') }}" class="btn btn--secondary btn--smd mt-5">@lang('Withdraw')</a>
                            @else
                                <a href="#" class="btn btn--secondary btn--smd disabled-button mt-5">@lang('Withdraw')</a>
                            @endif --}}
                            <form method="POST" action="{{ route('updateWallet') }}">
                                @csrf
                                {{-- <label for="amount">Amount:</label> --}}
                                {{-- <input type="number" name="amount" class="form-control mt-3" placeholder="Amount" min="1" step="0.01" required> --}}
                                @php
                                    $currentDay = date('j');
                                    $currentMonth = date('n');
                                    $currentYear = date('Y');
                                    $user = auth()->user();
                                @endphp
                                @if(($currentDay == 1 ||  $currentDay == 15 ||  $currentDay == 16 ||  $currentDay == 17 ||  $currentDay == 18 ||  $currentDay == 19 ||  $currentDay == 20))
                                    <input type="number" name="amount" class="form-control mt-3 textbox--new--theme" placeholder="Amount" min="1" step="0.01" required>
                                    <button id="wdvSubmit" class="btn btn--secondary regular-button-new-theme btn--smd mt-3 ">@lang('Withdraw')</button>
                                @else
                                    <input type="number" name="amount" class="form-control mt-3" disabled placeholder="Amount" min="1" step="0.01" required>
                                    <button id="wdvSubmit" class="btn btn--secondary btn--smd disabled-button disabled-button-new-theme mt-3 ">@lang('Withdraw')</button>
                                @endif

                                
                                @if($cube_to_wallet)
                                    <span style="font-size: medium;margin-top:15px" 
                                    class="badge badge--warning">Pending for approval</span>
                                    <h5 style="margin-top:6px">Total:- ${{ number_format($cube_to_wallet, 2) }} </h5>
                                @endif
                            </form>
                          </div>
                        </div>
                    </div>
                    <div class="col-sm-6 px-3">
                        <div class="card text-center shadow border--radius--new" style="height: 540px;">
                          <div class="card-body">
                            <h4 class="card-title new--label--theme--card--cube">Vouchers Cube</h4>
                            <p class="card-text">Use Vouchers to invite new members</p>

                            <span class="currency--size--new--theme">USD</span>
                            <h2>{{ number_format($user->pool_2, 2) }}</h2>
                            {{-- <p class="card-text">Profit From Referrals</p>
                             <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>1 Voucher</span>
                                </div>
                                <div class="col-sm-6">
                                    <p>$24</p>
                                </div>
                            </div> --}}
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-9 label--align">
                                    <span class="card-text--new--theme">Activated Vouchers in current month</span>
                                </div>
                                <div class="col-sm-3 label--align green--with--bold--left">
                                    <p class="card-text--new--theme green--with--bold">{{$ref_this_month}}</p>
                                </div>
                            </div>
                            <!-- <div class="row mt-3 mb-3">
                                <div class="col-sm-12">
                                    <span class="card-text--new--theme">Activated Vouchers in current month &nbsp;&nbsp;&nbsp;<span class="counter-text--new--theme">0</span></span>
                                </div>
                                {{-- <div class="col-sm-6">
                                    <p>0</p>
                                </div> --}}
                            </div> -->
                            <div class="copy-link">
                                <input type="hidden" id="copyURL" class="copyURL" value="{{ route('home') }}/user/register?reference={{ auth()->user()->username }}" readonly>
                            </div>
                            <button class="cmn--btn plan-btn btn mt-2 w-75 regular-button-new-theme"><span class="copyBoard" id="copyBoard"><i class="las la-copy"></i> <strong class="copyText">@lang('Copy Voucher to Clipboard')</strong></span></button>
                          </div>
                        </div>
                    </div>
                    <div class="col-sm-6 px-3">
                        <div class="card text-center shadow border--radius--new" style="height: 540px;">
                          <div class="card-body">
                                <h4 class="card-title new--label--theme--card--cube">Staking Cube</h4>
                                <p class="card-text">At the end of month complete balance<br> will be staked for 365 days with 4% APY</p>
                                <span class="currency--size--new--theme">FT</span>
                                <h2><!-- $ --> {{ number_format(($user->pool_3/$price_ft->price_ft), 2) }}</h2>
                                {{-- <p class="card-text">Profit From Staking</p> --}}
                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-7 label--align">
                                        <span class="card-text--new--theme">Total amount in stake</span>
                                    </div>
                                    <div class="col-sm-5 label--align green--with--bold--left">
                                        <p class="card-text--new--theme green--with--bold">0 FT</p>
                                    </div>
                                    <div class="col-sm-7 label--align">
                                        <span class="card-text--new--theme">Overall profit</span>
                                    </div>
                                    <div class="col-sm-5 label--align green--with--bold--left">
                                        <p class="card-text--new--theme green--with--bold">0 FT</p>
                                    </div>
                                </div>
                                
                                {{-- <div class="row mt-3 mb-3">
                                    <div class="col-sm-6">
                                        <span>Total Rented</span>
                                    </div>
                                    <div class="col-sm-6">
                                        <p>$24FT</p>
                                    </div>
                                </div>
                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-6">
                                        <span>Max. Earn</span>
                                    </div>
                                    <div class="col-sm-6">
                                        <p>Unlimited</p>
                                    </div>
                                </div>
                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-6">
                                        <span>Profit</span>
                                    </div>
                                    <div class="col-sm-6">
                                        <p>Unlimited</p>
                                    </div>
                                </div> --}}
                                {{-- <a href="{{ route('plan') }}" class="cmn--btn plan-btn btn mt-2" >Invest Now</a> --}}
                                <button class="cmn--btn plan-btn btn mt-2 w-75 disabled-button-new-theme"><span><strong class="">@lang('Detailed order history')</strong></span></button>
                          </div>
                        </div>
                    </div>
                    <div class="col-sm-6 px-3">
                        <div class="card text-center shadow border--radius--new" style="height: 540px;" >
                          <div class="card-body">
                            <h4 class="card-title new--label--theme--card--cube">NFTs Cube</h4>
                            <p class="card-text">On the 2nd of month unused balance<br> will be written off if not used!</p>
                            <span class="currency--size--new--theme">USD</span>
                            <h2>{{ number_format($user->pool_4, 2) }}</h2>
                            {{-- <p class="card-text">Total Invested in NFTs Cube</p> --}}
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-7 label--align">
                                    <span class="card-text--new--theme">Total invested (used)</span>
                                </div>
                                <div class="col-sm-5 label--align green--with--bold--left">
                                    <p class="card-text--new--theme green--with--bold">0 USD</p>
                                </div>
                            </div>
                            {{-- <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>Total Rented</span>
                                </div>
                                <div class="col-sm-6">
                                    <p>$1.00 - $33,333.00</p>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>Max. Earn</span>
                                </div>
                                <div class="col-sm-6">
                                    <p>20 USD</p>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>Profit</span>
                                </div>
                                <div class="col-sm-6">
                                    <p>capital + 20 USD</p>
                                </div>
                            </div> --}}
                            {{-- <a href="#" class="cmn--btn plan-btn btn mt-2">Buy NFT</a> --}}
                            <br>
                            <button class="cmn--btn plan-btn btn mt-2 w-75 disabled-button-new-theme"><span><strong class="">@lang('Investment history')</strong></span></button>
                          </div>
                          {{-- <div class="coming-soon-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background-color: rgba(255,255,255,0.7);">
                            <span style="font-size: 2em; text-align: center;">Coming Soon</span>
                         </div> --}}
                        </div>
                    </div>
                    @else
                    <div class="col-sm-6 px-3">
                        <div class="card text-center shadow border--radius--new " style="height: 540px;">
                          <div class="card-body">
                            <h4 class="card-title new--label--theme--card--cube">Rewards Cube</h4>
                            <p class="card-text">Balance withdrawable on 1st and 15th of month</p>
                            <h2>${{ number_format($user->interest_wallet, 2) }}</h2> 
                            {{-- <p class="card-text">Profit will start after 9 days and will expire on the 90th day.</p>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>Total NFT Rented</span>
                                </div>
                                <div class="col-sm-6">
                                    <p>0</p>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>Price in usd</span>
                                </div>
                                <div class="col-sm-6">
                                    <p>$0</p>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>Price in Ft</span>
                                </div>
                                <div class="col-sm-6">
                                    <p>0ft</p>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>Rent generated</span>
                                </div>
                                <div class="col-sm-6">
                                    <p>0</p>
                                </div>
                            </div>     
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>Start date</span>
                                </div>
                                <div class="col-sm-6">
                                    <p>0</p>
                                </div>
                            </div>   
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>End date</span>
                                </div>
                                <div class="col-sm-6">
                                    <p>
                                        0                                   
                                    </p>
                                </div>
                            </div>    
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>Next withdrawal Date</span>
                                </div>
                                <div class="col-sm-6">
                                    <p>
                                       0
                                </div>
                            </div>                                                                                                          --}}
                            {{-- @php
                                $currentDay = date('j');
                                $currentMonth = date('n');
                                $currentYear = date('Y');
                                $user = auth()->user();
                            @endphp
                            
                            @if(($user->launch_nft_owner == 0 && ($currentDay == 1 || $currentDay == 15) && (($currentMonth >= 11 && $currentYear == 2023) || ($currentYear > 2023))) || ($user->launch_nft_owner == 1 && ($currentDay == 1 || $currentDay == 2 || $currentDay == 15)))
                                <a href="{{ route('user.withdraw') }}" class="btn btn--secondary btn--smd mt-5">@lang('Withdraw')</a>
                            @else
                                <a href="#" class="btn btn--secondary btn--smd disabled-button mt-5">@lang('Withdraw')</a>
                            @endif --}}
                            <form method="POST" action="{{ route('updateWallet') }}">
                                @csrf
                                {{-- <label for="amount">Amount:</label> --}}
                                {{-- <input type="text" name="amount" class="form-control mt-3" placeholder="Amount" min="1" step="0.01" required> --}}
                                @php
                                    $currentDay = date('j');
                                    $currentMonth = date('n');
                                    $currentYear = date('Y');
                                    $user = auth()->user();
                                @endphp
                                @if($currentDay == 1 || $currentDay == 15)
                                    <input type="number" name="amount" class="form-control mt-3" placeholder="Amount" min="1" step="0.01" required>
                                    <button id="wdvSubmit" class="btn btn--secondary btn--smd mt-3 regular-button-new-theme">@lang('Withdraw')</button>
                                @else
                                    <input type="number" name="amount" class="form-control mt-3" disabled placeholder="Amount" min="1" step="0.01" required>
                                    <button id="wdvSubmit" class="btn btn--secondary btn--smd disabled-button disabled-button-new-theme mt-3 ">@lang('Withdraw')</button>
                                @endif
                            </form>
                          </div>
                        </div>
                    </div>
                    <div class="col-sm-6 px-3">
                        <div class="card text-center shadow border--radius--new" style="height: 540px;">
                          <div class="card-body">
                            <h4 class="card-title new--label--theme--card--cube">Vouchers Cube</h4>
                            <p class="card-text">Unlimited</p>
                            <h2>${{ number_format($user->pool_2, 2) }}</h2>
                            <p class="card-text">Profit From Referrals</p>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>1 Voucher</span>
                                </div>
                                <div class="col-sm-6">
                                    <p>$24</p>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>Total Voucher Use</span>
                                </div>
                                <div class="col-sm-6">
                                    <p>0</p>
                                </div>
                            </div>
                            <div class="copy-link">
                                <input type="hidden" id="copyURL" class="copyURL" value="{{ route('home') }}/user/register?reference={{ auth()->user()->username }}" readonly>
                            </div>
                            <button class="cmn--btn plan-btn btn mt-2 w-75 regular-button-new-theme"><span class="copyBoard" id="copyBoard"><i class="las la-copy"></i> <strong class="copyText">@lang('Copy Voucher')</strong></span></button>
                          </div>
                        </div>
                    </div>
                    <div class="col-sm-6 px-3">
                        <div class="card text-center shadow border--radius--new" style="height: 540px;">
                          <div class="card-body">
                                <h4 class="card-title new--label--theme--card--cube">Staking Cube</h4>
                                <p class="card-text">Unlimited</p>
                                <h2><!-- $ -->FT {{ number_format(($user->pool_3/$price_ft->price_ft), 2) }}</h2>
                                <p class="card-text">Profit From Staking</p>
                                {{-- <div class="row mt-3 mb-3">
                                    <div class="col-sm-6">
                                        <span>Total Rented</span>
                                    </div>
                                    <div class="col-sm-6">
                                        <p>$24FT</p>
                                    </div>
                                </div>
                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-6">
                                        <span>Max. Earn</span>
                                    </div>
                                    <div class="col-sm-6">
                                        <p>Unlimited</p>
                                    </div>
                                </div>
                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-6">
                                        <span>Profit</span>
                                    </div>
                                    <div class="col-sm-6">
                                        <p>Unlimited</p>
                                    </div>
                                </div> --}}
                                {{-- <a href="{{ route('plan') }}" class="cmn--btn plan-btn btn mt-2" >Invest Now</a> --}}
                          </div>
                        </div>
                    </div>
                    <div class="col-sm-6 px-3">
                        <div class="card text-center shadow border--radius--new" style="height: 540px;" >
                          <div class="card-body">
                            <h4 class="card-title new--label--theme--card--cube">NFTs Cube</h4>
                            <p class="card-text">Unlimited</p>
                            <h2>${{ number_format($user->pool_4, 2) }}</h2>
                            <p class="card-text">Total Invested in NFTs Cube</p>
                            {{-- <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>Total Rented</span>
                                </div>
                                <div class="col-sm-6">
                                    <p>$1.00 - $33,333.00</p>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>Max. Earn</span>
                                </div>
                                <div class="col-sm-6">
                                    <p>20 USD</p>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class="col-sm-6">
                                    <span>Profit</span>
                                </div>
                                <div class="col-sm-6">
                                    <p>capital + 20 USD</p>
                                </div>
                            </div> --}}
                            {{-- <a href="#" class="cmn--btn plan-btn btn mt-2">Buy NFT</a> --}}
                          </div>
                          {{-- <div class="coming-soon-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background-color: rgba(255,255,255,0.7);">
                            <span style="font-size: 2em; text-align: center;">Coming Soon</span>
                         </div> --}}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <style nonce="{{ csp_nonce() }}">
         .card::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: inherit;
      filter: blur(5px);
      opacity: 0.5;
      z-index: -1;
   }
    </style>

@endsection

@push('style')
    <link href="{{ asset('assets/global/css/jquery.treeView.css') }}" rel="stylesheet" type="text/css">
@endpush
@push('script')
<script src="{{ asset('assets/global/js/jquery.treeView.js') }}"></script>
{{-- <script>
    (function($){
    "use strict"
        $('.treeview').treeView();
        $('.copyBoard').click(function(){
                var copyText = document.getElementById("copyURL");
                // copyText = copyText[0];
                // copyText.select();
                // copyText.setSelectionRange(0, 99999);
                console.log(copyText);
                var range = document.createRange();
                range.selectNode(copyText);                

                /*For mobile devices*/
                document.execCommand("copy");
                $('.copyText').text('Copied');
                setTimeout(() => {
                    $('.copyText').text('Copy Voucher');
                }, 2000);
        });
    })(jQuery);
</script> --}}
<script nonce="{{ csp_nonce() }}">
    var copyButton = document.getElementById("copyBoard");
    copyButton.addEventListener("click", function() {
    var hiddenInput = document.getElementById("copyURL");
    var hiddenInputValue = hiddenInput.value;

    var dummyElement = document.createElement("textarea");
    dummyElement.value = hiddenInputValue;
    document.body.appendChild(dummyElement);
    dummyElement.select();
    document.execCommand("copy");
    document.body.removeChild(dummyElement);

    /For mobile devices/
    document.execCommand("copy");
                    $('.copyText').text('Copied');
                    setTimeout(() => {
                        $('.copyText').text('Copy Voucher');
                    }, 2000);

    });


    $(document).ready(function(){
        $("#wdvSubmit").on('click', function (event) {  
           event.preventDefault();
           var el = $(this);
           el.prop('disabled', true);
           setTimeout(function(){el.prop('disabled', false); }, 3000);
           var form = $(this).parents('form:first');
           form.submit();
        });
    }); 
</script>
@endpush