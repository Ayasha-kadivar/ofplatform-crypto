@php
    $links = getContent('policy_pages.element',false,null,true);
    $footer = getContent('footer.content',true);
    $socials = getContent('social_icon.element',false,null,true);
@endphp
<footer class="footer bg_img"
    data-background="{{ getImage('assets/images/frontend/footer/'.@$footer->data_values->image,'1920x1281') }}">
    <div class="footer__top">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 text-center">
                    <a href="{{ route('home') }}" class="site-logo"><img src="{{ getImage(getFilePath('logoIcon') .'/logo.png') }}" alt="image"></a>
                    <ul class="footer-short-menu d-flex flex-wrap justify-content-center mt-3">
                        @foreach($links as $link)
                            <li><a href="{{ route('policy.pages',[slug($link->data_values->title),$link->id]) }}">{{ __($link->data_values->title) }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="footer__bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-md-left text-center">
                    <p class="copy-right-text">&copy; {{date('Y')}} <a href="{{ route('home') }}" class="text--base">{{ __($general->site_name) }}</a>. @lang('All Rights Reserved')</p>
                </div>
                <div class="col-md-6">
                    <ul class="social-link-list d-flex flex-wrap justify-content-md-end justify-content-center">
                        @foreach($socials as $social)
                            <li><a href="{{ @$social->data_values->url }}" target="_blank">@php echo @$social->data_values->icon
                                    @endphp</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
