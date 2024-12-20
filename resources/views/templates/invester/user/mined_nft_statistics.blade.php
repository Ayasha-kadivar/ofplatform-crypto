@extends($activeTemplate.'layouts.master')
@section('content')
<div class="dashboard-inner">
    <div class="mb-4">
        <h3>@lang('Rented MinerNFTs')</h3>
    </div>
    

    <div class="mt-4">
        {{-- <div class="d-flex justify-content-between">
            <h5 class="title mb-3">@lang('Active Plan') <span class="count text-base">({{$activePlan}})</span></h5>
        </div> --}}
        <div class="plan-list d-flex flex-wrap flex-xxl-column gap-3 gap-xxl-0">
            @forelse($invests as $invest)
            <div class="plan-item-two">
                <div class="plan-info plan-inner-div">
                    <div class="d-flex align-items-center gap-3">
                        <div class="plan-name-data">
                            <div class="plan-name fw-bold">{{ $invest['mine_nft'] }} {{ $invest['mine_quantity_type'] }} NFT's mined</div>
                            <div class="plan-desc">@lang('Invested'): <span class="fw-bold">{{ showAmount($invest->mine_nft * ($invest->mine_quantity_type=='partial' ? $invest->partial_nft_price : $invest->one_nft_price)) }} {{ $general->cur_text }}</span></div>
                            @if($invest->mine_quantity_type=='partial')
                                <div class="plan-desc">@lang('Partial Total'): <span class="fw-bold" style="color:red;">{{ showAmount($invest->partial_total_amount)}} {{ $general->cur_text }}</span></div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="plan-start plan-inner-div">
                    <p class="plan-label">@lang('Start Date')</p>
                    <p class="plan-value date">{{ \Carbon\Carbon::parse($invest['buying_date'])->format('jS M Y') }}</p>
                </div>
                <div class="plan-inner-div">
                    <p class="plan-label">@lang('Next Return')</p>
                    <p class="plan-value">{{ (($invest['next_profit_date'] != '0000-00-00' && $invest['next_profit_date'] != NULL) ? \Carbon\Carbon::parse($invest['next_profit_date'])->format('jS M Y') : 'N/A') }}</p>
                </div>
                <div class="plan-inner-div text-end">
                    <p class="plan-label">@lang('Total Return')</p>
                    <p class="plan-value amount"> {{ $invest['total_profit'] }} {{ $general->cur_text }}</p>
                </div>
                <div class="plan-inner-div text-end">
                <a href="{{ route('user.invest.mined-nft-statistics.remove',$invest->id) }}" onclick="return confirm('Are you sure for delete minerNFT?')" class="plan-label fw-bold" style="color:red;cursor:pointer;"><u>@lang('Delete')</u></a>
                </div>
            </div>
            @empty
                <div class="accordion-body text-center bg-white p-4">
                    <h4 class="text--muted"><i class="far fa-frown"></i> {{ __($emptyMessage) }}</h4>
                </div>
            @endforelse
        </div>
    </div>
</div>
<div class="pagination">
    {{ $invests->links() }}
</div>

@endsection



@push('script')





@endpush
