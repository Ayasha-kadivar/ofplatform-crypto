@extends($activeTemplate.'layouts.master')
@section('content')

    <style>
        .design_set{
            padding-bottom:40px;
            text-align: center;
            display: flex;
            justify-content: center;
            font-size:15px;
            font-weight:600;
            line-height: 20px;
        }
        .img_logo{
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 58%;
        }
        
    </style>
    <div class="dashboard-inner">
        <div class="mb-4">
            <h3 class="mb-2 new--color--theme">@lang('Wallet Address Submission')</h3>
        </div>
        <div class="row">
            <div class="design_set">
                <div class="lobstr_logo" style="display:table-cell; vertical-align:middle; text-align:center">
                    <img class="img_logo" src="{{ getImage('assets/images/frontend/lobstr_logo/lwl.png') }}" alt="logo"><br>
                    <p>We strongly recommend use of LOBSTR wallet, as at the moment, only this wallet meet all requirements.<br>
                    Use of any other Stellar wallet is at your own risk, and Our Family is not responsible for eventual losses!</p><br>
                    <div class="col-12">
                        <a href="https://play.google.com/store/apps/details?id=com.lobstr.client" style="margin-right:14px;"><img src="{{ getImage('assets/images/frontend/lobstr_logo/gstore.png') }}" alt="gstore"></a>
                        <a href="https://itunes.apple.com/us/app/lobstr-stellar-wallet/id1404357892?mt=8" style="margin-left:14px;"><img src="{{ getImage('assets/images/frontend/lobstr_logo/appstore.png') }}" alt="appstore"></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card custom--card">
                    <div class="card-body">
                        <form action="{{route('user.wallet.submit')}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label class="form-label required" for="wallet_address">Wallet Address</label>
                                <input type="text" class="form-control form--control" name="wallet_address" value="" required="" id="wallet_address">
                            </div>

                            <div class="form-group" style="display:inline-flex;">
                                <button type="button" style="margin-right:10px;"
                                    class="email_send_OTP form-control" id="wallet_addr_verified"
                                     
                                    onclick="email_send_OTP()">Send OTP</button>
                                <input 
                                    type="number" 
                                     name= "otp"
                                    id="email_OTP" 
                                    class="email_verify_OTP form-control form--control required" 
                                    placeholder="Enter OTP" required="" />

                            </div>
                            
                            <div class="form-check mt-5">
                                <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" required>
                                <label class="form-check-label" for="defaultCheck1">
                                I confirm that above wallet address can be added to my profile
                                </label>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@push('style')
    <style>
        .form-group{
            margin-bottom: 12px;
        }
    </style>
@endpush
<script>
    function email_send_OTP()
    {
        
        jQuery("#wallet_addr_verified").attr("disabled", true);
        jQuery.ajax
        ({
            url:"{{route('user.otp.send')}}", 
            type:'post',
            data:{
                "_token": "{{ csrf_token() }}",
            },
            success:function(data)
            {
                if(data =='otp_sent'){
                    $("#wallet_addr_verified").attr("disabled", false);
                    alert('Successfully OTP sended your registered email address.')
                }else{
                    $("#wallet_addr_verified").attr("disabled", false);
                    alert('Something went to wrong please try after some time.')
                }  
            }
        });   
    }
</script>