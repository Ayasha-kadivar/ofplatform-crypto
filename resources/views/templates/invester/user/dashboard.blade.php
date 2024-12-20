@extends($activeTemplate.'layouts.master')
@section('content')

    <div class="dashboard-inner">

        <div class="row mt-4 mb-4">
            @if($user->kv == 0)
            <div class=" col-lg-2 height--new--design padding-new-design mt-4 mb-4">
                <div class="card card--100--percent--add card-body">
                    <div class="mb-2">
                        <img width="100%" src="{{ asset($activeTemplateTrue.'/images/dashboardImg/KYC_not_submitted.png') }}" alt="icon">
                        <span class="new--m-t-15 new--dash--label--icon--text">Your identity <br> still not confirmed.</span>
                        <span class="new--dash--label--icon--text">Kindly submit request</span>
                        <a href="{{ route('user.kyc.form') }}" class="btn new--dash--label--icon--link"><img class="new--dash--label--icon--img" src="{{ asset($activeTemplateTrue.'/images/dashboardImg/submit.png') }}" width="100%" alt=""></a>
                    </div>
                </div>
            </div>
            @elseif($user->kv == 2)
            <div class=" col-lg-2 height--new--design padding-new-design mt-4 mb-4">
                <div class="card card--100--percent--add card-body">
                    <div class="mb-2">
                        <img width="100%" src="{{ asset($activeTemplateTrue.'/images/dashboardImg/KYC_pending.png') }}" alt="icon">
                        <span class="new--m-t-15 new--dash--label--icon--text">Your KYC request <br> has been submitted <br> and pending <br> for approval!</span>
                        <span class="new--dash--label--icon--text">Kindly wait for admin approval.</span>
                    </div>
                </div>
            </div>
            @else
            <div class=" col-lg-2 height--new--design padding-new-design mt-4 mb-4">
                <div class="card card--100--percent--add card-body">
                    <div class="mb-2">
                        <img width="100%" src="{{ asset($activeTemplateTrue.'/images/dashboardImg/KYC_approved.png') }}" alt="icon">
                        <span class="new--m-t-15 new--dash--label--icon--text green--with--bold">Your KYC request <br> has been APPROVED</span>
                        <span class="new--dash--label--icon--text">As approved member you able to withdraw <br> to External wallet</span>
                    </div>
                </div>
            </div>
            @endif
            @if($user->ts == 0)
            <div class=" col-lg-2 height--new--design padding-new-design mt-4 mb-4">
                <div class="card card--100--percent--add card-body">
                    <div class="mb-2">
                        <img width="100%" src="{{ asset($activeTemplateTrue.'/images/dashboardImg/2FA_grey.png') }}" alt="icon">
                        <span class="new--m-t-15 new--dash--label--icon--text red--with--bold">2FA not active</span>
                        <span class="new--dash--label--icon--text">Turn 2FA on to secure your account!</span>
                        <a href="{{ route('user.twofactor') }}" class="red--with--bold new--dash--label--icon--text"><span class="">CONFIGURE 2FA</span></a>
                    </div>
                </div>
            </div>
            @else
            <div class=" col-lg-2 height--new--design padding-new-design mt-4 mb-4">
                <div class="card card--100--percent--add card-body">
                    <div class="mb-2">
                        <img width="100%" src="{{ asset($activeTemplateTrue.'/images/dashboardImg/2FA_rgb.png') }}" alt="icon">
                        <span class="new--m-t-15 new--dash--label--icon--text green--with--bold">2FA protection active</span>
                        <span class="new--dash--label--icon--text">2FA protection is active for your account</span>
                        <span class="new--dash--label--icon--text">Your account is secured!</span>
                    </div>
                </div>
            </div>
            @endif
            @if($user->fee_status == 0)
            <div class=" col-lg-2 height--new--design padding-new-design mt-4 mb-4">
                <div class="card card--100--percent--add card-body">
                    <div class="mb-2">
                        <a href="{{ route('user.maintenance-fee') }}">
                            <img width="100%" src="{{ asset($activeTemplateTrue.'/images/dashboardImg/MAINT_gray.png') }}" alt="icon">
                            <span class="new--m-t-15 new--dash--label--icon--text red--with--bold">Maintenance fee is not paid!</span>
                            <span class="new--dash--label--icon--text">Check Maintenance fee section for details</span>
                        </a>
                    </div>
                </div>
            </div>
            @elseif($user->fee_status == 1)
            <div class=" col-lg-2 height--new--design padding-new-design mt-4 mb-4">
                <div class="card card--100--percent--add card-body">
                    <div class="mb-2">
                        <img width="100%" src="{{ asset($activeTemplateTrue.'/images/dashboardImg/MAINT_gray.png') }}" alt="icon">
                        <span class="new--m-t-15 new--dash--label--icon--text orange--with--bold">Maintenance fee payment has been submitted and pending for approval!</span>
                        <span class="new--dash--label--icon--text">Kindly wait for admin approval.</span>
                    </div>
                </div>
            </div>
            @else
            <div class=" col-lg-2 height--new--design padding-new-design mt-4 mb-4">
                <div class="card card--100--percent--add card-body">
                    <div class="mb-2">
                        <a href="{{ route('user.maintenance-fee') }}">
                            <img width="100%" src="{{ asset($activeTemplateTrue.'/images/dashboardImg/MAINT_rgb.png') }}" alt="icon">
                            <span class="new--m-t-15 new--dash--label--icon--text green--with--bold">Maintenance fee payment confirmed!</span>
                            <span class="new--dash--label--icon--text">Payment is valid till <span class="green--with--bold fs-16  new--bolder">{{date('d.m.Y',strtotime($user->maintenance_expiration_date))}}.</span> <br> (DD.MM.YYYY)</span>
                        </a>
                    </div>
                </div>
            </div>
            @endif

            @if($user->wallet_address == 0) 
            <div class=" col-lg-2 height--new--design padding-new-design mt-4 mb-4">
                <div class="card card--100--percent--add card-body">
                    <div class="mb-2">
                        <img width="100%" src="{{ asset($activeTemplateTrue.'/images/dashboardImg/WALLET_grey.png') }}" alt="icon">
                        <span class="new--m-t-15 new--dash--label--icon--text red--with--bold">WALLET ADDRESS NOT PRESENT</span>
                        <span class="new--dash--label--icon--text">Add wallet address for external withdrawals</span>
                        <a href="{{route('user.wallet.form')}}" class="new--m-t-20 new--dash--label--icon--text red--with--bold">ADD WALLET ADDRESS</a>
                    </div>
                </div>
            </div>
            @else
            <div class=" col-lg-2 height--new--design padding-new-design mt-4 mb-4">
                <div class="card card--100--percent--add card-body">
                    <div class="mb-2">
                        <img width="100%" src="{{ asset($activeTemplateTrue.'/images/dashboardImg/WALLET_rgb.png') }}" alt="icon">
                        <span class="new--m-t-15 new--dash--label--icon--text green--with--bold">WALLET ADDRESS CONFIGURED</span>
                        <span class="new--dash--label--icon--text">Wallet address is present in profile and external withdrawals are possible</span>
                    </div>
                </div>
            </div>
            @endif

            @if(isset($vip_data) && $vip_data->status == 0 && $user->vip_user == 0)
            <div class=" col-lg-2 height--new--design mt-4 mb-4">
                <div class="card card--100--percent--add card-body">
                    <div class="mb-2">
                            <img width="100%" src="{{ asset($activeTemplateTrue.'/images/dashboardImg/VIP_grey.png') }}" alt="icon">
                            <span class="new--m-t-15 new--dash--label--icon--text orange--with--bold">Your VIP payment has been processed and pending for approval!</span>
                            <span class="new--dash--label--icon--text">Kindly wait for Admin approval.</span>
                    </div>
                </div>
            </div>
            @elseif($user->vip_user == 1)
            <div class=" col-lg-2 height--new--design mt-4 mb-4">
                <div class="card card--100--percent--add card-body">
                    <div class="mb-2">
                        <img width="100%" src="{{ asset($activeTemplateTrue.'/images/dashboardImg/VIP_rgb.png') }}" alt="icon">
                        <span class="new--m-t-15 new--dash--label--icon--text green--with--bold">VIP membership is active!</span>
                        <span class="new--dash--label--icon--text">Membership is valid till <span class="green--with--bold fs-16 new--bolder">{{date('d.m.Y',strtotime($user->vip_user_date))}}.</span> <br> (DD.MM.YYYY)</span>
                    </div>
                </div>
            </div>
            @else
            <div class=" col-lg-2 height--new--design mt-4 mb-4">
                <div class="card card--100--percent--add card-body">
                    <div class="mb-2">
                        <img width="100%" src="{{ asset($activeTemplateTrue.'/images/dashboardImg/VIP_grey.png') }}" alt="icon">
                        <span class="new--m-t-15 new--dash--label--icon--text red--with--bold">VIP inactive</span>
                        <span class="new--dash--label--icon--text">VIP membership provides privileges to ease your account management</span>
                        <a href="{{ route('user.vip_membership.index') }}" class="red--with--bold new--dash--label--icon--text"><span class="">ACTIVATE VIP</span></a>
                    </div>
                </div>
            </div>
            @endif
            
        </div>



        <div class="row mt-4 mb-4">
            @if($user->assembly_user == 1)
            <div class=" col-lg-4 height--new--design--row2 padding-new-design mt-4 mb-4">
                <!-- new--align--center -->
                <div class="row"><h5 class="green--with--bold align--center--new">You are Assembly member</h5></div>
                <div class="card card--85--percent--add card-body">
                    <div class="mb-2 row">
                        <div class=" col-lg-5" >
                            <img width="100%" src="{{ asset($activeTemplateTrue.'/images/dashboardImg/ASSEMBLY_rgb.png') }}" alt="icon">
                        </div>
                        <div class=" col-lg-7" >
                        <span class="new--dash--label--icon--text">Membership is valid till <span class="green--with--bold fs-16 new--bolder">{{date('d.m.Y',strtotime($user->assembly_user_date))}}.</span> <br> (DD.MM.YYYY)</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif  
            
            <div class=" col-lg-4 height--new--design--row2 padding-new-design mt-4 mb-4">
                <!-- new--align--center -->
                <div class="row "><h5 class="green--with--bold align--center--new">Current FT price</h5></div>
                <div class="card card--85--percent--add card-body">
                    <div class="mb-2 row">
                        <div class=" col-lg-5" >
                            <img width="100%" src="{{ asset($activeTemplateTrue.'/images/dashboardImg/FT_rgb.png') }}" alt="icon">
                        </div>
                        <div class=" col-lg-7" >
                            <span class="green--with--bold new--dash--label--icon--text fs-20 bolder--dollor-rate">1.00 USD</span>
                            <span class="new--dash--label--icon--text">Last refresh <span class="green--with--bold">5 seconds ago</span></span>
                        </div>
                    </div>
                </div>
            </div>

            @if($user->launch_nft_owner == 1)
            <div class=" col-lg-4 height--new--design--row2 padding-new-design mt-4 mb-4">
                <!-- new--align--center -->
                <div class="row"><h5 class="green--with--bold align--center--new">You are LaunchNFT owner</h5></div>
                <div class="card card--85--percent--add card-body">
                    <div class="mb-2 row">
                        <div class="col-lg-12">
                            <img width="100%" src="{{ asset($activeTemplateTrue.'/images/dashboardImg/LAUNCHNFT_rgb.png') }}" alt="icon">
                        </div>
                    </div>
                </div>
            </div>
            @endif

            
            
        </div>

        
        <div class="row mt-4 mb-4">
            <div class="row">
            <!-- left--with--padding -->
                <h5 class="green--with--bold align--center--new ">@lang('Deposits and Withdrawals')</h5><br>
            </div>
            <div class="show--deposit--withdraw--new">
                
                <div class="col-md-6 card-body card "> 
                    <div class="col-md-12 green--with--bold">
                        <h6>Last 5 Deposits</h6><br>
                    </div>
                    <table>
                        
                        <tr>
                            <th>Date</th>
                            <th class="display--right--view">Amount</th>
                        </tr>
                        @if($deposit_data && count($deposit_data) > 0)
                        @foreach($deposit_data as $kd => $vd)
                        <tr>
                            <td>{{showDatetime($vd->created_at,'d/m/Y h:i:s')}}</td>
                            <td class="display--right--view">{{showamount($vd->amount)}} FT</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                        <td colspan="2">No Data Found</td>
                        </tr>
                        @endif
                    </table>
                </div>
                <div class="col-md-6 col-md-6 card-body card ">
                    <div class="col-md-12 green--with--bold">
                        <h6>Last 5 Withdrawals</h6><br>
                    </div>
                    {{-- <table>
                        <tr>
                            <th>Date</th>
                            <th class="display--right--view">Amount</th>
                        </tr>
                        @if($withdrawal_data && count($withdrawal_data) > 0)
                        @foreach($withdrawal_data as $kd => $vd)
                        <tr>
                            <td>{{showDatetime($vd->created_at,'d/m/Y h:i:s')}}</td>
                            <td class="display--right--view">{{showamount($vd->amount)}} FT</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="2">No Data Found</td>
                        </tr>
                        @endif
                    </table> --}}
                </div>
            
            </div>
            
        </div>
        

        {{-- @if ($user->deposit_wallet <= 0 && $user->interest_wallet <= 0)
        <div class="alert border border--danger" role="alert">
            <div class="alert__icon d-flex align-items-center text--danger"><i class="fas fa-exclamation-triangle"></i></div>
            <p class="alert__message">
                <span class="fw-bold">@lang('Empty Balance')</span><br>
                <small><i>@lang('Your balance is empty. Please make') <a href="{{ route('user.deposit.index') }}" class="link-color">@lang('deposit')</a> @lang('for your next investment.')</i></small>
            </p>
        </div>
        @endif

        @if ($user->deposits->where('status',1)->count() == 1 && !$user->invests->count())
        <div class="alert border border--success" role="alert">
            <div class="alert__icon d-flex align-items-center text--success"><i class="fas fa-check"></i></div>
            <p class="alert__message">
                <span class="fw-bold">@lang('First Deposit')</span><br>
                <small><i><span class="fw-bold">@lang('Congratulations!')</span> @lang('You\'ve made your first deposit successfully. Go to') <a href="{{ route('plan') }}" class="link-color">@lang('investment plan')</a> @lang('page and invest now')</i></small>
            </p>
        </div>
        @endif --}}

        {{-- @if($pendingWithdrawals)
        <div class="alert border border--primary" role="alert">
            <div class="alert__icon d-flex align-items-center text--primary"><i class="fas fa-spinner"></i></div>
            <p class="alert__message">
                <span class="fw-bold">@lang('Withdrawal Pending')</span><br>
                <small><i>@lang('Total') {{ showAmount($pendingWithdrawals) }} {{ $general->cur_text }} @lang('withdrawal request is pending. Please wait for admin approval. The amount will send to the account which you\'ve provided. See') <a href="{{ route('user.withdraw.history') }}" class="link-color">@lang('withdrawal history')</a></i></small>
            </p>
        </div>
        @endif --}}

        {{--  @if($pendingDeposits)
        <div class="alert border border--primary" role="alert">
            <div class="alert__icon d-flex align-items-center text--primary"><i class="fas fa-spinner"></i></div>
            <p class="alert__message">
                <span class="fw-bold">@lang('Deposit Pending')</span><br>
                <small><i>@lang('Total') {{ showAmount($pendingDeposits) }} {{ $general->cur_text }} @lang('deposit request is pending. Please wait for admin approval. See') <a href="{{ route('user.deposit.history') }}" class="link-color">@lang('deposit history')</a></i></small>
            </p>
        </div>
        @endif --}}

        {{-- @if(!$user->ts)
        <div class="alert border border--warning" role="alert">
            <div class="alert__icon d-flex align-items-center text--warning"><i class="fas fa-user-lock"></i></div>
            <p class="alert__message">
                <span class="fw-bold">@lang('2FA Authentication')</span><br>
                <small><i>@lang('To keep safe your account, Please enable') <a href="{{ route('user.twofactor') }}" class="link-color">@lang('2FA')</a> @lang('security').</i> @lang('It will make secure your account and balance.')</small>
            </p>
        </div>
        @endif --}}

        {{-- @if($isHoliday)
        <div class="alert border border--info" role="alert">
            <div class="alert__icon d-flex align-items-center text--info"><i class="fas fa-toggle-off"></i></div>
            <p class="alert__message">
                <span class="fw-bold">@lang('Holiday')</span><br>
                <small><i>@lang('Today is holiday on this system. You\'ll not get any interest today from this system. Also you\'re unable to make withdrawal request today.') <br> @lang('The next working day is coming after') <span id="counter" class="fw-bold text--primary fs--15px"></span></i></small>
            </p>
        </div>
        @endif --}}
        
        @if($user->is_verify_email == NULL)
        <!-- <div class="alert border border--danger" role="alert">
            <div class="alert__icon d-flex align-items-center text--danger"><i class="fas fa-envelope"></i></div>
            <p class="alert__message">
                <span class="fw-bold">@lang('Verify Your Email')</span><br>
                {{-- <small><i>@lang('Please submit the required KYC information to verify yourself. Otherwise, you couldn\'t make any withdrawal requests to the system.') <a href="{{ route('user.kyc.form') }}" class="link-color">@lang('Click here')</a> @lang('to submit KYC information').</i></small> --}}
                <small><i>@lang('Verify your Email ') <a href="{{ route('user.profile.update') }}" class="link-color">@lang('Click here')</a> @lang('to Verify').</i></small>
            </p>
        </div> -->
        @endif

        @if($user->kv == 0)
        <!-- <div class="alert border border--info" role="alert">
            <div class="alert__icon d-flex align-items-center text--info"><i class="fas fa-file-signature"></i></div>
            <p class="alert__message">
                <span class="fw-bold">@lang('KYC Verification Required')</span><br>
                {{-- <small><i>@lang('Please submit the required KYC information to verify yourself. Otherwise, you couldn\'t make any withdrawal requests to the system.') <a href="{{ route('user.kyc.form') }}" class="link-color">@lang('Click here')</a> @lang('to submit KYC information').</i></small> --}}
                <small><i>@lang('KYC Required before withdrawal. ') <a href="{{ route('user.kyc.form') }}" class="link-color">@lang('Click here')</a> @lang('to submit KYC information').</i></small>
            </p>
        </div> -->
        @elseif($user->kv == 2)
        <!-- <div class="alert border border--warning" role="alert">
            <div class="alert__icon d-flex align-items-center text--warning"><i class="fas fa-user-check"></i></div>
            <p class="alert__message">
                <span class="fw-bold">@lang('KYC Verification Pending')</span><br>
                <small><i>@lang('KYC information is pending for admin approval. ') <a href="{{ route('user.kyc.data') }}" class="link-color">@lang('Click here')</a> @lang('to see your submitted information')</i></small>
            </p>
        </div> -->
        @else 
        <!-- <div class="alert border border--success" role="alert">
            <div class="alert__icon d-flex align-items-center text--success"><i class="fas fa-user-check"></i></div>
            <p class="alert__message">
                <span class="fw-bold">@lang('KYC Approved')</span><br>
                <small><i>@lang('Your KYC form is Approved') </small>
            </p>
        </div> -->
        @endif
        @if($user->fee_status == 0)
        <!-- <div class="alert border border--danger" role="alert">
            <div class="alert__icon d-flex align-items-center text--danger"><i class="fas fa-file-signature"></i></div>
            <p class="alert__message">
                <span class="fw-bold">@lang('Maintenance Fee Required')</span><br>
                <small><i>@lang('Please submit the required Maintenance Fee Information. Otherwise, your account will be deleted after 30 days. ') <a href="{{ url('user/maintenance-fee') }}" class="link-color">@lang('Click here')</a> @lang('to submit Fee information').</i></small>
            </p>
        </div> -->
        @elseif($user->fee_status == 1)
        <!-- <div class="alert border border--warning" role="alert">
            <div class="alert__icon d-flex align-items-center text--warning"><i class="fas fa-user-check"></i></div>
            <p class="alert__message">
                <span class="fw-bold">@lang('Maintenance Fee Pending')</span><br>
                <small><i>@lang('Maintenance Fee Information is pending for admin approval.') </i></small>
            </p>
        </div>  -->
        @else 
        <!-- <div class="alert border border--success" role="alert">
            <div class="alert__icon d-flex align-items-center text--success"><i class="fas fa-user-check"></i></div>
            <p class="alert__message">
                <span class="fw-bold">@lang('Maintenance Fee')</span><br>
                <small><i>@lang('Your Maintenance Fee Approved') </small>
            </p>
        </div> -->
        @endif
        
        @if($user->wallet_address == 0) 
        <!-- <div class="alert border border--info" role="alert">
            <div class="alert__icon d-flex align-items-center text--info"><i class="fas fa-wallet"></i></div>
            <p class="alert__message">
                <span class="fw-bold">@lang('Wallet Address Required')</span><br>
                <small><i>@lang('Wallet Address Required before withdrawal. ') <a href="{{ route('user.wallet.form') }}" class="link-color">@lang('Click here')</a> @lang('to submit Wallet Address').</i></small>
            </p>
        </div> -->
        @else 
        <!-- <div class="alert border border--success" role="alert">
            <div class="alert__icon d-flex align-items-center text--success"><i class="fas fa-user-check"></i></div>
            <p class="alert__message">
                <span class="fw-bold">@lang('Wallet Address Verified')</span><br>
                <small><i>@lang('Your Wallet Address is Verified please check in your profile section.') </small>
            </p>
        </div> -->
        @endif

        <div class="row g-3 mt-4">
            {{-- <div class="col-lg-4">
                <div class="dashboard-widget">
                    <div class="d-flex justify-content-between">
                        <h5 class="text-secondary">@lang('Successful Deposits')</h5>
                    </div>
                    <h3 class="text--secondary my-4">{{ showAmount($successfulDeposits) }} {{ $general->cur_text }}</h3>
                    <div class="widget-lists">
                        <div class="row">
                            <div class="col-4">
                                <p class="fw-bold">@lang('Submitted')</p>
                                <span>{{ $general->cur_sym }}{{ showAmount($submittedDeposits) }}</span>
                            </div>
                            <div class="col-4">
                                <p class="fw-bold">@lang('Pending')</p>
                                <span>{{ $general->cur_sym }}{{ showAmount($pendingDeposits) }}</span>
                            </div>
                            <div class="col-4">
                                <p class="fw-bold">@lang('Rejected')</p>
                                <span>{{ $general->cur_sym }}{{ showAmount($rejectedDeposits) }}</span>
                            </div>
                        </div>
                        <hr>
                        <p><small><i>@lang('You\'ve requested to deposit') {{ $general->cur_sym }}{{ showAmount($requestedDeposits) }}. @lang('Where') {{ $general->cur_sym }}{{ showAmount($initiatedDeposits) }} @lang('is just initiated but not submitted.')</i></small></p>
                    </div>
                </div>
            </div> 
            <div class="col-lg-4">
                <div class="dashboard-widget">
                    <div class="d-flex justify-content-between">
                        <h5 class="text-secondary">@lang('Successful Withdrawals')</h5>
                    </div>
                    <h3 class="text--secondary my-4">{{ showAmount($successfulWithdrawals) }} {{ $general->cur_text }}</h3>
                    <div class="widget-lists">
                        <div class="row">
                            <div class="col-4">
                                <p class="fw-bold">@lang('Submitted')</p>
                                <span>{{ $general->cur_sym }}{{ showAmount($submittedWithdrawals) }}</span>
                            </div>
                            <div class="col-4">
                                <p class="fw-bold">@lang('Pending')</p>
                                <span>{{ $general->cur_sym }}{{ showAmount($pendingWithdrawals) }}</span>
                            </div>
                            <div class="col-4">
                                <p class="fw-bold">@lang('Rejected')</p>
                                <span>{{ $general->cur_sym }}{{ showAmount($rejectedWithdrawals) }}</span>
                            </div>
                        </div>
                        <hr>
                        <p><small><i>@lang('You\'ve requested to withdraw') {{ $general->cur_sym }}{{ showAmount($requestedWithdrawals) }}. @lang('Where') {{ $general->cur_sym }}{{ showAmount($initiatedWithdrawals) }} @lang('is just initiated but not submitted.')</i></small></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="dashboard-widget">
                    <div class="d-flex justify-content-between">
                        <h5 class="text-secondary">@lang('Total Rented')</h5>
                    </div>
                    <h3 class="text--secondary my-4">{{ showAmount($invests) }} {{ $general->cur_text }}</h3>
                    <div class="widget-lists">
                        <div class="row">
                            <div class="col-4">
                                <p class="fw-bold">@lang('Completed')</p>
                                <span>{{ $general->cur_sym }}{{ showAmount($completedInvests) }}</span>
                            </div>
                            <div class="col-4">
                                <p class="fw-bold">@lang('Running')</p>
                                <span>{{ $general->cur_sym }}{{ showAmount($runningInvests) }}</span>
                            </div>
                            <div class="col-4">
                                <p class="fw-bold">@lang('Interests')</p>
                                <span>{{ $general->cur_sym }}{{ showAmount($interests) }}</span>
                            </div>
                        </div>
                        <hr>
                        <p><small><i>@lang('You\'ve invested') {{ $general->cur_sym }}{{ showAmount($depositWalletInvests) }} @lang('from the deposit wallet and') {{ $general->cur_sym }}{{ showAmount($interestWalletInvests) }} @lang('from the interest wallet')</i></small></p>
                    </div>
                </div>
            </div> --}}
        </div>

        <div class="row mt-4 mb-4">
            <div class="col-12">
                <div class=" card-body card">
                    <div class="mb-2">
                        <h5 class="title">@lang('Latest ROI Statistics')</h5>
                        <p> <small><i>@lang('Here is last 30 days statistics of your ROI (Return on Investment)')</i></small></p>
                    </div>
                    <div id="chart"></div>
                </div>
            </div>
        </div>

        <div class="new--social--part">
            <div class="row mt-4 mb-4">
                <div class="col-md-12 title--new--social">
                    <span>@lang('Join us on socials!')</span>
                </div>
                <div class="col-md-3">
                    <a target="_blank" href="https://t.me/OurFamily_Official_SRB">
                        <img width="100%" src="{{ asset($activeTemplateTrue.'/images/dashboardImg/OFF_SRB.png') }}" alt="icon">
                    </a>
                </div>
                <div class="col-md-3">
                    <a target="_blank" href="https://t.me/OurFamily_Official_EN">
                        <img width="100%" src="{{ asset($activeTemplateTrue.'/images/dashboardImg/OFF_ENG.png') }}" alt="icon">
                    </a>
                </div>
                <div class="col-md-3">
                    <a target="_blank" href="https://t.me/OurFamily_Official_ES">
                        <img width="100%" src="{{ asset($activeTemplateTrue.'/images/dashboardImg/OFF_ESP.png') }}" alt="icon">
                    </a>
                </div>
                <div class="col-md-3">
                    <a target="_blank" href="https://t.me/OurFamily_Official_HU">
                        <img width="100%" src="{{ asset($activeTemplateTrue.'/images/dashboardImg/OFF_HUN.png') }}" alt="icon">
                    </a>
                </div>
                
            </div>

            <div class="row mt-4 mb-4">
                <div class="col-md-1">
                </div>
                <div class="col-md-3 ml-45--new">
                    <a target="_blank" href="https://www.youtube.com/@OurFamily_FT">
                        <img width="100%" src="{{ asset($activeTemplateTrue.'/images/dashboardImg/YT.png') }}" alt="icon">
                    </a>
                </div>
                <div class="col-md-3">
                    <a target="_blank" href="https://linktr.ee/familytoken">
                        <img width="100%" src="{{ asset($activeTemplateTrue.'/images/dashboardImg/LINKTREE.png') }}" alt="icon">
                    </a>
                </div>
                
                <div class="col-md-3">
                    <a target="_blank" href="https://ourfamily.technology/">
                        <img width="100%" src="{{ asset($activeTemplateTrue.'/images/dashboardImg/FRONTPAGE.png') }}" alt="icon">
                    </a>
                </div>
                <div class="col-md-1">
                </div>
            </div>
        </div>
        

    </div>

