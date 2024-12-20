@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
        $contactContent = getContent('contact.content', true);
        $contactElement = getContent('contact.element', orderById: true);
    @endphp
    <section class="contact-area section-common-bg pt-120 pb-120">
        <div class="container">
            <div class="row gy-4">
                @foreach ($contactElement as $contact)
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="contact-meta p-relative">
                            <div class="contact-meta__item">
                                <div class="hex contact-meta__hex">
                                    <div class="inner-hex"></div>
                                    <span class="hex-icon">
                                        @php
                                            echo $contact->data_values->icon;
                                        @endphp
                                    </span>
                                </div>
                                <div class="contact-meta__content text-center ">
                                    <h4 class="contact-meta__title">
                                        {{ __(@$contact->data_values->title) }}
                                    </h4>
                                    <div class="contact-meta__address">
                                        <p>
                                            {{ __(@$contact->data_values->content) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <div class="contact-map-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-10">
                    <div class="contact-form">
                        <div class="contact-form__bg">
                            <div class="contact-form__inner">
                                <div class="contact-form__item">
                                    <form method="post" action="" class="verify-gcaptcha">
                                        @csrf
                                        <h2 class="contact-form__title text-center">
                                            @lang('Get In Touch With Us')
                                        </h2>
                                        <div class="row gy-4">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Name')</label>
                                                <input name="name" type="text" class="form-control form--control"
                                                    value="@if (auth()->user()) {{ auth()->user()->fullname }}@else{{ old('name') }} @endif"
                                                    @if (auth()->user()) readonly @endif required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">@lang('Email')</label>
                                                <input name="email" type="email" class="form-control form--control"
                                                    value="@if (auth()->user()) {{ auth()->user()->email }}@else{{ old('email') }} @endif"
                                                    @if (auth()->user()) readonly @endif required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">@lang('Subject')</label>
                                                <input name="subject" type="text" class="form-control form--control"
                                                    value="{{ old('subject') }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">@lang('Message')</label>
                                                <textarea name="message" wrap="off" class="form-control form--control" required>{{ old('message') }}</textarea>
                                            </div>
                                            <x-captcha />

                                            <div class="col-12 text-center">
                                                <button type="submit"
                                                    class="btn btn--outline-base w-100">@lang('Send Messages')</button>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="contact-map">
            <iframe src="{{ @$contactContent->data_values->map_url }}" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
@endsection
