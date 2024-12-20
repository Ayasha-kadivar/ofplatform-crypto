@php
    $testimonialContent = getContent('testimonial.content', true);
    $testimonialElement = getContent('testimonial.element', orderById: true);
    
@endphp

<section class="tesimonial-area section-common-bg pt-120  pb-120">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="section_title_wrapper">
                    <h2 class="section-title">
                        {{ __(@$testimonialContent->data_values->heading) }}
                    </h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="slider-margin">
                <div class="testimonial-slider-wrapper testimonial-slider">
                    @foreach ($testimonialElement as $testimonial)
                        <div class="testimonial-item">
                            <div class="testimonial-item-inner">
                                <div class="testimonial-item__icon">
                                    <i class="las la-quote-left"></i>
                                </div>
                                <div class="testimonial-item__subtitle">
                                    <p>{{ __(@$testimonial->data_values->quote) }}</p>
                                </div>
                                <div class="testimonial-item__author">
                                    <div class="testimonial-item__author-img">
                                        <img src="{{ getImage('assets/images/frontend/testimonial/' . @$testimonial->data_values->image, '75x75') }}"
                                            alt="@lang('client')">
                                    </div>
                                    <div class="testimonial-item__author-content">
                                        <h4>{{ __(@$testimonial->data_values->author) }}</h4>
                                        <span>{{ __(@$testimonial->data_values->designation) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</section>
