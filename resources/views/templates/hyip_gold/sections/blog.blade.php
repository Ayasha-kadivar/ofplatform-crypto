@php
    $blogContent = getContent('blog.content', true);
    $blogElement = getContent('blog.element', false, 3, true);
@endphp

<section class="blog-area section-common-bg pb-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-10">
                <div class="section_title_wrapper text-center mb-50">
                    <h2 class="section-title">
                        {{ __(@$blogContent->data_values->heading) }}
                    </h2>
                    <p class="section-subtitle margin-0 ">
                        {{ __(@$blogContent->data_values->sub_heading) }}
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach ($blogElement as $blog)
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
    </div>
</section>
