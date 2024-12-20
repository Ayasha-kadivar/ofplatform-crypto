 @php
     $latestDeposit = \App\Models\Deposit::with('user', 'gateway')
         ->where('status', 1)
         ->latest()
         ->limit(10)
         ->get();
     $fakeDeposit = \App\Models\Frontend::where('data_keys', 'transaction.element')
         ->whereJsonContains('data_values->trx_type', 'deposit')
         ->limit(10)
         ->get();
     $deposits = $latestDeposit->merge($fakeDeposit);
     $deposits = $deposits->sortByDesc('created_at')->take(10);
     
     $latestWithdraw = \App\Models\Withdrawal::with('user', 'method')
         ->where('status', 1)
         ->latest()
         ->limit(10)
         ->get();
     $fakeWithdraw = \App\Models\Frontend::where('data_keys', 'transaction.element')
         ->whereJsonContains('data_values->trx_type', 'withdraw')
         ->limit(10)
         ->get();
     
     $withdrawals = $latestWithdraw->merge($fakeWithdraw);
     $withdrawals = $withdrawals->sortByDesc('created_at')->take(10);
     $transactionContent = getContent('transaction.content', true);
 @endphp

 <section class="transaction-area-wrapper ">
     <div class="transaction-area-inner">
         <div class="transaction-area primary-bg pt-100 pb-90">
             <div class="container">
                 <div class="row justify-content-center">
                     <div class="col-xxl-10">
                         <div class="section_title_wrapper text-center mb-50">
                             <h2 class="section-title">
                                 {{ __($transactionContent->data_values->heading) }}
                             </h2>
                             <p class="section-subtitle margin-0 ">
                                 {{ __($transactionContent->data_values->sub_heading) }}
                             </p>
                         </div>
                     </div>
                 </div>
                 <div class="row justify-content-center">
                     <div class="col-xl-6">
                         <div class="table-content mb-30">
                             <h4 class="mb-20">@lang('Latest Deposit')</h4>
                             <table class="table transection__table table--responsive--md"
                                 style="background-image: url({{ asset($activeTemplateTrue . 'images/price/price-bg.png') }});">
                                 <thead>
                                     <tr>
                                         <th>@lang('Gatway')</th>
                                         <th>@lang('Date')</th>
                                         <th>@lang('Amount')</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     @foreach ($deposits as $deposit)
                                         <tr>
                                             @if (@$deposit->data_values)
                                                 <td data-label="@lang('Gatway')">
                                                     {{ __(@$deposit->data_values->gateway) }}
                                                 </td>
                                                 <td data-label="@lang('date')">
                                                     {{ showDateTime(@$deposit->data_values->created_at) }}
                                                 </td>
                                                 <td data-label="@lang('Amount')">
                                                     {{ showAmount(@$deposit->data_values->amount) }}
                                                     {{ $general->cur_text }}
                                                 </td>
                                             @else
                                                 <td data-label="@lang('Gatway')">
                                                     {{ __(@$deposit->gateway->name) }}
                                                 </td>
                                                 <td data-label="@lang('date')">
                                                     {{ showDateTime(@$deposit->created_at) }}
                                                 </td>
                                                 <td data-label="@lang('Amount')">
                                                     {{ showAmount(@$deposit->amount) }}
                                                     {{ $general->cur_text }}
                                                 </td>
                                             @endif
                                         </tr>
                                     @endforeach

                                 </tbody>
                             </table>
                         </div>
                     </div>
                     <div class="col-xl-6">
                         <div class="table-content mb-30">
                             <h4 class="mb-20">@lang('Latest Withdrawals')</h4>
                             <table class="table transection__table table--responsive--md"
                                 style="background-image: url({{ asset($activeTemplateTrue . 'images/price/price-bg.png') }});">
                                 <thead>
                                     <tr>
                                         <th>@lang('Gatway')</th>
                                         <th>@lang('Date')</th>
                                         <th>@lang('Amount')</th>
                                     </tr>
                                 </thead>
                                 <tbody>

                                     @foreach ($withdrawals as $withdrawal)
                                         <tr>

                                             @if ($withdrawal->data_values)
                                                 <td data-label="@lang('Gatway')">
                                                     {{ __(@$withdrawal->data_values->gateway) }}
                                                 </td>
                                                 <td data-label="@lang('date')">
                                                     {{ showDateTime($withdrawal->data_values->date) }}
                                                 </td>
                                                 <td data-label="@lang('Amount')">
                                                     {{ showAmount($withdrawal->data_values->amount) }}
                                                     {{ $general->cur_text }}
                                                 </td>
                                             @else
                                                 <td data-label="@lang('Gatway')">
                                                     {{ __(@$withdrawal->gateway->name) }}
                                                 </td>
                                                 <td data-label="@lang('date')">
                                                     {{ showDateTime($withdrawal->created_at) }}
                                                 </td>
                                                 <td data-label="@lang('Amount')">
                                                     {{ showAmount($withdrawal->amount) }}
                                                     {{ $general->cur_text }}
                                                 </td>
                                             @endif
                                         </tr>
                                     @endforeach
                                 </tbody>
                             </table>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </section>
