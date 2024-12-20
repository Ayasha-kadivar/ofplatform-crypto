@extends($activeTemplate.'layouts.master')
@push('style')
  <style>
    #countdown {
      text-align: center;
      font-size: 60px;
      margin-top: 0px;
      color:red;
    }
    #ftext {
      text-align: center;
      font-size: 20px;
      margin-top: 0px;
      color:red;
    }
  </style>
@endpush
@section('content')
<div class="dashboard-inner">
    <div class="row justify-content-center">
        <div class="col-md-9">
            {{-- <button type="button" class="btn btn-primary" id="manualBtn">Manual</button>
            <button type="button" class="btn btn-primary" id="stripeBtn">Stripe</button> --}}
            @if (auth('')->user()->is_suspend == 1)
              <!-- <p id="countdown"></p><br>
              <p id="ftext">YOUR PROFILE IS SUSPENDED AS MAINTENANCE FEE IS NOT PAID!<br> PAY MAINTENANCE FEE TO REVERT TO OPERATIONAL STATE!</p>
              
              <br><br> -->
            @endif

            <div class="card custom--card" id="manual">
                <div class="card-header">
                    <h5 class="text-center"> <i class="las la-wallet"></i> @lang('PAY or EXTEND your Maintenance Fee')</h5>
                </div>
                <div class="card-body">
                    <div class="mt-3">
                        @if(auth('')->user()->fee_status == 0 || auth('')->user()->fee_status == 2)
                          
                            @if(auth('')->user()->fee_status == NULL && auth('')->user()->maintenance_expiration_date == NULL)
                              <h4 class="mb-5" style="color:red;">Maintenance fee not paid!</h4>
                            @else
                              @if(auth('')->user()->maintenance_expiration_date)
                              @if(auth('')->user()->maintenance_expiration_date <=  date("Y-m-d"))
                                {{-- <h4 class="mb-5" style="color:red;">Maintenance fee expired {{date('d-m-Y', strtotime(auth('')->user()->maintenance_expiration_date))}}!</h4> --}}
                                <h4 class="mb-5" style="color:red;">Maintenance fee expired!</h4>
                              @else
                                <h4 class="mb-5" style="color:green;">Maintenance fee payment valid till {{date('d-m-Y', strtotime(auth('')->user()->maintenance_expiration_date))}}</h4>
                              @endif
                              @else
                                <h4 class="mb-5" style="color:red;">Maintenance fee not paid!</h4>
                              @endif
                            @endif

                        
                        <form method="POST" action="{{ route('upload.maintenance_fee') }}" enctype="multipart/form-data">
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
                              <h5>Kindly send $10 worth crypto to following wallet</h5><br>
                              
                            
                              <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                  <button class="accordion-button display-address-type" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                      Please use the BEP-20 or ERC-20 network and address below:
                                  </button>
                                </h2>
                                <div id="flush-collapseOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                  <!-- <div class="accordion-body"><b><h6 class="display-address-id">0xD66D6410658e34a2Eb545e6E2CB67f45bb316D0f</h6></b></div> -->
                                  <div class="accordion-body"><b><h6 class="display-address-id">0xdc37C430Dd0C94e25c51053166AeC8d71F4bcfa4</h6></b></div>
                                </div>
                              </div>
                                
                            </div>
                            <div class="form-group">
                                <label for="hash_id">Paste transaction HASH ID in field below</label>
                                <input type="text" name="hash_id" class="form-control" id="hash_id" minlength="64" maxlength="66" required>
                            </div>
                            <div class="form-group" style="height: 40px;">
                            <button type="submit" class="btn btn-primary mb-5">Submit</button>
                            </div>

                            <h8 style="font-size: smaller;font-weight:600"><b style="color:red">Please check the network and coin/token you are sending! If you send funds to non-listed network, funds will be lost!</b></h8>
                        </form>
                        @elseif(auth('')->user()->fee_status == 1)


                          @if(auth('')->user()->maintenance_expiration_date != NULL)
                            @if(auth('')->user()->maintenance_expiration_date <=  date("Y-m-d"))
                              {{-- <h4 class="mb-5" style="color:red;">Maintenance fee expired {{date('d-m-Y', strtotime(auth('')->user()->maintenance_expiration_date))}}!</h4> --}}
                              <h4 class="mb-5" style="color:red;">Maintenance fee expired!</h4>
                            @else
                              <h4 class="mb-5" style="color:green;">Maintenance fee payment valid till {{date('d-m-Y', strtotime(auth('')->user()->maintenance_expiration_date))}}</h4>
                            @endif
                          @endif
                          <h4 class="mb-5" style="color:green;">  HASH ID successfuly sent! <br>Kindly wait for Admin approval!</h4>
                        @endif

                        

                          {{-- <div class="accordion accordion-flush" id="accordionFlushExample">
                            <h4>Deposit wallets (Billeteras de deposito): </h4><br>
                            <b style="color:red">* IF YOU SEND ANY OTHER DIFFERENT COIN - NETWORK THAN THE ONES LISTED YOUR FUNDS WILL BE LOST !</b><br>
                            <div class="accordion-item">
                              <h2 class="accordion-header" id="flush-headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                    1. BUSD, please use the BEP20 network and address below:
                                </button>
                              </h2>
                              <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">0xD66D6410658e34a2Eb545e6E2CB67f45bb316D0f</div>
                              </div>
                            </div>
                            <div class="accordion-item">
                              <h2 class="accordion-header" id="flush-headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                    2. BNB, please use the BEP20 network and address below:
                                </button>
                              </h2>
                              <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">0xD66D6410658e34a2Eb545e6E2CB67f45bb316D0f</div>
                              </div>
                            </div>
                            <div class="accordion-item">
                              <h2 class="accordion-header" id="flush-headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                    3. USDT, please use the BEP20 network and address below:
                                </button>
                              </h2>
                              <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">0xD66D6410658e34a2Eb545e6E2CB67f45bb316D0f</div>
                              </div>
                            </div>
                            <div class="accordion-item">
                              <h2 class="accordion-header" id="flush-headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                                    4. ETH, please use the ERC20 network and address below:
                                </button>
                              </h2>
                              <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">0xD66D6410658e34a2Eb545e6E2CB67f45bb316D0f</div>
                              </div>
                            </div>
                            <div class="accordion-item">
                              <h2 class="accordion-header" id="flush-headingFive">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
                                    5. USDT, please use the ERC20 network and address below:
                                </button>
                              </h2>
                              <div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">0xD66D6410658e34a2Eb545e6E2CB67f45bb316D0f</div>
                              </div>
                            </div>
                         
                          </div> --}}

                    </div>
                </div>
            </div>

            @if(date('d-m-Y', strtotime(auth('')->user()->maintenance_expiration_date)) && auth('')->user()->maintenance_expiration_date  <=  date("Y-m-d"))
                <br>
                <h6 class="mb-5" style="color:red;">Your account will be deactivated if Maintenance Fee not paid till {{ date("d-m-Y",strtotime('+30 days',strtotime(auth('')->user()->maintenance_expiration_date))) }}</h6>
            @endif
          
        </div>
    </div>
