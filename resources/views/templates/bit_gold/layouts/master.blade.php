@extends($activeTemplate . 'layouts.app')
@section('panel')
    <div class="page-wrapper">
        @include($activeTemplate . 'partials.user_header')
        @if (!request()->routeIs('home'))
            @include($activeTemplate . 'partials.breadcrumb')
        @endif
        <div class="section-wrapper">
            @yield('content')
        </div>
        @include($activeTemplate . 'partials.footer')
    </div>
@endsection
