@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
        $bannerContent = getContent('banner.content', true);
    @endphp
    <section class="banner-area">
        <div class="banner__shape">
            <img class="w-100" src="{{ asset($activeTemplateTrue . 'images/banner/shape.png') }}" alt="@lang('image')">
        </div>
        <div class="single-banner" data-animation="fadeInUp" data-delay=".4s">
            <div class="container">
                <div class="row ">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                        <div class="banner-content">
                            <h1>{{ __(@$bannerContent->data_values->heading) }}</h1>
                            <a class="btn btn--outline-base" href="{{ @$bannerContent->data_values->button_link }}">
                                {{ __(@$bannerContent->data_values->button_name) }}
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <div class="banner-right-content p-relative d-none d-md-inline-block">
                            <img class="w-img banner-right-image"
                                src="{{ asset($activeTemplateTrue . 'images/banner/banner-right.png') }}"
                                alt="@lang('banner Image')">
                            <img class="coin-one p-absolute" src="{{ asset($activeTemplateTrue . 'images/banner/01.png') }}"
                                alt="@lang('image')">
                            <img class="coin-two p-absolute" src="{{ asset($activeTemplateTrue . 'images/banner/02.png') }}"
                                alt="@lang('image')">
                            <img class="coin-three p-absolute"
                                src="{{ asset($activeTemplateTrue . 'images/banner/03.png') }}" alt="@lang('image')">
                            <img class="coin-four p-absolute"
                                src="{{ asset($activeTemplateTrue . 'images/banner/04.png') }}" alt="@lang('image')">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection


@push('style')
    <style>
        @media (max-width: 767px) {
            .banner-area {
                background-image: url({{ getImage('assets/images/frontend/banner/' . @$bannerContent->data_values->image) }});

            }
        }
    </style>
@endpush
