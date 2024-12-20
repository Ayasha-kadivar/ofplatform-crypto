@extends($activeTemplate.'layouts.app')
@section('panel')

<main class="color-version-one">
    @include($activeTemplate.'partials.header')
    @if(!request()->routeIs('home'))
    @include($activeTemplate.'partials.breadcrumb')
    @endif
    @yield('content')
    @include($activeTemplate.'partials.footer')
</main>

@endsection
