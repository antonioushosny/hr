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
                    <li class="breadcrumb-item"><a href="{{route('employees')}}"><i class="zmdi zmdi-face"></i> {{__('admin.employees')}}</a></li>
                    <li class="breadcrumb-item "><a href="javascript:void(0);">{{__('admin.add_employee')}}</a></li>
                    
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
                        <h2><strong>{{trans('admin.'.$title)}}</strong> {{trans('admin.add_employee')}}  </h2>
                        
                    </div>
                    <div class="body row">
                        <div class="col-lg-10">
                                {!! Form::open(['route'=>['storeemployee'],'method'=>'post','autocomplete'=>'off', 'name'=>"myform" ,'id'=>'form_validation', 'enctype'=>'multipart/form-data' ])!!} 
                                <div class=" row">
                                    <div class="form-group form-float col-lg-6">
                                        <input type="text"  class="form-control" placeholder="{{__('admin.placeholder_name')}}" name="name" required>
                                        <label id="name-error" class="error" for="name" style="">  </label>
                                    </div>
                                    <div class="form-group form-float col-lg-6">
                                        <input type="text" class="form-control" placeholder="{{__('admin.placeholder_mobile')}}" name="mobile" maxlength= 14 onkeypress="isNumber(event);" autocomplete="off" required>
                                        <label id="mobile-error" class="error" for="mobile" style=""></label>
                                    </div>
                                    <div class="form-group form-float col-lg-6">
                                        <input type="text" class="form-control" placeholder="{{__('admin.national_id')}}" name="national_id" maxlength= 14 onkeypress="isNumber(event);" autocomplete="off" required>
                                        <label id="national_id-error" class="error" for="national_id" style=""></label>
                                    </div>
                                    <div class="form-group form-float col-lg-6">
                                        <input type="text" class="form-control" id="mac_address-field1" placeholder="{{__('admin.mac_address')}}" name="mac_address"   >
                                        <label id="mac_address-error" class="error" for="mac_address" style=""></label>
                                    </div>
                                    <div class= "form-group form-float col-lg-6"> 
                                        {!! Form::select('department_id',$departments
                                            ,'',['class'=>'form-control show-tick' ,'placeholder' =>trans('admin.department_id'),'required']) !!}
                                            <label id="department_id-error" class="error" for="department_id" style="">  </label>
                                    </div>
                                    <div class="form-group form-float col-lg-6">
                                        <input type="text" class="form-control" placeholder="{{__('admin.net_salary')}}" name="net_salary" maxlength= 14 onkeypress="isNumber(event);" autocomplete="off" required>
                                        <label id="net_salary-error" class="error" for="net_salary" style=""></label>
                                    </div>
                                    <div class="form-group form-float col-lg-6">
                                        <input type="text" class="form-control" placeholder="{{__('admin.cross_salary')}}" name="cross_salary" maxlength= 14 onkeypress="isNumber(event);" autocomplete="off" required>
                                        <label id="cross_salary-error" class="error" for="cross_salary" style=""></label>
                                    </div>
                                    <div class="form-group form-float col-lg-6">
                                        <input type="text" class="form-control" placeholder="{{__('admin.insurance')}}" name="insurance" maxlength= 14 onkeypress="isNumber(event);" autocomplete="off" required>
                                        <label id="insurance-error" class="error" for="insurance" style=""></label>
                                    </div>
                                    <div class="form-group form-float col-lg-6">
                                        <input type="text" class="form-control" placeholder="{{__('admin.annual_vacations')}}" name="annual_vacations" maxlength= 14 onkeypress="isNumber(event);" autocomplete="off" required>
                                        <label id="annual_vacations-error" class="error" for="annual_vacations" style=""></label>
                                    </div>
                                    <div class="form-group form-float col-lg-6">
                                        <input type="text" class="form-control" placeholder="{{__('admin.accidental_vacations')}}" name="accidental_vacations" maxlength= 14 onkeypress="isNumber(event);" autocomplete="off" required>
                                        <label id="accidental_vacations-error" class="error" for="accidental_vacations" style=""></label>
                                    </div>
                                    <div class="form-group form-float col-lg-6">
                                        <input type="email" class="form-control" placeholder="{{ __('admin.placeholder_email')}}" name="email" autocomplete="username email"  required>
                                        <label id="email-error" class="error" for="email" style=""></label>
                                    </div>
                                    <div class="form-group form-float col-lg-6">
                                        <input name="password" class="form-control"  placeholder="{{__('admin.placeholder_password')}}"   readonly type="text" required>
                                        <label id="password-error" class="error" for="password" style=""></label>
                                        <input type="button" class="btn btn-raised btn-primary btn-round waves-effect" value="{{ __('admin.Generate') }}" onClick="generate();" tabindex="2">
                                    </div>
                                </div>
                                
                                <div class="form-group form-float row"  >
                                    {{--  for image  --}}
                                    <div class= "col-md-6 col-xs-6">
                                
                                        <div class="form-group form-float  " >
                                            <div style="position:relative; ">
                                                <a class='btn btn-primary' href='javascript:;' >
                                                    {{trans('admin.Choose_Image')}}
            
                                                    {!! Form::file('image',['class'=>'form-control','id' => 'image_field', 'accept'=>'image/x-png,image/gif,image/jpeg' ,'style'=>'position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;','size'=> '40' ,'onchange' => 'readURL(this,"changeimage");' ]) !!}
                                                </a>
                                                &nbsp;
                                                <div class='label label-primary' id="upload-file-info" ></div>
                                                <span style="color: red " class="image text-center hidden"></span>
                                            </div>
                                            
                                        </div>
                                    
                                    </div>
                                     <div class="col-md-6">
                                        <img id="changeimage" src="{{asset('images/default.png')}}" width="100px" height="100px" alt=" {{trans('admin.image')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="radio inlineblock m-r-20">
                                        <input type="radio" name="status" id="active" class="with-gap" value="active" checked="">
                                        <label for="active">{{__('admin.active')}}</label>
                                    </div>                                
                                    <div class="radio inlineblock">
                                        <input type="radio" name="status" id="not_active" class="with-gap" value="not_active" >
                                        <label for="not_active">{{__('admin.not_active')}}</label>
                                    </div>
                                </div>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn btn-raised btn-primary btn-round waves-effect" type="submit">{{__('admin.add')}}</button>
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
            url: '{{ URL::route("storeemployee") }}',
            data:  new FormData($("#form_validation")[0]),
            processData: false,
            contentType: false,

            success: function(data) {
                $('.name').addClass('hidden');
                $('.email').addClass('hidden');
                $('.password').addClass('hidden');
                $('.image').addClass('hidden');
                $('.status').addClass('hidden');
                $('.mobile').addClass('hidden');
                $('.national_id').addClass('hidden');
                $('.mac_address').addClass('hidden');
                $('.insurance').addClass('hidden');
                $('.cross_salary').addClass('hidden');
                $('.net_salary').addClass('hidden');
                $('.annual_vacations').addClass('hidden');
                $('.accidental_vacations').addClass('hidden');
                $('.department_id').addClass('hidden');
                 console.log(data);
                if ((data.errors)) {                        
                    // toastr.error('{{trans('admin.Validation_error')}}', '{{trans('admin.Error_Alert')}}', {timeOut: 5000});
                    if (data.errors.name) {
                        $('#name-error').css('display', 'inline-block');
                        $('#name-error').text(data.errors.name);
                    }
                    if (data.errors.email) {
                        $('#email-error').css('display', 'inline-block');
                        $('#email-error').text(data.errors.email);
                    }
                    if (data.errors.password) {
                        $('#password-error').css('display', 'inline-block');
                        $('#password-error').text(data.errors.password);
                    }
                    if (data.errors.mobile) {
                        $('#mobile-error').css('display', 'inline-block');
                        $('#mobile-error').text(data.errors.mobile);
                    }
                    if (data.errors.national_id) {
                        $('#national_id-error').css('display', 'inline-block');
                        $('#national_id-error').text(data.errors.national_id);
                    }
                    if (data.errors.mac_address) {
                        $('#mac_address-error').css('display', 'inline-block');
                        $('#mac_address-error').text(data.errors.mac_address);
                    }
                    if (data.errors.net_salary) {
                        $('#net_salary-error').css('display', 'inline-block');
                        $('#net_salary-error').text(data.errors.net_salary);
                    }
                    if (data.errors.cross_salary) {
                        $('#cross_salary-error').css('display', 'inline-block');
                        $('#cross_salary-error').text(data.errors.cross_salary);
                    }
                    if (data.errors.insurance) {
                        $('#insurance-error').css('display', 'inline-block');
                        $('#insurance-error').text(data.errors.insurance);
                    }
                    if (data.errors.annual_vacations) {
                        $('#annual_vacations-error').css('display', 'inline-block');
                        $('#annual_vacations-error').text(data.errors.annual_vacations);
                    }
                    if (data.errors.accidental_vacations) {
                        $('#accidental_vacations-error').css('display', 'inline-block');
                        $('#accidental_vacations-error').text(data.errors.accidental_vacations);
                    }
                    if (data.errors.department_id) {
                        $('#department_id-error').css('display', 'inline-block');
                        $('#department_id-error').text(data.errors.department_id);
                    }
                } else {
                    window.location.replace("{{route('employees')}}");

                    }
        },
        });
    });
    $('.select2').select2({
    placeholder: "{{trans('admin.choose')}}"
});
</script>
    
@endsection
