@php
    $aboutContent = getContent('about.content', true);
    $aboutElement = getContent('about.element', orderById: true);
    $visionElement = getContent('vision_mission.element', orderById: true);
    
@endphp
<section class="about-area section-common-bg pt-100 pb-120">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-6 col-lg-6">
                <div class="about__left p-relative">
                    <img class="about__left-vector-1"
                        src="{{ asset($activeTemplateTrue . 'images/about/vector-01.png') }}" alt="@lang('image')">
                    <img class="about__left-vector-2"
                        src="{{ asset($activeTemplateTrue . 'images/about/vector-02.png') }}" alt="@lang('image')">
                    <img class="w-img" src="{{ asset($activeTemplateTrue . 'images/about/about.png') }}"
                        alt="@lang('about image')">
                </div>
            </div>
            <div class="col-xl-6 col-lg-6">
                <div class="about__right">
                    <div class="section_title_wrapper">
                        <h2 class="section-title">
                            {{ __($aboutContent->data_values->heading) }}
                        </h2>
                        <p class="section-subtitle mb-50">
                            {{ __($aboutContent->data_values->content) }}
                        </p>
                    </div>
                    <div class="about__right-featurs">
                        <div class="row">
                            @foreach ($visionElement as $item)
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                    <div class="about__right-featurs-item mb-30">
                                        <h4>
                                            @php
                                                echo $item->data_values->icon;
                                            @endphp
                                            {{ __($item->data_values->title) }}
                                        </h4>
                                        <p>{{ __($item->data_values->content) }}</p>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
                <div class="about__right-bottom">
                    <div class="row">
                        @foreach ($aboutElement as $about)
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                <div class="about__right-bottom-item about__right-bottom-item-border">
                                    <div class="about__right-bottom-item-left">
                                        @php
                                            echo $about->data_values->icon;
                                        @endphp
                                    </div>
                                    <div class="about__right-bottom-item-right">
                                        <h3><span
                                                class="counter">{{ $about->data_values->number }}</span>.0{{ $about->data_values->postfix }}
                                        </h3>
                                        <p>{{ __($about->data_values->title) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
