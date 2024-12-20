@php
    $faqContent = getContent('faq.content',true);
    $faqs = getContent('faq.element',null,false,true);
@endphp

<div class="faq-section pt-120 pb-120 bg--light full-height">
    <div class="container">
        <div class="mb-4">
            <h3>{{ __(@$faqContent->data_values->title) }}</h3>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @foreach($faqs as $faq)
                <div class="faq-item">
                    <div class="faq-item__title">
                        <h5 class="title">{{ __($faq->data_values->question) }}</h5>
                    </div>
                    <div class="faq-item__content">
                        <p>{{ __($faq->data_values->answer) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>