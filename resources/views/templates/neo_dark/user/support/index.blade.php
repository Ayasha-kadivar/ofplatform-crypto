@extends($activeTemplate.'layouts.master')
@section('content')
<section class="pt-150 pb-150">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-end mb-3">
                    <a href="{{ route('ticket.open') }}" class="btn btn-primary btn-sm">@lang('Open Support Ticket')</a>
                </div>
                <div class="table-responsive--sm neu--table">
                    <table class="table text-white">
                        <thead>
                            <tr>
                                <th>@lang('Subject')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Priority')</th>
                                <th>@lang('Last Reply')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($supports as $support)
                                <tr>
                                    <td> <a href="{{ route('ticket.view', $support->ticket) }}" class="fw-bold"> [@lang('Ticket')#{{ $support->ticket }}] {{ __($support->subject) }} </a></td>
                                    <td>
                                        @php echo $support->statusBadge; @endphp
                                    </td>
                                    <td>
                                        @if($support->priority == 1)
                                            <span class="badge badge--dark">@lang('Low')</span>
                                        @elseif($support->priority == 2)
                                            <span class="badge badge--success">@lang('Medium')</span>
                                        @elseif($support->priority == 3)
                                            <span class="badge badge--primary">@lang('High')</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($support->last_reply)->diffForHumans() }} </td>

                                    <td>
                                        <a href="{{ route('ticket.view', $support->ticket) }}" class="btn btn-primary w-auto btn-sm">
                                            <i class="fa fa-desktop"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{$supports->links()}}
            </div>
        </div>
    </div>
</section>
@endsection


@push('style')
    <style>
        .badge--dark {
            color: #999;
            border-color: #999;
            background-color: rgba(153, 153, 153, 0.15);
        }
    </style>
@endpush