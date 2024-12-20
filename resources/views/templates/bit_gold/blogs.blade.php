@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="pt-120 pb-120">
    <div class="container">
        <div class="row gy-4">
            @foreach($blogs as $k=> $data)
                <div class="col-lg-4 col-md-6">
                    <div class="blog-card h-100">
                        <div class="blog-card__thumb">
                            <img src="{{ getImage('assets/images/frontend/blog/thumb_'.@$data->data_values->image,'460x240') }}"
                                alt="image">
                        </div>
                        <div class="blog-card__content">
                            <h5 class="blog-card__title mb-2"><a
                                    href="{{ route('blog.details',[slug($data->data_values->title),$data->id]) }}">{{ __(@$data->data_values->title) }}</a>
                            </h5>

                            <p>@lang(strLimit(strip_tags(@$data->data_values->description),180))</p>
                            <a href="{{ route('blog.details',[slug($data->data_values->title),$data->id]) }}"
                                class="btn--base btn-md mt-4">@lang('Read More')</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $blogs->links() }}
    </div>
</section>

@if($sections != null)
@foreach(json_decode($sections) as $sec)
    @include($activeTemplate.'sections.'.$sec)
@endforeach
@endif
@endsection
