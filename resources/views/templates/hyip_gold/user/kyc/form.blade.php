@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card custom--card">

                <div class="card-body">
                    <form action="{{ route('user.kyc.submit') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <x-viser-form identifier="act" identifierValue="kyc" />

                        <div class="form-group">
                            <button type="submit" class="btn btn--outline-base w-100">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        input#nid_photo {
            padding: 18px;
        }

        .form-group {
            margin-bottom: 15px !important;
        }
    </style>
@endpush
