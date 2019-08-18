@extends('layouts.index')
@section('style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />

    @if($lang == 'ar')
    <style>
        .dtp ,.datetimepicker, .join_date{
            direction: ltr !important ;
            border-radius: 0 30px 30px 0 !important;
        }

        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 1.6px solid #aaa;
            border-radius: 13px;
            max-width: 97%;
            /* border: 1px solid; */
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
                    <li class="breadcrumb-item"><a href="{{route('drivers')}}"><i class="zmdi zmdi-accounts-add"></i> {{__('admin.drivers')}}</a></li>
                    <li class="breadcrumb-item "><a href="javascript:void(0);">{{__('admin.edit_driver')}}</a></li>
                    
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
                        <h2><strong>{{trans('admin.'.$title)}}</strong> {{trans('admin.edit_driver')}}  </h2>
                        
                    </div>
                    <div class="body row">
                        <div class="col-lg-6">
                            {!! Form::open(['route'=>['storedriver'],'method'=>'post','autocomplete'=>'off', 'id'=>'form_validation', 'enctype'=>'multipart/form-data' ])!!} 

                                <div class="form-group form-float">
                                    <input type="hidden" value="{{$driver->id}}" name="id" required>
                                </div>
                                @if(Auth::user()->role == 'admin' )
                                    <div class= "form-group form-float">
                                        {!! Form::select('provider_id',$providers
                                            ,$provider->id,['class'=>'form-control show-tick select2','id'=>'provider_id' ,'placeholder' =>trans('admin.choose_provider'),'required']) !!}
                                        <label id="provider_id-error" class="error" for="provider_id" style="">  </label>
                                    </div>
                                @elseif(Auth::user()->role == 'provider') 
                                    <div class="form-group form-float">
                                        <input type="hidden" value="{{Auth::user()->id}}" id="provider_id" name="provider_id" required>
                                    </div>
                                @else 
                                    <div class="form-group form-float">
                                        <input type="hidden" value="{{$provider->id}}" id="provider_id" name="provider_id" required>
                                    </div>
                                @endif

                                <!-- for center_id -->
                                @if(Auth::user()->role != 'center' )
                                    <div class= "form-group form-float">
                                        {!! Form::select('center_id',$centers
                                            ,$driver->center_id,['class'=>'form-control show-tick select2','id'=>'center_id' ,'placeholder' =>trans('admin.choose_center'),'required']) !!}
                                        <label id="center_id-error" class="error" for="center_id" style="">  </label>
                                    </div>
                                @else 
                                    <div class="form-group form-float">
                                        <input type="hidden" value="{{Auth::user()->id}}" id="center_id" name="center_id" required>
                                    </div>
                                @endif
                                <!-- for responsible_name -->
                                <div class="form-group form-float">
                                    <input type="text" class="form-control" placeholder="{{__('admin.placeholder_responsible_name')}}" name="responsible_name" value="{{$driver->name}}" required>
                                    <label id="responsible_name-error" class="error" for="responsible_name" style="">  </label>
                                </div>
                            
                                <!-- for email -->
                                <div class="form-group form-float">
                                    <input type="email" class="form-control" placeholder="{{__('admin.placeholder_email')}}" name="email" autocomplete="off" value="{{$driver->email}}" required>
                                    <label id="email-error" class="error" for="email" style=""></label>
                                </div>

                                <!-- for image  -->
                                <div class="form-group form-float row" >
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
                                                <span style="color: red " class="image text-driver hidden"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-10">
                                        
                                        @if($driver->image)
                                            <img id="changeimage" src="{{asset('img/'.$driver->image)}}" width="100px" height="100px" alt=" {{trans('admin.image')}}" />
                                        @else 
                                            <img id="changeimage" src="{{asset('images/default.png')}}" width="100px" height="100px" alt=" {{trans('admin.image')}}" />
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="radio inlineblock m-r-20">
                                        <input type="radio" name="status" id="active" class="with-gap" value="active" <?php echo ($driver->status == 'active') ? "checked=''" : ""; ?> >
                                        <label for="active">{{__('admin.active')}}</label>
                                    </div>                                
                                    <div class="radio inlineblock">
                                        <input type="radio" name="status" id="not_active" class="with-gap" value="not_active" <?php echo ($driver->status == 'not_active') ? "checked=''" : ""; ?> >
                                        <label for="not_active">{{__('admin.not_active')}}</label>
                                    </div>
                                </div>

                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn btn-raised btn-primary btn-round waves-effect" type="submit">{{__('admin.edit')}}</button>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>

<script>
    $('.select2').select2();
    //this for add new record
    $("#form_validation").submit(function(e){
          e.preventDefault();
          $(':input[type="submit"]').prop('disabled', true);
          var form = $(this);
        //    openModal();
          $.ajax({
              type: 'POST',
              url: '{{ URL::route("storedriver") }}',
              data:  new FormData($("#form_validation")[0]),
              processData: false,
              contentType: false,
               
              success: function(data) {
                  if ((data.errors)) {    
                    $(':input[type="submit"]').prop('disabled', false);                    
                        if (data.errors.center_id) {
                            $('#center_id-error').css('display', 'inline-block');
                            $('#center_id-error').text(data.errors.center_id);
                        }
                        if (data.errors.responsible_name) {
                            $('#responsible_name-error').css('display', 'inline-block');
                            $('#responsible_name-error').text(data.errors.responsible_name);
                        }
                        if (data.errors.email) {
                            $('#email-error').css('display', 'inline-block');
                            $('#email-error').text(data.errors.email);
                        }
                        if (data.errors.image) {
                            $('#image-error').css('display', 'inline-block');
                            $('#image-error').text(data.errors.image);
                        }
                        if (data.errors.mobile) {
                            $('#mobile-error').css('display', 'inline-block');
                            $('#mobile-error').text(data.errors.mobile);
                        }
                  } else {
                        window.location.replace("{{route('drivers')}}");

                     }
            },
        });
    });
    // id = $('#provider_id').val();
    // if(id !=''){
    //     $('#center_id').empty();
    //     $.ajax({
    //         type: 'GET',
    //         url: "<?php echo url('/')?>/providers/"+id+"/centers",
    //         success: data => {
    //             if(data.centers.length <= 0){
    //                 alert("{{trans('admin.notfoundcenter')}}");
    //             }
    //             data.centers.forEach(center =>
    //                 $('#center_id').append(`<option value="${center.id}">${center.name}</option>`)
    //             )
    //         }
    //     })
    // }
    $('#provider_id').on('change', e => {

        $('#center_id').empty();
        id = $('#provider_id').val();
        if(id !=''){

            $.ajax({
                type: 'GET',
                url: "<?php echo url('/')?>/providers/"+id+"/centers",
                success: data => {
                    if(data.centers.length <= 0){
                        alert("{{trans('admin.notfoundcenter')}}");
                    }
                    data.centers.forEach(center =>
                        $('#center_id').append(`<option value="${center.id}">${center.name}</option>`)
                    )
                }
            })
        }
    });
</script>

@endsection
