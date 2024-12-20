@extends($activeTemplate.'layouts.master')
@section('content')

    <div class="dashboard-inner">
        <div class="mb-4">
            <div class="row">
                <div class="col-md-8">
                    <h3>@lang('KYC Data')</h3>
                    <p>@lang('Your submitted KYC information is shown below. You couldn\'t change the data that you\'ve submitted. If the admin rejects your information, you\'ll be able to re-submit.')</p>
                </div>
            </div>
        </div>
        <div class="card custom--card">
            <div class="card-body">
                @if($user->kyc_data)
                <ul class="list-group">
                  @foreach($user->kyc_data as $val)
                  @continue(!$val->value)
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{__($val->name)}}
                    <span>
                        @if($val->type == 'checkbox')
                            {{ implode(',',$val->value) }}
                        @elseif($val->type == 'file')
                            <a target="_blank" href="{{ route('user.attachment.download',encrypt(getFilePath('verify').'/'.$val->value)) }}" class="me-3"><i class="fa fa-file"></i>  @lang('Attachment') </a>
                        @else
                        <p>{{__($val->value)}}</p>
                        @endif
                    </span>
                  </li>
                  @endforeach
                </ul>
                @else
                <h5 class="text-center">@lang('KYC data not found')</h5>
                @endif
            </div>
        </div>
    </div>

@endsection
