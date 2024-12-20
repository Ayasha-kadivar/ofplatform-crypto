@php
    if (auth()->check() && auth()->user()->reg_step != 1) {
        $layout = 'frontend';
    }
@endphp

@extends($activeTemplate . 'layouts.' . $layout)
@section('content')
    <section class="section-common-bg {{ $layout == 'frontend' ? 'pt-160 pb-140' : '' }}">
        <div class="container">
            <div class="row mb-none-50 justify-content-center">
                @auth
                    <div class="col-md-12">
                        <div class="text-end mb-3">
                            <a href="{{ route('user.invest.statistics') }}" class="btn btn--outline-base btn-sm">
                                @lang('My Investments')
                            </a>
                        </div>
                    </div>
                @endauth
                @include($activeTemplate . 'partials.plan', ['plans' => $plans])
            </div>
        </div>
    </section>
    @guest
        @if ($sections->secs != null)
            @foreach (json_decode($sections->secs) as $sec)
                @include($activeTemplate . 'sections.' . $sec)
            @endforeach
        @endif
    @endguest
@endsection
