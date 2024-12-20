@php
$planList = \App\Models\Plan::where('status', 1)
    ->orderBy('id','desc')
    ->get();
$calculationContent = getContent('calculation.content', true);
@endphp

<!-- profit-calculator-section start -->
<section class="profit-calculator-section pb-150 pt-150">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="profit-calculator-wrapper">
                    <h2 class="title">{{ __(@$calculationContent->data_values->heading) }}</h2>
                    <p class="mb-4">{{ __(@$calculationContent->data_values->sub_heading) }}</p>

                    <form class="profit-calculator-form exchange-form">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>@lang('Plan')</label>
                                <select id="changePlan" class="form-control">
                                    @foreach ($planList as $k => $data)
                                        <option value="{{ $data->id }}" data-fixed_amount="{{ $data->fixed_amount }}" data-minimum_amount="{{ $data->minimum }}" data-maximum_amount="{{ $data->maximum }}">{{ $data->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label>@lang('Invest Amount') <span class="invest-range"></span></label>
                                <input type="text" placeholder="0.00" class="invest-input form-control" onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')">
                            </div>


                            <div class="form-group col-md-12">
                                <h5 class="profit-input base--color"></h5>
                                <code class="period"></code>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="profit-thumb">
                    <img src="{{getImage('assets/images/frontend/calculation/'.@$calculationContent->data_values->image)}}" alt="image">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- profit-calculator-section end -->

@push('script')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                var curSym = '{{ $general->cur_sym }}';
                $("#changePlan").on('change', function () {
                    var selectedPlan = $('#changePlan').find(':selected');
                    var planId = selectedPlan.val();
                    var data = selectedPlan.data();
                    var fixedAmount = parseFloat(data.fixed_amount).toFixed(2);
                    var minimumAmount = parseFloat(data.minimum_amount).toFixed(2);
                    var maximumAmount = parseFloat(data.maximum_amount).toFixed(2);

                    if (fixedAmount > 0) {
                        $('.invest-input').val(fixedAmount);
                        $('.invest-input').attr('readonly', true);
                        $('.invest-range').text('');
                    }else{
                        $('.invest-input').val(minimumAmount);
                        $('.invest-input').attr('readonly', false);
                        $('.invest-range').text('('+curSym+minimumAmount+' - '+curSym+maximumAmount+')');
                    }

                    var investAmount =  $('.invest-input').val();
                    var profitInput = $('.profit-input').text('');

                    $('.period').text('');

                    if (investAmount != '' && planId != null) {
                        ajaxPlanCalc(planId, investAmount)
                    }
                }).change();

                $(".invest-input").on('change', function() {
                    var planId = $("#changePlan option:selected").val();
                    var investAmount = $(this).val();
                    var profitInput = $('.profit-input').text('');
                    $('.period').text('');
                    if (investAmount != '' && planId != null) {
                        ajaxPlanCalc(planId, investAmount)
                    }
                });
            });

            function ajaxPlanCalc(planId, investAmount) {
                $.ajax({
                    url: "{{ route('planCalculator') }}",
                    type: "post",
                    data: {
                        planId,
                        _token: '{{ csrf_token() }}',
                        investAmount
                    },
                    success: function(response) {
                        var alertStatus = "{{ $general->alert }}";
                        if (response.errors) {
                            iziToast.error({
                                message: response.errors,
                                position: "topRight"
                            });
                        } else {
                            var msg = `${response.description}`
                            $('.profit-input').text(msg);
                            if (response.netProfit) {
                                $('.period').text('Net Profit ' + response.netProfit);
                            }
                        }
                    }
                });
            }
        })(jQuery);
    </script>
@endpush
