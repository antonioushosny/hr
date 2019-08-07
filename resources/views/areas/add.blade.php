@extends('layouts.index')
@section('style')
<style>
    .btn.btn-simple, .navbar .navbar-nav>a.btn.btn-simple {
        color: #171515;
        border-color: #8c99e0 !important ;
    }
    /* .btn:hover, .btn:focus, .btn:active, .btn.active, .btn:active:focus, .btn:active:hover, .btn.active:focus, .btn.active:hover, .show>.btn.dropdown-toggle, .show>.btn.dropdown-toggle:focus, .show>.btn.dropdown-toggle:hover, .navbar .navbar-nav>a.btn:hover, .navbar .navbar-nav>a.btn:focus, .navbar .navbar-nav>a.btn:active, .navbar .navbar-nav>a.btn.active, .navbar .navbar-nav>a.btn:active:focus, .navbar .navbar-nav>a.btn:active:hover, .navbar .navbar-nav>a.btn.active:focus, .navbar .navbar-nav>a.btn.active:hover, .show>.navbar .navbar-nav>a.btn.dropdown-toggle, .show>.navbar .navbar-nav>a.btn.dropdown-toggle:focus, .show>.navbar .navbar-nav>a.btn.dropdown-toggle:hover {
        background-color: #ffffff !important ;
        color: #fff;
    } */
    
</style>
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
                    <li class="breadcrumb-item"><a href="{{route('areas')}}"><i class="zmdi zmdi-accounts-add"></i> {{__('admin.areas')}}</a></li>
                    <li class="breadcrumb-item "><a href="javascript:void(0);">{{__('admin.add_area')}}</a></li>
                    
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
                        <h2><strong>{{trans('admin.'.$title)}}</strong> {{trans('admin.add_area')}}  </h2>
                        
                    </div>
                    <div class="body">
                        {!! Form::open(['route'=>['storearea'],'method'=>'post','autocomplete'=>'off', 'id'=>'form_validation', 'enctype'=>'multipart/form-data' ])!!} 
                            <div class="form-group form-float">
                                <input type="text" class="form-control" placeholder="{{__('admin.placeholder_name_ar')}}" name="name_ar" required>
                                <label id="name-ar-error" class="error" for="name_ar" style="">  </label>
                            </div>
                            <div class="form-group form-float">
                                <input type="text" class="form-control" placeholder="{{__('admin.placeholder_name_en')}}" name="name_en" required>
                                <label id="name-en-error" class="error" for="name_en" style="">  </label>
                            </div>
                            <div class= "form-group form-float"> 
                                {!! Form::select('city_id',$cities
                                    ,'',['class'=>'form-control show-tick' ,'placeholder' =>trans('admin.choose_city'),'required']) !!}
                                    <label id="city-id-error" class="error" for="city_id" style="">  </label>
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
              url: '{{ URL::route("storearea") }}',
              data:  new FormData($("#form_validation")[0]),
              processData: false,
              contentType: false,
               
              success: function(data) {
                  if ((data.errors)) {   
                    $(':input[type="submit"]').prop('disabled', false);                     
                        if (data.errors.name_ar) {
                            $('#name-ar-error').css('display', 'inline-block');
                            $('#name-ar-error').text(data.errors.name_ar);
                        }
                        if (data.errors.name_en) {
                            $('#name-en-error').css('display', 'inline-block');
                            $('#name-en-error').text(data.errors.name_en);
                        }
                        if (data.errors.city_id) {
                            $('#city-id-error').css('display', 'inline-block');
                            $('#city-id-error').text(data.errors.city_id);
                        }
                        
                  } else {
                        window.location.replace("{{route('areas')}}");

                     }
            },
          });
        });

</script>
    
@endsection
