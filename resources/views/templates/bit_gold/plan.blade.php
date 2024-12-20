@extends($activeTemplate . 'layouts.' . $layout)
@section('content')
    <section class="@if($layout == 'frontend') pt-120 pb-120 @else pt-60 pb-60 @endif">
        <div class="container">
            <div class="row justify-content-center gy-4">
                @auth
                    <div class="col-md-12">
                        <div class="text-end">
                            <a href="{{ route('user.invest.statistics') }}" class="btn btn--base">
                                @lang('My Investments')
                            </a>
                        </div>
                    </div>
                @endauth
                @include($activeTemplate.'partials.plan', ['plans' => $plans])
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

