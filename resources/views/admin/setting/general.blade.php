    @extends('admin.layouts.app')
    @section('panel')
        <div class="row mb-none-30">
            <div class="col-lg-12 col-md-12 mb-30">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group ">
                                        <label> @lang('Site Title')</label>
                                        <input class="form-control" type="text" name="site_name" required value="{{$general->site_name}}">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group ">
                                        <label>@lang('Currency')</label>
                                        <input class="form-control" type="text" name="cur_text" required value="{{$general->cur_text}}">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group ">
                                        <label>@lang('Currency Symbol')</label>
                                        <input class="form-control" type="text" name="cur_sym" required value="{{$general->cur_sym}}">
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-sm-6">
                                    <label> @lang('Timezone')</label>
                                    <select class="select2-basic" name="timezone">
                                        @foreach($timezones as $timezone)
                                        <option value="'{{ @$timezone}}'">{{ __($timezone) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4 col-sm-6">
                                    <label> @lang('Site Base Color')</label>
                                    <div class="input-group">
                                        <span class="input-group-text p-0 border-0">
                                            <input type='text' class="form-control colorPicker" value="{{$general->base_color}}"/>
                                        </span>
                                        <input type="text" class="form-control colorCode" name="base_color" value="{{ $general->base_color }}"/>
                                    </div>
                                </div>

                                <div class="form-group col-md-4 col-sm-6">
                                    <label> @lang('Site Secondary Color')</label>
                                    <div class="input-group">
                                        <span class="input-group-text p-0 border-0">
                                            <input type='text' class="form-control colorPicker" value="{{$general->secondary_color}}"/>
                                        </span>
                                        <input type="text" class="form-control colorCode" name="secondary_color" value="{{ $general->secondary_color }}"/>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('Balance Transfer Fixed Charge')</label>
                                        <div class="input-group">
                                            <input class="form-control bal-charge" type="text" name="f_charge" required value="{{getAmount($general->f_charge)}}" @if(!$general->b_transfer) readonly @endif>
                                            <div class="input-group-text">{{ $general->cur_text }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('Balance Transfer Percent Charge')</label>
                                        <div class="input-group">
                                            <input class="form-control bal-charge" type="text" name="p_charge" required value="{{getAmount($general->p_charge)}}" @if(!$general->b_transfer) readonly @endif>
                                            <div class="input-group-text">%</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('Registration Bonus')</label>
                                        <div class="input-group">
                                            <input class="form-control bal-charge" type="text" name="signup_bonus_amount" required value="{{getAmount($general->signup_bonus_amount)}}" @if(!$general->signup_bonus_control) readonly @endif>
                                            <div class="input-group-text">{{ $general->cur_text }}</div>
                                            @if(!$general->signup_bonus_control)
                                            <small class="text--small text-muted"><i><i class="las la-info-circle"></i> @lang('To give the registration bonus, please enable the module from the') <a href="{{ route('admin.setting.system.configuration') }}" class="text--small">@lang('System Configuration')</a></i></small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group ">
                                        <label> @lang('FT Price in USD')</label>
                                        <input class="form-control" type="number" name="price_ft" required value="{{$general->price_ft}}" step="0.01">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('style')
        <style>
            .border-line-area {
                position: relative;
                text-align: center;
                z-index: 1;
            }
            .border-line-area::before {
                position: absolute;
                content: '';
                top: 50%;
                left: 0;
                width: 100%;
                height: 1px;
                background-color: #e5e5e5;
                z-index: -1;
            }
            .border-line-title {
                display: inline-block;
                padding: 3px 10px;
                background-color: #fff;
            }
        </style>
    @endpush

    @push('script-lib')
        <script src="{{ asset('assets/admin/js/spectrum.js') }}"></script>
    @endpush

    @push('style-lib')
        <link rel="stylesheet" href="{{ asset('assets/admin/css/spectrum.css') }}">
    @endpush

    @push('script')
        <script>
            (function ($) {
                "use strict";
                $('.colorPicker').spectrum({
                    color: $(this).data('color'),
                    change: function (color) {
                        $(this).parent().siblings('.colorCode').val(color.toHexString().replace(/^#?/, ''));
                    }
                });

                $('.colorCode').on('input', function () {
                    var clr = $(this).val();
                    $(this).parents('.input-group').find('.colorPicker').spectrum({
                        color: clr,
                    });
                });

                $('select[name=timezone]').val("'{{ config('app.timezone') }}'").select2();
                $('.select2-basic').select2({
                    dropdownParent:$('.card-body')
                });

                $('[name=b_transfer]').change(function () {
                    if($(this).is(":checked")){
                        $('.bal-charge').removeAttr('readonly');
                    }else{
                        $('.bal-charge').attr('readonly',true);
                    }
                }).change();
            })(jQuery);

        </script>
    @endpush

