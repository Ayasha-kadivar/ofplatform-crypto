@php
    $promotionCount = App\Models\PromotionTool::count();
@endphp

<header class="header">
    <div class="header__bottom">
        <div class="container">
            <nav class="navbar navbar-expand-xl p-0 align-items-center">
                <a class="site-logo site-title" href="{{ route('home') }}"><img src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="site-logo"></a>
                <ul class="account-menu responsive-account-menu ms-3">
                    <li class="icon"><a href="{{ route('user.home') }}"><i class="las la-user"></i></a></li>
                </ul>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="menu-toggle"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav main-menu ms-auto">
                        <li> <a href="{{ route('user.home') }}">@lang('Dashboard')</a></li>
                        <li><a href="{{ route('plan') }}">@lang('Investment')</a></li>
                        <li class="menu_has_children"><a href="javascript:void(0)">@lang('Finance')</a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('user.deposit.index') }}">@lang('Deposit')</a></li>
                                <li><a href="{{ route('user.withdraw') }}">@lang('Withdraw')</a></li>
                                @if ($general->b_transfer)
                                    <li><a href="{{ route('user.transfer.balance') }}">@lang('Transfer Balance')</a></li>
                                @endif
                                <li><a href="{{ route('user.transactions') }}">@lang('Transactions')</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('user.referrals') }}">@lang('Referrals')</a></li>
                        @if ($general->promotional_tool && $promotionCount)
                            <li><a href="{{ route('user.promotional.banner') }}">@lang('Promotional Tool')</a></li>
                        @endif
                        <li class="menu_has_children"><a href="javascript:void(0)">@lang('Account')</a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('user.profile.setting') }}">@lang('Profile Setting')</a></li>
                                <li><a href="{{ route('user.change.password') }}">@lang('Change Password')</a></li>
                                <li><a href="{{ route('user.twofactor') }}">@lang('2FA Security')</a></li>
                                <li><a href="{{ route('ticket.index') }}">@lang('Support Ticket')</a></li>
                                <li><a href="{{ route('user.logout') }}"> {{ __('Logout') }}</a></li>
                            </ul>
                        </li>
                    </ul>
                    <div class="nav-right">
                        <ul class="account-menu ms-3">
                            @guest
                                <li class="icon"><a href="{{ route('user.login') }}"><i class="las la-user"></i></a></li>
                            @else
                                <li class="icon"><a href="{{ route('user.home') }}"><i class="las la-user"></i></a></li>
                            @endif
                        </ul>
                        @if ($general->language_switch)
                            <select class="select d-inline-block w-auto ms-xl-3 langSel">
                                @foreach ($language as $item)
                                    <option value="{{ $item->code }}" @if (session('lang') == $item->code) selected @endif>{{ __($item->name) }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>
            </nav>
        </div>
    </div><!-- header__bottom end -->
</header>
<!-- header-section end  -->
