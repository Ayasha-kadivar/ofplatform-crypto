@extends('admin.layouts.app')
@push('style')
<style>
    input.picker[type="date"] {
    position: relative;
    }

    input.picker[type="date"]::-webkit-calendar-picker-indicator {
    position: absolute;
    top: 0;
    right: 0;
    width: 100%;
    height: 100%;
    padding: 0;
    color: transparent;
    background: transparent;
    }
</style>
@endpush

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.hashid.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="payment-method-item">
                            <div class="payment-method-body">

                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('HASH ID')</label>
                                            <div class="input-group">
                                                <input type="text" name="hash_id" class="form-control border-radius-5" value="{{ old('hash_id') }}" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Transaction Type')</label>
                                            <div class="input-group">
                                                <select class="form-control"required name="trx_type" id="trx_type">
                                                    <option value="vip_membership">vip_membership</option>
                                                    <option value="maintenance_fees">maintenance_fees</option>
                                                </select>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label>@lang('User')</label>
                                            <div class="input-group">
                                                <select id="user_id" name="user_id" class="user_id" style="width: 100%;" required></select>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label>@lang('Amount')</label>
                                            <div class="input-group">
                                                <input type="number" step="any" min="20" max="200" class="form-control" name="amount" value="{{ old('amount') }}" required/>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label>@lang('Current Expiry Date')</label>
                                            <div class="input-group">
                                                <span id="current_expiry"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label>@lang('Next Expiry Date')</label>
                                            <div class="input-group" id="next_expiry">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row mt-12">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                        <label>@lang('Remark')</label>
                                            <div class="input-group">
                                                <textarea name="remark" required class="form-control" id="remark" cols="30" rows="5">{{ old('remark') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row mt-12">
                                    <div class="form-group col-xl-3 col-md- col-12">
                                        <label>@lang('Do you want to override the HASH ID details?') </label>
                                        <input type="checkbox" data-width="100%" data-height="50" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')" name="is_duplicate" checked>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div><!-- card end -->
        </div>
    </div>

@endsection


@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.hashid.index') }}" />
@endpush

@push('script')
<script>
    (function($){
    "use strict"

        $("#user_id").select2({
            minimumInputLength: 3,
            ajax: {
            url: "{{route('admin.users.allUserSearch')}}",
            dataType: 'json',
            data: (params) => {
                return {
                q: params.term,
                }
            },
            processResults: (data, params) => {
                const results = data.map(item => {
                return {
                    id: item.id,
                    text: item.email,
                };
                });
                return {
                results: results,
                }
            },
            },
        });


        $(document).on('change','body #user_id',function(){
            userdetail($(this).val(),$('#trx_type').val());
           // var selectedSelect2OptionSource = $("#user_id :selected").data().data.source;
            //console.log(selectedSelect2OptionSource);
        });

        $(document).on('keyup','body #amount',function(){
            userdetail($('#user_id').val(),$('#trx_type').val());
        });

        $(document).on('change','body #trx_type',function(){
            if($(this).val() == 'vip_membership'){
                $('#amount').attr('min',20);
                $('#amount').attr('max',200);
            }else{
                $('#amount').attr('min',10);
                $('#amount').attr('max',10);
            }
            userdetail($('#user_id').val(),$(this).val());
        });

        function userdetail(id,t_type) {
            if(id && t_type){
                $.ajax({
                    type: "get",
                    url: "{{ route('admin.users.searchByDetails') }}",
                    data: {
                        id: id,
                        t_type: t_type,
                        amount: $('#amount').val(),
                    },
                    success: function(data) {
                        var data = $.parseJSON(data);
                        if(data != null || typeof data!='undefined'){
                            var current_e , next_e = '';
                            if(t_type == 'vip_membership'){
                                current_e=data.vip_user_date;
                            }else{
                                current_e=data.maintenance_expiration_date;
                            }
                            next_e=(data.next_expiration_date)?data.next_expiration_date:'';
                            console.log(current_e);
                            console.log(next_e);
                            $('#current_expiry').html(current_e);
                            $('#next_expiry').html('<input type="date" name="next_e" class="form-control picker" placeholder="YYYY-MM-DD" value="'+next_e+'"/>');                            
                        }
                    }
                });
            }else{
                $('#current_expiry').html('');
                $('#next_expiry').html('');
            }
        }
        
    })(jQuery);
</script>
@endpush