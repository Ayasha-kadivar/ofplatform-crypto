@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <!-- blog section start here -->
    <section class="blog-area section-common-bg pb-120 pt-120 ">
        <div class="container">
            <div class="row">
                @foreach ($blogs as $blog)
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="blog-item mb-30">
                            <div class="blog-item__thumb">
                                <a href="{{ route('blog.details', [slug($blog->data_values->title), $blog->id]) }}">
                                    <img class="w-img"
                                        src="{{ getImage('assets/images/frontend/blog/thumb_' . @$blog->data_values->image, '410x330') }}"
                                        alt="@lang('blog')">
                                </a>
                            </div>
                            <div class="blog-item__content">
                                <h4>
                                    <a href="{{ route('blog.details', [slug($blog->data_values->title), $blog->id]) }}">
                                        {{ __($blog->data_values->title) }}
                                    </a>
                                </h4>
                                <p>
                                    @lang(strLimit(strip_tags(@$blog->data_values->description), 100))
                                </p>
                                <div class="blog-item__content-meta">
                                    <ul class="post-meta d-flex flex-wrap justify-content-between">
                                        <li>
                                            <i class="fas fa-user"></i>
                                            @lang(' By Admin')
                                        </li>
                                        <li>
                                            <i class="fas fa-calendar-check"></i>
                                            {{ showDateTime($blog->created_at, 'Y M d') }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach


            </div>
            @if ($blogs->hasPages())
                {{ paginateLinks($blogs) }}
            @endif
        </div>
    </section>
    @if ($sections != null)
        @foreach (json_decode($sections) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
