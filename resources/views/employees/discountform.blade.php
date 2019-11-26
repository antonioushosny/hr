@extends('layouts.index')
@section('style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<style>
.select2-selection.select2-selection--multiple {
    display: block;
    width: 100% !important;
    background-color: transparent;
    border: 1px solid #888888;
    border-radius: 30px;
    color: #2c2c2c;
    line-height: normal;
    font-size: 1.1em;
    -webkit-box-shadow: none;
    box-shadow: none;
    
}



</style>
@endsection
 @section('content')
<!-- Main Content -->
<section class="content home">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-12">
                <h2>{{__('admin.dashboard')}}
                <small>{{__('admin.Welcome to hr')}}</small>
                </h2>
            </div>            
                @if($lang =='ar')
                <div class="col-lg-7 col-md-7 col-sm-12 text-left">
                <ul class="breadcrumb float-md-left" style=" padding: 0.6rem; direction: ltr; ">
                @else 
                <div class="col-lg-7 col-md-7 col-sm-12 text-right">
                <ul class="breadcrumb float-md-right">
                @endif
                    <li class="breadcrumb-item active"><a href="{{route('home')}}"><i class="zmdi zmdi-home"></i>{{__('admin.dashboard')}}</a></li>
                    <li class="breadcrumb-item"><a href="{{route('discounts',$id)}}"><i class="zmdi zmdi-face"></i> {{__('admin.discounts')}}</a></li>
                    <li class="breadcrumb-item "><a href="javascript:void(0);">
                            @if(!isset($data->id))                          
                            {{ trans('admin.add_discount') }}
                            @else
                            {{ trans('admin.edit_discount') }}
                            @endif                   
                         </a></li>
                    
                </ul>
            </div>
        </div>
    </div>

     
    <div class="container-fluid">
        
        <!-- Exportable Table -->
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>{{trans('admin.'.$title)}}</strong>
                            @if(!isset($data->id))                          
                            {{ trans('admin.add_discount') }}
                            @else
                            {{ trans('admin.edit_discount') }}
                            @endif 

                            for  {{ $employee->name }}
                        </h2>
                        
                    </div>
                    <div class="body row">
                        <div class="col-lg-10">
                                {!! Form::open(['route'=>['storediscount'],'method'=>'post','autocomplete'=>'off', 'name'=>"myform" ,'id'=>'form_validation', 'enctype'=>'multipart/form-data' ])!!} 
                                <div class=" row">
                                    <input type="hidden" value="{{ !isset($data->id)?null:$data->id }}" name="id" required>
                                    <input type="hidden" value="{{ $id }}" name="employee_id" required>

                                   
                                    <div class="form-group form-float col-lg-12">
                                        <input type="text" value="{{ !isset($data->amount)?null:$data->amount }}" class="form-control" placeholder="{{__('admin.amount')}}" name="amount" maxlength= 14 onkeypress="isNumber(event);" required>
                                        <label id="amount-error" class="error" for="amount" style=""></label>
                                    </div>

                                    <div class="form-group form-float col-lg-12">
                                        <input type="text" value="{{ !isset($data->reason)?null:$data->reason }}"  class="form-control" placeholder="{{__('admin.reason')}}" name="reason" required>
                                        <label id="reason-error" class="error" for="reason" style="">  </label>
                                    </div>
                                    
                                </div>
        
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn btn-raised btn-primary btn-round waves-effect" type="submit">{{__('admin.save')}}</button>
                            </form>
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>
  
    </div>

</section>
  
@endsection 

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>

<script>

    //this for add new record 

    $("#form_validation").submit(function(e){
        $('.add').disabled =true;
        e.preventDefault();
        var form = $(this);
         $.ajax({
            type: 'POST',
            url: '{{ URL::route("storediscount") }}',
            data:  new FormData($("#form_validation")[0]),
            processData: false,
            contentType: false,

            success: function(data) {
                $('.amount').addClass('hidden');
                $('.reason').addClass('hidden');
             
                if ((data.errors)) {                        
                    // toastr.error('{{trans('admin.Validation_error')}}', '{{trans('admin.Error_Alert')}}', {timeOut: 5000});
                    if (data.errors.amount) {
                        $('#amount-error').css('display', 'inline-block');
                        $('#amount-error').text(data.errors.amount);
                    }
                    if (data.errors.reason) {
                        $('#reason-error').css('display', 'inline-block');
                        $('#reason-error').text(data.errors.reason);
                    }
                   
                } else {
                    window.location.replace("{{route('discounts',$id)}}");

                    }
        },
        });
    });
    $('.select2').select2({
    placeholder: "{{trans('admin.choose')}}"
});
</script>
    
@endsection
