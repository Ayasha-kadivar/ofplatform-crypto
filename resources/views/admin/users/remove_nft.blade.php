@extends('admin.layouts.app')
@section('panel')
    <style>
        .dashboard-inner {
            font-family: Arial, sans-serif;
            color: #333;
            padding: 20px;
        }

        .dashboard-inner h3 {
            color: #00796b;
            font-weight: bold;
        }

        .plan-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }

        .plan-item-two {
            /* background: #f9f9f9; */
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1);
            padding: 20px;
            flex: 1 1 300px;
            min-width: 300px;
            position: relative;
        }

        / Badge styles / .menu-badge {
            padding: 4px 10px;
            font-size: 14px;
            border-radius: 12px;
            font-weight: bold;
        }

        .green--bg--show {
            background: #4caf50;
            color: #fff;
        }

        .orange--bg--show {
            background: #ff9800;
            color: #fff;
        }

        .red--bg--show {
            background: #f44336;
            color: #fff;
        }

        .plan-name {
            font-size: 18px;
            font-weight: bold;
            color: #4caf50;
        }

        .plan-desc {
            font-size: 14px;
            margin: 8px 0;
            color: #666;
        }

        .plan-desc .fw-bold {
            font-weight: bold;
        }

        .plan-desc .orange--show {
            color: #ff9800;
        }

        .form-switch {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        input.form-check-input {
            width: 40px;
            height: 20px;
            background: #ddd;
            border-radius: 20px;
            position: relative;
            cursor: pointer;
            appearance: none;
            outline: none;
        }

        input.form-check-input:checked {
            background: #4caf50;
        }

        input.form-check-input::after {
            content: '';
            width: 16px;
            height: 16px;
            background: #fff;
            border-radius: 50%;
            position: absolute;
            top: 2px;
            left: 2px;
            transition: 0.3s;
        }

        input.form-check-input:checked::after {
            transform: translateX(20px);
        }

        .red--show {
            color: #f44336;
            font-weight: bold;
        }

        button.manual_go_admin {
            background: #00796b;
            color: #fff;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        button.manual_go_admin:hover {
            background: #004d40;
        }

        .plan-value.amount {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            text-align: right;
        }

        .plan-item-two {
            flex: 1 1 auto;
            margin-bottom: 20px;
        }

        .investment_first {
            display: flex;
        }

        .notice {
            font-weight: 400;
            font-size: 14px;
        }

        .plan-item-two .plan-inner-div {
            flex-grow: 1;
            flex-shrink: 0;
            width: auto;
        }

        .notice span.orange--show {
            color: #ea5455;
        }

        @media (max-width: 768px) {
            .plan-item-two {
                flex: 1 1 100%;
            }
        }
    </style>

    <div class="dashboard-inner">
        <div class="mb-4">
            <h3 class="mb-2 new--color--theme">@lang('Rented FamilyNFTs') - {{ $user->username }}</h3>
        </div>


        <div class="card mt-4 p-4">
            <div class="d-flex justify-content-between">
                <h5 class="title mb-12">
                    <div style="float:left">@lang('Total FamilyNFTs ')
                        @if ($expiredPlan == 0 && $activePlan > 0)
                            <span class="count text-base menu-badge pill green--bg--show">{{ $total }}</span>
                        @elseif($expiredPlan > 0 && $activePlan > 0)
                            <span class="count text-base menu-badge pill orange--bg--show">{{ $total }}</span>
                        @elseif($expiredPlan > 0 && $activePlan == 0)
                            <span class="count text-base menu-badge pill red--bg--show">{{ $total }}</span>
                        @else
                            <span class="count text-base menu-badge pill green--bg--show">{{ $total }}</span>
                        @endif
                    </div>
                    <div class="selectall-nft">
                        <input type="checkbox" id="select-all" /> <label for="select-all">@lang('Select All')</label>
                    </div>
                </h5>

                <h5 class="title mb-12">
                    <div class="active-expired">
                    <div style="float:left">@lang('Active ')
                        <span class="count text-base menu-badge pill green--bg--show">{{ $activePlan }}</span>
                    </div>
                    <div style="float:right">&nbsp;&nbsp; @lang('Expired ')
                        <span class="menu-badge pill red--bg--show count text-base">{{ $expiredPlan }}</span>
                    </div>
                </div>
                    <button id="delete-selected" class="btn btn-danger">@lang('Delete Selected')</button>

                </h5>

            </div>


            <div class="plan-list d-flex flex-wrap flex-xxl-column gap-3 gap-xxl-0">
                @forelse($familyNFTs as $invest)
                    <div class="plan-item-two" style="display: block;">
                        @php
                            $fix_date = '2024-09-01';
                        @endphp
                        <div class="investment_first" >
                            <div class="select-manual-nft" style="
                            display: flex;
                            margin-right: 20px;
                        ">
                                <input type="checkbox" class="select-nft" data-id="{{ $invest['id'] }}" />
                            </div>
                            <div class="plan-info plan-inner-div">
                                {{-- <div class="select-manual-nft">
                                    <input type="checkbox" class="select-nft" data-id="{{ $invest['id'] }}" />
                                </div> --}}
                                <div class="d-flex align-items-center gap-3">
                                    <div class="plan-name-data">
                                        <div class="plan-name fw-bold green--show">{{ $invest['rented_nft'] }} NFT's rented
                                        </div>

                                        <div class="plan-desc">@lang('Initial investment'): <span
                                                class="fw-bold">{{ showAmount($invest['rented_nft'] * $invest['one_nft_price']) }}
                                                {{ $general->cur_text }}
                                                ({{ showAmount(($invest['rented_nft'] * $invest['one_nft_price']) / $invest['ft_price']) }}
                                                FTs)
                                            </span></div>


                                        @php
                                            /* if($invest['buying_date'] >= $fix_date){ */
                                            $total_investment =
                                                $invest['rented_nft'] * $invest['one_nft_price'] +
                                                $invest['renewalsum'];
                                            /* }else{
                                    $total_investment = ($invest['rented_nft']*$invest['one_nft_price']);
                                }*/
                                        @endphp
                                        @if ($invest['buying_date'] >= $fix_date)
                                            <div class="plan-desc">@lang('Total investment'): <span
                                                    class="fw-bold">{{ showAmount($total_investment) }}
                                                    {{ $general->cur_text }}
                                                    ({{ showAmount($total_investment / $invest['ft_price']) }} FTs) </span>
                                            </div>
                                        @else
                                            <div class="plan-desc"><span class="">@lang('Total investment'):&nbsp;</span><span
                                                    class="orange--show fw-bold">{{ showAmount($total_investment) }}
                                                    {{ $general->cur_text }}
                                                    ({{ showAmount($total_investment / $invest['ft_price']) }} FTs) </span>
                                            </div>
                                        @endif

                                        @php
                                            $last_investment = $invest->lastRenewal
                                                ? $invest->lastRenewal->amount
                                                : $invest['rented_nft'] * $invest['one_nft_price'];
                                        @endphp

                                        @if ($invest['buying_date'] >= $fix_date || $invest['last_renew_date'] >= $fix_date)
                                            <div class="plan-desc">@lang('Last investment'): <span
                                                    class="fw-bold">{{ showAmount($last_investment) }}
                                                    {{ $general->cur_text }}
                                                    ({{ showAmount($last_investment / $invest['ft_price']) }} FTs) </span>
                                            </div>
                                        @else
                                            <div class="plan-desc "><span
                                                    class="">@lang('Last investment'):&nbsp;</span><span
                                                    class="fw-bold orange--show">{{ showAmount($last_investment) }}
                                                    {{ $general->cur_text }}
                                                    ({{ showAmount($last_investment / $invest['ft_price']) }} FTs) </span>
                                            </div>
                                        @endif

                                        <div>

                                            @if ($invest['contract_expiry_date'] <= date('Y-m-d'))
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
                                                :
                                                &nbsp;</span>{{ \Carbon\Carbon::parse($invest['buying_date'])->format('jS M Y') }}
                                        </div>

                                        @if ($invest['buying_date'] >= $fix_date || $invest['last_renew_date'] >= $fix_date)
                                            <div class="plan-desc ">
                                                <span class="fw-bold">@lang('Last renewal date')
                                                    : </span>
                                                {{ $invest['last_renew_date'] ? \Carbon\Carbon::parse($invest['last_renew_date'])->format('jS M Y') : '' }}
                                            </div>
                                        @else
                                            <div class="plan-desc">
                                                <span class="fw-bold">@lang('Last renewal date'):&nbsp;</span><span
                                                    class="orange--show">
                                                    {{ $invest['last_renew_date'] ? \Carbon\Carbon::parse($invest['last_renew_date'])->format('jS M Y') : \Carbon\Carbon::parse($invest['buying_date'])->format('jS M Y') }}</span>
                                            </div>
                                        @endif


                                        <div class="plan-desc"><span class="fw-bold">@lang('Next return date')
                                                :
                                                &nbsp;</span>{{ \Carbon\Carbon::parse($invest['next_profit_date'])->format('jS M Y') }}
                                        </div>

                                        <div class="plan-desc"><span class="fw-bold">@lang('Expiration date')
                                                :
                                                &nbsp;</span>{{ \Carbon\Carbon::parse($invest['contract_expiry_date'])->format('jS M Y') }}
                                        </div>

                                        @if ($invest['buying_date'] >= $fix_date)
                                            <div class="plan-desc"><span class="fw-bold">@lang('Renewal cycles')
                                                    : &nbsp;</span>{{ $invest['renewal_count'] }}
                                            </div>
                                        @else
                                            <div class="plan-desc"><span class="fw-bold ">@lang('Renewal cycles'): &nbsp;
                                                </span><span class="orange--show">{{ $invest['renewal_count'] }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="plan-inner-div">
                                <p class="plan-label">@lang('Next Return')</p>
                                <p class="plan-value">{{ \Carbon\Carbon::parse($invest['next_profit_date'])->format('jS M Y') }}</p>
                            </div> -->
                            @if ($user->vip_user == 1 && $user->vip_user_date > date('Y-m-d'))
                                <!-- Auto Renewal for VIP Users -->
                                <div class="plan-inner-div">
                                    <p class="plan-label">@lang('Auto Renewal')</p>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input admin_check_input" type="checkbox"
                                            {{ $invest['auto_renewal'] == 1 ? 'checked' : '' }}
                                            data-minernft="{{ $invest['id'] }}" role="switch" id="admin_auto_renewal">
                                    </div>
                                </div>
                            @elseif($user->vip_user == 0 && $invest['contract_expiry_date'] <= date('Y-m-d'))
                                <!-- Manual Renewal for Expired Users -->
                                <div class="plan-inner-div">
                                    <p class="plan-label">@lang('Manual Renewal')</p>
                                    <p class="plan-value">
                                        <button class="manual_go_admin" data-token="{{ csrf_token() }}"
                                            data-minernft="{{ $invest['id'] }}">
                                            Go
                                        </button>
                                    </p>
                                </div>
                            @else
                                <!-- Placeholder for Other Users -->
                                <div class="plan-inner-div">
                                    <p class="plan-label" style="width:110px; max-width:110px;"></p>
                                    <p class="plan-value" style="width:110px; max-width:110px;"></p>
                                </div>
                            @endif

                            <div class="plan-inner-div text-end">
                                <p class="plan-label">@lang('Total Return')</p>
                                <p class="plan-value amount">
                                    {{ $invest['total_profit'] > 0 ? $invest['total_profit'] : 0 }}
                                    {{ $general->cur_text }}</p>
                            </div>
                        </div>
                        @if ($invest['buying_date'] < $fix_date)
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
            {{-- <div class="notice"><br>
                <b><span class="orange--show">NOTICE</span> <br>
                    Display for RentedNFTs is changed on 1st September 2024! For FamilyNFTs rented till 31st August 2024
                    section <span class="orange--show">Total investment</span> and <span class="orange--show">Renewal
                        cycles</span> will display inaccurate (incomplete) information!</b>
            </div> --}}
        </div>
    </div>
@endsection
@push('script')
    <script>
        //   // Auto Renewal Toggle (Admin Side)

        $('.our_check_input').click(function() {
            var auto_renewal = 0;
            if ($(this).is(':checked') == true) {
                auto_renewal = 1;
            }
            var nftid = $(this).attr('data-minernft');
            $.post("/admin/invest/update_auto_renewal", {
                "_token": "{{ csrf_token() }}",
                auto_renewal: auto_renewal,
                nftid: nftid
            }, function(result) {
                console.log(result);
            });
        });

        // // Manual Renewal (Admin Side)
        $('.manual_go_admin').click(function() {
            // alert("Button clicked!");
            var mnftid = $(this).attr('data-minernft');
            $(this).prop('disabled', true);
            if (confirm("Are you sure for manual renewal?") == true) {
                $.post("/admin/invest/update_manual_nft", {
                    "_token": $(this).data('token'), // Correctly passing CSRF token
                    mnftid: mnftid
                }, function(result) {
                    if (!alert(result.message)) {
                        window.location.reload(); // Reload page after renewal
                    }
                });
            } else {
                $(this).prop('disabled', false);
                return false;
            }
        });
    </script>
@endpush
