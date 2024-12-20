@extends($activeTemplate.'layouts.app')
@section('panel')


<div class="d-flex flex-wrap">

    @include($activeTemplate.'partials.sidebar')

    <div class="dashboard-wrapper">

        @include($activeTemplate.'partials.topbar')

        <div class="dashboard-container">

            @yield('content')

        </div>
    </div>
</div>
@endsection
