@extends($activeTemplate.'layouts.master')
@section('content')
<section class="pb-150 pt-150">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <form action="">
                    <div class="mb-3 d-flex justify-content-end table-search">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" value="{{ request()->search }}" placeholder="@lang('Search by transactions')">
                            <button class="input-group-text bg--dark-light text-white">
                                <i class="las la-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive--sm neu--table">
                    <table class="table text-white">
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
                                    <span class="fw-bold"><span class="text-primary"> {{ __(@$withdraw->method->name) }}</span></span>
                                    <br>
                                    <small>{{ $withdraw->trx }}</small>
                                </td>
                                <td class="text-center">
                                    {{ showDateTime($withdraw->created_at) }} <br>  {{ diffForHumans($withdraw->created_at) }}
                                </td>
                                <td class="text-center">
                                    {{ __($general->cur_sym) }}{{ showAmount($withdraw->amount ) }} - <span class="text-danger" title="@lang('charge')">{{ showAmount($withdraw->charge)}} </span>
                                        <br>
                                        <strong title="@lang('Amount after charge')">
                                        {{ showAmount($withdraw->amount-$withdraw->charge) }} {{ __($general->cur_text) }}
                                        </strong>

                                    </td>
                                    <td class="text-center">
                                    1 {{ __($general->cur_text) }} =  {{ showAmount($withdraw->rate) }} {{ __($withdraw->currency) }}
                                        <br>
                                        <strong>{{ showAmount($withdraw->final_amount) }} {{ __($withdraw->currency) }}</strong>
                                    </td>
                                    <td class="text-center">
                                    @php echo $withdraw->statusBadge @endphp
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary w-auto detailBtn"
                                    data-user_data="{{ json_encode($withdraw->withdraw_information) }}"
                                    @if ($withdraw->status == 3)
                                    data-admin_feedback="{{ $withdraw->admin_feedback }}"
                                    @endif
                                    ><i class="la la-desktop"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                {{$withdraws->links()}}
            </div>
        </div>
    </div>
</section>



 {{-- APPROVE MODAL --}}
<div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-bg">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Details')</h5>
                <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </span>
            </div>
            <div class="modal-body">
                <ul class="list-group userData">

                </ul>
                <div class="feedback text-white"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
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

@push('style')
    <style>
        .bg--dark-light {
            background-color: #ffffff1f; 
        }
    </style>
@endpush

