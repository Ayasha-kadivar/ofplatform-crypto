@extends($activeTemplate.'layouts.master')
@section('content')

    <div class="dashboard-inner">
        <div class="mb-4">
            <h3 class="mb-2">@lang('Profile Update')</h3>
        </div>

        <div class="card custom--card">
            <div class="card-body">
                <form action="{{route('user.profile.update.submit')}}" method="POST" class="">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">@lang('Username')</label>
                                <input type="text" class="form-control form--control checkUser h-45" name="username" value="{{ old('username') }}" required maxlength="25">
                                <small class="text-danger usernameExist"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">@lang('E-Mail Address')</label>
                                <input type="email" class="form-control form--control h-45 checkUser" name="email" value="{{ old('email') }}" required maxlength="50">
                                <small class="text-danger emailExist"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">@lang('Country')</label>
                                <select name="country" class="form--control form-select" required>
                                    @foreach ($countries as $key => $country)
                                        <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}">
                                            {{ __($country->country) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">@lang('Mobile')</label>
                                <div class="input-group ">
                                    <span class="input-group-text mobile-code">

                                    </span>
                                    <input type="hidden" name="mobile_code">
                                    <input type="hidden" name="country_code">
                                    <input type="number" name="mobile" value="{{ old('mobile') }}" class="form-control form--control checkUser" required>
                                </div>
                                <small class="text-danger mobileExist"></small>
                            </div>
                        </div>

                        {{-- <div class="form-group col-sm-6">
                            <label class="form-label">@lang('First Name')</label>
                            <input type="text" class="form-control form--control" name="firstname" value="{{ old('firstname') }}" required>
                        </div>

                        <div class="form-group col-sm-6">
                            <label class="form-label">@lang('Last Name')</label>
                            <input type="text" class="form-control form--control" name="lastname" value="{{ old('lastname') }}" required>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="form-label">@lang('Address')</label>
                            <input type="text" class="form-control form--control" name="address" value="{{ old('address') }}">
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="form-label">@lang('State')</label>
                            <input type="text" class="form-control form--control" name="state" value="{{ old('state') }}">
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="form-label">@lang('Zip Code')</label>
                            <input type="text" class="form-control form--control" name="zip" value="{{ old('zip') }}">
                        </div>

                        <div class="form-group col-sm-6">
                            <label class="form-label">@lang('City')</label>
                            <input type="text" class="form-control form--control" name="city" value="{{ old('city') }}">
                        </div> --}}
                        <div class="col-12">
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>


    </div>

@endsection

@push('script')
    <script>
        "use strict";
        (function($) {
            @if ($mobileCode)
                $(`option[data-code={{ $mobileCode }}]`).attr('selected', '');
            @endif
            $('select[name=country]').change(function() {
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
            });
            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
            $('.checkUser').on('focusout', function(e) {
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                if ($(this).attr('name') == 'mobile') {
                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                    var data = {
                        mobile: mobile,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'email') {
                    var data = {
                        email: value,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'username') {
                    var data = {
                        username: value,
                        _token: token
                    }
                }
                $.post(url, data, function(response) {
                    console.log(data);
                    if (response.data != false && response.type == 'email') {
                        $('#existModalCenter').modal('show');
                    } else if (response.data != false && response.type == 'username') {
                        $(`.${response.type}Exist`).text(`${response.type} already exist`);
                    } else {
                        $(`.${response.type}Exist`).text('');
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
