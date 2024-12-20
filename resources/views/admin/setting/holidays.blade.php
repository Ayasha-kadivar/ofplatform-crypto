@extends('admin.layouts.app')
@section('panel')


<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--md  table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th scope="col">@lang('SL')</th>
                                <th scope="col">@lang('Date')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($holidays as $holiday)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $holiday->date }}</td>
                                    <td>
                                        <button
                                            data-action="{{ route('admin.setting.remove', $holiday->id) }}"
                                            class="btn btn-sm btn-outline--danger removeBtn"><i
                                                class="las la-trash"></i> @lang('Delete')</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">@lang('Data not found')</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>
            @if($holidays->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($holidays) }}
                </div>
            @endif
        </div><!-- card end -->
    </div>
</div>

<div class="card mt-5">
    <div class="card-header"><b class="lead">@lang('Weekly Holidays')</b></div>
    <form action="{{ route('admin.setting.offday') }}" method="post">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="form-group col-lg-3 col-sm-6 col-md-4">
                    <label class="form-control-label"> @lang('Sunday')</label>
                    <input type="checkbox" data-height="50" data-width="100%" data-size="large" data-onstyle="-success"
                        data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Holiday')"
                        data-off="@lang('Payday')" name="off_day[sun]" @if(@$general->off_day->sun) checked @endif>
                </div>
                <div class="form-group col-lg-3 col-sm-6 col-md-4">
                    <label class="form-control-label"> @lang('Monday')</label>
                    <input type="checkbox" data-height="50" data-width="100%" data-size="large" data-onstyle="-success"
                        data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Holiday')"
                        data-off="@lang('Payday')" name="off_day[mon]" @if(@$general->off_day->mon) checked @endif>
                </div>
                <div class="form-group col-lg-3 col-sm-6 col-md-4">
                    <label class="form-control-label"> @lang('Tuesday')</label>
                    <input type="checkbox" data-height="50" data-width="100%" data-size="large" data-onstyle="-success"
                        data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Holiday')"
                        data-off="@lang('Payday')" name="off_day[tue]" @if(@$general->off_day->tue) checked @endif>
                </div>
                <div class="form-group col-lg-3 col-sm-6 col-md-4">
                    <label class="form-control-label"> @lang('Wednesday')</label>
                    <input type="checkbox" data-height="50" data-width="100%" data-size="large" data-onstyle="-success"
                        data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Holiday')"
                        data-off="@lang('Payday')" name="off_day[wed]" @if(@$general->off_day->wed) checked @endif>
                </div>
                <div class="form-group col-lg-3 col-sm-6 col-md-4">
                    <label class="form-control-label"> @lang('Thursday')</label>
                    <input type="checkbox" data-height="50" data-width="100%" data-size="large" data-onstyle="-success"
                        data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Holiday')"
                        data-off="@lang('Payday')" name="off_day[thu]" @if(@$general->off_day->thu) checked @endif>
                </div>
                <div class="form-group col-lg-3 col-sm-6 col-md-4">
                    <label class="form-control-label"> @lang('Friday')</label>
                    <input type="checkbox" data-height="50" data-width="100%" data-size="large" data-onstyle="-success"
                        data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Holiday')"
                        data-off="@lang('Payday')" name="off_day[fri]" @if(@$general->off_day->fri) checked @endif>
                </div>
                <div class="form-group col-lg-3 col-sm-6 col-md-4">
                    <label class="form-control-label"> @lang('Saturday')</label>
                    <input type="checkbox" data-height="50" data-width="100%" data-size="large" data-onstyle="-success"
                        data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Holiday')"
                        data-off="@lang('Payday')" name="off_day[sat]" @if(@$general->off_day->sat) checked @endif>
                </div>
                <div class="form-group mb-0">
                    <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                </div>
            </div>
        </div>
    </form>
</div>


<div class="modal fade" id="addHoliday">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Add Holiday')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.setting.holiday.submit') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('Title')</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="form-group">
                        <label>@lang('Enter Date')</label>
                        <input type="date" class="form-control" name="date" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="removeModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeModalLabel">@lang('Remove Holiday')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="las la-times"></i></span>
                </button>
            </div>
            <form action="" method="post">
                @csrf
                <div class="modal-body">
                    <p>@lang('Are you sure to remove this holiday?')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@push('breadcrumb-plugins')
    <button class="btn btn-outline--primary btn-sm" data-bs-toggle="modal" data-bs-target="#addHoliday"><i
            class="las la-plus"></i> @lang('Add New')</button>
@endpush
@push('script')
    <script>
        (function ($) {
            "use strict";
            $('.removeBtn').on('click', function () {
                var modal = $('#removeModal');
                modal.find('form').attr('action', $(this).data('action'));
                modal.modal('show');
            });
        })(jQuery);

    </script>
@endpush
