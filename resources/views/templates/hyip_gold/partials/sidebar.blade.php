<div class="dashboard-sidebar">
    <div class="inner-sidebar">
        <div class="sidebar-logo">
            <a href="{{ route('home') }}">
                <img src="{{ getImage('assets/images/logoIcon/logo.png') }}" alt="@lang('logo img')">
            </a>
        </div>
        <!-- Sidebar Remove Btn Start -->
        <div class="cross-btn d-lg-none d-block">
            <i class="fas fa-times"></i>
        </div>
        <!-- Sidebar Remove Btn End -->
        <div class="sidebar__menuWrapper">
            <!-- account blance start here -->
            <div class="dashboard-account">
                <div class="dashboard-account__icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <h6 class="dashboard-account__title">@lang('messages.welcome')</h6>
                <ul class="dashboard-account__wallet">
                    <li>
                        <span>
                            {{ showAmount(auth()->user()->deposit_wallet) }} {{ $general->cur_text }}
                        </span>
                        (@lang('Deposit Wallet'))
                    </li>
                    <li>
                        <span>
                            {{ showAmount(auth()->user()->interest_wallet) }} {{ $general->cur_text }}
                        </span>
                        (@lang('Interest Wallet'))
                    </li>
                </ul>

            </div>

            <div class="sidebar-menu">
                <ul class="sidebar-menu-list">
                    <li class="sidebar-menu-list__item {{ menuActive('user.home') }} ">
                        <a href="{{ route('user.home') }}" class="sidebar-menu-list__link">
                            <span class="icon"><i class="fa fa-tachometer-alt"></i></span>
                            <span class="text">@lang('Dashboard')</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-list__item {{ menuActive('plan') }} ">
                        <a href="{{ route('plan') }}" class="sidebar-menu-list__link">
                            <span class="icon">
                                <i class="fas fa-cubes pr-1"></i>
                            </span>
                            <span class="text">@lang('Investment')</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-list__item has-dropdown {{ menuActive('user.deposit*', 3) }} ">
                        <a href="javascript:void(0);" class="sidebar-menu-list__link ">
                            <span class="icon"><i class="fas fa-coins"></i></span>
                            <span class="text">@lang('Manage Deposits')</span>
                        </a>
                        <div class="sidebar-submenu {{ menuActive('user.deposit*', 2) }}">
                            <ul class="sidebar-submenu-list">
                                <li class="sidebar-submenu-list__item {{ menuActive('user.deposit') }}">
                                    <a href="{{ route('user.deposit.index') }}"
                                        class="sidebar-submenu-list__link">@lang('Add Deposit')</a>
                                </li>
                                <li class="sidebar-submenu-list__item {{ menuActive('user.deposit.history') }}">
                                    <a href="{{ route('user.deposit.history') }}"
                                        class="sidebar-submenu-list__link">@lang('Deposit history')</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="sidebar-menu-list__item has-dropdown {{ menuActive('user.withdraw*', 3) }}">
                        <a href="javascript:void(0);" class="sidebar-menu-list__link">
                            <span class="icon"><i class="fas fa-wallet"></i></i></span>
                            <span class="text">@lang('Manage Withdraw')</span>
                        </a>
                        <div class="sidebar-submenu {{ menuActive('user.withdraw*', 2) }}">
                            <ul class="sidebar-submenu-list">
                                <li class="sidebar-submenu-list__item {{ menuActive('user.withdraw') }}">
                                    <a href="{{ route('user.withdraw') }}" class="sidebar-submenu-list__link">
                                        @lang('Withdraw')
                                    </a>
                                </li>
                                <li class="sidebar-submenu-list__item {{ menuActive('user.withdraw.history') }}">
                                    <a href="{{ route('user.withdraw.history') }}"
                                        class="sidebar-submenu-list__link">
                                        @lang('Withdraw Log')
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    @if ($general->b_transfer)
                        <li class="sidebar-menu-list__item {{ menuActive('user.transfer.balance') }}">
                            <a href="{{ route('user.transfer.balance') }}" class="sidebar-menu-list__link">
                                <i class="fas fa-dollar-sign"></i>
                                @lang('Transfer Balanace')
                            </a>
                        </li>
                    @endif
                    <li class="sidebar-menu-list__item {{ menuActive('user.transactions') }}">
                        <a href="{{ route('user.transactions') }}" class="sidebar-menu-list__link">
                            <span class="icon"><i class="fas fa-exchange-alt pr-1"></i></span>
                            <span class="text">@lang('Transaction')</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-list__item {{ menuActive('user.referrals') }}">
                        <a href="{{ route('user.referrals') }}" class="sidebar-menu-list__link">
                            <span class="icon">
                                <i class="fas fa-handshake  pr-1"></i>
                            </span>
                            <span class="text">@lang('Referrals')</span>
                        </a>
                    </li>
                    @if ($general->b_transfer)
                        <li class="sidebar-menu-list__item  {{ menuActive('user.promotional.banner') }}">
                            <a href="{{ route('user.promotional.banner') }}" class=" sidebar-menu-list__link">
                                <i class="fas fa-ad "></i>
                                @lang('Promotional Banner')
                            </a>
                        </li>
                    @endif
                    <li class="sidebar-menu-list__item {{ menuActive('ticket*') }}">
                        <a href="{{ route('ticket.index') }}" class="sidebar-menu-list__link">
                            <span class="icon"><i class="fas fa-ticket-alt"></i></span>
                            <span class="text">@lang('Tickets')</span>
                        </a>
                    </li>


                    <li class="sidebar-menu-list__item  {{ menuActive('user.twofactor') }}">
                        <a href="{{ route('user.twofactor') }}" class="sidebar-menu-list__link">
                            <span class="icon"><i class="fas fa-user-lock"></i></span>
                            <span class="text">@lang('2FA Security')</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-list__item ">
                        <a href="{{ route('user.logout') }}" class="sidebar-menu-list__link">
                            <span class="icon"><i class="fas fa-sign-out-alt"></i> </span>
                            <span class="text">@lang('Logout')</span>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</div>
