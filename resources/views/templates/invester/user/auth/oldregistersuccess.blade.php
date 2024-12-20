@extends($activeTemplate . 'layouts.app')
@section('panel')

    <!-- Account Section -->
    <section class="account-section position-relative">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-7 col-md-8">
                    <a href="{{ route('home') }}" class="text-center d-block mb-3 mb-sm-4 auth-page-logo"><img src="{{ getImage(getFilePath('logoIcon') . '/logo_2.png') }}" alt="logo"></a>
                    <form action="{{ route('user.old-register') }}" method="POST" class="verify-gcaptcha account-form">
                        @csrf
                        <div class="mb-4">
                            <h4 class="mb-2">Old registeration Data Received</h4>
                            <p>We have received your data. Admin will review and activate the account after all the approval.</p>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Account Section -->


@endsection
