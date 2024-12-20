@extends($activeTemplate.'layouts.master')
@section('content')
<section class="pt-150 pb-150">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card card-bg">
                    <div class="card-header">
                        <h5 class="title">@lang('KYC Form')</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{route('user.kyc.submit')}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <x-viser-form identifier="act" identifierValue="kyc" />

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary w-100">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
