@extends($activeTemplate.'layouts.master')
@section('content')
<div class="dashboard-inner">
    <div class="mb-4">
        <h3 class="mb-2 new--color--theme">@lang('Rented FamilyNFTs')</h3>
    </div>
    

    <div class="mt-4">
        <div class="d-flex justify-content-between">
            <h5 class="title mb-12">
                <div style="float:left">@lang('Total FamilyNFTs ') 
                    @if($expiredPlan == 0 && $activePlan > 0)
                    <span class="count text-base menu-badge pill green--bg--show">{{ $total }}</span>
                    @elseif($expiredPlan > 0 && $activePlan > 0)
                    <span class="count text-base menu-badge pill orange--bg--show">{{ $total }}</span>
                    @elseif($expiredPlan > 0 && $activePlan == 0)
                    <span class="count text-base menu-badge pill red--bg--show">{{ $total }}</span>
                    @else
                    <span class="count text-base menu-badge pill green--bg--show">{{ $total }}</span>
                    @endif
                </div>
            </h5>

            <h5 class="title mb-12"><div style="float:left">@lang('Active ') <span class="count text-base menu-badge pill green--bg--show">{{ $activePlan }}</span></div> <div style="float:right">&nbsp;&nbsp; @lang('Expired ') <span class="menu-badge pill red--bg--show count text-base">{{ $expiredPlan }}</span></div></h5>

        </div>


        <div class="plan-list d-flex flex-wrap flex-xxl-column gap-3 gap-xxl-0">
            @forelse($invests as $invest)
            <div class="plan-item-two" style="display: block;">
                @php
                    $fix_date = '2024-09-01';
                @endphp
                <div class="investment_first">
                    <div class="plan-info plan-inner-div">
                        <div class="d-flex align-items-center gap-3">
                            <div class="plan-name-data">
                                <div class="plan-name fw-bold green--show">{{ $invest['rented_nft'] }} NFT's rented</div>
                                
                                <div class="plan-desc">@lang('Initial investment'): <span class="fw-bold">{{ showAmount($invest['rented_nft']*$invest['one_nft_price']) }} {{ $general->cur_text }} ({{ showAmount($invest['rented_nft']*$invest['one_nft_price']/$invest['ft_price']) }} FTs)  </span></div>

                                
                                @php
                                /* if($invest['buying_date'] >= $fix_date){ */
                                    $total_investment = (($invest['rented_nft']*$invest['one_nft_price']) + $invest['renewalsum']);
                                /* }else{
                                    $total_investment = ($invest['rented_nft']*$invest['one_nft_price']);
                                }*/
                                @endphp
                                @if($invest['buying_date'] >= $fix_date)
                                    <div class="plan-desc">@lang('Total investment'): <span class="fw-bold">{{ showAmount($total_investment) }} {{ $general->cur_text }} ({{ showAmount($total_investment/$invest['ft_price']) }} FTs)  </span></div>
                                @else
                                    <div class="plan-desc"><span class="">@lang('Total investment'):&nbsp;</span><span class="orange--show fw-bold">{{ showAmount($total_investment) }} {{ $general->cur_text }} ({{ showAmount($total_investment/$invest['ft_price']) }} FTs)  </span></div>
                                @endif

                                @php 
                                    $last_investment = ($invest->lastRenewal)?$invest->lastRenewal->amount:($invest['rented_nft']*$invest['one_nft_price']);
                                @endphp

                                @if($invest['buying_date'] >= $fix_date || $invest['last_renew_date'] >= $fix_date)
                                    <div class="plan-desc">@lang('Last investment'): <span class="fw-bold">{{ showAmount($last_investment) }} {{ $general->cur_text }} ({{ showAmount($last_investment/$invest['ft_price']) }} FTs)  </span></div>
                                @else
                                    <div class="plan-desc "><span class="">@lang('Last investment'):&nbsp;</span><span class="fw-bold orange--show">{{ showAmount($last_investment) }} {{ $general->cur_text }} ({{ showAmount($last_investment/$invest['ft_price']) }} FTs)  </span></div>
                                @endif
                                
                                <div>

                                @if($invest['contract_expiry_date'] <= date("Y-m-d"))
                                    <span style="color:red;"><b>Expired</b></span>
                                @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="plan-info plan-inner-div">
                        <div class="d-flex align-items-center gap-3">
                            <div class="plan-name-data">
                                <div class="plan-desc"><span class="fw-bold">@lang('Initial start date')
                                : &nbsp;</span>{{ \Carbon\Carbon::parse($invest['buying_date'])->format('jS M Y') }}
                                </div>
                                
                                @if($invest['buying_date'] >= $fix_date  || $invest['last_renew_date'] >= $fix_date)
                                    <div class="plan-desc ">
                                    <span class="fw-bold">@lang('Last renewal date')
                                        : </span>
                                        {{ $invest['last_renew_date']?\Carbon\Carbon::parse($invest['last_renew_date'])->format('jS M Y'):'' }}
                                    </div>
                                @else
                                    <div class="plan-desc">
                                    <span class="fw-bold">@lang('Last renewal date'):&nbsp;</span><span class="orange--show">
                                    {{ $invest['last_renew_date']?\Carbon\Carbon::parse($invest['last_renew_date'])->format('jS M Y'):\Carbon\Carbon::parse($invest['buying_date'])->format('jS M Y') }}</span>
                                    </div>
                                @endif


                                <div class="plan-desc"><span class="fw-bold">@lang('Next return date')
                                : &nbsp;</span>{{ \Carbon\Carbon::parse($invest['next_profit_date'])->format('jS M Y') }}
                                </div>
                                
                                <div class="plan-desc"><span class="fw-bold">@lang('Expiration date')
                                : &nbsp;</span>{{ \Carbon\Carbon::parse($invest['contract_expiry_date'])->format('jS M Y') }}
                                </div>

                                @if($invest['buying_date'] >= $fix_date)
                                    <div class="plan-desc"><span class="fw-bold">@lang('Renewal cycles')
                                    : &nbsp;</span>{{$invest['renewal_count']}}
                                    </div>
                                @else
                                    <div class="plan-desc"><span class="fw-bold ">@lang('Renewal cycles'): &nbsp;
                                    </span><span class="orange--show">{{$invest['renewal_count']}}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- <div class="plan-inner-div">
                        <p class="plan-label">@lang('Next Return')</p>
                        <p class="plan-value">{{ \Carbon\Carbon::parse($invest['next_profit_date'])->format('jS M Y') }}</p>
                    </div> -->

                    @if(auth()->user()->vip_user==1 && auth()->user()->vip_user_date > date("Y-m-d"))
                    <div class="plan-inner-div">
                        <p class="plan-label">@lang('Auto Renewal')</p>
                        <div class="form-check form-switch">
                            <input class="form-check-input our_check_input" type="checkbox" {{$invest['auto_renewal']==1?'checked':''}} data-minernft = "{{$invest['id']}}" role="switch" id="auto_renewal">
                        </div>
                    </div>
                    @else
                    <div class="plan-inner-div">
                        <p class="plan-label" style="width:110px;max-width:110px;"></p>
                        <p class="plan-value" style="width:110px;max-width:110px;"></p>
                    </div>
                    @endif
                    
                    @if(auth()->user()->vip_user==0 && $invest['contract_expiry_date'] <= date("Y-m-d"))
                    <div class="plan-inner-div">
                        <p class="plan-label">@lang('Manual Renewal')</p>
                        <p class="plan-value"><button class="manual_go" data-token="{{ csrf_token() }}" data-minernft = "{{$invest['id']}}" >Go</button></p>
                    </div>
                    @else
                    <div class="plan-inner-div">
                        <p class="plan-label" style="width:110px;max-width:110px;"></p>
                        <p class="plan-value" style="width:110px;max-width:110px;"></p>
                    </div>
                    @endif

                    <div class="plan-inner-div text-end">
                        <p class="plan-label">@lang('Total Return')</p>
                        <p class="plan-value amount"> {{ ($invest['total_profit'] > 0) ? $invest['total_profit'] : 0 }} {{ $general->cur_text }}</p>
                    </div>
                </div>
                @if($invest['buying_date'] < $fix_date)
                <!-- <div class="investment_note">
                    <span class="red--show">Note: It's not accurate that it is orange color.</span>
                </div> -->
                @endif
                
            </div>
            @empty
                <div class="accordion-body text-center bg-white p-4">
                    <h4 class="text--muted"><i class="far fa-frown"></i> {{ __($emptyMessage) }}</h4>
                </div>
            @endforelse
        </div>
        <div><br>
            <b><span class="orange--show">NOTICE</span> <br>
            Display for RentedNFTs is changed on 1st September 2024! For FamilyNFTs rented till 31st August 2024 section <span class="orange--show">Total investment</span> and <span class="orange--show">Renewal cycles</span> will display inaccurate (incomplete) information!</b></div>
    </div>
</div>
{{-- <div class="pagination">
    {{ $invests->links() }}
</div> --}}

@endsection



@push('script')

<script>
$('.our_check_input').click(function () {
    //alert($(this).is(':checked'));

    var auto_renewal = 0;
    if($(this).is(':checked') == true){
        auto_renewal = 1;
    }
    var nftid = $(this).attr('data-minernft');
    $.post("/user/invest/update_auto_renewal", { "_token": "{{ csrf_token() }}",auto_renewal: auto_renewal,nftid: nftid}, function(result){
        console.log(result);
    });
});

$('.manual_go').click(function () {
    var mnftid = $(this).attr('data-minernft');
    $(this).prop('disabled', true);
    if (confirm("Are you sure for manual renewal?") == true) {
        $.post("/user/invest/update_manual_nft", { "_token": "{{ csrf_token() }}",mnftid: mnftid}, function(result){
            if(!alert(result.message)){window.location.reload();}
        });
    }else{
        $(this).prop('disabled', false);
        return false;
    }
});
</script>


@endpush
