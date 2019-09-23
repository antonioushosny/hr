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
    /* -webkit-transition: color 0.3s ease-in-out, border-color 0.3s ease-in-out, background-color 0.3s ease-in-out;
    -moz-transition: color 0.3s ease-in-out, border-color 0.3s ease-in-out, background-color 0.3s ease-in-out;
    -o-transition: color 0.3s ease-in-out, border-color 0.3s ease-in-out, background-color 0.3s ease-in-out;
    -ms-transition: color 0.3s ease-in-out, border-color 0.3s ease-in-out, background-color 0.3s ease-in-out;
    transition: color 0.3s ease-in-out, border-color 0.3s ease-in-out, background-color 0.3s ease-in-out; */
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
                <small>{{__('admin.Welcome to fannie')}}</small>
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
                    <li class="breadcrumb-item"><a href="{{route('admins')}}"><i class="zmdi zmdi-face"></i> {{__('admin.admins')}}</a></li>
                    <li class="breadcrumb-item "><a href="javascript:void(0);">{{__('admin.edit_admin')}}</a></li>
                    
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
                            <h2><strong>{{trans('admin.'.$title)}}</strong> {{trans('admin.edit_admin')}}  </h2>
                            
                        </div>
                        <div class="body row">
                            <div class="col-lg-6">
                                {!! Form::open(['route'=>['storeadmin'],'method'=>'post','name'=>"myform",'autocomplete'=>'off', 'id'=>'form_validation', 'enctype'=>'multipart/form-data' ])!!} 
                                     <div class="form-group form-float">
                                        <input type="hidden" value="{{$admin->id}}" name="id" required>
                                    </div>
                                    <div class="form-group form-float">
                                        <input type="text" value="{{$admin->name}}" class="form-control" placeholder="{{__('admin.placeholder_name')}}" name="name" required>
                                        <label id="name-error" class="error" for="name" style="">  </label>
                                    </div>
                                    <div class="form-group form-float">
                                        <input type="email" value="{{$admin->email}}" class="form-control" placeholder="{{__('admin.placeholder_email')}}" name="email" autocomplete="off" required>
                                        <label id="email-error" class="error" for="email" style=""></label>
                                    </div>
                                     
                                    <div class="form-group form-float">
                                           
                                        <input name="password" class="form-control"  readonly type="text"   placeholder="{{__('admin.placeholder_password')}}" >
                                        
                                        <label id="password-error" class="error" for="password" style=""></label>
                                        <input type="button" class="btn btn-raised btn-primary btn-round waves-effect" value="{{ __('admin.Generate') }}" onClick="generate();" tabindex="2">
                                    </div>
                                    <div class="form-group form-float">
                                    <label  for="roles" style=""> {{trans('admin.roles')}} </label>
                                        {!! Form::select('roles[]', $roles,$userRole, array('class' => 'form-control select2','multiple')) !!}
                                        
                                        <label id="roles-error" class="error" for="roles" style=""></label>
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
                                        
                                            @if($admin->image)
                                                <img id="changeimage" src="{{asset('img/'.$admin->image)}}" width="100px" height="100px" alt=" {{trans('admin.image')}}" />
                                            @else 
                                                <img id="changeimage" src="{{asset('images/default.png')}}" width="100px" height="100px" alt=" {{trans('admin.image')}}" />
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="radio inlineblock m-r-20">
                                            <input type="radio" name="status" id="active" class="with-gap" value="active" <?php echo ($admin->status == 'active') ? "checked=''" : ""; ?> >
                                            <label for="active">{{__('admin.active')}}</label>
                                        </div>                                
                                        <div class="radio inlineblock">
                                            <input type="radio" name="status" id="not_active" class="with-gap" value="not_active" <?php echo ($admin->status == 'not_active') ? "checked=''" : ""; ?> >
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
<script>
     //this for add new record
    $("#form_validation").submit(function(e){
           {{--  $('#addModal').modal('hide');  --}}
           $('.add').disabled =true;
          e.preventDefault();
          var form = $(this);
        //    openModal();
          $.ajax({
              type: 'POST',
              url: '{{ URL::route("storeadmin") }}',
              data:  new FormData($("#form_validation")[0]),
              processData: false,
              contentType: false,
               
              success: function(data) {
  
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
                        
                  } else {
                        window.location.replace("{{route('admins')}}");

                     }
            },
          });
        });
        $('.select2').select2({
    placeholder: "{{trans('admin.choose')}}"
});
</script>
    
@endsection
