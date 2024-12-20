@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">



            <div class="dashboard-table">
                <table class="table transection__table table--responsive--xl">
                    <thead>
                        <tr>
                            <th>@lang('Gateway | Transaction')</th>
                            <th>@lang('Initiated')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Conversion')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse($withdraws as $withdraw)
                            <tr>
                                <td>
                                    <span>
                                        <span class="fw-bold">
                                            <span class="text-white">{{ __(@$withdraw->method->name) }}</span>
                                        </span>
                                        <br>
                                        <small>{{ $withdraw->trx }}</small>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span>
                                        {{ showDateTime($withdraw->created_at) }} <br>
                                        {{ diffForHumans($withdraw->created_at) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span>
                                        {{ __($general->cur_sym) }}{{ showAmount($withdraw->amount) }} - <span
                                            class="text-danger" title="@lang('charge')">{{ showAmount($withdraw->charge) }}
                                        </span>
                                        <br>
                                        <strong title="@lang('Amount after charge')">
                                            {{ showAmount($withdraw->amount - $withdraw->charge) }}
                                            {{ __($general->cur_text) }}
                                        </strong>
                                    </span>

                                </td>
                                <td class="text-center">
                                    <span>
                                        1 {{ __($general->cur_text) }} = {{ showAmount($withdraw->rate) }}
                                        {{ __($withdraw->currency) }}
                                        <br>
                                        <strong>{{ showAmount($withdraw->final_amount) }}
                                            {{ __($withdraw->currency) }}</strong>
                                    </span>
                                </td>
                                <td class="text-center">
                                    @php echo $withdraw->statusBadge @endphp
                                </td>
                                <td>
                                    <button class="icon-btn btn btn--base detailBtn"
                                        data-user_data="{{ json_encode($withdraw->withdraw_information) }}"
                                        @if ($withdraw->status == 3) data-admin_feedback="{{ $withdraw->admin_feedback }}" @endif>
                                        <i class="fa fa-desktop"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($withdraws->hasPages())
                    <div class="card-footer">
                        {{ $withdraws->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>



    {{-- APPROVE MODAL --}}
    <div id="detailModal" class="modal custom--modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
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
                    <button type="button" class="btn btn-danger btn-md" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')
    <form action="">
        <div class="input-group">
            <input type="text" name="search" class="form-control form--control" value="{{ request()->search }}"
                placeholder="@lang('Search by transactions')">
            <button class="input-group-text bg--base">
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
                var userData = $(this).data('user_data');
                var html = ``;
                userData.forEach(element => {
                    if (element.type != 'file') {
                        html += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>${element.name}</span>
                            <span">${element.value}</span>
                        </li>`;
                    }
                });
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
