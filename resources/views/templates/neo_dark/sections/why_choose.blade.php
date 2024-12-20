@php
    $chooses = getContent('why_choose.element','','',1);
    $chooseContent = getContent('why_choose.content',true);
@endphp
<section class="feature-section pb-150 pt-150">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-header text-center">
                    <h2 class="section__title">{{ __(@$chooseContent->data_values->heading) }}</h2>
                    <p>{{ __(@$chooseContent->data_values->sub_heading) }}</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mb-30-none">
            @foreach($chooses as $k => $data)
            <div class="col-md-6 col-sm-10 col-xl-4">
                <div class="feature--item">

                    <div class="feature-thumb icon">
                     @php echo @$data->data_values->icon @endphp
                    </div>
                    <div class="feature-content">
                        <h5 class="title">{{__($data->data_values->title)}}</h5>
                        <p>{{__($data->data_values->content)}}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
