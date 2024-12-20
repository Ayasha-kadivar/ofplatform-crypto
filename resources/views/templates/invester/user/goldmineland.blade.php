@extends($activeTemplate.'layouts.master')

@section('content')
<div class="dashboard-inner">
    <div class="mb-4">
        <h3 class="mb-2 new--color--theme"> Gold Miner Land NFTs</h3>
    </div>

    <div class="mt-4">
        <div class="plan-list d-flex flex-wrap flex-xxl-column gap-3 gap-xxl-0">
            @forelse($land_gold_mines as $land_gold_mine)
            <div class="plan-item-two">
                <div class="plan-info plan-inner-div">
                    <div class="d-flex align-items-center gap-3">
                        <div class="plan-name-data">
                            <div class="plan-name fw-bold">{{ $land_gold_mine->quantity }} GoldMinerShovelNFT</div>
                            <div class="plan-desc">Invested: <span
                                    class="fw-bold">{{ $land_gold_mine->gold_amount * $land_gold_mine->quantity}}
                                    {{ $general->cur_text }}</span>
                            </div>
                            <div class="plan-desc">Discounted Price: <span
                                    class="fw-bold">{{ $land_gold_mine->discount }}
                                    %</span></div>
                        </div>
                    </div>
                </div>
                <div class="plan-start plan-inner-div">
                    <p class="plan-label">Gold Market Place</p>
                    <p class="plan-value date">{{ $land_gold_mine->gold_market_price }} {{$general->cur_text}}</p>
                </div>
                <div class="plan-start plan-inner-div">
                    <p class="plan-label">Buying Date</p>
                    <p class="plan-value date">
                        {{ \Carbon\Carbon::parse($land_gold_mine->buying_date)->format('jS M Y') }}
                    </p>
                </div>
                <div class="plan-inner-div text-end">
                    <p class="plan-label">Payment Method</p>
                    <p class="plan-value amount">
                        {{ $land_gold_mine->payment_method == 'deposit_wallet' ? 'Deposit Wallet' : ($land_gold_mine->payment_method == 'reward_cubes' ? 'Reward Cube' : 'NFT Cube') }}
                    </p>

                </div>
                <div class="plan-inner-div text-end">
                    <p class="plan-label"> Gold Maturity Date</p>
                    <p class="plan-value amount">
                        {{ \Carbon\Carbon::parse($land_gold_mine->maturity_date)->format('jS M Y') }}
                    </p>
                </div>
            </div>
            @empty
            <div class="accordion-body text-center bg-white p-4">
                <h4 class="text--muted"><i class="far fa-frown"></i> No Gold Miner Land NFTs found.</h4>
            </div>
            @endforelse
        </div>
    </div>
</div>
<div class="pagination">
    {{ $land_gold_mines->links() }}
</div>
@endsection