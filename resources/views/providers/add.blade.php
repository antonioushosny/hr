@extends('layouts.index')
@section('style')
    @if($lang == 'ar')
    <style>
        .dtp ,.datetimepicker, .join_date{
            direction: ltr !important ;
            border-radius: 0 30px 30px 0 !important;
        }
    </style>
    @endif
@endsection
 @section('content')
<!-- Main Content -->
<section class="content home">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-12">
                <h2>{{__('admin.dashboard')}}
                    <small>{{__('admin.Welcome to Khazan')}}</small>
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
                    <li class="breadcrumb-item"><a href="{{route('providers')}}"><i class="zmdi zmdi-accounts-add"></i> {{__('admin.providers')}}</a></li>
                    <li class="breadcrumb-item "><a href="javascript:void(0);">{{__('admin.add_provider')}}</a></li>
                    
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
                            <h2><strong>{{trans('admin.'.$title)}}</strong> {{trans('admin.add_provider')}}  </h2>
                            
                        </div>
                        <div class="body row">
                            <div class="col-lg-6">
                                {!! Form::open(['route'=>['storeprovider'],'method'=>'post','autocomplete'=>'off', 'id'=>'form_validation', 'name'=>"myform",'enctype'=>'multipart/form-data' ])!!} 

                                
                                    <!-- for company_name -->
                                    <div class="form-group form-float">
                                        <input type="text" class="form-control" placeholder="{{__('admin.placeholder_company_name')}}" name="company_name" required>
                                        <label id="company_name-error" class="error" for="company_name" style="">  </label>
                                    </div>
                                    <!-- for responsible_name -->
                                    <div class="form-group form-float">
                                        <input type="text" class="form-control" placeholder="{{__('admin.placeholder_responsible_name')}}" name="responsible_name" required>
                                        <label id="responsible_name-error" class="error" for="responsible_name" style="">  </label>
                                    </div>
                                
                                    <!-- for email -->
                                    <div class="form-group form-float">
                                        <input type="email" class="form-control" placeholder="{{__('admin.placeholder_email')}}" name="email" autocomplete="off" required>
                                        <label id="email-error" class="error" for="email" style=""></label>
                                    </div>
                                    <!-- for password -->
                                    <div class="form-group form-float">
                                         <input name="password" class="form-control"  placeholder="{{__('admin.placeholder_password')}}"   readonly type="text" required>
                                        <input type="button" class="btn btn-raised btn-primary btn-round waves-effect" value="{{ __('admin.Generate') }}" onClick="generate();" tabindex="2">
                                        <label id="password-error" class="error" for="password" style=""></label>
                                    </div>
                                    <!-- for mobile -->
                                    <div class="form-group form-float">
                                        <input type="text" class="form-control" placeholder="{{__('admin.mobile')}}" name="mobile" onkeypress="isNumber(event); ">
                                        <label id="mobile-error" class="error" for="mobile" style="">  </label>
                                    </div>
                                    <!-- <div class="input-group form-float join_dates">
                                        <span class="input-group-addon"><i class="zmdi zmdi-smartphone"></i></span>
                                        <input type="text" class="form-control mobile-phone-number" placeholder="Ex: +00 (000) 000-00-00">
                                    </div> -->
                                    <!-- for description -->
                                    <div class="form-group form-float">
                                        <textarea rows="4"  name="description"  class="form-control no-resize"  placeholder="{{__('admin.placeholder_description')}}" ></textarea>

                                        <label id="description-error" class="error" for="description" style="">  </label>
                                    </div>
                                    <!-- for address -->
                                    <div class="form-group form-float">
                                        <textarea rows="4" name="address"  class="form-control no-resize"  placeholder="{{__('admin.placeholder_address')}}" ></textarea>

                                        <label id="address-error" class="error" for="address" style="">  </label>
                                    </div>
                                    <div class="input-group join_date">
                                        <span class="input-group-addon">
                                            <i class="zmdi zmdi-calendar"></i>
                                        </span>
                                        <input type="text" name="join_date"  class="form-control datetimepicker" placeholder="{{__('admin.join_date')}}">
                                        <label id="join_date-error" class="error" for="join_date" style="">  </label>
                                    </div>
                                    <!-- for image  -->
                                    <div class="form-group form-float row"  >
                                        {{--  for image  --}}
                                        <div class= "col-md-2 col-xs-3">
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

                                        <div class="col-md-10">
                                            
                                            <img id="changeimage" src="{{asset('images/default.png')}}" width="100px" height="100px" alt=" {{trans('admin.image')}}" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="radio inlineblock m-r-20">
                                            <input type="radio" name="status" id="active" class="with-gap" value="active" checked="">
                                            <label for="active">{{__('admin.active')}}</label>
                                        </div>                                
                                        <div class="radio inlineblock">
                                            <input type="radio" name="status" id="not_active" class="with-gap" value="not_active"  >
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


<script>

    //this for add new record
    $("#form_validation").submit(function(e){
           {{--  $('#addModal').modal('hide');  --}}
           $('.add').disabled =true;
           $(':input[type="submit"]').prop('disabled', true);
          e.preventDefault();
          var form = $(this);
        //    openModal();
          $.ajax({
              type: 'POST',
              url: '{{ URL::route("storeprovider") }}',
              data:  new FormData($("#form_validation")[0]),
              processData: false,
              contentType: false,
               
              success: function(data) {
                  if ((data.errors)) {   
                    $(':input[type="submit"]').prop('disabled', false);                     
                        if (data.errors.company_name) {
                            $('#company_name-error').css('display', 'inline-block');
                            $('#company_name-error').text(data.errors.company_name);
                        }
                        if (data.errors.responsible_name) {
                            $('#responsible_name-error').css('display', 'inline-block');
                            $('#responsible_name-error').text(data.errors.responsible_name);
                        }
                        if (data.errors.city_id) {
                            $('#city-id-error').css('display', 'inline-block');
                            $('#city-id-error').text(data.errors.city_id);
                        }
                        if (data.errors.image) {
                            $('#image-error').css('display', 'inline-block');
                            $('#image-error').text(data.errors.image);
                        }
                        if (data.errors.email) {
                            $('#email-error').css('display', 'inline-block');
                            $('#email-error').text(data.errors.email);
                        }
                        if (data.errors.mobile) {
                            $('#mobile-error').css('display', 'inline-block');
                            $('#mobile-error').text(data.errors.mobile);
                        }
                        if (data.errors.password) {
                            $('#password-error').css('display', 'inline-block');
                            $('#password-error').text(data.errors.password);
                        }
                  } else {
                        window.location.replace("{{route('providers')}}");

                     }
            },
          });
        });

</script>
    
@endsection
