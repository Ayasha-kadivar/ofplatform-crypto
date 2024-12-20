@extends($activeTemplate.'layouts.frontend')
@section('content')
@php
$contactContent = getContent('contact.content',true);
$contactElement = getContent('contact.element',null,false,true);
@endphp
<div class="contact-section py-5 bg--light">
    <div class="container">
        <h3 class="text-center mb-4">{{ __($pageTitle) }}</h3>
        <div class="card custom--card">
            <div class="card-body">
                <h3 class="title mb-2">{{ __(@$contactContent->data_values->title) }}</h3>
                <p class="mb-3">{{ __(@$contactContent->data_values->subtitle) }}</p>
                <div class="mb-3">
                    @foreach($contactElement as $contact)
                    <p><span class="fw-bold"> @php echo $contact->data_values->icon @endphp
                            {{ __($contact->data_values->title) }}</span>: {{ __($contact->data_values->content) }}</p>
                    @endforeach
                </div>
                <form action="{{ route('contact') }}" class="contact-form verify-gcaptcha" method="post">
                    @csrf
                    <div class="row gy-4">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>@lang('Name')</label>
                                <input type="text" name="name" class="form-control form--control h-45" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>@lang('Email')</label>
                                <input type="email" name="email" class="form-control form--control h-45" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>@lang('Subject')</label>
                                <input type="text" name="subject" class="form-control form--control h-45" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>@lang('Message')</label>
                                <textarea class="form-control form--control" name="message"
                                    placeholder="@lang('Write down your message')..." required></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <x-captcha />
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn--base">@lang('Send Message')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
