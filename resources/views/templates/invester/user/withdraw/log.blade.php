@extends($activeTemplate.'layouts.master')
@section('content')
<div class="dashboard-inner">
    <div class="mb-4">
        <div class="d-flex justify-content-between">
            <h3 class="mb-2">@lang('Withdraw History')</h3>
            <span>
                <a href="{{ route('user.withdraw') }}" class="btn btn--secondary btn--smd">@lang('Withdraw Now') <i class="las la-long-arrow-alt-right"></i></a>
            </span>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            
            <div class="accordion table--acordion" id="transactionAccordion">
                @forelse($withdraws as $withdraw)
                    <div class="accordion-item transaction-item">
                        <h2 class="accordion-header" id="h-{{$loop->iteration}}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c-{{$loop->iteration}}" aria-expanded="false" aria-controls="c-1">
                            <div class="col-lg-4 col-sm-5 col-8 order-1 icon-wrapper">
                                <div class="left">
                                    @if($withdraw->status == 1)
                                    <div class="icon icon-success">
                                        <i class="las la-check"></i>
                                    </div>
                                    @elseif($withdraw->status == 2)
                                    <div class="icon icon-warning">
                                        <i class="las la-spinner fa-spin"></i>
                                    </div>
                                    @elseif($withdraw->status == 3)
                                    <div class="icon icon-danger">
                                        <i class="las la-ban"></i>
                                    </div>
                                    @endif
                                    {{-- @dd(@$withdraw->metamask_info) --}}
                                    @if(@$withdraw->withdraw_type == "metamask")
                                    <div class="content">
                                        <h6 class="trans-title">{{ __(@$withdraw->withdraw_type) }}</h6>
                                        <span class="text-muted font-size--14px mt-2">{{showDateTime($withdraw->created_at,'M d Y @g:i:a')}}</span>
                                    </div>
                                    @else
                                    <div class="content">
                                        <h6 class="trans-title">{{ __(@$withdraw->method->name) }}</h6>
                                        <span class="text-muted font-size--14px mt-2">{{showDateTime($withdraw->created_at,'M d Y @g:i:a')}}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-12 order-sm-2 order-3 content-wrapper mt-sm-0 mt-3">
                                <p class="text-muted font-size--14px"><b>#{{ $withdraw->trx }}</b></p>
                            </div>
                            <div class="col-lg-4 col-sm-3 col-4 order-sm-3 order-2 text-end amount-wrapper">
                                <p><b>{{ showAmount($withdraw->amount ) }} {{ $general->cur_text }}</b></p>
                            </div>
                        </button>
                        </h2>
                        @if(@$withdraw->withdraw_type == "metamask")
                        <div id="c-{{$loop->iteration}}" class="accordion-collapse collapse" aria-labelledby="h-1" data-bs-parent="#transactionAccordion">
                            <div class="accordion-body">
                                @php
                                    $meta = json_decode($withdraw->metamask_info);
                                @endphp
                                <ul class="caption-list">
                                    <li>
                                        <span class="caption">@lang('Wallet Address')</span>
                                        <span class="value">{{ $meta->from }}</span>
                                    </li>
                                    <li>
                                        <span class="caption">@lang('Transection Hash')</span>
                                        <span class="value">{{ $meta->transactionHash }}</span>
                                    </li>
                                    <li>
                                        <span class="caption">@lang('Rate')</span>
                                        <span class="value">{{ showAmount($withdraw->rate) }} {{ __($general->cur_text) }}</span>
                                    </li>
                                    <li>
                                        <span class="caption">@lang('Conversion')</span>
                                        <span class="value">{{ $general->cur_sym }}{{ showAmount($withdraw->amount - $withdraw->charge) }} x {{ $general->cur_sym }}{{ showAmount($withdraw->rate) }} = {{ $general->cur_sym }}{{ showAmount($withdraw->amount) }}</span>
                                    </li>
                                    <li>
                                        <span class="caption">@lang('In FT')</span>
                                        <span class="value">{{ showAmount($withdraw->amount) }} FT</span>
                                    </li>
                                    <li>
                                        <span class="caption">@lang('Status')</span>
                                        <span class="value">
                                            @php echo $withdraw->statusBadge @endphp <a href="javascript:void(0)"><i class="las la-info-circle detailBtn"
                                                data-user_data="{{ json_encode($withdraw->withdraw_information) }}"
                                                @if ($withdraw->status == 3)
                                                data-admin_feedback="{{ $withdraw->admin_feedback }}"
                                                @endif></i></a>
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @else
                        <div id="c-{{$loop->iteration}}" class="accordion-collapse collapse" aria-labelledby="h-1" data-bs-parent="#transactionAccordion">
                            <div class="accordion-body">
                                <ul class="caption-list">
                                    <li>
                                        <span class="caption">@lang('Charge')</span>
                                        <span class="value">{{ showAmount($withdraw->charge) }} {{ $general->cur_text }}</span>
                                    </li>
                                    <li>
                                        <span class="caption">@lang('After Charge')</span>
                                        <span class="value">{{ showAmount($withdraw->amount-$withdraw->charge) }} {{ __($general->cur_text) }}</span>
                                    </li>
                                    <li>
                                        <span class="caption">@lang('Conversion')</span>
                                        <span class="value">{{ $general->cur_sym }}{{ showAmount($withdraw->amount - $withdraw->charge) }} x {{ $general->cur_sym }}{{ showAmount($withdraw->rate) }} = {{ $general->cur_sym }}{{ showAmount($withdraw->final_amount) }}</span>
                                    </li>
                                    <li>
                                        <span class="caption">@lang('In FT')</span>
                                        <span class="value">{{ showAmount($withdraw->final_amount) }} FT</span>
                                    </li>
                                    <li>
                                        <span class="caption">@lang('Status')</span>
                                        <span class="value">
                                            @php echo $withdraw->statusBadge @endphp <a href="javascript:void(0)"><i class="las la-info-circle detailBtn"
                                                data-user_data="{{ json_encode($withdraw->withdraw_information) }}"
                                                @if ($withdraw->status == 3)
                                                data-admin_feedback="{{ $withdraw->admin_feedback }}"
                                                @endif></i></a>
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @endif
                    </div><!-- transaction-item end -->
                @empty
                    <div class="accordion-body text-center">
                        <h4 class="text--muted"><i class="far fa-frown"></i> {{ __($emptyMessage) }}</h4>
                    </div>
                @endforelse
            </div>

            <div class="mt-3">
                {{ $withdraws->links() }}
            </div>
        </div>
    </div>
</div>



 {{-- APPROVE MODAL --}}
<div id="detailModal" class="modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Details')</h5>
                <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </span>
            </div>
            <div class="modal-body">
                <ul class="list-group userData">

                </ul>
                <div class="feedback"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--dark btn--sm" data-bs-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        (function ($) {
            "use strict";
            $('.detailBtn').on('click', function () {
                var modal = $('#detailModal');
                var userData = $(this).data('user_data');
                var html = ``;
                userData.forEach(element => {
                    if(element.type != 'file'){
                        html += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>${element.name}</span>
                            <span">${element.value}</span>
                        </li>`;
                    }
                });
                modal.find('.userData').html(html);

                if($(this).data('admin_feedback') != undefined){
                    var adminFeedback = `
                        <div class="my-3">
                            <strong>@lang('Admin Feedback')</strong>
                            <p>${$(this).data('admin_feedback')}</p>
                        </div>
                    `;
                }else{
                    var adminFeedback = '';
                }

                modal.find('.feedback').html(adminFeedback);

                modal.modal('show');
            });
        })(jQuery);

    </script>
@endpush
