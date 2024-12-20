@extends($activeTemplate.'layouts.app')

@section('panel')
    <section class="account-section position-relative">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card custom--card">
                        <div class="card-body">
                            <h3 class="text-center text-danger mb-3">@lang('Your Account is deactivated!')</h3>
                            <p class="fw-bold mb-1">@lang('Reason'):</p>
                            <br>
                            <p>Maintenance fee has not been paid withing 60 days from account creation date!</p>
                            <p class="mt-2">To re-activate your account kindly send mail to 
                            <a class="text-primary" href="mailto:issues@ourfamily.support" >issues@ourfamily.support</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
