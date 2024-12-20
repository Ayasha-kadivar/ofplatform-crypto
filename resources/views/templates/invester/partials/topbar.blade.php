@php
$curr_url = Route::currentRouteName();
@endphp
<div class="dashboard-nav d-flex flex-wrap align-items-center justify-content-between">
    <div class="nav-left d-flex gap-4 align-items-center">
        <div class="dash-sidebar-toggler d-xl-none" id="dash-sidebar-toggler">
            <i class="fas fa-bars"></i>
        </div>
    </div>
    <div class="nav-right d-flex flex-wrap align-items-center gap-3">
        @if ($general->language_switch)
            <select name="langSel" class="langSel form--control h-auto px-2 py-1 border-0" style="color: black !important">
                @foreach($language as $item)
                    <option value="{{$item->code}}" @if(session('lang') == $item->code) selected  @endif>{{ __($item->name) }}</option>
                @endforeach
            </select>
        @endif
        <ul class="nav-header-link d-flex flex-wrap gap-2">
            {{-- <li class="nav-item">
                <a href="{{ route('user.nftrent') }}" class="nav-link btn btn--secondary" style="padding: 5px 10px;font-size: 13px;"> @lang('NFT E-Shop')</a>
            </li> --}}
            {{-- <li class="nav-item">
                <a class="nav-link {{ $curr_url=='metamask'?'active':'' }}" href="{{ route('user.metamaskTest') }}">
                    <i class="ni ni-planet text-orange"></i>
                    <span class="nav-link-text">Metamask</span>
                </a>
            </li> --}}
            <li>
                <a class="link" href="javascript:void(0)">{{ getInitials(auth()->user()->fullname) }}</a>
                <div class="dropdown-wrapper">
                    <div class="dropdown-header">
                        <h6 class="name text--base">{{ auth()->user()->fullname }}</h6>
                        <p class="fs--14px">{{ auth()->user()->username }}</p>
                    </div>
                    <ul class="links">
                        <li><a href="{{ route('user.profile.setting') }}"><i class="las la-user"></i> @lang('Profile')</a></li>
                        <li><a href="{{ route('user.change.password') }}"><i class="las la-key"></i> @lang('Change Password')</a></li>
                        <li><a href="{{ route('user.logout') }}"><i class="las la-sign-out-alt"></i> @lang('Logout')</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>
