@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="row">
            <form action="" method="GET" class="d-flex flex-wrap gap-2">
                <div class="row">
                <div class="col-lg-2 input-group w-auto flex-fill">
                    <input type="search" name="name" class="form-control bg--white" placeholder="Name" value="{{ app('request')->input('name') }}
">
                </div>
                <div class="col-lg-2 input-group w-auto flex-fill">
                    <input type="search" name="surname" class="form-control bg--white" placeholder="Surname" value="{{ app('request')->input('surname') }}">
                </div>
                <div class="col-lg-2 input-group w-auto flex-fill">
                    <input type="search" name="username" class="form-control bg--white" placeholder="Username" value="{{ app('request')->input('username') }}">
                </div>
                <div class="col-lg-2 input-group w-auto flex-fill">
                    <input type="search" name="email" class="form-control bg--white" placeholder="Email" value="{{ app('request')->input('email') }}">
                </div>
                <div class="col-lg-2 input-group w-auto flex-fill">
                    <input type="search" name="phone" class="form-control bg--white" placeholder="Phone" value="{{ app('request')->input('phone') }}">
                </div>
                <div class="col-lg-2 input-group w-auto flex-fill">
                    <input type="search" name="country" class="form-control bg--white" placeholder="Country" value="{{ app('request')->input('country') }}">
                </div>
                
                </div>
                <div class="row">
                <div class="col-lg-2 input-group w-auto flex-fill">
                    <input type="search" name="city" class="form-control bg--white" placeholder="City" value="{{ app('request')->input('city') }}">
                </div>
                <div class="col-lg-2 input-group w-auto flex-fill">
                    <input type="search" name="zip" class="form-control bg--white" placeholder="ZIP" value="{{ app('request')->input('zip') }}">
                </div>
                <div class="col-lg-2 input-group w-auto flex-fill">
                    <input type="search" name="sponsor" class="form-control bg--white" placeholder="Sponsor" value="{{ app('request')->input('sponsor') }}">
                </div>
                <div class="col-lg-2 input-group w-auto flex-fill">
                    <input type="search" name="wallet_address" class="form-control bg--white" placeholder="Wallet Address" value="{{ app('request')->input('wallet_address') }}">
                </div>
                <div class="col-lg-2 input-group w-auto flex-fill">
                    <input type="search" name="hash_id" class="form-control bg--white" placeholder="VIP Member Hash ID" value="{{ app('request')->input('hash_id') }}">
                </div>
                <div class="col-lg-4 input-group w-auto flex-fill">
                    <input type="number" name="no_of_rented_nft" class="form-control bg--white" placeholder="No. Of Family NFTs" value="{{ app('request')->input('no_of_rented_nft') }}">
                </div>
                </div>
                <div class="row">
                <div class="col-lg-2 input-group w-auto flex-fill">
                        <select name="amount_type" class="form-control" id="amount_type">
                        <option value="">Select Type</option>
                        <option value="total"> Search by Total Balance</option>
                        <option value="deposit" >Search by Deposit Wallet</option>
                        <option value="cubeone" >Search by Cube One</option>
                        <option value="cubetwo" >Search by Cube Two</option>
                        <option value="cubethree" >Search by Cube Three</option>
                        <option value="cubefour" >Search by Cube Four</option>
                    </select>
                    <input type="search" name="balance" class="form-control bg--white" placeholder="More than Balances" value="{{ app('request')->input('balance') }}">
                </div>
                <div class="col-lg-2 input-group w-auto flex-fill">
                    <button class="btn btn--primary" type="submit"><i class="la la-search"></i></button>
                </div>
                
                <br>
                </div>
            </form>
        </div>
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div style="margin:10px;">
                        <button class="export-csv btn btn--primary input-group-text">Export CSV</button>

                        <button class="export-excel btn btn--primary input-group-text">Export Excel</button>
                    </div>
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
                                    <span class="fw-bold">{{$user->fullname}}</span>
                                    @if($user->launch_nft_owner == 1)
                                    <span>
                                        <span style="font-size: medium;" class="badge badge--primary">Launch NFT Owner</span>
                                    </span>
                                    @endif
                                    <br>
                                    @if($user->vip_user == 1)
                                    <span>
                                        <span style="font-size: medium;" class="badge badge--primary">VIP User</span>
                                    </span>
                                    <br>
                                    @endif
                                    <span class="small">
                                    <a href="{{ route('admin.users.detail', $user->id) }}"><span>@</span>{{ $user->username }}</a>
                                    </span>
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
                                    {{-- @lang('Deposit Wallet') {{ $general->cur_sym }}{{ showAmount($user->deposit_ft) }}<br> --}}
                                    @lang('Total in Pools') {{ $general->cur_sym }}{{ showAmount($user->pool_2+$user->pool_3+$user->pool_4+$user->interest_wallet) }}<br>
                                    @lang('Interest Wallet') {{ $general->cur_sym }}{{ showAmount($user->interest_wallet) }}
                                    </span>
                                </td>

                                <td>
                                    @if (auth('admin')->check() && auth('admin')->user()->role_status == 0 || auth('admin')->check() && auth('admin')->user()->role_status == 1)
                                    <a href="{{ route('admin.users.detail', $user->id) }}" class="btn btn-sm btn-outline--primary">
                                        <i class="las la-desktop"></i> @lang('Details')
                                    </a>
                                    @else
                                    @endif
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
    <!-- <x-search-form amountSearch="yes" placeholder="Username / Email" /> -->
@endpush

@push('script')
<script>

var am_type = "{{ (app('request')->input('amount_type'))}}";
$("#amount_type").val(am_type);
function download_csv(data, sensor) {
    let csvHeader = Object.keys(data[0]).join(',') + '\n'; // header row
    let csvBody = data.map(row => Object.values(row).join(',')).join('\n');

    var hiddenElement = document.createElement('a');
    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csvHeader + csvBody);
    hiddenElement.target = '_blank';
    hiddenElement.download = sensor + '.csv';
    hiddenElement.click();
}

function checkurl(){
    var url = window.location.href;
    if(url.indexOf('?') != -1)
        return true;
    else if(url.indexOf('&') != -1)
        return true;
    return false
}
jQuery('body').on('click', '.export-csv', function() {
    $(this).prop('disabled', true);
    var appendkey = '?is_csv=1';
    if(checkurl()==true){
        appendkey = '&is_csv=1';
    }
    $.ajax({
      url: $(location).attr('href')+appendkey,
      type: "GET",
      success:function(data) {
        $('.export-csv').prop('disabled', false);
        if(typeof data.data!='undefined' && data.data!='' && data.data!=null){
            download_csv(data.data, 'users');
        }
      },error: function (jqXHR, textStatus, errorThrown) { $('.export-csv').prop('disabled', false); }
    });
});

jQuery('body').on('click', '.export-excel', function() {
    $('.export-excel').prop('disabled', true);
    var appendkey = '?is_csv=0';
    if(checkurl()==true){
        appendkey = '&is_csv=0';
    }
    $.ajax({
      url: $(location).attr('href')+appendkey,
      type: "GET",
      success:function(data) {
        $('.export-excel').prop('disabled', false);
        if(typeof data.data!='undefined' && data.data!='' && data.data!=null){
            var $a = $("<a>");
                $a.attr("href",data.data);
                $("body").append($a);
                $a.attr("download","users.xls");
                $a[0].click();
                $a.remove();
        }
        //console.log(data);
      },error: function (jqXHR, textStatus, errorThrown) { $('.export-excel').prop('disabled', false);console.log(errorThrown); }
    });
});


</script>
@endpush