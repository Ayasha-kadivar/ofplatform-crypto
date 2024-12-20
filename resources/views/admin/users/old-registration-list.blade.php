@extends('admin.layouts.app')
@section('panel')
<style>
    .menu-badge {
        padding: 1px 6px;
        font-size: 0.625rem;
        font-weight: 500;
        border-radius: 3px;
        box-shadow: 0 4px 5px 0 rgb(0 0 0 / 20%);
    }
</style>
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('User')</th>
                                <th>@lang('Email-Phone')</th>
                                <th>@lang('Country')</th>
                                <th>@lang('Joined At')</th>
                                <th>@lang('Balance')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{$user->firstname}} {{$user->lastname}}</span>
                                    <br>
                                    <span class="small">
                                    <a href="{{ route('admin.users.old.registration.detail', $user->id) }}"><span>@</span>{{ $user->username }}</a>
                                    </span>
                                    <div style="width: 70px;" class="menu-badge pill {{$user->status == 'Approved' ? 'bg--success' : 'bg--danger'}}">{{$user->status}}</div>
                                </td>


                                <td>
                                    {{ $user->email }}<br>{{ $user->mobile }}
                                </td>
                                <td>
                                    <span class="fw-bold" title="{{ @$user->address->country }}">{{ $user->country_code }}</span>
                                </td>



                                <td>
                                    {{ showDateTime($user->created_at) }} <br> {{ diffForHumans($user->created_at) }}
                                </td>


                                <td>
                                    <span class="fw-bold">
                                    @lang('Old Balance') {{ $general->cur_sym }}{{ showAmount($user->balance) }}<br>
                                    @lang('Purchased Packages') {{ $user->number_of_packages }}
                                    </span>
                                </td>

                                <td>
                                    <a href="{{ route('admin.users.old.registration.detail', $user->id) }}" class="btn btn-sm btn-outline--primary">
                                        <i class="las la-desktop"></i> @lang('Details')
                                    </a>
                                    @if (request()->routeIs('admin.users.kyc.pending'))
                                    <a href="{{ route('admin.users.kyc.details', $user->id) }}" target="_blank" class="btn btn-sm btn-outline--dark">
                                        <i class="las la-user-check"></i>@lang('KYC Data')
                                    </a>
                                    @endif
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
                @if ($users->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($users) }}
                </div>
                @endif
            </div>
        </div>


    </div>
@endsection



@push('breadcrumb-plugins')
    <x-search-form placeholder="Username / Email" />
@endpush
