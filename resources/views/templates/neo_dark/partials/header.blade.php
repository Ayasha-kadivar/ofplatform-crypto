    <!-- header-section start  -->
<header class="header-section">
    <div class="header-top">
        <div class="container-fluid">
            <div class="header-top-content d-flex flex-wrap align-items-center justify-content-between">
                <div class="header-top-left">
                        @if ($general->language_switch)
                            <select class="langSel">
                                @foreach($language as $item)
                                    <option value="{{$item->code}}" @if(session('lang') == $item->code) selected  @endif>{{ __($item->name) }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                <div class="header-top-right">
                    <div class="header-action d-flex flex-wrap align-items-center">
                        @guest
                        <a href="{{ route('user.login') }}" class="btn btn-primary btn-small">@lang('Login')</a>
                        <a href="{{ route('user.register') }}" class="btn btn-primary btn-small">@lang('Register')</a>
                        @else
                            <a href="{{ route('user.logout') }}" class="btn btn-primary btn-small w-auto">@lang('Logout')</a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-bottom">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-xl align-items-center">

                <a href="{{ route('home') }}" class="site-logo site-title">
                    <img src="{{ getImage(getFilePath('logoIcon').'/logo.png') }}" alt="logo">
                </a>
                <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="menu-toggle"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav main-menu ms-auto">
                        <li><a href="{{route('home')}}">{{trans('Home')}}</a></li>
                        @php
                            $pages = App\Models\Page::where('tempname',$activeTemplate)->where('is_default',0)->get();
                        @endphp
                        @foreach($pages as $k => $data)
                            <li><a href="{{ route('pages',[$data->slug]) }}">{{ __($data->name) }}</a></li>
                        @endforeach
                        <li><a href="{{route('plan')}}">{{trans('Plan')}}</a></li>
                        <li><a href="{{ route('blogs') }}">@lang('Blog')</a></li>
                        <li><a href="{{ route('contact') }}">@lang('Contact')</a></li>



                        @guest
                        <li class="d-sm-none"><a href="{{route('user.login')}}">@lang('Login')</a></li>
                        <li class="d-sm-none"><a href="{{route('user.register')}}">@lang('Register')</a></li>
                        @endguest
                        
                        @auth
                            <li><a href="{{route('user.home')}}">@lang('Dashboard')</a></li>
                        @endauth
                        @if ($general->language_switch)
                            <li class="menu_has_children d-sm-none">
                                <select class="langSel">
                                    @foreach($language as $item)
                                        <option value="{{$item->code}}" @if(session('lang') == $item->code) selected  @endif>{{ __($item->name) }}</option>
                                    @endforeach
                                </select>
                            </li>
                        @endif
                    </ul>

                </div><!-- navbar-collapse end -->
            </nav>
        </div>
    </div>
</header>
