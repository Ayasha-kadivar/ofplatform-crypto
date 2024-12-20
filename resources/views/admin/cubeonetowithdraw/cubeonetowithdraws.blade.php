@extends('admin.layouts.app')

@section('panel')
    <div class="row justify-content-center">
        @if (request()->routeIs('admin.cubeonetowithdraw.log') || request()->routeIs('admin.cubeonetowithdraw.method') || request()->routeIs('admin.users.cubeonetowithdraws') || request()->routeIs('admin.users.cubeonetowithdraws.method'))
            <div class="col-xl-4 col-sm-6 mb-30">
                <div class="widget-two box--shadow2 has-link b-radius--5 bg--success">
                    <a href="{{ route('admin.withdraw.approved') }}" class="item-link"></a>
                    <div class="widget-two__content">
                        <h2 class="text-white">{{ __($general->cur_sym) }}{{ showAmount($successful) }}</h2>
                        <p class="text-white">@lang('Approved Withdrawals')</p>
                    </div>
                </div><!-- widget-two end -->
            </div>
            <div class="col-xl-4 col-sm-6 mb-30">
                <div class="widget-two box--shadow2 has-link b-radius--5 bg--6">
                    <a href="{{ route('admin.withdraw.pending') }}" class="item-link"></a>
                    <div class="widget-two__content">
                        <h2 class="text-white">{{ __($general->cur_sym) }}{{ showAmount($pending) }}</h2>
                        <p class="text-white">@lang('Pending Withdrawals')</p>
                    </div>
                </div><!-- widget-two end -->
            </div>
            <div class="col-xl-4 col-sm-6 mb-30">
                <div class="widget-two box--shadow2 b-radius--5 has-link bg--pink">
                    <a href="{{ route('admin.withdraw.rejected') }}" class="item-link"></a>
                    <div class="widget-two__content">
                        <h2 class="text-white">{{ __($general->cur_sym) }}{{ showAmount($rejected) }}</h2>
                        <p class="text-white">@lang('Rejected Withdrawals')</p>
                    </div>
                </div><!-- widget-two end -->
            </div>
        @endif
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">

                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Gateway | Transaction')</th>
                                    <th>@lang('Initiated')</th>
                                    <th>@lang('User')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('FT')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cubeonetowithdraws as $withdraw)
                                    @php
                                        $details = $withdraw->withdraw_information != null ? json_encode($withdraw->withdraw_information) : null;
                                    @endphp
                                    <tr>
                                        {{-- @dd(@$withdraw) --}}
                                        <td>
                                            <span class="fw-bold"> FT Wallet </span></span>
                                            <br>
                                            <small>{{ $withdraw->trx }}</small>
                                        </td>
                                        <td>
                                            {{ showDateTime($withdraw->created_at) }} <br> {{ diffForHumans($withdraw->created_at) }}
                                        </td>
                                        <td>
                                            @if(isset($withdraw->user->fullname))
                                            <span class="fw-bold">{{ $withdraw->user->fullname }}</span>
                                            <br>
                                            <span class="small"> <a href="{{ appendQuery('search', @$withdraw->user->username) }}"><span>@</span>{{ $withdraw->user->username }}</a> </span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong title="@lang('Amount after charge')">
                                                {{ showAmount($withdraw->amount - $withdraw->charge) }} {{ __($general->cur_text) }}
                                            </strong>
                                        </td>
                                        <td>
                                            <strong>{{ showAmount($withdraw->ft) }} FT</strong>
                                        </td>

                                        <td>
                                            @php echo $withdraw->statusBadge @endphp
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.cubeonetowithdraw.details', $withdraw->id) }}" class="btn btn-sm btn-outline--primary ms-1">
                                                <i class="la la-desktop"></i> @lang('Details')
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($cubeonetowithdraws->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($cubeonetowithdraws) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>
@endsection


@push('breadcrumb-plugins')
    <x-search-form dateSearch='yes' />
@endpush
