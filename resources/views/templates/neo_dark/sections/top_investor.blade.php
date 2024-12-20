@php
    $topInvestor = \App\Models\Invest::with('user')
        ->selectRaw('SUM(amount) as totalAmount, user_id')
        ->orderBy('totalAmount', 'desc')
        ->groupBy('user_id')
        ->limit(8)
        ->get();
    
    $top_investorContent = getContent('top_investor.content', true);
@endphp

<!-- investor-section start -->
<section class="investor-section pb-150 pt-150">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-header text-center">
                    <h2 class="section__title">{{ __(@$top_investorContent->data_values->heading) }}</h2>
                    <p>{{ __(@$top_investorContent->data_values->sub_heading) }}</p>
                </div><!-- section-header end -->
            </div>
        </div>
        <div class="row gy-4 justify-content-center">
            @foreach ($topInvestor as $k => $data)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="investor-item">
                        <span class="investor-item__number">{{ ordinal($loop->iteration) }}</span>
                        <div class="investor-item__content">
                            <h6 class="investor__name text-shadow">{{ @json_decode(json_encode($data->user->username)) }}</h3>
                                <p>@lang('Total Invest') <span class="amount">{{ $general->cur_sym }}{{ showAmount($data->totalAmount) }}</span></p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
<!-- investor-section end -->
