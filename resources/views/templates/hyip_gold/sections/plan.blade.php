  @php
      $plans = App\Models\Plan::where('status', 1)
          ->where('featured',1)
          ->get();
      $gatewayCurrency = null;
      if (auth()->check()) {
          $gatewayCurrency = App\Models\GatewayCurrency::whereHas('method', function ($gate) {
              $gate->where('status', 1);
          })
              ->with('method')
              ->orderby('method_code')
              ->get();
      }
  @endphp

  <section class="price-table section-common-bg pt-120 ">
      <div class="container">
          <div class="row">
              @include($activeTemplate . 'partials.plan', ['plans' => $plans])
          </div>
      </div>
  </section>
