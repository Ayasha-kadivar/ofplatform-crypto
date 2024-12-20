@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-md-12 mb-30">
            <div class="card bl--5-primary">
                <div class="card-body">
                    <p class="text--primary">@lang('If the logo and favicon are not changed after you update from this page, please') <span class="text--danger">@lang('clear the cache')</span> @lang('from your browser. As we keep the filename the same after the update, it may show the old image for the cache. usually, it works after clear the cache but if you still see the old logo or favicon, it may be caused by server level or network level caching. Please clear them too.')</p>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-xl-4">
                                <label class="font-weight-bold">@lang('Logo For White Background')</label>
                                <div class="image-upload">
                                    <div class="thumb">
                                        <div class="avatar-preview">
                                            <div class="profilePicPreview logoPicPrev logoPrev bg--dark" style="background-image: url({{ getImage(getFilePath('logoIcon').'/logo.png', '?'.time()) }})">
                                                <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                        <div class="avatar-edit">
                                            <input type="file" class="profilePicUpload" id="profilePicUpload" accept=".png, .jpg, .jpeg" name="logo">
                                            <label for="profilePicUpload" class="bg--primary">@lang('Select Light Logo') </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-xl-4">
                                <label class="font-weight-bold">@lang('Logo For Dark Background')</label>
                                <div class="image-upload">
                                    <div class="thumb">
                                        <div class="avatar-preview">
                                            <div class="profilePicPreview logoPicPrev logoPrev" style="background-image: url({{ getImage(getFilePath('logoIcon').'/logo_2.png', '?'.time()) }})">
                                                <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                        <div class="avatar-edit">
                                            <input type="file" class="profilePicUpload" id="profilePicUpload1" accept=".png, .jpg, .jpeg" name="logo_2">
                                            <label for="profilePicUpload1" class="bg--primary">@lang('Select Dark Logo') </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-xl-4">
                                <label class="font-weight-bold">@lang('Favicon Image')</label>
                                <div class="image-upload">
                                    <div class="thumb">
                                        <div class="avatar-preview">
                                            <div class="profilePicPreview logoPicPrev iconPrev" style="background-image: url({{ getImage(getFilePath('logoIcon') .'/favicon.jpeg', '?'.time()) }})">
                                                <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                        <div class="avatar-edit">
                                            <input type="file" class="profilePicUpload" id="profilePicUpload2" accept=".png, .jpg, .jpeg" name="favicon">
                                            <label for="profilePicUpload2" class="bg--primary">@lang('Select Favicon')</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
