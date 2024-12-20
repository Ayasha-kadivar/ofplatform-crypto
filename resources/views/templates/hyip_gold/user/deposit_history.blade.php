@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-end">


    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="dashboard-table">
                <table class="table transection__table table--responsive--xl">
                    <thead>
                        <tr>
                            <th>@lang('Gateway | Transaction')</th>
                            <th class="text-center">@lang('Initiated')</th>
                            <th class="text-center">@lang('Amount')</th>
                            <th class="text-center">@lang('Conversion')</th>
                            <th class="text-center">@lang('Status')</th>
                            <th>@lang('Details')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deposits as $deposit)
                            <tr>
                                <td>
                                    <span>
                                        <span class="fw-bold"> <span
                                                class="text-primary">{{ __($deposit->gateway?->name) }}</span>
                                        </span>
                                        <br>
                                        <small> {{ $deposit->trx }} </small>
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span>
                                        {{ showDateTime($deposit->created_at) }}<br>{{ diffForHumans($deposit->created_at) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span>
                                        {{ __($general->cur_sym) }}{{ showAmount($deposit->amount) }} + <span
                                            class="text-danger" title="@lang('charge')">{{ showAmount($deposit->charge) }}
                                        </span>
                                        <br>
                                        <strong title="@lang('Amount with charge')">
                                            {{ showAmount($deposit->amount + $deposit->charge) }}
                                            {{ __($general->cur_text) }}
                                        </strong>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span>
                                        1 {{ __($general->cur_text) }} = {{ showAmount($deposit->rate) }}
                                        {{ __($deposit->method_currency) }}
                                        <br>
                                        <strong>{{ showAmount($deposit->final_amo) }}
                                            {{ __($deposit->method_currency) }}</strong>
                                    </span>
                                </td>
                                <td class="text-center">
                                    @php echo $deposit->statusBadge @endphp
                                </td>
                                @php
                                    $details = $deposit->detail != null ? json_encode($deposit->detail) : null;
                                @endphp

                                <td>
                                    <a href="javascript:void(0)"
                                        class="btn btn--base btn-sm  @if ($deposit->method_code >= 1000) detailBtn @else disabled @endif"
                                        @if ($deposit->method_code >= 1000) data-info="{{ $details }}" @endif
                                        @if ($deposit->status == 3) data-admin_feedback="{{ $deposit->admin_feedback }}" @endif>
                                        <i class="fa fa-desktop"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if ($deposits->hasPages())
                    {{ $deposits->links() }}
                @endif
            </div>
        </div>
    </div>
    {{-- APPROVE MODAL --}}
    <div id="detailModal" class="modal fade custom--modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content  modal--content">
                <div class="modal-header modal--header">
                    <h5 class="modal-title modal--title">@lang('Details')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body  modal--body">
                    <ul class="list-group userData mb-2">
                    </ul>
                    <div class="feedback"></div>
                </div>
                <div class="modal-footer modal--footer">
                    <button type="button" class="btn btn--danger btn-sm"
                        data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <form action="">
        <div class="input-group">
            <input type="text" name="search" class="form--control form-control" value="{{ request()->search }}"
                placeholder="@lang('Search by transactions')">
            <button class="input-group-text bg--base ">
                <i class="las la-search"></i>
            </button>
        </div>

    </form>
@endpush


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
