@extends('admin.layouts.app')
@php $price_ft = App\Models\GeneralSetting::first(); @endphp
@section('panel')
    <div class="row mb-none-30">
        {{-- @dd($cubeonetowithdraw) --}}
        <div class="col-lg-4 col-md-4 mb-30">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Withdraw Via') Cube One</h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Date')
                            <span class="fw-bold">{{ showDateTime($cubeonetowithdraw->created_at) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Trx Number')
                            <span class="fw-bold">{{ $cubeonetowithdraw->trx }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Amount')
                            <span class="fw-bold">{{ showAmount($cubeonetowithdraw->amount ) }} {{ __($general->cur_text) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Current FT Rate')
                            <span class="fw-bold">1 FT
                                = {{__($price_ft->price_ft)}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Status')
                            @php echo $cubeonetowithdraw->statusBadge @endphp
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @if($cubeonetowithdraw->status == 1 && (auth('admin')->user()->role_status == 0  || auth('admin')->user()->username == 'alexandra'))
        <div class="col-lg-8 col-md-8 mb-30">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2">@lang('Action to Withdraw')</h5>
                    
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <button class="btn btn-outline--success ms-1 approveBtn" data-id="{{ $cubeonetowithdraw->id }}" data-amount="{{ showAmount($cubeonetowithdraw->amount) }} {{$cubeonetowithdraw->currency}}">
                                    <i class="fas la-check"></i> @lang('Approve')
                                </button>

                                <button class="btn btn-outline--danger ms-1 rejectBtn" data-id="{{ $cubeonetowithdraw->id }}">
                                    <i class="fas fa-ban"></i> @lang('Reject')
                                </button>
                            </div>
                        </div>
                </div>
            </div>
        </div>
        @elseif($cubeonetowithdraw->status == 2)
        <div class="col-lg-8 col-md-8 mb-30">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2">@lang('Details of Approved')</h5>
                    <ul class="caption-list">
                        <li>
                            <span class="caption">@lang('Rate: ')</span>
                            <span class="value">{{ showAmount($price_ft->price_ft) }} FT</span>
                        </li>
                        <li>
                            <span class="caption">@lang('FT: ')</span>
                            <span class="value"> {{ showAmount( ($cubeonetowithdraw->ft ) ) }} FT </span>
                        </li>
                        <li>
                            <span class="caption">@lang('Admin Feedback: ')</span>
                            <span class="value"> {{ $cubeonetowithdraw->admin_feedback }} </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @else
        <div class="col-lg-8 col-md-8 mb-30">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2">@lang('Details of Rejected')</h5>
                    <ul class="caption-list">
                        <li>
                            <span class="caption">@lang('Current FT Rate: ')</span>
                            <span class="value">{{ showAmount($price_ft->price_ft) }} FT</span>
                        </li>
                        <li>
                            <span class="caption">@lang('FT: ')</span>
                            <span class="value"> {{ showAmount( ($cubeonetowithdraw->ft ) ) }} FT </span>
                        </li>
                        <li>
                            <span class="caption">@lang('Admin Feedback: ')</span>
                            <span class="value"> {{ $cubeonetowithdraw->admin_feedback }} FT </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- APPROVE MODAL --}}
    <div id="approveModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong>@lang('Approve Withdrawal Confirmation')</strong>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.cubeonetowithdraw.approve') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <input type="hidden" name="user_id">
                    <div class="modal-body">
                        <!-- <p>@lang('Have you sent') <span class="fw-bold withdraw-amount text-success"></span>?</p> -->
                        <h5 class="modal-title">@lang('Withdrawal Confirmation Details')</h5>
                        <p class="withdraw-detail"></p>
                        <textarea name="details" class="form-control pt-3" rows="3" placeholder="@lang('Provide the details. eg: transaction number')" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- REJECT MODAL --}}
    <div id="rejectModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Reject Withdrawal Confirmation')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{route('admin.cubeonetowithdraw.reject')}}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <strong>@lang('Reason of Rejection')</strong>
                            <textarea name="details" class="form-control pt-3" rows="3" value="{{ old('details') }}" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function ($) {
            "use strict";
            $('.approveBtn').on('click', function() {
                var modal = $('#approveModal');
                modal.find('input[name=id]').val($(this).data('id'));
                //modal.find('.withdraw-amount').text($(this).data('amount'));
                modal.modal('show');
            });

            $('.rejectBtn').on('click', function() {
                var modal = $('#rejectModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });
        })(jQuery);

    </script>
@endpush
