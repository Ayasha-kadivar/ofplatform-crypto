@extends($activeTemplate.'layouts.master')
@section('content')
<section class="pt-150 pb-150">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-bg">
                    <div class="card-body p-5">
                        <form action="{{$data->url}}" method="{{$data->method}}">
                            <ul class="list-group text-center">
                                <li class="list-group-item d-flex justify-content-between">
                                    @lang('You have to pay '):
                                    <strong>{{showAmount($deposit->final_amo)}} {{__($deposit->method_currency)}}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    @lang('You will get '):
                                    <strong>{{showAmount($deposit->amount)}}  {{__($general->cur_text)}}</strong>
                                </li>
                            </ul>
                             <script src="{{$data->src}}"
                                class="stripe-button"
                                @foreach($data->val as $key=> $value)
                                data-{{$key}}="{{$value}}"
                                @endforeach
                            >
                            </script>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        (function ($) {
            "use strict";
            $('button[type="submit"]').addClass("btn btn-primary w-100 mt-3");
            $('button[type="submit"]').text("Pay Now");
            $('button[type="submit"]').removeClass("stripe-button-el");
        })(jQuery);
    </script>
@endpush
