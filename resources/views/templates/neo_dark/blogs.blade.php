@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="pt-120 pb-120">
    <div class="container">
        <div class="row mb-none-30 justify-content-center">

            @foreach($blogs as $k=> $data)
            <div class="col-lg-4 col-md-6 mb-30">
                <div class="post-item">
                    <div class="post-item__thumb"><img src="{{getImage('assets/images/frontend/blog/thumb_'.@$data->data_values->image)}}" alt="image"></div>
                    <div class="post-item__content">
                        <h3 class="post__title text-shadow">
                            <a href="{{route('blog.details',[slug($data->data_values->title),$data->id])}}">
                                {{__(@$data->data_values->title)}}
                            </a>
                        </h3>
                        <p>@lang(strLimit(strip_tags(@$data->data_values->description),180))</p>
                        <a href="{{route('blog.details',[slug($data->data_values->title),$data->id])}}" class="btn btn-primary btn-small mt-4">{{trans('Read More')}}</a>
                    </div>
                </div><!-- post-item end -->
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
