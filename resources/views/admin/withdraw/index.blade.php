@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two custom-data-table">
                            <thead>
                                <tr>
                                    <th>@lang('Method')</th>
                                    <th>@lang('Currency')</th>
                                    <th>@lang('Charge')</th>
                                    <th>@lang('Withdraw Limit')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($methods as $method)
                                    <tr>
                                        <td>{{ __($method->name) }}</td>

                                        <td class="fw-bold">{{ __($method->currency) }}</td>
                                        <td class="fw-bold">{{ showAmount($method->fixed_charge) }} {{ __($general->cur_text) }} {{ 0 < $method->percent_charge ? ' + ' . showAmount($method->percent_charge) . ' %' : '' }} </td>
                                        <td class="fw-bold">{{ $method->min_limit + 0 }}
                                            - {{ $method->max_limit + 0 }} {{ __($general->cur_text) }}</td>
                                        <td>
                                            @php
                                                echo $method->statusBadge;
                                            @endphp
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.withdraw.method.edit', $method->id) }}" class="btn btn-sm btn-outline--primary ms-1"><i class="las la-pen"></i> @lang('Edit')</a>
                                                @if ($method->status == 1)
                                                    <button class="btn btn-sm btn-outline--danger ms-1 confirmationBtn" data-question="@lang('Are you sure to disable this method?')" data-action="{{ route('admin.withdraw.method.deactivate', $method->id) }}">
                                                        <i class="la la-eye-slash"></i> @lang('Disable')
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--success ms-1 confirmationBtn" data-question="@lang('Are you sure to enable this method?')" data-action="{{ route('admin.withdraw.method.activate', $method->id) }}">
                                                        <i class="la la-eye"></i> @lang('Enable')
                                                    </button>
                                                @endif
                                            </div>
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
            </div><!-- card end -->
        </div>
    </div>
    <x-confirmation-modal />
@endsection



@push('breadcrumb-plugins')
    <a class="btn btn-outline--primary" href="{{ route('admin.withdraw.method.create') }}"><i class="las la-plus"></i>@lang('Add New')</a>
    <div class="input-group w-auto">
        <input type="text" name="search_table" class="form-control bg--white" placeholder="@lang('Search')...">
        <button class="btn btn--primary input-group-text"><i class="fa fa-search"></i></button>
    </div>
@endpush
