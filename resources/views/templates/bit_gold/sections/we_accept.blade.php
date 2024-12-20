@php
$weAcceptContent = getContent('we_accept.content', true);
$methods = getContent('we_accept.element', false, null, true);
@endphp
<section class="pb-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="section-header">
                    <h2 class="section-title"><span class="font-weight-normal">{{ __(@$weAcceptContent->data_values->heading_w) }}</span> <b class="base--color">{{ __(@$weAcceptContent->data_values->heading_c) }}</b></h2>
                    <p>{{ __(@$weAcceptContent->data_values->sub_heading) }}</p>
                </div>
            </div>
        </div><!-- row end -->
        <div class="row">
            <div class="col-lg-12">
                <div class="payment-slider">
                    @foreach ($methods as $method)
                        <div class="single-slide">
                            <div class="brand-item">
                                <img src="{{ getImage('assets/images/frontend/we_accept/' . $method->data_values->image, '75x75') }}" alt="image">
                            </div><!-- brand-item end -->
                        </div>
                    @endforeach
                </div><!-- payment-slider end -->
            </div>
        </div>
    </div>
</section>
