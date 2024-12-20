@php
    $planList = \App\Models\Plan::where('status', 1)
        ->orderBy('id', 'desc')
        ->get();
@endphp
<div class="select-plan-area section-common-bg pb-120">
    <div class="container">
        <div class="select-paln-wrapper">
            <form action="mail.php" class="plan-form">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group has-icon has-icon-select mb-20">
                            <label for="plan">@lang('Select Plan')</label>
                            <select class="form-select form--control" id="changePlan">
                                @foreach ($planList as $plan)
                                    <option value="{{ $plan->id }}" data-fixed_amount="{{ $plan->fixed_amount }}"
                                        data-minimum_amount="{{ $plan->minimum }}"
                                        data-maximum_amount="{{ $plan->maximum }}"> {{ $plan->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group has-icon mb-20 ">
                            <label for="amount">@lang('Enter The Amount')</label>
                            <input type="text" class="form-control form--control invest-input" placeholder="0.00"
                                onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')">
                        </div>
                    </div>
                    <div class="col-sm-12 ">
                        <div class="select-plan-price mb-20">
                            <ul>
                                <li class="profit-input base--color"></li>
                                <li><code class="period"></code>

                            </ul>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                var curSym = '{{ $general->cur_sym }}';
                $("#changePlan").on('change', function() {
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
                    } else {
                        $('.invest-input').val(minimumAmount);
                        $('.invest-input').attr('readonly', false);
                        $('.invest-range').text('(' + curSym + minimumAmount + ' - ' + curSym +
                            maximumAmount + ')');
                    }

                    var investAmount = $('.invest-input').val();
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
