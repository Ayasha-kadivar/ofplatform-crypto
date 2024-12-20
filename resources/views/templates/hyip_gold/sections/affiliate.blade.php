@php
    $affiliateContent = getContent('affiliate.content', true);
    $affiliateElement = getContent('affiliate.element', orderById: true);
@endphp
<section class="affilite-program pt-120 pb-120 p-relative">
    <div class="banner__shape affilite-shape">
        <img class="w-100" src="{{ asset($activeTemplateTrue . '/images/banner/shape.png') }}" alt="@lang('Image')">
    </div>
    <div class="container">
        <div class="row align-items-lg-center align-items-xl-start">
            <div class="col-xl-5 col-lg-6 ">
                <div class="section_title_wrapper">
                    <h2 class="section-title">
                        {{ __(@$affiliateContent->data_values->heading) }}
                    </h2>
                    <p class="section-subtitle mb-50">
                        {{ __(@$affiliateContent->data_values->sub_heading) }}
                    </p>
                </div>
                <div class="skill-wrapper">
                    @foreach ($affiliateElement as $affiliate)
                        <div class="skill-wrapper__item mb-40 d-flex align-items-center">
                            <div class="skill-wrapper__item-left">
                                <span>{{ @$affiliate->data_values->commission }}</span>
                            </div>
                            <div class="skill-wrapper__item-right">
                                <h2>{{ $affiliate->data_values->level }}</h2>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-xl-7 col-lg-6 ">
                <div class="affilite-program__right">
                    <div class="affilite-program__right-img">
                        <img class="w-img" src="{{ asset($activeTemplateTrue . 'images/program/affilate-image.png') }}"
                            alt="@lang('image')">
                        <img class="affilite__coin-one p-absolute"
                            src="{{ asset($activeTemplateTrue . 'images/program/coin-1.png') }}"
                            alt="@lang('image')">
                        <img class="affilite__coin-two p-absolute"
                            src="{{ asset($activeTemplateTrue . 'images/program/coin-2.png') }}"
                            alt="@lang('image')">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
