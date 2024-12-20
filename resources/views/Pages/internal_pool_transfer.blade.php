@extends($activeTemplate.'layouts.master')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/web3@1.3.0/dist/web3.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<div>
    <div class="dashboard-inner container pt-120 pb-120">
        <div class="mb-4">
            <div class="row mb-4">
                <div class="col-lg-8">
                    <h3 class="mb-2 new--color--theme">@lang('Internal transfer')</h3>
                </div>
            </div>
            <div class="row gy-4">

                <div class="col-sm-4">
                    <form action="{{ route('user.inter_pool.save')}}" method="POST">
                        @csrf
                        <div class="card rounded-3 shadow p-2 mb-5 bg-body" style="height:668px">
                            <img src="{{asset('assets/images/nft3.jpeg')}}" class="card-img-top p-2" alt="...">
                            <div class="card-body">
                                <!-- <h5 class="card-title">Inter Cube Transfer 123</h5> -->
                                <span id="info-note" style="color: red; display: none;">When requesting an intertransfer from Rewards Cube please input the corresponding amount of FT equivalent to the $ value you want added to those Cubes, according to Live Price of FT in the moment of request</span>
                                <div class="row">
                                    <div class="col-12">
                                        <select class="form-select mt-2" id="pool_from"
                                            name="pool_from">
                                            <option value="">Select From</option>
                                            <option value="DepositWallet">From Deposit Wallet</option>
                                            <option value="RewardsCube">From Rewards Cube</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <select class="form-select mt-2" id="pool_to"
                                            name="pool_to">
                                            <option value="">Select To</option>
                                            <option value="VouchersCube">To Vouchers Cube</option>
                                            <option value="RewardsCube">To Rewards Cube</option>
                                            <option value="NftsCube">To NFTs Cube</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row  mt-2">
                                    <div class="col-12">
                                        <input type="number" min="1" class="form-control" id="ft" name="ft" placeholder="FT" step="0.01">
                                        <input type="number" min="1" class="form-control" id="amount" name="amount" placeholder="Amount" step="0.01" style="display: none;">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <input type="submit" class="cmn--btn plan-btn btn mt-2" name="Submit" value="Submit">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>

                <div id="error"></div>


            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#mineOption").change(function() {
            let selVal = $('#mineOption').val();
            if (selVal == 'whole') {
                $('#mineRentAmt').show();
                $('#qtyLabel').html("Quantity");
                $('#minePaymentMethodDiv').show();
            } else if (selVal == 'partial') {
                $('#mineRentAmt').show();
                $('#qtyLabel').html("Fractions");
                $('#minePaymentMethodDiv').show();
            } else {
                $('#mineRentAmt').hide();
                $('#minePaymentMethodDiv').hide();
                $('#qtyLabel').html("Fractions");
                $('#btnDepositWallet').hide();
            }
        });

        $("#pool_from").change(function() {
            if($(this).val() === 'DepositWallet') {
                $('#info-note').show(500);
                $('#ft').show();
                $('#ft').attr('required', true);
                $('#amount').attr('false', true);
                $('#amount').hide();
            } else {
                $('#ft').hide();
                $('#ft').attr('required', false);
                $('#amount').show();
                $('#amount').attr('required', true);
                $('#info-note').hide(500);
            }
        });
    });
</script>

@endsection
