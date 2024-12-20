@php
    $faqElement = getContent('faq.element', orderById: true);
@endphp

<section class="faq-area section-common-bg pt-120 pb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-10">
                @foreach ($faqElement as $faq)
                    <div class="faq-item-innner">
                        <div class="faq-item">
                            <h2 class="faq-item-title">
                                {{ __(@$faq->data_values->question) }}
                            </h2>
                            <p class="faq-items-content">
                                {{ __($faq->data_values->answer) }}
                            </p>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</section>
<!-- faq area end here -->
