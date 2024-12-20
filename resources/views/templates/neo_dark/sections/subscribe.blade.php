@php
    $subscribeContent = getContent('subscribe.content',true);
@endphp

<section class="newsletter-section  pt-150  pb-150 " id="subscribe">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-7">
                <div class="section-header margin-olpo">
                    <h2 class="section__title">{{ __(@$subscribeContent->data_values->heading) }}</h2>
                    <p>{{ __(@$subscribeContent->data_values->sub_heading) }}</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-7">
                <form class="newslater-form" method="post">
                    @csrf
                    <input type="email" name="email" placeholder="@lang('Email Address')">
                    <button type="submit">
                        <i class="fa fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

@push('script')
<script>
    (function ($) {
        "use strict";
        $('.newslater-form').on('submit', function (e) {
            e.preventDefault();
            var data = $('.newslater-form').serialize();
            $.ajax({
                type: "POST",
                url: "{{ route('subscribe') }}",
                data: data,
                success: function (response) {
                        if(response.status == 'success'){
                            notify('success', response.message);
                            $('#email').val('');
                        }else{
                            notify('error', response.message);
                        }
                }
           });
        });
    })(jQuery);
</script>
@endpush

