@extends($activeTemplate.'layouts.master')
@section('content')
<div class="dashboard-inner">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <br><br>

            <div class="card custom--card" id="manual">
                <div class="card-header">
                      
                      <h5 class="text-center"> <i class="las la-wallet"></i> @lang('PURCHASE or EXTEND your VIP membership')</h5>


                      

                </div>
                <div class="card-body">
                    <div class="mt-3">

                    
                      @if($user_pending_req == 0)
                        @if(auth('')->user()->vip_user == 1 && auth('')->user()->vip_user_date >  date("Y-m-d"))
                          <h4 class="mb-5" style="color:green">VIP Membership valid till  
                            {{-- {{auth('')->user()->vip_user_date}} --}}
                            {{ \Carbon\Carbon::parse(auth('')->user()->vip_user_date)->format('d-m-Y') }}
                          </h4>
                        @else
                          @if(auth('')->user()->vip_user_date != NULL)
                            <h4 class="mb-5" style="color:red">VIP Membership expired 
                            {{ \Carbon\Carbon::parse(auth('')->user()->vip_user_date)->format('d-m-Y') }}</h4>
                          @endif
                        @endif
                        {{-- @if(auth('')->user()->vip_user_date <=  date("Y-m-d")) --}}
                        {{-- @if(auth('')->user()->vip_user == 0) --}}
                        <form method="POST" action="{{ route('user.vip_membership.insert') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                              <label>@lang('Payment Method')</label>
                              <select class="payment-method form-select form-control form--control" name="maintenance_fees_type">
                                  <option value="0" >@lang('Manual send USDT, BNB or ETH (BEP-20 or ERC-20)')</option>
                                  <option value="1" >@lang('Manual send USDT or TRX (TRC-20)')</option>
                                  <option value="2" >@lang('Manual send USDC or XLM (Stellar)')</option>
                              </select>
                            </div>
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                              <!-- <h4>Deposit wallets (Billeteras de deposito): </h4><br> -->
                              <h5>Kindly send $20 (for 1 month) or $200 (for 1 year) worth crypto to following wallet</h5><br>
                              
                            
                              <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                  <button class="accordion-button display-address-type" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                      Please use the BEP-20 or ERC-20 network and address below:
                                  </button>
                                </h2>
                                <div id="flush-collapseOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                  <div class="accordion-body"><b><h6 class="display-address-id">0x8834B6dA0FB3dBFC59d079154588795A0D9E24DD</h6></b></div>
                                </div>
                              </div>
                                
                            </div>
                            <div class="form-group">
                                <label for="hash_id">Paste transaction HASH ID in field below</label>
                                <input type="text" name="hash_id" class="form-control" id="hash_id" minlength="64" maxlength="66" required>
                            </div>
                            <div class="form-group">
                                <label for="membership_amount">Select Duration</label>

                                <select class="form-select form-control form--control" name="membership_amount">
                                  <option value="20" >@lang('1 Month Payment')</option>
                                  <option value="200" >@lang('1 Year Payment')</option>
                                </select>
                            </div>
                            {{-- <div class="form-group">
                                <label for="vip_fee">VIP Membership Fee Image Proof</label>
                                <input type="file" name="vip_fee" class="form-control" id="vip_fee" accept=".jpg,.jpeg,.png" required>
                                <p class="text-muted">@lang('JPG, PNG and JPEG.')</p>
                            </div> --}}
                            <div class="form-group" style="height: 40px;">
                            <button type="submit" class="btn btn-primary mb-5">Submit</button>
                            </div>

                            <h8 style="font-size: smaller;font-weight:600"><b style="color:red">Please check the network and coin/token you are sending! If you send funds to non-listed network, funds will be lost!</b></h8>
                        </form>
                        @else

                          @if(auth('')->user()->vip_user == 1 && auth('')->user()->vip_user_date >  date("Y-m-d"))
                            <h4 class="mb-5" style="color:green">VIP Membership valid till  
                              {{-- {{auth('')->user()->vip_user_date}} --}}
                              {{ \Carbon\Carbon::parse(auth('')->user()->vip_user_date)->format('d-m-Y') }}
                            </h4>
                          @else
                            @if(auth('')->user()->vip_user_date != NULL)
                              <h4 class="mb-5" style="color:red">VIP Membership expired 
                              {{ \Carbon\Carbon::parse(auth('')->user()->vip_user_date)->format('d-m-Y') }}</h4>
                            @endif
                          @endif
                          <h4 class="mb-5" style="color:green;">  HASH ID successfuly sent! <br>Kindly wait for Admin approval!</h4>
                        @endif


                        {{-- <div class="accordion accordion-flush" id="accordionFlushExample">
                            <h4>VIP Membership Fees: </h4><br>
                            <b style="color:red">* IF YOU SEND ANY OTHER DIFFERENT COIN - NETWORK THAN THE ONES LISTED YOUR FUNDS WILL BE LOST !</b><br>
                            <div class="accordion-item">
                              <h2 class="accordion-header" id="flush-headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                    1. BUSD, please use the BEP20 network and address below:
                                </button>
                              </h2>
                              <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">0x8834B6dA0FB3dBFC59d079154588795A0D9E24DD</div>
                              </div>
                            </div>
                            <div class="accordion-item">
                              <h2 class="accordion-header" id="flush-headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                    2. BNB, please use the BEP20 network and address below:
                                </button>
                              </h2>
                              <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">0x8834B6dA0FB3dBFC59d079154588795A0D9E24DD</div>
                              </div>
                            </div>
                            <div class="accordion-item">
                              <h2 class="accordion-header" id="flush-headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                    3. USDT, please use the BEP20 network and address below:
                                </button>
                              </h2>
                              <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">0x8834B6dA0FB3dBFC59d079154588795A0D9E24DD</div>
                              </div>
                            </div>
                            <div class="accordion-item">
                              <h2 class="accordion-header" id="flush-headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                                    4. ETH, please use the ERC20 network and address below:
                                </button>
                              </h2>
                              <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">0x8834B6dA0FB3dBFC59d079154588795A0D9E24DD</div>
                              </div>
                            </div>
                            <div class="accordion-item">
                              <h2 class="accordion-header" id="flush-headingFive">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
                                    5. USDT, please use the ERC20 network and address below:
                                </button>
                              </h2>
                              <div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">0x8834B6dA0FB3dBFC59d079154588795A0D9E24DD</div>
                              </div>
                            </div>   
                        </div> --}}


                        {{-- @elseif(auth('')->user()->vip_user == 1)
                        <h4 class="mb-5">You are already vip member till {{auth('')->user()->vip_user_date}}</h4>
                        @else
                        <h4 class="mb-5">You have already VIP Member</h4>
                        @endif 
                        @endif --}}
                          
                </div>
            </div>
            
        </div>
    </div>
</div>

@endsection


@push('script')

    <script>
      $('body').on('change', '.payment-method', function () {
        var pt = $(this).val();
        if(pt == 0){
          $('.display-address-type').html('Please use the BEP-20 or ERC-20 network and address below');
          $('.display-address-id').html('0x8834B6dA0FB3dBFC59d079154588795A0D9E24DD');
        }else if(pt == 1){
          $('.display-address-type').html('Please use the TRC-20 network and address below');
          $('.display-address-id').html('TGVVZF34ZEfHvYcgQgX3mq546Hc1BwYnc6');
        }else if(pt == 2){
          $('.display-address-type').html('Please use the STELLAR network and address below');
          $('.display-address-id').html('GAFV7AB277PPQUV4JTAMJ7VE23H35SWVXBXPPE5FZP5WI7CMGYGVFFJ2');
        }
      });
      $('button[type=submit]').click(function() {
        if ($(this).parents('form').valid()) {
          $(this).attr('disabled', 'disabled');
          $(this).parents('form').submit();
        }
      });
    </script>


@endpush
