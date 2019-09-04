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
                    <li class="breadcrumb-item"><a href="{{route('techsubscriptions')}}"><i class="zmdi zmdi-money-box"></i> {{__('admin.add_subscriptions_tech')}}</a></li>
                    <li class="breadcrumb-item "><a href="javascript:void(0);">{{__('admin.edit_subscription_tech')}}</a></li>
                    
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
                        <h2><strong>{{trans('admin.'.$title)}}</strong> {{trans('admin.edit_subscription_tech')}}  </h2>
                        
                    </div> 
                    <div class="body row">
                        <div class="col-lg-6">
                            {!! Form::open(['route'=>['storeaddtechsubscription'],'method'=>'post','autocomplete'=>'off', 'id'=>'form_validation', 'enctype'=>'multipart/form-data' ])!!} 
                            <div class= "form-group form-float"> 
                                    {!! Form::select('fannie',$usernames
                                        ,'',['class'=>'select2 form-control show-tick selectpicker','id'=>'user_id1' ,'placeholder' =>trans('admin.choose_user')]) !!}
                                        <label id="user_id-error" class="error" for="user_id" style="">  </label>
                                </div>

                                <div class= "form-group form-float"> 
                                    {!! Form::select('fannie2',$usernumber
                                        ,'',['class'=>'select2 form-control show-tick selectpicker','id'=>'user_id2' ,'placeholder' =>trans('admin.choose_user_num')]) !!}
                                        <label id="user_id-error2" class="error" for="user_id" style="">  </label>
                                </div>  
                                <div class= "form-group form-float"> 
                                    {!! Form::select('sub_type',$types
                                        ,'',['class'=>' form-control show-tick selectpicker','id'=>'sub_type' ,'placeholder' =>trans('admin.choose_subscription'),'required']) !!}
                                        <label id="sub_type-error" class="error" for="sub_type" style="">  </label>
                                </div>

                                
                                
                            <!-- for image  -->
                            <div class="form-group form-float row"  >
                                        {{--  for image  --}}
                                    <div class= "col-md-2 col-xs-3">
                                        <div class="form-group form-float  " >
                                            <div style="position:relative; ">
                                                <a class='btn btn-primary' href='javascript:;' >
                                                    {{trans('admin.Choose_deposit')}}
            
                                                    {!! Form::file('deposit',['class'=>'form-control','id' => 'image_field', 'accept'=>'image/x-png,image/gif,image/jpeg' ,'style'=>'position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;','size'=> '40' ,'onchange' => 'readURL(this,"changeimage");' ]) !!}
                                                </a>
                                                &nbsp;
                                                <div class='label label-primary' id="upload-file-info" ></div>
                                                <span style="color: red " id="deposit-error" class="image text-user hidden"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-10">
                                        
                                        <img id="changeimage" src="{{asset('images/default.png')}}" width="100px" height="100px" alt=" {{trans('admin.image')}}" />
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>


<script>
$('#user_id1').change(function(){
    console.log($('#user_id1').val());
    var value = $('#user_id1').val();
    $('#user_id2').empty();
    $('#user_id2').append(`<option value="">{{trans('admin.choose_user_num')}}</option>`); 
// Set selected 

@foreach ($alluser as $data)
    
        if(value=="{{$data->id}}")
        {
            $('#user_id2').append(`<option value="{{$data->id}}" selected>{{$data->mobile}}</option>`);
        }
        else
        {
            $('#user_id2').append(`<option value="{{$data->id}}">{{$data->mobile}}</option>`);
        }
         
    
            
        @endforeach
$('#user_id2').selectpicker('refresh');
    $('#user_id2').selectpicker('render');
});

$('#user_id2').change(function(){
    console.log($('#user_id2').val());
    var value = $('#user_id2').val();
    $('#user_id1').empty();
    $('#user_id1').append(`<option value="">{{trans('admin.choose_user_num')}}</option>`); 
// Set selected 

@foreach ($alluser as $data)
    
        if(value=="{{$data->id}}")
        {
            $('#user_id1').append(`<option value="{{$data->id}}" selected>{{$data->name}}</option>`);
        }
        else
        {
            $('#user_id1').append(`<option value="{{$data->id}}">{{$data->name}}</option>`);
        }
         
    
            
        @endforeach
$('#user_id1').selectpicker('refresh');
    $('#user_id1').selectpicker('render');
});
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
              url: '{{ URL::route("storeaddtechsubscription") }}',
              data:  new FormData($("#form_validation")[0]),
              processData: false,
              contentType: false,
               
              success: function(data) {
                  console.log(data);
                  if ((data.errors)) {              
                    $(':input[type="submit"]').prop('disabled', false);          
                        if (data.errors.fannie) {
                            $('#user_id-error').css('display', 'inline-block');
                            $('#user_id-error').text(data.errors.fannie);
                        }
                        if (data.errors.sub_type) {
                            $('#sub_type-error').css('display', 'inline-block');
                            $('#sub_type-error').text(data.errors.sub_type);
                        }

                        if (data.errors.deposit) {
                            $('#deposit-error').css('display', 'inline-block');
                            $('#deposit-error').text(data.errors.deposit);
                        }
                        if (data.errors.cost) {
                            $('#cost-error').css('display', 'inline-block');
                            $('#cost-error').text(data.errors.cost);
                        }
                  } 
                //   else {
                //         window.location.replace("{{route('techsubscriptions')}}");

                //      }
            },
          });
        });
        $('.select2').select2();
</script>
    
@endsection
