  <header class="header3">
      <div id="header-sticky" class="header-main header-main3">
          <div class="container">
              <div class="row align-items-center">
                  <div class="col-xl-12 col-lg-12">
                      <div class="header-main-content-wrapper">
                          <div class="header-main-left header-main-left-header3">
                              <div class="logo header3-logo">
                                  <a href="{{ route('home') }}" class="logo-w">
                                      <img src="{{ getImage('assets/images/logoIcon/logo.png') }}"
                                          alt="@lang('logo img')">
                                  </a>
                              </div>
                          </div>
                          <div class=" header-main-middle">
                              <div class="main-menu main-menu3 d-none d-lg-inline-block">
                                  <nav id="mobile-menu2">
                                      <ul>
                                          <li>
                                              <a href="{{ route('home') }}">@lang('Home')</a>
                                          </li>
                                          @php
                                              $pages = App\Models\Page::where('tempname', $activeTemplate)
                                                  ->where('is_default', 0)
                                                  ->latest('id')
                                                  ->get();
                                          @endphp
                                          @foreach ($pages as $data)
                                              <li>
                                                  <a href="{{ route('pages', [$data->slug]) }}">
                                                      {{ __($data->name) }}
                                                  </a>
                                              </li>
                                          @endforeach
                                          <li>
                                              <a href="{{ route('plan') }}">@lang('Plans')</a>
                                          </li>
                                          <li>
                                              <a href="{{ route('blogs') }}">@lang('Blog')</a>
                                          </li>
                                          <li><a href="{{ route('contact') }}">@lang('Contact')</a></li>
                                      </ul>
                                  </nav>
                              </div>
                              <div class="menu-bar d-lg-none">
                                  <a class="side-toggle" href="javascript:void(0)">
                                      <div class="bar-icon">
                                          <span></span>
                                          <span></span>
                                          <span></span>
                                      </div>
                                  </a>
                              </div>
                              @if ($general->language_switch)
                                  <div class="header-main-right d-none d-lg-inline-block">
                                      <div class="header-language ">
                                          <select class="form-select custom-select langSel"
                                              aria-label="Default select example">
                                              @foreach ($language as $item)
                                                  <option value="{{ $item->code }}"
                                                      @if (session('lang') == $item->code) selected @endif>
                                                      {{ __($item->name) }}
                                                  </option>
                                              @endforeach
                                          </select>
                                      </div>
                                  </div>
                              @endif

                              @guest
                                  <div class="header-main-right-btn d-none d-lg-inline-block">
                                      <a class="btn btn--outline-base mr-10" href="{{ route('user.login') }}">
                                          @lang('Login')
                                      </a>
                                      <a class="btn btn--outline-base" href="{{ route('user.register') }}">
                                          @lang('Register')
                                      </a>
                                  </div>
                              @else
                                  <div class="header-main-right-btn d-none d-lg-inline-block">
                                      <a class="btn btn--outline-base mr-10" href="{{ route('user.home') }}">
                                          @lang('Dashboard')
                                      </a>
                                        <a class="btn btn--outline-base mr-10" href="{{ route('user.logout') }}">
                                            @lang('Logout')
                                            
                                        </a>
                                  </div>
                              @endguest
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </header>

  <div class="fix">
      <div class="side-info">
          <div class="side-info-content">
              <div class="offset-widget offset-logo mb-30">
                  <div class="row align-items-center">
                      <div class="col-9">
                          <div class="logo">
                              <a href="{{ route('home') }}">
                                  <img src="{{ getImage('assets/images/logoIcon/logo.png') }}" alt="@lang('Logo')">
                              </a>
                          </div>
                      </div>
                      <div class="col-3 text-end"><button class="side-info-close"><i class="las la-times"></i></button>
                      </div>
                  </div>
              </div>
              <div class="mobile-menu2 d-xl-none fix"></div>
              <div class="header-main-right-btn mb-30">
                  @guest
                      <a class="btn btn--outline-base mr-10" href="{{ route('user.login') }}"> @lang('Login')</a>
                      <a class="btn btn--outline-base" href="{{ route('user.register') }}"> @lang('Register')</a>
                  @else
                      <a class="btn btn--outline-base" href="{{ route('user.home') }}">@lang('Dashboard')</a>
                  @endguest
              </div>
          </div>
      </div>
  </div>
  <div class="offcanvas-overlay"></div>
  <div class="offcanvas-overlay-white"></div>