</div>

@endsection

{{-- <form action="{{ route('charges') }}" method="post">
    @csrf
    <input type="text" name="name" placeholder="Name on Card">
    <input type="text" name="email" placeholder="Email">
    <input type="text" name="card_number" placeholder="Card Number">
    <input type="text" name="exp_month" placeholder="Expiry Month (MM)">
    <input type="text" name="exp_year" placeholder="Expiry Year (YYYY)">
    <input type="text" name="cvc" placeholder="CVC">
    <input type="submit" value="Pay Now">
  </form>

  
  @if (session()->has('success'))
  <div class="alert alert-success">
      {{ session()->get('success') }}
  </div>
@endif --}}


@push('script')

    <script>
      $('body').on('change', '.payment-method', function () {
        var pt = $(this).val();
        if(pt == 0){
          $('.display-address-type').html('Please use the BEP-20 or ERC-20 network and address below');
          // $('.display-address-id').html('0xD66D6410658e34a2Eb545e6E2CB67f45bb316D0f');
          $('.display-address-id').html('0xdc37C430Dd0C94e25c51053166AeC8d71F4bcfa4');
        }else if(pt == 1){
          $('.display-address-type').html('Please use the TRC-20 network and address below');
          // $('.display-address-id').html('TCvHxETNN2oztKjzB5YQJVDPPgA9JYBGp2');
          $('.display-address-id').html('TJNgWLKBwgGewVE84teVkVt14hobqFMBNp');
        }else if(pt == 2){
          $('.display-address-type').html('Please use the STELLAR network and address below');
          $('.display-address-id').html('GDNKUZ3ACP2JHPJ64VGIDFSQDY56MJEPJC6L7M5ATN63TY636XTU3KHY');
        }
      });
      $('button[type=submit]').click(function() {
        if ($(this).parents('form').valid()) {
          $(this).attr('disabled', 'disabled');
          $(this).parents('form').submit();
        }
      });
    </script>
    {{-- <script src="{{ asset('assets/global/js/card.js') }}"></script>

    <script>
        (function ($) {
            "use strict";
            var card = new Card({
                form: '#payment-form',
                container: '.card-wrapper',
                formSelectors: {
                    numberInput: 'input[name="cardNumber"]',
                    expiryInput: 'input[name="cardExpiry"]',
                    cvcInput: 'input[name="cardCVC"]',
                    nameInput: 'input[name="name"]'
                }
            });
        })(jQuery);
    </script> --}}

@if (auth('')->user()->is_suspend == 1)
    <!-- <script>
        var countDownDate = new Date("July 16, 2024 23:59:59").getTime();
        var x = setInterval(function() {
          var now = new Date().getTime();
          var distance = countDownDate - now;
          var days = Math.floor(distance / (1000 * 60 * 60 * 24));
          var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
          var seconds = Math.floor((distance % (1000 * 60)) / 1000);
              
          document.getElementById("countdown").innerHTML = days + "d " + hours + "h "
          + minutes + "m " + seconds + "s ";
              
          if (distance < 0) {
              clearInterval(x);
              document.getElementById("countdown").innerHTML = "EXPIRED";
          }
        }, 1000);
    </script> -->
@endif
{{-- <script>
    document.getElementById("manualBtn").addEventListener("click", function() {
      document.getElementById("manual").style.display = "block";
      document.getElementById("stripe").style.display = "none";
    });
    document.getElementById("stripeBtn").addEventListener("click", function() {
      document.getElementById("manual").style.display = "none";
      document.getElementById("stripe").style.display = "block";
    });
  </script> --}}
@endpush