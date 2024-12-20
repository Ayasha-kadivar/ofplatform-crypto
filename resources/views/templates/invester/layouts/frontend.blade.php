@extends($activeTemplate . 'layouts.app')
@section('panel')
    <div class="preloader">
        <div class="animated-preloader"></div>
    </div>
    <div class="overlay"></div>
    <div class="header">
        <div class="container">
            <div class="header-bottom">
                <div class="header-bottom-area align-items-center">
                    <div class="logo"><a href="{{ route('home') }}"><img
                                src="{{ asset(getImage(getFilePath('logoIcon') . '/logo.png')) }}" alt="logo"></a></div>
                    <ul class="menu ms-auto">
                        <li>
                            <a href="{{ route('home') }}">@lang('Home')</a>
                        </li>
                        <li>
                            <a href="{{ route('plan') }}">@lang('Plan')</a>
                        </li>
                        @php
                            $pages = App\Models\Page::where('tempname', $activeTemplate)
                                ->where('is_default', 0)
                                ->get();
                        @endphp
                        @foreach ($pages as $k => $data)
                            <li><a href="{{ route('pages', [$data->slug]) }}">{{ __($data->name) }}</a></li>
                        @endforeach
                        <li>
                            <a href="{{ route('contact') }}">@lang('Contact')</a>
                        </li>
                        @if (auth()->check())
                            <li class="menu-btn">
                                <a href="{{ route('user.home') }}" class="ps-2"> <i class="las la-user"></i>
                                    @lang('Dashboard')</a>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('user.register') }}">@lang('Register')</a>
                            </li>
                            <li class="menu-btn">
                                <a href="{{ route('user.login') }}"> <i class="las la-user"></i> @lang('Login')</a>
                            </li>
                        @endif
                    </ul>
                    @if ($general->language_switch)
                    <select name="langSel" class="langSel form--control h-auto px-2 py-1 border-0">
                        @foreach ($language as $item)
                            <option value="{{ $item->code }}" @if (session('lang') == $item->code) selected @endif>
                                {{ __($item->name) }}
                            </option>
                        @endforeach
                    </select>
                    @endif
                    <div class="header-trigger-wrapper d-flex d-lg-none align-items-center">
                        <div class="header-trigger">
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @yield('content')

    @php
        $content = getContent('footer.content',true);
    @endphp
    <!-- Footer Section -->
    <footer class="py-4">
        <div class="container">
            <div class="footer-content text-center">
                <a href="{{ route('home') }}" class="logo mb-3"><img
                        src="{{ asset(getImage(getFilePath('logoIcon') . '/logo_2.png')) }}" alt="images"></a>
                <p class="footer-text mx-auto">{{ __($content->data_values->content) }}</p>
                <ul class="footer-links d-flex flex-wrap gap-3 justify-content-center mt-3 mb-3">
                    <li><a href="{{ route('home') }}" class="link-color">@lang('Home')</a></li>
                    <li><a href="{{ route('contact') }}" class="link-color">@lang('Contact')</a></li>
                    <li><a href="{{ route('user.login') }}" class="link-color">@lang('Sign In')</a></li>
                    <li><a href="{{ route('user.register') }}" class="link-color">@lang('Sign Up')</a></li>
                </ul>
                <p class="copy-right-text">&copy; {{ date('Y') }} <a href="{{ route('home') }}"
                        class="text--base">{{ __($general->site_name) }}</a>. @lang('All Rights Reserved')</p>
            </div>
        </div>
    </footer>
    <!-- Footer Section -->
@endsection
