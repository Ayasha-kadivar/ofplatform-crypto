@extends('admin.layouts.app')
@section('panel')
{{-- @dd($user) --}}
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card b-radius--10">
                <div class="card-body">
                    {{-- <a href="{{ route('admin.download.attachment',encrypt(getFilePath('verify').'/'.$user->maintenance_fee)) }}" class="me-3"><i class="fa fa-file"></i>  @lang('Attachment') </a> --}}
                    <div class="row">
                        <div class="col-4">ID</div>
                        <div class="col-8">{{$user->id}}</div>
                    </div>
                    <div class="row">
                        <div class="col-4">Username</div>
                        <div class="col-8">{{$user->username}}</div>
                    </div>
                    <div class="row">
                        <div class="col-4">Email</div>
                        <div class="col-8">{{$user->email}}</div>
                    </div>
                    <div class="row">
                        <div class="col-4">Phone Number</div>
                        <div class="col-8">{{$user->mobile}}</div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-4">Submitted At</div>
                        <div class="col-8">{{$user->created_at}}</div>
                    </div> --}}
                    <div class="row">
                        <div class="col-4">Payment Method</div>
                        <div class="col-8">
                            @if($user->maintenance_fees_type == 0)
                                BEP-20 or ERC-20
                            @elseif($user->maintenance_fees_type == 1)
                                TRC-20
                            @else
                                Stellar
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">Hash ID</div>
                        <div class="col-8">{{$user->maintenance_fee_hash}}</div>
                    </div>
                    <div class="row">
                        <div class="col-6">$10 Maintenance Fee</div>
                        <div class="col-6">
                            {{-- <img src="{{ asset('maintenance-fees/' . $user->maintenance_fee) }}" alt="Maintenance Fee" style="width:30% !important;"> --}}
                            {{-- <a href="{{ route('download.maintenance_fee', $user->id) }}" download>Attachment
                                <i class="fas fa-download"></i>
                            </a> --}}

                            {{-- 
                            @if(file_exists(public_path('maintenance-fees/' . $user->maintenance_fee)))

                                <a href="{{ asset('maintenance-fees/' . $user->maintenance_fee) }}" target="_blank">
                                    <img src="{{ asset('maintenance-fees/' . $user->maintenance_fee) }}" alt="Maintenance Fee" style="width:30%">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>

                            @else

                                <a href="{{config('filesystems.disks.gcs.storage_api_uri')}}/maintenance-fees/{{ $user->maintenance_fee}}" target="_blank">
                                    <img src="{{config('filesystems.disks.gcs.storage_api_uri')}}/maintenance-fees/{{ $user->maintenance_fee}}" alt="Maintenance Fee" style="width:30%">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            @endif
                            --}}
                            
                            

                        </div>
                    </div>

                    @if($user->fee_status == 1)

                    @if((auth('admin')->user()->id == 29 || auth('admin')->user()->id == 19 || auth('admin')->user()->role_status == 0 || auth('admin')->user()->username == 'alejandra'))
                    <div class="d-flex flex-wrap justify-content-end mt-3">
                        {{-- <button class="btn btn-outline--danger me-3 confirmationBtn" data-question="@lang('Are you sure to reject this documents?')" data-action="{{ route('admin.users.kyc.reject', $user->id) }}"><i class="las la-ban"></i>@lang('Reject')</button> --}}
                        {{-- <form action="{{ route('users.rejected', $user->id) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <button type="submit" class="btn btn-outline-danger me-3">Rejected</button>
                        </form> --}}
                        <button class="btn btn-outline-danger me-3 rejectBtn"
                                        data-id="{{ $user->id }}"
                                        ><i class="las la-ban"></i> @lang('Reject')
                                </button>
                        <form action="{{ route('users.approve', $user->id) }}" method="POST">
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
                <form action="{{route('users.rejected', $user->id)}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>@lang('Are you sure to') @lang('reject')  @lang('this fees') </span>?</p>

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
