@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="pt-120 pb-120">
    <div class="container">
        @php
            echo $cookie->data_values->description
        @endphp
    </div>
</section>
@endsection
