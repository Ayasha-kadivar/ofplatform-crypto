<style>
    .image-upload>input {
       /* display: none; */
    }
    .selfie_img{
        height:220px;
        width:250px;
        cursor:pointer;
    }
    label.required:after {
        content: '';
    }
</style>
@foreach($formData as $data)
    <div class="form-group">
        <label class="form-label">{{ __($data->name) }}
        </label>

        @if($data->type == 'text')
            <input type="text"
            class="form-control form--control"
            name="{{ $data->label }}"
            value="{{ old($data->label) }}"
            @if($data->is_required == 'required') required @endif
            >
        @elseif($data->type == 'textarea')
            <textarea
                class="form-control form--control"
                name="{{ $data->label }}"
                @if($data->is_required == 'required') required @endif
            >{{ old($data->label) }}</textarea>
        @elseif($data->type == 'select')
            <select
                class="form-control form--control form-select"
                name="{{ $data->label }}"
                @if($data->is_required == 'required') required @endif
            >
                <option value="">@lang('Select One')</option>
                @foreach ($data->options as $item)
                    <option value="{{ $item }}" @selected($item == old($data->label))>{{ __($item) }}</option>
                @endforeach
            </select>
        @elseif($data->type == 'checkbox')
            @foreach($data->options as $option)
                <div class="form-check">
                    <input
                        class="form-check-input"
                        name="{{ $data->label }}[]"
                        type="checkbox"
                        value="{{ $option }}"
                        id="{{ $data->label }}_{{ titleToKey($option) }}"
                    >
                    <label class="form-check-label" for="{{ $data->label }}_{{ titleToKey($option) }}">{{ $option }}</label>
                </div>
            @endforeach
        @elseif($data->type == 'radio')
            @foreach($data->options as $option)
                <div class="form-check">
                    <input
                    class="form-check-input"
                    name="{{ $data->label }}"
                    type="radio"
                    value="{{ $option }}"
                    id="{{ $data->label }}_{{ titleToKey($option) }}"
                    @checked($option == old($data->label))
                    >
                    <label class="form-check-label" for="{{ $data->label }}_{{ titleToKey($option) }}">{{ $option }}</label>
                </div>
            @endforeach
        @elseif($data->type == 'file')
            @if($data->label == 'selfie')
            <div class="image-upload">
                
                <input
                type="file" id="file-input" 
                class="form-control form--control"
                name="{{ $data->label }}"
                @if($data->is_required == 'required') required @endif
                accept="@foreach(explode(',',$data->extensions) as $ext) .{{ $ext }}, @endforeach"
                >
                <pre class="text--base mt-1">@lang('Supported mimes'): {{ $data->extensions }}</pre>
                <label for="file-input" style="content: '';">
                    <img src="{{ asset('assets/admin/images/selfie.jpg') }}" class="selfie_img"/>
                </label>
                
                <span style="color:red;font-weight:700;font-size: 16px;">Note: Kindly make selfie with ID in your hand. Selfie without ID will not be accepted and KYC request will be rejected!</span>
            </div>
	    
            @else
                <input
                type="file"
                class="form-control form--control"
                name="{{ $data->label }}"
                @if($data->is_required == 'required') required @endif
                accept="@foreach(explode(',',$data->extensions) as $ext) .{{ $ext }}, @endforeach"
                >
                <pre class="text--base mt-1">@lang('Supported mimes'): {{ $data->extensions }}</pre>
            @endif
        @endif
    </div>
@endforeach
