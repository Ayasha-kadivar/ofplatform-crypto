@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="text-end mb-3 d-flex flex-wrap justify-content-between gap-1">
                    <a href="mailto:issues@ourfamily.support" class="btn--base btn-sm">@lang('Open Support Ticket')</a>
                </div>
                <div class="card">

                    <div class="card-body text-center">
                        <h5 class="text--muted">@lang('“We are developing new support ticketing system, please write an email to <a href="mailto:issues@ourfamily.support"><b>issues@ourfamily.support</b></a> </br> for english, german and balkan support. </br>
                        Estamos desarrollando un nuevo sistema de tickets de soporte, por favor escribe un email a <a href="mailto:issues@ourfamily.support"><b>issues@ourfamily.support</b></a> para soporte en español”')</h5>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
