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
                    <li class="breadcrumb-item"><a href="{{route('subscriptions')}}"><i class="zmdi zmdi-money"></i> {{__('admin.subscriptions')}}</a></li>
                    <li class="breadcrumb-item "><a href="javascript:void(0);">{{__('admin.edit_subscription')}}</a></li>
                    
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
                            <h2><strong>{{trans('admin.'.$title)}}</strong> {{trans('admin.edit_subscription')}}  </h2>
                            
                        </div>
                        <div class="body row">
                            <div class="col-lg-6">
                                {!! Form::open(['route'=>['storeaddsubscription'],'method'=>'post','autocomplete'=>'off', 'id'=>'form_validation', 'enctype'=>'multipart/form-data' ])!!} 

                                    <div class="form-group form-float">
                                        <input type="hidden" value="{{$subscription->id}}" name="id" required>
                                    </div>
                                    <div class="form-group form-float">
                                        <input type="text" value="{{$subscription->name_ar}}" class="form-control" placeholder="{{__('admin.placeholder_name_ar')}}" name="name_ar" required>
                                        <label id="name-ar-error" class="error" for="name_ar" style="">  </label>
                                    </div>
                                    <div class="form-group form-float">
                                        <input type="text" value="{{$subscription->name_en}}" class="form-control" placeholder="{{__('admin.placeholder_name_en')}}" name="name_en" required>
                                        <label id="name-en-error" class="error" for="name_en" style="">  </label>
                                    </div>

                                    <div class="form-group form-float">
                                    <input type="number" class="form-control" value="{{$subscription->no_month}}" min="1" max="12" placeholder="{{__('admin.placeholder_no_month')}}" name="no_month" required>
                                    <label id="no-month-error" class="error" for="no_month" style="">  </label>
                                    </div>
                                
                                
                                    <div class="form-group form-float">
                                        <input type="number" value="{{$subscription->cost}}" class="form-control" placeholder="{{__('admin.placeholder_cost')}}" name="cost" required>
                                        <label id="cost-error" class="error" for="cost" style="">  </label>
                                    </div>

                                    <div class="form-group">
                                        <div class="radio inlineblock m-r-20">
                                            <input type="radio" name="status" id="active" class="with-gap" value="active" <?php echo ($subscription->status == 'active') ? "checked=''" : ""; ?> >
                                            <label for="active">{{__('admin.active')}}</label>
                                        </div>                                
                                        <div class="radio inlineblock">
                                            <input type="radio" name="status" id="not_active" class="with-gap" value="not_active" <?php echo ($subscription->status == 'not_active') ? "checked=''" : ""; ?> >
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
              url: '{{ URL::route("storeaddsubscription") }}',
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
                        if (data.errors.no_month) {
                            $('#no-month-error').css('display', 'inline-block');
                            $('#no-month-error').text(data.errors.no_month);
                        }
                        if (data.errors.cost) {
                            $('#cost-error').css('display', 'inline-block');
                            $('#cost-error').text(data.errors.cost);
                        }
                        
                  } else {
                        window.location.replace("{{route('subscriptions')}}");

                     }
            },
          });
        });

</script>
    
@endsection