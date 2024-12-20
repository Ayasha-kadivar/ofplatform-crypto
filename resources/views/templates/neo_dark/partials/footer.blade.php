@php
    $links = getContent('policy_pages.element',false,null,true);
    $footer = getContent('footer.content',true);
    $socials = getContent('social_icon.element',false,null,true);
@endphp

<!-- footer-section start -->
<footer class="footer-section">
    <div class="container">
        <div class="row mb-none-50 justify-content-center text-center">
            <div class="col-xl-6 col-md-7 mb-50">
                <div class="footer-widget">
                    <div class="about__widget">
                        <a href="{{route('home')}}" class="mb-3 mb-sm-4">
                            <img src="{{ getImage(getFilePath('logoIcon') .'/logo.png') }}" alt="footer"
                                 class="max-250">
                        </a>
                        <p class="mt-3">{{ __(@$footer->data_values->content) }}</p>

                        <ul class="social-links">

                            @foreach($socials as $social)
                                <li><a href="{{ @$social->data_values->url }}">@php echo @$social->data_values->icon
                                        @endphp</a></li>
                            @endforeach

                        </ul>
                        <ul class="privacy-links">
                            @foreach($links as $link)
                                <li><a href="{{ route('policy.pages',[slug($link->data_values->title),$link->id]) }}" class="base--color">@lang     ($link->data_values->title)</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div><!-- footer-widget end -->
            </div>
        </div>
    </div>
</footer>
<div class="footer-bottom">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <p class="copy-right-text">&copy; {{date('Y')}} <a href="{{ route('home') }}" class="text-white">{{ __($general->sitename) }}</a>. @lang('All Rights Reserved')</p>
            </div>
        </div>
    </div>
</div>
<!-- footer-section end  -->
