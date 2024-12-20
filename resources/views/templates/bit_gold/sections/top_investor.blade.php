@php
    $topInvestor = \App\Models\Invest::with('user')
        ->selectRaw('SUM(amount) as totalAmount, user_id')
        ->orderBy('totalAmount', 'desc')
        ->groupBy('user_id')
        ->limit(8)
        ->get();
    
    $top_investorContent = getContent('top_investor.content', true);
@endphp
<section class="pt-120 pb-120 border-top-1">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="section-header">
                    <h2 class="section-title"><span class="font-weight-normal">{{ __(@$top_investorContent->data_values->heading_w) }}</span> <b class="base--color">{{ __(@$top_investorContent->data_values->heading_c) }}</b></h2>
                    <p>{{ __(@$top_investorContent->data_values->sub_heading) }}</p>
                </div>
            </div>
        </div><!-- row end -->
        <div class="row justify-content-center gy-4">
            @foreach ($topInvestor as $k => $data)
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="investor-card border-radius--5">
                        <span class="investor-card__number">{{ ordinal($loop->iteration) }}</span>
                        <h5 class="investor-card__name">{{ $data->user->fullname }}</h5>
                        <h6 class="name">{{ @json_decode(json_encode($data->user->username)) }}</h6>
                        <span class="amount f-size-14">@lang('Investment') - {{ $general->cur_sym }}{{ showAmount($data->totalAmount) }}</span>
                    </div><!-- investor-card end -->
                </div>
            @endforeach
        </div>
    </div>
</section>
