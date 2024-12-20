<div class="input-group w-auto flex-fill">
    <input name="date" type="search" data-range="true" data-multiple-dates-separator=" - " data-language="en" data-format="Y-m-d" class="datepicker-here form-control bg--white pe-2" data-position='bottom right' placeholder="@lang('Start Date - End Date')" autocomplete="off" value="{{ request()->date }}">
    <button class="btn btn--primary input-group-text"><i class="la la-search"></i></button>
</div>

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{asset('assets/admin/css/vendor/datepicker.min.css')}}">
@endpush