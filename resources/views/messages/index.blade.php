@extends('layouts.index')
@section('style')
<style>
    .hidden{
        display:none;
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
                    <li class="breadcrumb-item "><a href="javascript:void(0);">{{  __('admin.messages') }} </a></li>
                    
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
                            <h2><strong>{{  __('admin.messages') }}  </h2>
                            
                        </div>
                        <div class="body">
                            {!! Form::open(['route'=>['send_messages'],'method'=>'post','autocomplete'=>'off', 'id'=>'form_validation', 'enctype'=>'multipart/form-data' ])!!} 

                                <div class="row">
                                    <div class="col-md-1">{{ __('admin.title') }}</div>
                                    <div class="col-md-11">
                                        <!-- for title -->
                                        <div class="form-group form-float">
                                            <input type="text"  class="form-control" placeholder="{{__('admin.placeholder_title')}}" name="title" required>
                                            <label id="title-error" class="error" for="title" style="">  </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-1">{{ __('admin.message') }}</div>

                                    <div class="col-md-11">
                                        <div class="form-group form-float">
                                            <textarea name="message" cols="30" rows="5" placeholder="{{__('admin.placeholder_message')}}" class="form-control no-resize" required></textarea>
                                        </div>
                                        
                                    </div>
                                    
                                    
                                </div>
                                
                                
                                <div class="form-group">
                                    <div class="radio inlineblock m-r-20">
                                        <input type="radio" name="for" id="sendforall_field" class="with-gap" value="all" checked > 
                                        <label for="sendforall_field">{{__('admin.sendforall')}}</label>
                                    </div>
                                    <div class="radio inlineblock">
                                        <input type="radio" name="for" id="all_fannies" class="with-gap" value="all_fannies" <?php echo ( isset($data->status) && $data->status == 'all_fannies') ? "checked=''" : ""; ?> >
                                        <label for="all_fannies">{{__('admin.all_fannies')}}</label>
                                    </div>
                                    <div class="radio inlineblock">
                                        <input type="radio" name="for" id="all_users" class="with-gap" value="all_users" <?php echo ( isset($data->status) && $data->status == 'all_users') ? "checked=''" : ""; ?> >
                                        <label for="all_users">{{__('admin.all_users')}}</label>
                                    </div>                                 
                                    <div class="radio inlineblock">
                                        <input type="radio" name="for" id="send_field" class="with-gap" value="not_all" <?php echo ( isset($data->status) && $data->status == 'not_active') ? "checked=''" : ""; ?> >
                                        <label for="send_field">{{__('admin.send_spec')}}</label>
                                    </div>

                                    
                                </div>
                                <div class="row  clients hidden">
                                    <div class="col-md-1">{{ __('admin.clients') }}</div>
                                    <div class= "form-group form-float col-md-11">
                                        {!! Form::select('ids[]',[],'',['class'=>'form-control show-tick' ,'placeholder' =>trans('admin.choose'),'id' => 'clients','multiple'=>true]) !!}
                                            <label id="ids-error" class="error" for="ids[]" style="">  </label>
                                    </div> 
                                </div> 
                                 


                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn btn-raised btn-primary btn-round waves-effect" type="submit">{{__('admin.send')}}</button>
                            </form>
                        </div>
                </div>
            </div>
        </div>
  
    </div>

</section>
  
@endsection 

@section('script')


<script src="{{ asset('assets/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('assets/js/pages/forms/editors.js') }}"></script>


<script>
        @foreach($clients as $client)

            $("#clients").append($('<option>', {
                    value: {{$client->id}},
                    text: '{{$client->name}}'
                }));
        @endforeach
 

        $('#send_field').on('click', function(event) {
            $('.clients').removeClass('hidden'); 
        });
        $('#sendforall_field').on('click', function(event) {
            $('.clients').addClass('hidden');
        });
        $('#all_fannies').on('click', function(event) {
            $('.clients').addClass('hidden');
        });
        $('#all_users').on('click', function(event) {
            $('.clients').addClass('hidden');
        });
        
    //this for add new record
    $("#form_validation").submit(function(e){
           {{--  $('#addModal').modal('hide');  --}}
           $('.add').disabled =true;
          e.preventDefault();
          var form = $(this);
        //    openModal();
          $.ajax({
              type: 'POST',
              url: '{{ URL::route("send_messages") }}',
              data:  new FormData($("#form_validation")[0]),
              processData: false,
              contentType: false,
               
              success: function(data) {
                  if ((data.errors)) {                        
                        if (data.errors.title) {
                            $('#title-error').css('display', 'inline-block');
                            $('#title-error').text(data.errors.title);
                        }
                        if (data.errors.message) {
                            $('#message-error').css('display', 'inline-block');
                            $('#message-error').text(data.errors.message);
                        }
                        if (data.errors.for) {
                            $('#for-error').css('display', 'inline-block');
                            $('#for-error').text(data.errors.for);
                        }
                        
                  } else {
                    location.reload();

                     }
            },
          });
        });

</script>
    
@endsection