@endsection

@push('script')
<script src="{{ asset($activeTemplateTrue.'/js/lib/apexcharts.min.js') }}"></script>

<script>

    // apex-line chart
    var options = {
        chart: {
            height: 350,
            type: "area",
            toolbar: {
                show: false
            },
            dropShadow: {
                enabled: true,
                enabledSeries: [0],
                top: -2,
                left: 0,
                blur: 10,
                opacity: 0.08,
            },
            animations: {
                enabled: true,
                easing: 'linear',
                dynamicAnimation: {
                    speed: 1000
                }
            },
        },
        colors: ["#ff6633"],
        dataLabels: {
            enabled: false
        },
        series: [
            {
                name: "Price",
                data: [
                    @foreach($chartData as $cData)
                        {{ getAmount($cData->amount) }},
                    @endforeach

                ]
            }
        ],
        fill: {
            type: "gradient",
            //colors: ['#4c7de6', '#4c7de6', '#4c7de6'],
            colors: ['#ff6633','#ff6633','#ff6633'],
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.6,
                opacityTo: 0.9,
                stops: [0, 90, 100]
            }
        },
        xaxis: {
            title: "Value",
            categories: [
                @foreach($chartData as $cData)
                "{{ Carbon\Carbon::parse($cData->date)->format('d F') }}",
                @endforeach
            ]
        },
        grid: {
            padding: {
                left: 5,
                right: 5
            },
            xaxis: {
                lines: {
                    show: false
                }
            },
            yaxis: {
                lines: {
                    show: false
                }
            },
        },
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);

    chart.render();

    @if($isHoliday)
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

        createCountDown('counter', {{\Carbon\Carbon::parse($nextWorkingDay)->diffInSeconds()}});
    @endif

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

</script>
@endpush
