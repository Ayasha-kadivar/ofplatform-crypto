@extends('admin.layouts.app')
@section('panel')
{{-- @dd($user) --}}
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card b-radius--10">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">ID</div>
                        <div class="col-8">{{$user->user->id}}</div>
                    </div>
                    <div class="row">
                        <div class="col-4">Username</div>
                        <div class="col-8">{{$user->user->username}}</div>
                    </div>
                    <div class="row">
                        <div class="col-4">Email</div>
                        <div class="col-8">{{$user->user->email}}</div>
                    </div>
                    <div class="row">
                        <div class="col-4">Phone Number</div>
                        <div class="col-8">{{$user->user->mobile}}</div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-4">Submitted At</div>
                        <div class="col-8">{{$user->created_at}}</div>
                    </div> --}}
                    <div class="row">
                        <div class="col-4">Payment Method</div>
                        <div class="col-8">
                            @if($user->fees_type == 0)
                                BEP-20 or ERC-20
                            @elseif($user->fees_type == 1)
                                TRC-20
                            @else
                                Stellar
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">Hash ID</div>
                        <div class="col-8">{{$user->hash_id}}</div>
                    </div>
                    <div class="row">
                        <div class="col-6">${{showAmount($user->amount)}} VIP Fee</div>
                       
                    </div>

                    @if($user->status == 0)

                    @if((auth('admin')->user()->id == 29 || auth('admin')->user()->id == 19 || auth('admin')->user()->role_status == 0 || auth('admin')->user()->username == 'alexandra'))
                    <div class="d-flex flex-wrap justify-content-end mt-3">
                        <button class="btn btn-outline-danger me-3 rejectBtn"
                                        data-id="{{ $user->id }}"
                                        ><i class="las la-ban"></i> @lang('Reject')
                                </button>
                        <form action="{{ route('vip.approve', $user->id) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <button type="submit" class="btn btn-outline-success me-3">Approve</button>
                        </form>
                        
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- REJECT MODAL --}}
    <div id="rejectModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Reject Deposit Confirmation')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{route('vip.rejected', $user->id)}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>@lang('Are you sure to') @lang('reject')  @lang('this vip fees') </span>?</p>

                        <div class="form-group">
                            <label class="fw-bold mt-2">@lang('Reason for Rejection')</label>
                            <textarea name="message" maxlength="255" class="form-control" rows="5" required>{{ old('message') }}</textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection


@push('script')
    <script>
        (function ($) {
            "use strict";

            $('.rejectBtn').on('click', function () {
                var modal = $('#rejectModal');
                modal.modal('show');
            });
        })(jQuery);


        $('button[type=submit]').click(function() {
            if ($(this).parents('form').valid()) {
                $(this).attr('disabled', 'disabled');
                $(this).parents('form').submit();
            }
        });
    </script>
@endpush
