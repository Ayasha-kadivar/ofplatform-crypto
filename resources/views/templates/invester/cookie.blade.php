@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pt-120 pb-120 bg--light full-height">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="title mb-2">{{ __($pageTitle) }}</h3>
                    <hr class="mb-4">
                    @php
                        echo $cookie->data_values->description;
                    @endphp
                </div>
            </div>
        </div>
    </section>
@endsection
