@extends($activeTemplate . 'layouts.app')
@section('panel')
    <div class="dashboard">
        @include($activeTemplate . 'partials.sidebar')
        @include($activeTemplate . 'partials.topbar')

        <div class="body-wrapper">
            @include($activeTemplate . 'partials.dbreadcrumb')
            @yield('content')
        </div>
    @endsection

    @push('script')
        <script>
            (function($) {
                "use strict";
                $(".langSel").on("change", function() {
                    window.location.href = "{{ route('home') }}/change/" + $(this).val();
                });

            })(jQuery);
        </script>
        <script>
            (function($) {
                "use strict";

                $('form').on('submit', function() {
                    if ($(this).valid()) {
                        $(':submit', this).attr('disabled', 'disabled');
                    }
                });

                var inputElements = $('[type=text],[type=password],select,textarea');
                $.each(inputElements, function(index, element) {
                    element = $(element);
                    element.closest('.form-group').find('label').attr('for', element.attr('name'));
                    element.attr('id', element.attr('name'))
                });

                $.each($('input, select, textarea'), function(i, element) {

                    if (element.hasAttribute('required')) {
                        $(element).closest('.form-group').find('label').addClass('required');
                    }

                });


                $('.showFilterBtn').on('click', function() {
                    $('.responsive-filter-card').slideToggle();
                });


                Array.from(document.querySelectorAll('table')).forEach(table => {
                    let heading = table.querySelectorAll('thead tr th');
                    Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
                        Array.from(row.querySelectorAll('td')).forEach((colum, i) => {
                            colum.setAttribute('data-label', heading[i].innerText)
                        });
                    });
                });

            })(jQuery);
        </script>
    @endpush
