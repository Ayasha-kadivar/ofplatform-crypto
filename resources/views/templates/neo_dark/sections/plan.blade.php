@php
    $planCaption = getContent('plan.content',true);
    $plans = App\Models\Plan::where('status',1)->where('featured', 1)->get();
    $gatewayCurrency = null;
    if(auth()->check()){
        $gatewayCurrency = App\Models\GatewayCurrency::whereHas('method', function ($gate) {
                $gate->where('status', 1);
            })->with('method')->orderby('method_code')->get();
    }
@endphp


<!-- pricing-section start -->
<section class="pricing-section pb-150 pt-150">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-header text-center">
                    <h2 class="section__title"> {{ __(@$planCaption->data_values->heading) }}</h2>
                    <div class="header__divider">
                        <span class="left-dot"></span>
                        <span class="right-dot"></span>
                    </div>
                    <p>{{ __(@$planCaption->data_values->sub_heading) }}</p>
                </div><!-- section-header end -->
            </div>
        </div>
        <div class="row mb-none-50">
            @include($activeTemplate.'partials.plan', ['plans' => $plans])
        </div>
    </div>
</section>
