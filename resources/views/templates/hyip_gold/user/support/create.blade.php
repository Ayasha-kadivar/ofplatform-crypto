@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="text-end">
                <a href="{{ route('ticket.index') }}" class="btn btn-sm btn--outline-base mb-2">@lang('My Support Ticket')</a>
            </div>
            <div class="card custom--card">
                <div class="card-body">
                    <form action="{{ route('ticket.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group mb-3 col-md-6">
                                <label class="form-label">@lang('Name')</label>
                                <input type="text" name="name"
                                    value="{{ @$user->firstname . ' ' . @$user->lastname }}"
                                    class="form-control form--control" required readonly>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label class="form-label">@lang('Email Address')</label>
                                <input type="email" name="email" value="{{ @$user->email }}"
                                    class="form-control form--control" required readonly>
                            </div>

                            <div class="form-group mb-3 col-md-6">
                                <label class="form-label">@lang('Subject')</label>
                                <input type="text" name="subject" value="{{ old('subject') }}"
                                    class="form-control form--control" required>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label class="form-label">@lang('Priority')</label>
                                <select name="priority" class="form-control form--control" required>
                                    <option value="3">@lang('High')</option>
                                    <option value="2">@lang('Medium')</option>
                                    <option value="1">@lang('Low')</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">@lang('Message')</label>
                                <textarea name="message" id="inputMessage" rows="6" class="form-control form--control" required>{{ old('message') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="text-end">
                                <button type="button" class="btn btn--outline-base btn-sm addFile">
                                    <i class="fa fa-plus"></i> @lang('Add New')
                                </button>
                            </div>
                            <div class="file-upload">
                                <label class="form-label">@lang('Attachments')</label> <small
                                    class="text-danger">@lang('Max 5 files can be uploaded'). @lang('Maximum upload size is')
                                    {{ ini_get('upload_max_filesize') }}</small>
                                <input type="file" name="attachments[]" id="inputAttachments"
                                    class="form-control form--control mb-2 attach" />
                                <div id="fileUploadsContainer"></div>
                                <p class="ticket-attachments-message text-muted">
                                    @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'),
                                    .@lang('pdf'), .@lang('doc'), .@lang('docx')
                                </p>
                            </div>

                        </div>

                        <div class="form-group">
                            <button class="btn btn--outline-base w-100" type="submit"><i
                                    class="fa fa-paper-plane"></i>&nbsp;@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .input-group-text:focus {
            box-shadow: none !important;
        }

        .attach {
            padding: 19px !important;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            var fileAdded = 0;
            $('.addFile').on('click', function() {
                if (fileAdded >= 4) {
                    notify('error', 'You\'ve added maximum number of file');
                    return false;
                }
                fileAdded++;
                $("#fileUploadsContainer").append(`
                    <div class="input-group my-3">
                        <input type="file" name="attachments[]" class="form-control form--control attach" required />
                        <button type="button" class="input-group-text btn-danger remove-btn"><i class="las la-times"></i></button>
                    </div>
                `)
            });
            $(document).on('click', '.remove-btn', function() {
                fileAdded--;
                $(this).closest('.input-group').remove();
            });
        })(jQuery);
    </script>
@endpush
