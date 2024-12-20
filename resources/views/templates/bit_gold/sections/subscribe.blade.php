@php
$subscribeContent = getContent('subscribe.content', true);
@endphp
<section class="pb-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="subscribe-wrapper bg_img" data-background="{{ getImage('assets/images/frontend/subscribe/' . @$subscribeContent->data_values->image, '1920x1281') }}">
                    <div class="row align-items-center">
                        <div class="col-lg-5">
                            <h2 class="title">{{ __(@$subscribeContent->data_values->heading) }}</h2>
                        </div>
                        <div class="col-lg-7 mt-lg-0 mt-4">
                            <form class="subscribe-form" method="post">
                                @csrf
                                <input type="email" class="form-control" name="email" placeholder="@lang('Email Address')">
                                <button type="submit" class="subscribe-btn"><i class="las la-paper-plane"></i></button>
                            </form>
                        </div>
                    </div>
                </div><!-- subscribe-wrapper end -->
            </div>
        </div>
    </div>
</section>


@push('script')
    <script>
        (function($) {
            "use strict";
            $('.subscribe-form').on('submit', function(e) {
                e.preventDefault();
                var data = $('.subscribe-form').serialize();
                $.ajax({
                    type: "POST",
                    url: "{{ route('subscribe') }}",
                    data: data,
                    success: function(response) {
                        if (response.status == 'success') {
                            notify('success', response.message);
                            $('#email').val('');
                        } else {
                            notify('error', response.message);
                        }
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
