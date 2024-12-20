@extends($activeTemplate.'layouts.master')
@section('content')

<section class="cmn-section">
    <div class="container">
        <div class="row justify-content-center gy-4">
            @foreach($banners as $banner)
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="thumb__350px">
                                <img src="{{ getImage(fileManager()->promotions()->path.  '/'. @$banner->banner) }}" class="w-100 h-100">
                            </div>
                        </div>
                        <div class="referral-form card-body">

                            @php
                                $string = '<a href="'.route('home').'?reference='.auth()->user()->username.'" target="_blank"> <img src="'.getImage(fileManager()->promotions()->path.  '/'. @$banner->banner) .'" alt="image"/></a>';
                            @endphp

                            <textarea type="url" id="reflink{{ $banner->id }}" class="form--control form-control from-control-lg" rows="5" readonly>@php echo  $string @endphp</textarea>
                            <button  type="button"  data-copytarget="#reflink{{ $banner->id }}" class=" btn--base justify-content-center w-100 mt-3 copybtn btn-block"><i class="fa fa-copy"></i> &nbsp; @lang('Copy')</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@push('style')
    <style>
        textarea.form--control {
            min-height: auto !important;
        }
    </style>
@endpush


@push('script')
<script>
    document.querySelectorAll('.copybtn').forEach((element)=>{
        element.addEventListener('click', copy, true);
    })

    function copy(e) {
        var
            t = e.target,
            c = t.dataset.copytarget,
            inp = (c ? document.querySelector(c) : null);
        if (inp && inp.select) {
            inp.select();
            try {
                document.execCommand('copy');
                inp.blur();
                t.classList.add('copied');
                setTimeout(function() { t.classList.remove('copied'); }, 1500);
            }catch (err) {
                alert(`@lang('Please press Ctrl/Cmd+C to copy')`);
            }
        }
    }

</script>
@endpush
