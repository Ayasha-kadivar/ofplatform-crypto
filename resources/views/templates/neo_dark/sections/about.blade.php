@php
$aboutCaption = getContent('about.content', true);
@endphp
<!--=======About-Section Starts Here=======-->
<section class="about-section pt-150 pb-150">
    <div class="container">
        <div class="row justify-content-between flex-wrap-reverse">
            <div class="col-lg-6 pe-xl-5">
                <div class="section-header text-left margin-olpo mb-0">
                    <h2 class="section__title">{{ __(@$aboutCaption->data_values->heading) }}</h2>
                    <div class="about-content">
                        <p>@php echo nl2br(@$aboutCaption->data_values->content) @endphp</p>

                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-thumb">
                    <div class="thumb">
                        <img src="{{ getImage('assets/images/frontend/about/' . @$aboutCaption->data_values->image) }}" alt="about">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--=======About-Section Ends Here=======-->
