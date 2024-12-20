@extends($activeTemplate.'layouts.app')
@section('panel')
    <section class="account-section position-relative">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card custom--card">
                        <div class="card-body">
                            @if($user->ban_type == 'temporary')
                            <h3 class="text-center text-danger mb-3">@lang('You are temporarily banned till '){{date('d-m-Y', strtotime($user->till_ban_date))}}</h3>
                            @else
                            <h3 class="text-center text-danger mb-3">@lang('You are permanently banned!')</h3>
                            @endif
                            <p class="fw-bold mb-1">@lang('Reason'):</p>
                            <p>{{ $user->ban_reason }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
