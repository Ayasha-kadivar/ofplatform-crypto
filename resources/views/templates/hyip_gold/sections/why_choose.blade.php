@php
    $choosUsContent = getContent('why_choose.content', true);
    $choseUsElement = getContent('why_choose.element', orderById: true);
@endphp
<section class="why-choose-area section-common-bg pt-120 pb-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="section_title_wrapper text-center">
                    <h2 class="section-title">
                        {{ __(@$choosUsContent->data_values->heading) }}
                    </h2>
                    <p class="section-subtitle margin-0 mb-50">
                        {{ __(@$choosUsContent->data_values->sub_heading) }}
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4 col-lg-6 col-md-6">
                @foreach ($choseUsElement as $chooseUs)
                    @if ($loop->index % 2 == 0)
                        <div
                            class="why-choose-item__wrapper {{ $loop->index % 4 == 0 ? ' ' : 'mr-80 ml--80 col-show-xl' }}">
                            <div class="why-choose-item why-choose-item__style-one">
                                <div class="choose-icon">
                                    @php
                                        echo $chooseUs->data_values->icon;
                                    @endphp
                                </div>
                                <h4>{{ __($chooseUs->data_values->title) }}</h4>
                                <p>{{ __($chooseUs->data_values->content) }}</p>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 d-none d-xl-inline-block">
                <div class="why-choose__center ml--80 mr--80 mt-80 col-show-xl">
                    <img class="w-img"
                        src="{{ getImage('assets/images/frontend/why_choose/' . @$choosUsContent->data_values->image, '610x370') }}"
                        alt="img not found">
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6">
                @foreach ($choseUsElement as $chooseUs)
                    @if ($loop->index % 2 == 1)
                        <div
                            class="why-choose-item__wrapper {{ $loop->index % 3 == 0 ? 'ml-80 mr--80 col-show-xl' : '' }}">
                            <div class="why-choose-item why-choose-item__style-two ">
                                <div class="choose-icon choose-icon2">
                                    @php
                                        echo $chooseUs->data_values->icon;
                                    @endphp
                                </div>
                                <h4>{{ __($chooseUs->data_values->title) }}</h4>
                                <p>{{ __($chooseUs->data_values->content) }}</p>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</section>
