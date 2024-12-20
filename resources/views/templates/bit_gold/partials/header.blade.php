<!-- header-section start  -->
<header class="header">
    <div class="header__bottom">
        <div class="container">
            <nav class="navbar navbar-expand-xl p-0 align-items-center">
                <a class="site-logo site-title" href="{{ route('home') }}"><img
                        src="{{ getImage(getFilePath('logoIcon').'/logo.png') }}"
                        alt="site-logo"></a>
                <ul class="account-menu responsive-account-menu ms-3">
                    @guest
                        <li class="icon"><a href="{{ route('user.login') }}"><i class="las la-user"></i></a></li>
                    @else
                        <li class="icon"><a href="{{ route('user.home') }}"><i class="las la-user"></i></a></li>
                    @endif
                </ul>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="menu-toggle"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav main-menu ms-auto">
                        <li> <a href="{{ route('home') }}">@lang('Home')</a></li>
                        @php
                            $pages = App\Models\Page::where('tempname',$activeTemplate)->where('is_default',0)->get();
                        @endphp
                        @foreach($pages as $k => $data)
                            <li><a href="{{ route('pages',[$data->slug]) }}">{{ __($data->name) }}</a></li>
                        @endforeach
                        <li><a href="{{ route('plan') }}">@lang('Plan')</a></li>
                        <li><a href="{{ route('blogs') }}">@lang('Blog')</a></li>
                        <li><a href="{{ route('contact') }}">@lang('Contact')</a></li>
                    </ul>
                    <div class="nav-right">
                        <ul class="account-menu ms-3">
                            @guest
                                <li class="icon"><a href="{{ route('user.login') }}"><i
                                            class="las la-user"></i></a></li>
                            @else
                                <li class="icon"><a href="{{ route('user.home') }}"><i
                                            class="las la-user"></i></a></li>
                            @endif
                        </ul>
                        @if ($general->language_switch)
                        <select class="select d-inline-block w-auto ms-xl-3 langSel">
                            @foreach($language as $item)
                                <option value="{{ $item->code }}" @if(session('lang')==$item->code) selected
                                    @endif>{{ __($item->name) }}</option>
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
