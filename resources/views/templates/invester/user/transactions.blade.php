@extends($activeTemplate.'layouts.master')
@section('content')
<div class="dashboard-inner">
    <div class="mb-4">

        <h3 class="mb-2 new--color--theme">@lang('My Transactions History')</h3>
    </div>
    <hr>
    <div class="filter-area mb-3">
        <div class="d-flex flex-wrap gap-4">
            <div class="flex-grow-1">
                <form action="">
                    <div class="custom-input-box trx-search">
                        <label>@lang('Transaction Number')</label>
                        <input type="text" name="search" value="{{ request()->search }}" placeholder="@lang('Transaction Number')">
                        <button type="submit" class="icon-area">
                            <i class="las la-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="flex-grow-1">
                <div class="custom-input-box">
                    <label>@lang('Wallet')</label>
                    <select name="wallet_type" onChange="window.location.href=this.value">
                        <option value={{queryBuild('wallet','')}}>@lang('All')</option>
                        <option value="{{ queryBuild('wallet_type','deposit_wallet') }}" @selected(request()->wallet_type == 'deposit_wallet')>@lang('Deposit Wallet')</option>
                        <option value="{{ queryBuild('wallet_type','interest_wallet') }}" @selected(request()->wallet_type == 'interest_wallet')>@lang('Interest Wallet')</option>
                    </select>
                </div>
            </div>
            <div class="flex-grow-1">
                <div class="custom-input-box">
                    <label>@lang('Type')</label>
                    <select name="trx_type" onChange="window.location.href=this.value">
                        <option value="{{queryBuild('trx_type','')}}">@lang('All')</option>
                        <option value="{{queryBuild('trx_type','%2B')}}" @selected(request()->trx_type == '+')>@lang('Plus')</option>
                        <option value="{{queryBuild('trx_type','-')}}" @selected(request()->trx_type == '-')>@lang('Minus')</option>
                    </select>
                </div>
            </div>
            <div class="flex-grow-1">
                <div class="custom-input-box">
                    <label>@lang('Remark')</label>
                    <select name="remark" onChange="window.location.href=this.value">
                        <option value="{{ queryBuild('remark','') }}">@lang('Any')</option>
                        @foreach($remarks as $remark)
                        <option value="{{ queryBuild('remark',$remark->remark) }}" @selected(request()->remark == $remark->remark)>{{ __(keyToTitle($remark->remark)) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="accordion table--acordion" id="transactionAccordion">
        @forelse($transactions as $transaction)
            <div class="accordion-item transaction-item">
                <h2 class="accordion-header" id="h-{{$loop->iteration}}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c-{{$loop->iteration}}">
                    <div class="col-lg-4 col-sm-5 col-8 order-1 icon-wrapper">
                        <div class="left">
                            <div class="icon tr-icon @if($transaction->trx_type == '+') icon-success @else icon-danger @endif">
                                <i class="las la-long-arrow-alt-right"></i>
                            </div>
                            <div class="content">
                                <h6 class="trans-title">{{ __(keyToTitle($transaction->remark)) }} - {{ __(keyToTitle($transaction->wallet_type)) }}</h6>
                                <span class="text-muted font-size--14px mt-2">{{showDateTime($transaction->created_at,'M d Y @g:i:a')}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-12 order-sm-2 order-3 content-wrapper mt-sm-0 mt-3">
                        <p class="text-muted font-size--14px"><b>#{{ $transaction->trx }}</b></p>
                    </div>
                    <div class="col-lg-4 col-sm-3 col-4 order-sm-3 order-2 text-end amount-wrapper">
                        <p>
                            <b>{{ showAmount($transaction->amount ) }} {{ $general->cur_text }}</b><br>
                            <small class="fw-bold text-muted">@lang('Balance'): {{ showAmount($transaction->post_balance) }} {{ $general->cur_text }}</small>
                        </p>

                    </div>
                </button>
                </h2>
                <div id="c-{{$loop->iteration}}" class="accordion-collapse collapse" aria-labelledby="h-1" data-bs-parent="#transactionAccordion">
                    <div class="accordion-body">
                        <ul class="caption-list">
                            <li>
                                <span class="caption">@lang('Charge')</span>
                                <span class="value">{{ showAmount($transaction->charge) }} {{ $general->cur_text }}</span>
                            </li>
                            <li>
                                <span class="caption">@lang('Post Balance')</span>
                                <span class="value">{{ showAmount($transaction->post_balance) }} {{ $general->cur_text }}</span>
                            </li>
                            <li>
                                <span class="caption">@lang('Details')</span>
                                <span class="value">{{ __($transaction->details) }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div><!-- transaction-item end -->
        @empty
            <div class="accordion-body text-center">
                <h4 class="text--muted"><i class="far fa-frown"></i> {{ __($emptyMessage) }}</h4>
            </div>
        @endforelse
    </div>


    @if($transactions->hasPages())
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    @endif
</div>
@endsection
@push('style')
    <style>
        .trx-search{
            position: relative;
        }
        .trx-search .icon-area{
            position: absolute;
            top: 10px;
            right: 8px;
            font-size: 20px;
            background: transparent;
            border: none;
        }
    </style>
@endpush
