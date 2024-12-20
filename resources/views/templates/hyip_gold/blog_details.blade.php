@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="blog-area-details section-common-bg pb-120 pt-120">

        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-8">
                    <div class="blog-item blog-details-post">
                        <div class="blog-item__thumb">
                            <img class="w-img"
                                src="{{ getImage('assets/images/frontend/blog/' . @$blog->data_values->image) }}"
                                alt="img not found">
                        </div>
                        <div class="blog-item__content mb-80">
                            @php
                                echo $blog->data_values->description;
                            @endphp

                            <ul class="share-link">
                                <li><strong>@lang('Share'):</strong> </li>
                                <li>
                                    <a
                                        href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}">
                                        <span class="lab la-facebook-f"></span>
                                    </a>
                                </li>
                                <li><a
                                        href="https://twitter.com/intent/tweet?text={{ __($blog->data_values->title) }}&amp;url={{ urlencode(url()->current()) }}">
                                        <span class="lab la-twitter"></span>
                                    </a>
                                </li>
                                <li><a
                                        href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ __($blog->data_values->title) }}&amp;summary=dit is de linkedin summary">
                                        <span class="fab fa-linkedin"></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class=" mt-80">
                            <div class="fb-comments" data-href="{{ url()->current() }}" data-numposts="5"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4">
                    <div class="blog__sidebar__area">
                        <div class="widget-items">
                            <div class="widget widget_post__area">
                                <h4 class="widget__title">
                                    @lang('Latest Blog Post')
                                </h4>
                                @foreach ($blogs as $blog)
                                    <ul class="widget_single_post">
                                        <li>
                                            <a href="{{ route('blog.details', [slug($blog->data_values->title), $blog->id]) }}"
                                                class="widget__post">
                                                <div class="widget__post__thumb">
                                                    <img src="{{ getImage('assets/images/frontend/blog/thumb_' . @$blog->data_values->image, '410x330') }}"
                                                        alt="@lang('blog Image')">
                                                </div>
                                                <div class="widget__post__content">
                                                    <h4 class="widget__post__title">
                                                        {{ __($blog->data_values->title) }}
                                                    </h4>
                                                    <ul class="widget__post__meta">
                                                        <li><i class="fas fa-user"></i>@lang('By Admin')</li>
                                                        <li><i
                                                                class="fas fa-calendar-check"></i>{{ showDateTime($blog->created_at, 'Y M d') }}
                                                        </li>
                                                    </ul>
                                                </div>
                                            </a>
                                        </li>

                                    </ul>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('fbComment')
    @php echo loadExtension('fb-comment') @endphp
@endpush
