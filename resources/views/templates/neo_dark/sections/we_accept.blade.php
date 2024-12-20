@php
    $methods = getContent('we_accept.element',false,null,true);

    $weAcceptContent = getContent('we_accept.content',true);
@endphp

    <section class="currency-accept-section pt-150 pb-150">
        <div class="container">
            <div class="row align-items-end">
                <div class="col-lg-6">
                    <div class="section-header text-left margin-olpo mb-lg-0">
                        <h2 class="section__title">{{ __(@$weAcceptContent->data_values->heading) }}</h2>
                        <p>{{ __(@$weAcceptContent->data_values->sub_heading) }}</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="currency-slider">
                        @foreach($methods as $method)
                        <div class="payment-thumb">
                            <a href="javascript:void(0)">
                                <img src="{{getImage('assets/images/frontend/we_accept/'.$method->data_values->image,'75x75')}}" alt="sponsor">
                            </a>
                        </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </section>
