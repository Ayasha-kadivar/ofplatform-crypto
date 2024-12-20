@extends($activeTemplate.'layouts.master')
@section('content')

    <div class="dashboard-inner">
        <div class="mb-4">
            <h3 class="mb-2 new--color--theme">@lang('KYC Submission')</h3>
            <p>@lang('The system requires you to submit KYC (know your client) information. Your submitted data will be verified by the system\s admin. If all of your information is correct, the admin will approve the KYC data and you\'ll be able to make withdrawal requests') @if($general->b_transfer) @lang('and transfer money to other users') @endif.</p>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card custom--card">
                    <div class="card-body">
                        <form action="{{route('user.kyc.submit')}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <x-viser-form identifier="act" identifierValue="kyc" />

                            {{-- <button class="btn btn--base w-25" type="button" onclick="window.open('{{ url('user/stripe-payment') }}', '_blank')">
                                Pay Now
                            </button> --}}

                            <div class="form-check mt-5">
                                <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" required>
                                <label class="form-check-label" for="defaultCheck1">
                                  I Agree to KYC
                                </label>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@push('style')
    <style>
        .form-group{
            margin-bottom: 12px;
        }
    </style>
@endpush
