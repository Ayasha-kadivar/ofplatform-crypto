@extends($activeTemplate . 'layouts.app')
@section('panel')


    <main class="color-version-one">
        @include($activeTemplate . 'partials.user_header')
        @if (!request()->routeIs('home'))
            @include($activeTemplate . 'partials.breadcrumb')
        @endif
        @include($activeTemplate . 'partials.sidebar')
        @yield('content')
        @include($activeTemplate . 'partials.footer')
    </main>
@endsection
