@php
$promotionCount = App\Models\PromotionTool::count();
$price_ft = App\Models\GeneralSetting::first();
@endphp
<script src="https://cdn.jsdelivr.net/npm/web3@1.3.0/dist/web3.min.js"></script>
<script>
window.addEventListener('load', async () => {
    // Check if web3 is available
    if (typeof web3 !== 'undefined') {
        // Use the web3 provider from MetaMask
        const web3 = new Web3(Web3.givenProvider);
        try {
            // Get the user's wallet address
            const accounts = await web3.eth.getAccounts();
            const address = accounts[0];
            // Get the user's wallet balance in wei
            const balanceWei = await web3.eth.getBalance(address);
            // Convert wei to ether
            const balanceEther = web3.utils.fromWei(balanceWei, 'ether');
            // Display the balance on the page
            document.getElementById("wallet-balance").innerHTML = balanceEther + " ETH";
        } catch (error) {
            console.error(error);
        }
    } else {
        console.log('web3 is not available');
    }
});
</script>
<style nonce="{{ csp_nonce() }}">
.sidebar-menu li a {
    padding: 8px 10px !important;
}

.back_none::before{
    background:none !important;
}

.menu-badge {
  padding: 5px;
  font-size: 14px;
  color: white;
  font-weight: bolder;
  border-radius: 10px;
  box-shadow: 0 4px 5px 0 rgba(0, 0, 0, 0.2);
}
</style>
<div class="dashboard-sidebar" id="dashboard-sidebar">
    <button class="btn-close dash-sidebar-close d-xl-none"></button>
    <a href="{{ route('home') }}" class="logo"><img src="{{ asset(getImage(getFilePath('logoIcon').'/logo_2.png')) }}"
            alt="images"></a>
    <div class="bg--lights">
        <div class="profile-info">
            {{-- <p class="fs--13px mb-3 fw-bold">@lang('METAMASK BALANCE')</p>
            <div id="wallet-balance"></div> --}}
            <p class="fs--13px mb-3 fw-bold label-color-black">@lang('ACCOUNT BALANCE')</p>
            <h4 class="usd-balance text--base mb-2 fs--30"> 
                    <small class="label-color-black">@lang('Deposit Wallet')</small> <br> <small class="label-color-green">{{ showAmount(auth()->user()->deposit_ft) }} <span class="currency--display--new--theme">FT</span></small>    
            </h4>

            <h4 class="usd-balance text--base mb-2 fs--30"> <small class="label-color-black">@lang('Vesting Wallet')</small> <br> <small class="label-color-green">0.00 <span class="currency--display--new--theme">FT</span></small> </h4>

            <h4 class="usd-balance text--base mb-2 fs--30"> <small class="label-color-black">@lang('Affiliate Rewards')</small> <br> <small class="label-color-green">{{ showAmount(auth()->user()->affiliate_reward )}} <span class="currency--display--new--theme">{{ $general->cur_text }}</span></small> </h4>

            <h4 class="usd-balance text--base mb-2 fs--30"> <small class="label-color-black">@lang('Total Cubes Balance')</small> <br> <small class="label-color-green">{{ showAmount(auth()->user()->interest_wallet+auth()->user()->pool_2+auth()->user()->pool_3+auth()->user()->pool_4 )}} <span class="currency--display--new--theme">{{ $general->cur_text }}</span></small> </h4>

            <h4 class="usd-balance text--base mb-2 fs--30"> <small class="label-color-black">@lang('Rewards Cube')</small> <br> <small class="label-color-orange">{{ showAmount(auth()->user()->interest_wallet) }} <span class="currency--display--new--theme">{{ $general->cur_text }}</span></small> </h4>
           
            
        </div>
        <div>
            <div class="mt-4 d-flex flex-wrap gap-2">
                {{-- <a href="{{ route('user.deposit.index') }}" class="btn btn--base btn--smd">@lang('Buy FT')</a> --}}
                <style>
                .disabled-button {
                    pointer-events: none;
                    opacity: 0.6;
                }
                </style>

                @php
                $currentDay = date('j');
                $currentMonth = date('n');
                $currentYear = date('Y');
                $user = auth()->user();
                @endphp

                @if($currentDay == 1 || $currentDay == 15)
                    {{-- <a href="{{ route('user.withdraw') }}" class="btn btn--secondary btn--smd"
                    style="width: 78%;">@lang('Withdraw')</a> --}}
                    <a href="#" class="btn btn--secondary btn--smd new--button--change disabled-button"
                    style="width: 78%;">@lang('Withdraw')</a>
                @else
                <a href="#" class="btn btn--secondary btn--smd new--button--change disabled-button"
                    style="width: 78%;">@lang('Withdraw')</a>
                @endif
            </div>

            <div class="mt-4 d-flex flex-wrap gap-2">
                <a href="{{ route('user.nftrent') }}" class="btn btn--secondary btn--smd  new--button--change @if ($user->is_suspend == 1) disabled-button @endif" style="width: 78%;">@lang('NFT
                    E-Shop')</a>
            </div>
            <div class="mt-4 d-flex flex-wrap gap-2">
                <a href="{{ route('user.inter-pool-transfer') }}" class="btn btn--secondary btn--smd  new--button--change @if ($user->is_suspend == 1) disabled-button @endif"
                    style="width: 78%;">@lang('Internal transfer')</a>
            </div>

        </div>
    </div>
    <ul class="sidebar-menu">

        
        @if ($user->is_suspend == 0)
            <li><a href="{{ route('user.home') }}" class="{{ menuActive('user.home') }}"><img
                        src="{{ asset($activeTemplateTrue.'/images/icon/dashboard.png') }}" alt="icon">
                    @lang('Dashboard')</a></li>
            <li><a href="{{ route('plan') }}" class="{{ menuActive(['plan']) }}"><img
                        src="{{ asset($activeTemplateTrue.'/images/icon/investment.png') }}" alt="icon"> @lang('Cubes')</a>
            </li>

            @php
                $count_rentnft = \App\Models\RentNFT::where('user_id', auth()->id())->sum('rented_nft');
                $activePlan = \App\Models\RentNFT::where('user_id', auth()->id())->where('contract_expiry_date', '>', date("Y-m-d"))->sum('rented_nft');
                $expiredPlan = \App\Models\RentNFT::where('user_id', auth()->id())->where('contract_expiry_date', '<=', date("Y-m-d"))->sum('rented_nft');
            @endphp
            <li><a href="{{ route('user.invest.statistics') }}"
                    class="{{ menuActive(['user.invest.statistics', 'user.invest.log']) }}"><img
                        src="{{ asset($activeTemplateTrue.'/images/icon/investment.png') }}" alt="icon"> @lang('Rented FamilyNFTs')
                    @if($expiredPlan == 0 && $activePlan > 0)
                    <span class="menu-badge pill green--bg--show ms-auto">{{$count_rentnft}}</span>
                    @elseif($expiredPlan > 0 && $activePlan > 0)
                    <span class="menu-badge pill orange--bg--show ms-auto">{{$count_rentnft}}</span>
                    @elseif($expiredPlan > 0 && $activePlan == 0)
                    <span class="menu-badge pill red--bg--show ms-auto">{{$count_rentnft}}</span>
                    @endif
                </a></li>
            {{--<li><a href="{{ route('user.invest.mined-nft-statistics') }}"
                    class="{{ menuActive(['user.invest.mined-nft-statistics']) }}"><img
                        src="{{ asset($activeTemplateTrue.'/images/icon/investment.png') }}" alt="icon"> @lang('Rented
                    MinerNFTs')</a></li>--}}
            
            <li class="sidebar-menu-item sidebar-dropdown">
                <a href="javascript:void(0)" class="{{menuActive('user.invest.gold-*')}}">
                    <img src="{{ asset($activeTemplateTrue.'/images/icon/investment.png') }}" alt="icon">
                    <span class="menu-title">@lang('GoldMiner') </span>
                </a>
                <div class="sidebar-submenu {{menuActive('user.invest.gold-*')}} ">
                    <ul>
                        <li>
                            <a href="{{ route('user.invest.gold-mined-nft-statistics') }}" class="{{ menuActive(['user.invest.gold-mined-nft-statistics']) }} back_none">GoldMinerExcavatorNFT</a></li>
                        <li>
                            <a href="{{ route('user.invest.gold-mined-shovel-nft-statistics') }}" class="{{ menuActive(['user.invest.gold-mined-shovel-nft-statistics']) }} back_none">GoldMinerShovelNFT</a></li>
                        <li>
                            <a href="{{ route('user.invest.gold-mined-land-nft-statistics') }}" class="{{ menuActive(['user.invest.gold-mined-land-nft-statistics']) }} back_none">GoldMinerLandNFT</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li><a href="{{ route('user.deposit.index') }}" class="{{ menuActive('user.deposit*') }}"><img
                src="{{ asset($activeTemplateTrue.'/images/icon/wallet.png') }}" alt="icon"> @lang('Deposit')</a></li>

            {{-- <li><a href="{{ route('user.withdraw') }}" class="{{ menuActive('user.withdraw*') }}"><img
                        src="{{ asset($activeTemplateTrue.'/images/icon/withdraw.png') }}" alt="icon"> @lang('Withdraw')</a>
            </li> --}}
            {{-- <li><a href="{{ route('user.nftrent') }}" class="{{ menuActive('user.nftrent') }}"><img
                src="{{ asset($activeTemplateTrue.'/images/icon/2fa.png') }}" alt="icon"> @lang('NFT E-Shop')</a></li> --}}
            @if($general->b_transfer)
            <li><a href="{{ route('user.transfer.balance') }}" class="{{ menuActive('user.transfer.balance') }}"><img
                        src="{{ asset($activeTemplateTrue.'/images/icon/balance-transfer.png') }}" alt="icon">
                    @lang('Transfer Balance')</a></li>
            @endif
            <li><a href="{{ route('user.transactions') }}" class="{{ menuActive('user.transactions') }}"><img
                        src="{{ asset($activeTemplateTrue.'/images/icon/transaction.png') }}" alt="icon">
                    @lang('Transactions')</a></li>
            <li><a href="{{ route('user.referrals') }}" class="{{ menuActive('user.referrals') }}"><img
                        src="{{ asset($activeTemplateTrue.'/images/icon/referral.png') }}" alt="icon">
                    @lang('Referrals')</a></li>
            @if($general->promotional_tool && $promotionCount)
            <li><a href="{{ route('user.promotional.banner') }}" class="{{ menuActive('user.promotional.banner') }}"><img
                        src="{{ asset($activeTemplateTrue.'/images/icon/promotion.png') }}" alt="icon"> @lang('Promotional
                    Banner')</a></li>
            @endif

            <li><a href="{{ route('ticket.index') }}"
                    class="{{ menuActive(['ticket', 'ticket.view', 'ticket.open']) }}"><img
                        src="{{ asset($activeTemplateTrue.'/images/icon/ticket.png') }}" alt="icon"> @lang('Support
                    Ticket')</a></li>
            <li><a href="{{ route('user.twofactor') }}" class="{{ menuActive('user.twofactor') }}"><img
                        src="{{ asset($activeTemplateTrue.'/images/icon/2fa.png') }}" alt="icon"> @lang('2FA')</a></li>
            @if(auth()->user()->kv != 1)
            <li><a href="{{ route('user.kyc.form') }}" class="{{ menuActive('user.kyc.form') }}"><img
                        src="{{ asset($activeTemplateTrue.'/images/icon/2fa.png') }}" alt="icon"> @lang('KYC-Form')</a></li>
            @endif
        @endif

        
        <li><a href="{{ route('user.maintenance-fee') }}" class="{{ menuActive('user.maintenance-fee') }}"><img
                    src="{{ asset($activeTemplateTrue.'/images/icon/payment.png') }}" alt="icon"> @lang('Maintenance Fee')</a>
        </li>

        @if ($user->is_suspend == 0)
            <li><a href="{{ route('user.vip_membership.index') }}" class="{{ menuActive('user.vip_membership*') }}"><img
            src="{{ asset($activeTemplateTrue.'/images/icon/2fa.png') }}" alt="icon"> @lang('VIP Membership')</a></li>
        
        
            <li><a href="{{ route('user.profile.setting') }}" class="{{ menuActive('user.profile.setting') }}"><img
                        src="{{ asset($activeTemplateTrue.'/images/icon/profile.png') }}" alt="icon"> @lang('Profile')</a>
            </li>
            <li><a href="{{ route('user.change.password') }}" class="{{ menuActive('user.change.password') }}"><img
                        src="{{ asset($activeTemplateTrue.'/images/icon/password.png') }}" alt="icon"> @lang('Change
                    Password')</a></li>
            <li><a href="{{ route('user.logout') }}" class="{{ menuActive('user.logout') }}"><img
                        src="{{ asset($activeTemplateTrue.'/images/icon/logout.png') }}" alt="icon"> @lang('Logout')</a>
            </li>
        @endif
    </ul>
</div>