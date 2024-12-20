@extends($activeTemplate.'layouts.master')

@section('content')
<div class="dashboard-inner">
    <div class="mb-4">
        <h3 class="mb-2 new--color--theme"> Gold Miner NFTs</h3>
    </div>

    <div class="mt-4">
        <div class="plan-list d-flex flex-wrap flex-xxl-column gap-3 gap-xxl-0">
            @forelse($goldnfts as $goldnft)
            <div class="plan-item-two">
                <div class="plan-info plan-inner-div">
                    <div class="d-flex align-items-center gap-3">
                        <div class="plan-name-data">
                            <div class="plan-name fw-bold">{{ $goldnft->quantity }} GoldMinerExcavatorNFT</div>
                            <div class="plan-desc">Invested: <span
                                    class="fw-bold">{{ $goldnft->gold_amount * $goldnft->quantity}}
                                    {{ $general->cur_text }}</span>
                            </div>
                            <div class="plan-desc">Discounted Price: <span class="fw-bold">{{ $goldnft->discount }}
                                    %</span></div>
                        </div>
                    </div>
                </div>
                <div class="plan-start plan-inner-div">
                    <p class="plan-label">Gold Market Place</p>
                    <p class="plan-value date">{{ $goldnft->gold_market_price }} {{$general->cur_text}}</p>
                </div>
                <div class="plan-start plan-inner-div">
                    <p class="plan-label">Buying Date</p>
                    <p class="plan-value date">{{ \Carbon\Carbon::parse($goldnft->buying_date)->format('jS M Y') }}</p>
                </div>
                <div class="plan-inner-div text-end">
                    <p class="plan-label">Payment Method</p>
                    <p class="plan-value amount">
                        {{ $goldnft->payment_method == 'deposit_wallet' ? 'Deposit Wallet' : ($goldnft->payment_method == 'reward_cubes' ? 'Reward Cube' : 'NFT Cube') }}
                    </p>

                </div>
                <div class="plan-inner-div text-end">
                    <p class="plan-label"> Gold Maturity Date</p>
                    <p class="plan-value amount">{{ \Carbon\Carbon::parse($goldnft->maturity_date)->format('jS M Y') }}
                    </p>
                </div>
            </div>
            @empty
            <div class="accordion-body text-center bg-white p-4">
                <h4 class="text--muted"><i class="far fa-frown"></i> No Gold Miner Excavator NFTs found.</h4>
            </div>
            @endforelse
        </div>
    </div>
</div>
<div class="pagination">
    {{ $goldnfts->links() }}
</div>
@endsection