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
                    <li class="breadcrumb-item"><a href="{{route('tasks')}}"><i class="zmdi zmdi-face"></i> {{__('admin.tasks')}}</a></li>
                    <li class="breadcrumb-item "><a href="javascript:void(0);">{{__('admin.add_task')}}</a></li>
                    
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
                        <h2><strong>{{trans('admin.'.$title)}}</strong> {{trans('admin.add_task')}}  </h2>
                        
                    </div>
                    <div class="body row">
                        <div class="col-lg-10">
                                {!! Form::open(['route'=>['storetask'],'method'=>'post','autocomplete'=>'off', 'name'=>"myform" ,'id'=>'form_validation', 'enctype'=>'multipart/form-data' ])!!} 
                                <div class=" row">
                                    <div class="form-group form-float col-lg-6">
                                        <input type="text"  class="form-control" placeholder="{{__('admin.title')}}" name="title" required>
                                        <label id="title-error" class="error" for="title" style="">  </label>
                                    </div>
                                    <div class="form-group form-float col-lg-6">
                                        <input type="date" class="form-control" placeholder="{{__('admin.date')}}" name="date"  required>
                                        <label id="date-error" class="error" for="date" style=""></label>
                                    </div>
                                    <div class="form-group form-float col-lg-6">
                                        <input type="text" class="form-control" placeholder="{{__('admin.time')}}" name="time" maxlength= 14 onkeypress="isNumber(event);" autocomplete="off" required>
                                        <label id="time-error" class="error" for="time" style=""></label>
                                    </div>
                                    <div class="form-group form-float col-lg-6">
                                        <input type="text"  class="form-control" placeholder="{{__('admin.project_name')}}" name="project_name" required>
                                        <label id="project_name-error" class="error" for="project_name" style="">  </label>
                                    </div>
                                    <div class= "form-group form-float col-lg-6"> 
                                        {!! Form::select('employee_id',$employees
                                            ,'',['class'=>'form-control show-tick' ,'placeholder' =>trans('admin.employee_id'),'required']) !!}
                                            <label id="employee_id-error" class="error" for="employee_id" style="">  </label>
                                    </div>
                                    
                                    <div class= "form-group form-float col-lg-6"> 
                                        {!! Form::select('status',['waiting'=>'Waiting','working on'=>'Working On','done'=>'Done','not completed'=>'Not Completed',]
                                            ,'',['class'=>'form-control show-tick' ,'placeholder' =>trans('admin.status'),'required']) !!}
                                            <label id="status-error" class="error" for="status" style="">  </label>
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
            url: '{{ URL::route("storetask") }}',
            data:  new FormData($("#form_validation")[0]),
            processData: false,
            contentType: false,

            success: function(data) {
                $('.title').addClass('hidden');
                $('.date').addClass('hidden');
                $('.time').addClass('hidden');
                $('.employee_id').addClass('hidden');
                $('.status').addClass('hidden');
                $('.project_name').addClass('hidden');
               
                 console.log(data);
                if ((data.errors)) {                        
                    // toastr.error('{{trans('admin.Validation_error')}}', '{{trans('admin.Error_Alert')}}', {timeOut: 5000});
                    if (data.errors.title) {
                        $('#title-error').css('display', 'inline-block');
                        $('#title-error').text(data.errors.title);
                    }
                    if (data.errors.date) {
                        $('#date-error').css('display', 'inline-block');
                        $('#date-error').text(data.errors.date);
                    }
                    if (data.errors.time) {
                        $('#time-error').css('display', 'inline-block');
                        $('#time-error').text(data.errors.time);
                    }
                    if (data.errors.employee_id) {
                        $('#employee_id-error').css('display', 'inline-block');
                        $('#employee_id-error').text(data.errors.employee_id);
                    }
                    if (data.errors.status) {
                        $('#status-error').css('display', 'inline-block');
                        $('#status-error').text(data.errors.status);
                    }
                    if (data.errors.project_name) {
                        $('#project_name-error').css('display', 'inline-block');
                        $('#project_name-error').text(data.errors.project_name);
                    }
                    
                } else {
                    window.location.replace("{{route('tasks')}}");

                    }
        },
        });
    });
    $('.select2').select2({
    placeholder: "{{trans('admin.choose')}}"
});
</script>
    
@endsection
