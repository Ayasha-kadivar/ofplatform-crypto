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
                                <th scope="col">@lang('Name')</th>
                                <th scope="col">@lang('Time')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($times as $time)
                                <tr>
                                    <td>{{ $time->name }}</td>
                                    <td>{{ $time->time }} @lang('Hours')</td>
                                    <td>
                                    <a href="javascript:void(0)"
                                    data-id="{{$time->id}}"
                                    data-name="{{$time->name}}"
                                    data-time="{{$time->time}}"
                                    data-route="{{route('admin.time.update', $time->id)}}"
                                    data-bs-toggle="modal" data-bs-target="#editModal"
                                    class="btn btn-outline--primary editBtn btn-sm"><i class="las la-pen"></i> @lang('Edit')</a>
                                    <button class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to delete this time?')" data-action="{{ route('admin.time.delete', $time->id) }}"><i class="las la-trash"></i> @lang('Delete')</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"> @lang('Edit Time')</h4>
                    <button type="button" class="close" data-bs-dismiss="modal"><span><i class="las la-times"></i></span></button>
                </div>
                <form method="post" action="">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Time Name') </label>
                            <input type="text" class="form-control" placeholder="@lang('e.g. Hour, Day, Week')" name="name" required>
                        </div>

                        <div class="form-group">
                            <label>@lang('Time in Hours')</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="time" required>
                                <div class="input-group-text">@lang('Hours')</div>
                            </div>
                            <p><small class="text-muted text-center"><i class="las la-dot-circle"></i><i>@lang('Interest will be given after this time which you\'ve put above')</i></small></p>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45"><i class="fa fa-send"></i> @lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="timeModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Add New Time')</h4>
                    <button type="button" class="close" data-bs-dismiss="modal"><span><i class="las la-times"></i></span></button>
                </div>
                <form method="post" action="{{route('admin.time.store')}}">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <label>@lang('Time Name') </label>
                            <input type="text" class="form-control" placeholder="@lang('e.g. Hour, Day, Week')" name="name" required>
                        </div>

                        <div class="form-group">
                            <label>@lang('Time in Hours')</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="time" required>
                                <div class="input-group-text">@lang('Hours')</div>
                            </div>
                            <p><small class="text-muted text-center"><i class="las la-dot-circle"></i><i>@lang('Interest will be given after this time which you\'ve put above')</i></small></p>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45"><i class="fa fa-send"></i> @lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <button type="button" data-bs-target="#timeModal" data-bs-toggle="modal" class="btn btn-sm btn-outline--primary"><i class="las la-plus"></i>@lang('Add New')
    </button>
@endpush


@push('script')
    <script>
        $(function ($) {
            "use strict";

            $('.editBtn').on('click', function () {
                var modal = $('#editModal');
                modal.find('form').attr('action', $(this).data('route'));
                modal.find('input[name=name]').val($(this).data('name'));
                modal.find('input[name=time]').val($(this).data('time'));
            });
        });
    </script>
@endpush
