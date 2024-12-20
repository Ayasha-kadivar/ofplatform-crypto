@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
    $contact = getContent('contact.content', true);
    $contactInfo = getContent('contact.element', null,false,true);
    @endphp


    <!-- contact-section start -->
    <section class="contact-section pt-150 pb-150">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="section-header">
                        <h2 class="section__title">{{ __(@$contact->data_values->heading) }}</h2>
                        <p>{{ __(@$contact->data_values->sub_heading) }}</p>
                    </div>
                    <div>
                        @foreach (@$contactInfo as $info)
                            <div class="contact-item">
                                <div class="icon">@php echo $info->data_values->icon @endphp</div>
                                <div class="content">
                                    <h3 class="title text-shadow">{{ __($info->data_values->title) }}</h3>
                                    <p>{{ __($info->data_values->content) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="map">
                        <div class="maps" id="maps" data-latitude="{{ trim(@$contact->data_values->latitude) }}" data-longitude="{{ trim(@$contact->data_values->longitude) }}"></div>
                    </div>
                </div>
            </div>



            <div class="row pt-150">
                <div class="col-lg-12">
                    <div class="contact-form-wrapper">
                        <h3 class="contact-form__title text-shadow">{{ __($contact->data_values->title) }}</h3>
                        <form class="contact-form verify-gcaptcha" action="{{ route('contact') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label>@lang('Your Name')</label>
                                    <input name="name" type="text" placeholder="@lang('Enter your name')" class="form-control" value="{{ old('name') }}" required autocomplete="off">
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>@lang('Email Address')</label>
                                    <input name="email" type="text" class="form-control" placeholder="@lang('Enter your email')" value="{{ old('email') }}" required autocomplete="off">
                                </div>
                                <div class="form-group col-lg-12">
                                    <label>@lang('Subject')</label>
                                    <input name="subject" type="text" class="form-control" placeholder="@lang('Write your subject')" value="{{ old('subject') }}" required autocomplete="off">
                                </div>
                                <div class="form-group col-lg-12">
                                    <label>@lang('Your Message')</label>
                                    <textarea class="form-control h-auto" rows="5" name="message" id="message" placeholder="@lang('Write your message')" autocomplete="off">{{ old('message') }}</textarea>
                                </div>
                                <div class="form-group col-12">
                                    <x-captcha />
                                </div>

                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-primary btn-small w-100">@lang('Send Message')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- contact-section end -->
@endsection


@push('script')
    <script src="https://maps.google.com/maps/api/js?key={{ trim(@$contact->data_values->map_api_key) }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/map.js') }}"></script>
@endpush
