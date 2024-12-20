@extends($activeTemplate.'layouts.master')
@section('content')
<script>
    "use strict"
    function createCountDown(elementId, sec) {
        var tms = sec;
        var x = setInterval(function () {
            var distance = tms * 1000;
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            var days = `<span class="text--base">${days}d</span>`;
            var hours = `<span class="text--base">${hours}h</span>`;
            var minutes = `<span class="text--base">${minutes}m</span>`;
            var seconds = `<span class="text--base">${seconds}s</span>`;
            document.getElementById(elementId).innerHTML = days +' '+ hours + " " + minutes + " " + seconds;
            if (distance < 0) {
                clearInterval(x);
                document.getElementById(elementId).innerHTML = "COMPLETE";
            }
            tms--;
        }, 1000);
    }
</script>
<section class="pb-150 pt-150">
    <div class="container">
        <div class="row justify-content-center">
            <div class=" col-xl-6 col-lg-8">
                <div class="text-end mb-3">
                    <a href="{{ route('user.withdraw.history') }}" class="btn btn-primary">@lang('History')</a>
                </div>
                <div class="card card-bg">
                    <div class="card-body countdown-card">
                        @if($isHoliday && !$general->holiday_withdraw)
                        <div class="text-center">
                            <h4 class="mb-3">@lang('Withdrawal request is disable for today. Please wait for next working day.')</h4>
                            <h2 class="text--base mb-3">@lang('Next Working Day')</h2>
                            <div id="counter" class="countdown-wrapper"></div>
                            <script>createCountDown('counter', {{\Carbon\Carbon::parse($nextWorkingDay)->diffInSeconds()}});</script>
                        </div>
                        @else
                        <form action="{{route('user.withdraw.money')}}" method="post">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">@lang('Method')</label>
                                <select class="form-control form--control" name="method_code" required>
                                    <option value="">@lang('Select Gateway')</option>
                                    @foreach($withdrawMethod as $data)
                                        <option value="{{ $data->id }}" data-resource="{{$data}}">  {{__($data->name)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">@lang('Amount')</label>
                                <div class="input-group">
                                    <input type="number" step="any" name="amount" value="{{ old('amount') }}" class="form-control form--control" required>
                                    <span class="input-group-text">{{ $general->cur_text }}</span>
                                </div>
                            </div>
                            <div class="mt-3 preview-details d-none">
                                <ul class="list-group text-center">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>@lang('Limit')</span>
                                        <span><span class="min fw-bold">0</span> {{__($general->cur_text)}} - <span class="max fw-bold">0</span> {{__($general->cur_text)}}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>@lang('Charge')</span>
                                        <span><span class="charge fw-bold">0</span> {{__($general->cur_text)}}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>@lang('Receivable')</span> <span><span class="receivable fw-bold"> 0</span> {{__($general->cur_text)}} </span>
                                    </li>
                                    <li class="list-group-item d-none justify-content-between rate-element">

                                    </li>
                                    <li class="list-group-item d-none justify-content-between in-site-cur">
                                        <span>@lang('In') <span class="base-currency"></span></span>
                                        <strong class="final_amo">0</strong>
                                    </li>
                                </ul>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mt-3">@lang('Submit')</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('script')
<script>
    (function ($) {
            "use strict";
            $('select[name=method_code]').change(function(){
                if(!$('select[name=method_code]').val()){
                    $('.preview-details').addClass('d-none');
                    return false;
                }
                var resource = $('select[name=method_code] option:selected').data('resource');
                var fixed_charge = parseFloat(resource.fixed_charge);
                var percent_charge = parseFloat(resource.percent_charge);
                var rate = parseFloat(resource.rate)
                var toFixedDigit = 2;
                $('.min').text(parseFloat(resource.min_limit).toFixed(2));
                $('.max').text(parseFloat(resource.max_limit).toFixed(2));
                var amount = parseFloat($('input[name=amount]').val());
                if (!amount) {
                    amount = 0;
                }
                if(amount <= 0){
                    $('.preview-details').addClass('d-none');
                    return false;
                }
                $('.preview-details').removeClass('d-none');

                var charge = parseFloat(fixed_charge + (amount * percent_charge / 100)).toFixed(2);
                $('.charge').text(charge);
                if (resource.currency != '{{ $general->cur_text }}') {
                    var rateElement = `<span>@lang('Conversion Rate')</span> <span class="fw-bold">1 {{__($general->cur_text)}} = <span class="rate">${rate}</span>  <span class="base-currency">${resource.currency}</span></span>`;
                    $('.rate-element').html(rateElement);
                    $('.rate-element').removeClass('d-none');
                    $('.in-site-cur').removeClass('d-none');
                    $('.rate-element').addClass('d-flex');
                    $('.in-site-cur').addClass('d-flex');
                }else{
                    $('.rate-element').html('')
                    $('.rate-element').addClass('d-none');
                    $('.in-site-cur').addClass('d-none');
                    $('.rate-element').removeClass('d-flex');
                    $('.in-site-cur').removeClass('d-flex');
                }
                var receivable = parseFloat((parseFloat(amount) - parseFloat(charge))).toFixed(2);
                $('.receivable').text(receivable);
                var final_amo = parseFloat(parseFloat(receivable)*rate).toFixed(toFixedDigit);
                $('.final_amo').text(final_amo);
                $('.base-currency').text(resource.currency);
                $('.method_currency').text(resource.currency);
                $('input[name=amount]').on('input');
            });
            $('input[name=amount]').on('input',function(){
                var data = $('select[name=method_code]').change();
                $('.amount').text(parseFloat($(this).val()).toFixed(2));
            });
        })(jQuery);
</script>
@endpush
