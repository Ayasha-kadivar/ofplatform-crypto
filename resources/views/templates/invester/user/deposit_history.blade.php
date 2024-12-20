@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-inner">
        <div class="mb-4">
            <div class="d-flex justify-content-between">
                <h3 class="mb-2 new--color--theme">@lang('Deposit History')</h3>
                <span>
                    <a href="{{ route('user.deposit.index') }}" class="btn btn--secondary btn--smd">@lang('Deposit Now') <i class="las la-long-arrow-alt-right"></i></a>
                </span>
            </div>
        </div>
        <div class="accordion table--acordion" id="transactionAccordion">
            @forelse($deposits as $deposit)
                <div class="accordion-item transaction-item">
                    <h2 class="accordion-header" id="h-{{ $loop->iteration }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c-{{ $loop->iteration }}" aria-expanded="false" aria-controls="c-1">
                            <div class="col-lg-4 col-sm-5 col-8 order-1 icon-wrapper">
                                <div class="left">
                                    @if ($deposit->status == 1)
                                        <div class="icon icon-success">
                                            <i class="las la-check"></i>
                                        </div>
                                    @elseif($deposit->status == 2)
                                        <div class="icon icon-warning">
                                            <i class="las la-spinner fa-spin"></i>
                                        </div>
                                    @elseif($deposit->status == 3)
                                        <div class="icon icon-danger">
                                            <i class="las la-ban"></i>
                                        </div>
                                    @endif
                                    <div class="content">
                                        <h6 class="trans-title">{{ __($deposit->gateway?->name) }}</h6>
                                        <span class="text-muted font-size--14px mt-2">{{ showDateTime($deposit->created_at, 'M d Y @g:i:a') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-12 order-sm-2 order-3 content-wrapper mt-sm-0 mt-3">
                                <p class="text-muted font-size--14px"><b>#{{ $deposit->trx }}</b></p>
                            </div>
                            <div class="col-lg-4 col-sm-3 col-4 order-sm-3 order-2 text-end amount-wrapper">
                                {{-- <p><b>{{ showAmount($deposit->final_amo) }} {{ $general->cur_text }}</b></p> --}}
                                <p><b>{{ showAmount($deposit->amount) }} {{ __($deposit->method_currency) }}</b></p>
                            </div>
                        </button>
                    </h2>
                    <div id="c-{{ $loop->iteration }}" class="accordion-collapse collapse" aria-labelledby="h-1" data-bs-parent="#transactionAccordion">
                        <div class="accordion-body">
                            <ul class="caption-list">
                                <li>
                                    <span class="caption">@lang('FT Purchased')</span>
                                    <span class="value">{{ showAmount($deposit->amount) }} {{ __($deposit->method_currency) }}</span>
                                </li>
                                {{-- <li>
                                    <span class="caption">@lang('FT Rate')</span>
                                    <span class="value">{{ showAmount($deposit->rate) }} {{ __($general->cur_text) }}</span>
                                </li>
                                <li>
                                    <span class="caption">@lang('Charge')</span>
                                    <span class="value">{{ showAmount($deposit->charge) }} {{ $general->cur_text }}</span>
                                </li>
                                <li>
                                    <span class="caption">@lang('With Charge')</span>
                                    <span class="value">{{ showAmount($deposit->final_amo) }} {{ __($general->cur_text) }}</span>
                                </li> --}}
                                {{-- <li>
                                    <span class="caption">@lang('Conversion')</span>
                                    <span class="value">{{ showAmount($deposit->amount + $deposit->charge) }} {{ __($general->cur_text) }} x {{ showAmount($deposit->rate) }} {{ __($deposit->method_currency) }} = {{ showAmount($deposit->final_amo) }} {{ __($deposit->method_currency) }}</span>
                                </li> --}}
                                <li>
                                    @php
                                        $details = $deposit->detail != null ? json_encode($deposit->detail) : null;
                                    @endphp
                                    <span class="caption">@lang('Status')</span>
                                    <span class="value">
                                        @php echo $deposit->statusBadge @endphp @if ($deposit->method_code >= 1000)
                                            <a href="javascript:void(0)" class="detailBtn" data-info="{{ $details }}" @if ($deposit->status == 3) data-admin_feedback="{{ $deposit->admin_feedback }}" @endif><i class="las la-info-circle"></i></a>
                                        @endif
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div><!-- transaction-item end -->
            @empty
                <div class="accordion-body text-center bg-white">
                    <h4 class="text--muted"><i class="far fa-frown"></i> {{ __($emptyMessage) }}</h4>
                </div>
            @endforelse
        </div>
        <div class="mt-3">
            {{ $deposits->links() }}
        </div>
    </div>


    {{-- APPROVE MODAL --}}
    <div id="detailModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Details')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <ul class="list-group userData mb-2">
                    </ul>
                    <div class="feedback"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark btn--sm" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        (function($) {
            "use strict";
            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');

                var userData = $(this).data('info');
                var html = '';
                if (userData) {
                    userData.forEach(element => {
                        if (element.type != 'file') {
                            html += `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>${element.name}</span>
                                <span">${element.value}</span>
                            </li>`;
                        }
                    });
                }

                modal.find('.userData').html(html);

                if ($(this).data('admin_feedback') != undefined) {
                    var adminFeedback = `
                        <div class="my-3">
                            <strong>@lang('Admin Feedback')</strong>
                            <p>${$(this).data('admin_feedback')}</p>
                        </div>
                    `;
                } else {
                    var adminFeedback = '';
                }

                modal.find('.feedback').html(adminFeedback);


                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
