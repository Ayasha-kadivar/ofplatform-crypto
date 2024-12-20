@php
    $investorContent = getContent('top_investor.content', true);
    $topInvestor = \App\Models\Invest::with('user')
        ->selectRaw('SUM(amount) as totalAmount, user_id')
        ->orderBy('totalAmount', 'desc')
        ->groupBy('user_id')
        ->limit(8)
        ->get();
@endphp
<section class="investor-area section-common-bg pt-70 pb-120">
    <div class="container mb-30">
        <div class="row  justify-content-center">
            <div class="col-xl-10 col-12">
                <div class="row">
                    <div class="col-xl-9 col-lg-9 col-md-8">
                        <div class="section_title_wrapper mb-50">
                            <h2 class="section-title">
                                {{ __(@$investorContent->data_values->heading) }}
                            </h2>
                            <p class="section-subtitle ">
                                {{ __(@$investorContent->data_values->sub_heading) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row gy-4 justify-content-center">
            @foreach ($topInvestor as $k => $data)
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="investor-card border-radius--5">
                        <span class="investor-card__number text--base">{{ ordinal($loop->iteration) }}</span>
                        <h5 class="investor-card__name">{{ $data->user->fullname }}</h5>
                        <h6 class="name">{{ @json_decode(json_encode($data->user->username)) }}</h6>
                        <span class="amount f-size-14 text--base">@lang('Investment') -
                            {{ $general->cur_sym }}{{ showAmount($data->totalAmount) }}</span>
                    </div><!-- investor-card end -->
                </div>
            @endforeach
        </div>
    </div>
</section>
