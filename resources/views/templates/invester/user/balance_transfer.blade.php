@extends($activeTemplate.'layouts.master')
@section('content')

    <div class="dashboard-inner">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="mb-4">
                    <h3 class="mb-2">@lang('Transfer Balance')</h3>
                    <p>@lang('You can transfer the balance to another user from both of your wallets. The transferred amount will be added to the deposit wallet of the targeted user.')</p>
                </div>
                <div class="card custom--card">
                    <form action="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label>@lang('Wallet')</label>
                                <select class="form-control form--control form-select" name="wallet">
                                    <option value="">@lang('Select a wallet')</option>
                                    <option value="deposit_wallet">@lang('Deposit Wallet') - {{ showAmount($user->deposit_wallet) }} {{ $general->cur_text }}</option>
                                    <option value="interest_wallet">@lang('Interest Wallet') - {{ showAmount($user->interest_wallet) }} {{ $general->cur_text }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>@lang('Username')</label>
                                <input type="text" name="username" class="form-control form--control findUser" required>
                                <code class="error-message"></code>
                            </div>
                            <div class="form-group">
                                <label>@lang('Amount') <small class="text--success">(@lang('Charge'): {{ getAmount($general->f_charge) }} {{ $general->cur_text }} + {{ getAmount($general->p_charge) }}%)</small></label>
                                <div class="input-group">
                                    <input type="number" step="any" autocomplete="off" name="amount" class="form-control form--control" required>
                                    <span class="input-group-text">{{ $general->cur_text }}</span>
                                </div>
                                <small><code class="calculation"></code></small>
                            </div>

                            @if(auth()->user()->ts)
                            <div class="form-group">
                                <label>@lang('Google Authenticator Code')</label>
                                <input type="text" name="authenticator_code" class="form-control form--control" required>
                            </div>
                            @endif


                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
<script>
    $('input[name=amount]').on('input',function(){
        var amo = parseFloat($(this).val());
        var calculation = amo + ( parseFloat({{ $general->f_charge }}) + ( amo * parseFloat({{ $general->p_charge }}) ) / 100 );
        if (calculation) {
            $('.calculation').text(calculation+' {{ $general->cur_text }} will cut from your selected wallet');
        }else{
            $('.calculation').text('');
        }
    });

    $('.findUser').on('focusout',function(e){
        var url = '{{ route('user.findUser') }}';
        var value = $(this).val();
        var token = '{{ csrf_token() }}';

        var data = {username:value,_token:token}
        $.post(url,data,function(response) {
            if (response.message) {
                $('.error-message').text(response.message);
            }else{
                $('.error-message').text('');
            }
        });
    });
</script>
@endpush
