@extends($activeTemplate.'layouts.master')
@section('content')

<div class="dashboard-inner">
    <div class="mb-4">
        <h3 class="mb-2 new--color--theme">@lang('My Referrals')</h3>
    </div>
    <div class="row gy-4">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="mb-1">@lang('Refer & Enjoy the Bonus')</h4>
                    <p class="mb-3">@lang('You will get 1$ commission every time your direct referrals rent or re-activate a FamilyNFT.') </p>
                    <div class="copy-link">
                        <input type="text" class="copyURL" value="{{ route('home') }}/user/register?reference={{ auth()->user()->username }}" readonly>
                        <span class="copyBoard" id="copyBoard"><i class="las la-copy"></i> <strong class="copyText">@lang('Copy')</strong></span>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('exportReferralTreeToCSV') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">@lang('Export to CSV')</button>
                    </form><br>
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-all-tab" data-bs-toggle="pill" data-bs-target="#pills-all" type="button" role="tab" aria-controls="pills-all" aria-selected="true">All Users   <span class="count text-base menu-badge pill orange--bg--show">{{$all}}</span></button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Active Users   <span class="count text-base menu-badge pill green--bg--show">{{$active}}</span></button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Deactivated Users  <span class="menu-badge pill red--bg--show count text-base">{{$deactivated}}</span></button>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-all" role="tabpanel" aria-labelledby="pills-all-tab">
                            
                            <div class="treeview-container">
                                <ul class="treeview">
                                
                                {{-- {{ $user->fullname }} ( {{ $user->email }} / {{ $user->mobile }} ) --}}
                                        @include($activeTemplate.'partials.under_tree',['user'=>$user,'layer'=>0,'isFirst'=>true,'isBlock'=>'all'])
                                
                                </ul>
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                            
                            <div class="treeview-container">
                                <ul class="treeview">
                                
                                {{-- {{ $user->fullname }} ( {{ $user->email }} / {{ $user->mobile }} ) --}}
                                        @include($activeTemplate.'partials.under_tree',['user'=>$user,'layer'=>0,'isFirst'=>true,'isBlock'=>0])
                                
                                </ul>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                            
                            <div class="treeview-container">
                                <ul class="treeview">
                                
                                    {{-- {{ $user->fullname }} ( {{ $user->email }} / {{ $user->mobile }} ) --}}
                                        @include($activeTemplate.'partials.under_tree',['user'=>$user,'layer'=>0,'isFirst'=>true,'isBlock'=>1])
                                
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @if($user->allReferrals->count())
            {{-- <div class="card">
                <div class="card-body">
                    <form action="{{ route('exportReferralTreeToCSV') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">@lang('Export to CSV')</button>
                    </form>
                    <div class="treeview-container">
                        <ul class="treeview">
                        <li class="items-expanded"> {{ $user->fullname }} ( {{ $user->email }} / {{ $user->mobile }} )
                                @include($activeTemplate.'partials.under_tree',['user'=>$user,'layer'=>0,'isFirst'=>true])
                            </li>
                        </ul>
                    </div>
                </div>
            </div> --}}
            @endif
        </div>
    </div>
</div>

@endsection

@push('style')
    <link href="{{ asset('assets/global/css/jquery.treeView.css') }}" rel="stylesheet" type="text/css">
@endpush
@push('script')
<script src="{{ asset('assets/global/js/jquery.treeView.js') }}"></script>
<script>
    (function($){
    "use strict"
        $('.treeview').treeView();
        $('.copyBoard').click(function(){
                var copyText = document.getElementsByClassName("copyURL");
                copyText = copyText[0];
                copyText.select();
                copyText.setSelectionRange(0, 99999);

                /*For mobile devices*/
                document.execCommand("copy");
                $('.copyText').text('Copied');
                setTimeout(() => {
                    $('.copyText').text('Copy');
                }, 2000);
        });
    })(jQuery);
</script>
@endpush
