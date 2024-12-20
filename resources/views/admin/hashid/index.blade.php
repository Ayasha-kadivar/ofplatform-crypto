@extends('admin.layouts.app')

@section('panel')
    <div class="row">

        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Added By')</th>
                                    <th>@lang('Added By Type')</th>
                                    <th>@lang('User')</th>
                                    <th>@lang('HASH ID')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Transaction Type')</th>
                                    <th>@lang('Remark')</th>
                                    <th>@lang('Created At')</th>
                                    <th>@lang('Updated At')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hashids as $hashid)
                                    <tr>
                                        @if($hashid->added_by == 'user')
                                        <td>{{ $hashid->username }}<br> ({{$hashid->email}})</td>
                                        @else
                                        <td>{{ $hashid->a_username }}<br> ({{$hashid->a_email}})</td>
                                        @endif
                                        <td>{{ $hashid->added_by }}</td>
                                        <td>{{ $hashid->r_username }}<br> ({{$hashid->r_email}})</td>
                                        <td>{{ $hashid->hash_id }}</td>
                                        <td>{{ getAmount($hashid->amount) }}</td>
                                        <td>{{ $hashid->trx_type }}</td>
                                        <td>{{ $hashid->remark }}</td>
                                        <td>{{ showDateTime($hashid->created_at) }}</td>
                                        <td>{{ showDateTime($hashid->updated_at) }}</td>

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
                @if ($hashids->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($hashids) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>


    </div>

@endsection

@push('breadcrumb-plugins')
    <a class="btn btn-outline--primary" href="{{ route('admin.hashid.create') }}"><i class="las la-plus"></i>@lang('Add New')</a>
    <div class="input-group w-auto">
    <x-search-form dateSearch='yes' />
    </div>
@endpush