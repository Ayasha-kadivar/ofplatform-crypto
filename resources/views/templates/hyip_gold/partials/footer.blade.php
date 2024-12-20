@php
    $socialElement = getContent('social_icon.element', orderById: true);
    $policyPages = getContent('policy_pages.element', orderById: true);
    $pages = App\Models\Page::where('tempname', $activeTemplate)
        ->where('is_default', 0)
        ->latest('id')
        ->get();
    $contactElement = getContent('contact.element', orderById: true);
    $footerContent = getContent('footer.content', true);
@endphp
<footer class="footer__area ">
    <div class="footer-inner footer__area-bg pt-120"
        style="background-image: url({{ asset($activeTemplateTrue . 'images/footer/footer-bg.png') }});">
        <div class="container">
            <div class="row">
                <div class="footer-top text-center pb-50">
                    <a href="{{ route('home') }}" class="logo">
                        <img src="{{ getImage('assets/images/logoIcon/logo.png') }}" alt="@lang('logo img')">
                    </a>
                    <div class="footer-top__social">
                        <ul>
                            @foreach ($socialElement as $social)
                                <li>
                                    <a href="{{ $social->data_values->url }}">
                                        @php
                                            echo $social->data_values->icon;
                                        @endphp
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-middle pb-20">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                        <div class="footer-widget mb-30">
                            <h4 class="footer-widget__title">
                                @lang('About Us')
                            </h4>
                            <p>{{ __($footerContent->data_values->content) }}</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                        <div class="footer-widget mb-30">
                            <h4 class="footer-widget__title">
                                @lang('Quick Link')
                            </h4>
                            <ul class="footer-widget__link">
                                @foreach ($pages as $data)
                                    <li>
                                        <a href="{{ route('pages', [$data->slug]) }}">
                                            {{ __($data->name) }}
                                        </a>
                                    </li>
                                @endforeach
                                <li><a href="{{ route('plan') }}">@lang('Plans')</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                        <div class="footer-widget mb-30">
                            <h4 class="footer-widget__title">
                                @lang('Privacy & Policy')
                            </h4>

                            <ul class="footer-widget__link">
                                @foreach ($policyPages as $policy)
                                    <li>
                                        <a
                                            href="{{ route('policy.pages', [slug($policy->data_values->title), $policy->id]) }}">{{ __($policy->data_values->title) }}</a>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                        <div class="footer-widget mb-30">
                            <h4 class="footer-widget__title">
                                @lang('Contact us')
                            </h4>
                            <ul class="footer-widget__contact">
                                @foreach ($contactElement as $contact)
                                    <li>
                                        <a href="javascript:void(0)">
                                            @php
                                                echo $contact->data_values->icon;
                                            @endphp
                                            {{ $contact->data_values->content }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom pb-30">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="footer-copyright text-center">
                            <p> @lang('Copyright ')© <?php echo date('Y'); ?> @lang('All Right Reserved.') </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
