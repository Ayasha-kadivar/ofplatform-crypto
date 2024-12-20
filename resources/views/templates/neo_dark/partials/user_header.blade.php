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
                        <a href="{{ route('user.home') }}" class="btn btn-primary btn-small w-auto">@lang('Dashboard')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-bottom">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-xl align-items-center">

                <a href="{{url('/')}}" class="site-logo site-title">
                    <img src="{{ getImage(getFilePath('logoIcon').'/logo.png') }}" alt="logo">
                </a>


                <button type="button" class="dashboard-side-menu-open ms-auto"><i class="fa fa-bars"></i>
                </button>
            </nav>
        </div>
    </div>
</header>
