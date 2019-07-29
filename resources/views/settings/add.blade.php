@extends('layouts.index')
@section('style')

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
                    <li class="breadcrumb-item"><a href="{{route('settings',$type)}}"><i class="zmdi zmdi-accounts-add"></i> {{__('admin.settings')}}</a></li>
                    <li class="breadcrumb-item "><a href="javascript:void(0);">{{ !isset($data->id)?__('admin.add_setting'):__('admin.edit_setting') }} </a></li>
                    
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
                            <h2><strong>{{trans('admin.'.$title)}}</strong> {{ !isset($data->id)?__('admin.add_setting'):__('admin.edit_setting') }} </h2>
                        </div>
                        <div class="body">
                            {!! Form::open(['route'=>['storesetting'],'method'=>'post','autocomplete'=>'off', 'id'=>'form_validation', 'enctype'=>'multipart/form-data' ])!!} 

                                <div class="row">
                                    <div class="col-md-1">{{ __('admin.title_ar') }}</div>
                                    <div class="col-md-5">
                                        <!-- for title_ar -->
                                        <div class="form-group form-float">
                                            <input type="text" value="{{ !isset($data->title_ar)?'':$data->title_ar }}" class="form-control" placeholder="{{__('admin.placeholder_title_ar')}}" name="title_ar" required>
                                            <label id="name-ar-error" class="error" for="title_ar" style="">  </label>
                                        </div>
                                    </div>
                                    <div class="col-md-1">{{ __('admin.title_en') }}</div>
                                    <div class="col-md-5">
                                        <!-- for title_en -->
                                        <div class="form-group form-float">
                                            <input type="text" value="{{ !isset($data->title_en)?null:$data->title_en }}"  class="form-control" placeholder="{{__('admin.placeholder_title_en')}}" name="title_en" required>
                                            <label id="name-en-error" class="error" for="title_en" style="">  </label>
                                        </div>
                                    </div>
                                    <div class="col-md-1">{{ __('admin.desc_ar') }}</div>
                                    <div class="col-md-11">
                                        <!-- for desc_ar -->
                                        <div class="form-group form-float">
                                            @if($data)
                                            {!! Form::textarea('desc_ar',$data->disc_ar,['class'=>'form-control ','id' => 'disc_ar_field2','rows'=>7,'placeholder' => trans('admin.placeholder_disc_ar')]) !!}
                                            @else 
                                            {!! Form::textarea('desc_ar','',['class'=>'form-control ','id' => 'disc_ar_field2','rows'=>7,'placeholder' => trans('admin.placeholder_disc_ar')]) !!}
                                            @endif
                                            
                                            <label id="desc-ar-error" class="error" for="desc_ar" style="">  </label>
                                        </div>
                                    </div>
                                    <div class="col-md-1">{{ __('admin.desc_en') }}</div>
                                    <div class="col-md-11">
                                        <!-- for desc_en -->
                                        <div class="form-group form-float">
                                            

                                            @if($data)
                                            {!! Form::textarea('desc_en',$data->disc_en,['class'=>'form-control ','id' => 'disc_en_field2','rows'=>7,'placeholder' => trans('admin.placeholder_desc_en')]) !!}
                                            @else 
                                            {!! Form::textarea('desc_en','',['class'=>'form-control ','id' => 'disc_en_field2','rows'=>7,'placeholder' => trans('admin.placeholder_desc_en')]) !!}
                                            @endif
                                            <label id="desc-en-error" class="error" for="desc_en" style="">  </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- for type -->
                                <div class= "form-group form-float">
                                    {!! Form::hidden('type',$type,['class'=>'form-control show-tick']) !!}
                                    <label id="type-error" class="error" for="type" style="">  </label>
                                </div>

                                <!-- for id -->
                                <div class= "form-group form-float">
                                    {!! Form::hidden('id',!isset($data->id)?null:$data->id ,['class'=>'form-control show-tick']) !!}
                                </div>

                                <!-- for image  -->
                                {{--  <div class="form-group form-float row"  >
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
                                </div>  --}}
                                
                                <div class="form-group">
                                    <div class="radio inlineblock m-r-20">
                                        <input type="radio" name="status" id="active" class="with-gap" value="active" checked > 
                                        <label for="active">{{__('admin.active')}}</label>
                                    </div>                                 
                                    <div class="radio inlineblock">
                                        <input type="radio" name="status" id="not_active" class="with-gap" value="not_active" <?php echo ( isset($data->status) && $data->status == 'not_active') ? "checked=''" : ""; ?> >
                                        <label for="not_active">{{__('admin.not_active')}}</label>
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

</section>
  
@endsection 

@section('script')

<script src="{{ asset('assets/plugins/ckeditor/ckeditor.js') }}"></script> <!-- Ckeditor --> 
<script src="{{ asset('assets/js/pages/forms/editors.js') }}"></script>
<script>
    CKEDITOR.replace('disc_en_field2');
    CKEDITOR.config.height = 300;

    CKEDITOR.replace('disc_ar_field2');
    CKEDITOR.config.height = 300;
    //this for add new record
    $("#form_validation").submit(function(e){
           {{--  $('#addModal').modal('hide');  --}}
           $('.add').disabled =true;
          e.preventDefault();
          var form = $(this);
        //    openModal();
          $.ajax({
              type: 'POST',
              url: '{{ URL::route("storesetting") }}',
              data:  new FormData($("#form_validation")[0]),
              processData: false,
              contentType: false,
              success: function(data) {
                  if ((data.errors)) {                        
                        if (data.errors.title_ar) {
                            $('#name-ar-error').css('display', 'inline-block');
                            $('#name-ar-error').text(data.errors.title_ar);
                        }
                        if (data.errors.title_en) {
                            $('#name-en-error').css('display', 'inline-block');
                            $('#name-en-error').text(data.errors.title_en);
                        }
                        if (data.errors.city_id) {
                            $('#city-id-error').css('display', 'inline-block');
                            $('#city-id-error').text(data.errors.city_id);
                        }
                        if (data.errors.image) {
                            $('#image-error').css('display', 'inline-block');
                            $('#image-error').text(data.errors.image);
                        }
                  } else {
                      
                    $.ajax({
                        type: 'POST',
                        url: '{{ URL::route("storesetting") }}',
                        data:  new FormData($("#form_validation")[0]),
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            if ((data.errors)) {                        
                                  if (data.errors.title_ar) {
                                      $('#name-ar-error').css('display', 'inline-block');
                                      $('#name-ar-error').text(data.errors.title_ar);
                                  }
                                  if (data.errors.title_en) {
                                      $('#name-en-error').css('display', 'inline-block');
                                      $('#name-en-error').text(data.errors.title_en);
                                  }
                                  if (data.errors.city_id) {
                                      $('#city-id-error').css('display', 'inline-block');
                                      $('#city-id-error').text(data.errors.city_id);
                                  }
                                  if (data.errors.image) {
                                      $('#image-error').css('display', 'inline-block');
                                      $('#image-error').text(data.errors.image);
                                  }
                            } else {
                                
                                  location.reload();
                               }
                      },
                    });
                     }
            },
          });
        });

</script>
    
@endsection
