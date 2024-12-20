@php
    $breadcrumbContent = getContent('breadcrumb.content', true);
@endphp
<section class="breadcrumb-area primary-bg p-relative pt-120 pb-80">
    <div class="breadcrumb-area__shape">
        <img class="w-img" src="{{ asset($activeTemplateTrue . 'images/breadcrumb/breadcrumb-shape.png') }}"
            alt="@lang('Image')">
    </div>
    <div class="breadcrumb-area__shape2">
        <img class="w-img" src="{{ asset($activeTemplateTrue . 'images/banner/shape.png') }}" alt="@lang('Image')">
    </div>

    <div class="breadcrumb-wrapper  ">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                    <div class="breadcrumb-area__inner">
                        <h2 class="breadcrumb-area__inner-title">
                            {{ __($pageTitle) }}
                        </h2>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 text-end">
                    <div class="breadcrumb-right-content d-none d-md-inline-block">
                        <img class="w-img banner-right-image"
                            src="{{ getImage('assets/images/frontend/breadcrumb/' . @$breadcrumbContent->data_values->image, '435x435') }}"
                            alt="@lang('Image')">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
