@php
  $calculationCaption = getContent('calculation.content',true);
  $planList = \App\Models\Plan::where('status', 1)->orderBy('id','desc')->get();
@endphp
<section class="pt-120 pb-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-header text-center">
                    <h2 class="section-title"><span class="font-weight-normal">{{ __(@$calculationCaption->data_values->heading_w) }}</span> <b class="base--color">{{ __(@$calculationCaption->data_values->heading_c) }}</b></h2>
                    <p>{{ __(@$calculationCaption->data_values->sub_heading) }}</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="profit-calculator-wrapper">
                    <form class="profit-calculator">
                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label>@lang('Choose Plan')</label>
                                <select class="base--bg" id="changePlan">
                                    @foreach($planList as $k => $data)
                                        <option value="{{$data->id}}" data-fixed_amount="{{ $data->fixed_amount }}" data-minimum_amount="{{ $data->minimum }}" data-maximum_amount="{{ $data->maximum }}"> {{ $data->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label>@lang('Invest Amount') <span class="invest-range"></span></label>
                                <input type="text" placeholder="0.00" class="form-control base--bg invest-input" onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')">
                            </div>
                            <div class="col-lg-12">
                                <h5 class="profit-input base--color"></h5>
                                <code class="period"></code>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@push('script')
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {
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

                $(".invest-input").on('change', function () {
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
                  url: "{{route('planCalculator')}}",
                  type: "post",
                  data: {
                      planId,
                      _token:'{{ csrf_token() }}',
                      investAmount
                  },
                  success: function (response) {
                      var alertStatus = "{{$general->alert}}";
                      if (response.errors) {
                          iziToast.error({message: response.errors, position: "topRight"});
                      }else{
                          var msg = `${response.description}`
                          $('.profit-input').text(msg);
                          if(response.netProfit){
                              $('.period').text('Net Profit '+response.netProfit);
                          }
                      }
                  }
              });
          }
        })(jQuery);

    </script>
@endpush
