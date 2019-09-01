@extends('layouts.index')
 @section('style')
<link rel="stylesheet" href="{{ asset('rtl/plugins/iCheck/square/blue.css') }}">
 
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
                    <li class="breadcrumb-item"><a href="{{route('roles.index')}}"><i class="zmdi zmdi-accounts-add"></i> {{__('admin.roles')}}</a></li>
                    <li class="breadcrumb-item "><a href="javascript:void(0);">{{__('admin.add_role')}}</a></li>
                    
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
                        <h2><strong>{{trans('admin.'.$title)}}</strong> {{trans('admin.add_role')}}  </h2>
                        
                    </div> 
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="body row">
                        <div class="col-lg-6">
                            {!! Form::open(['route'=>['roles.store'],'method'=>'post','autocomplete'=>'off', 'id'=>'form_validation', 'enctype'=>'multipart/form-data' ])!!} 
                            
                                <div class="form-group form-float">
                                   
                                    {!! Form::text('name', null, array('placeholder' => __('admin.Name'),'class' => 'form-control')) !!}
                                    <label id="name-error" class="error" for="name" style="">  </label>
                                </div>
                                <div class="form-group form-float">
                                    <input type="checkbox" class="checkbox icheck" id="check-all" />
                                    <label>{{__('admin.check_all')}}  </label>
                                 

                                </div>

                                <div class="form-group form-float">
                                    <div class="form-group">
                                        <strong>{{__('admin.permissions')}}:</strong>
                                        <br/>
                                        <label id="permission-error" class="error" for="name" style="">  </label>
                                    <table>
                                        @foreach($permission->chunk(4) as $chunk)
                                        <tr>

                                            @foreach($chunk as $value)
                                            <td>
                                                <label>{{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name check icheck')) }}
                                                {{ __('role.'.$value->name) }}</label>
                                            </td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                    </table>
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
<script src="{{ asset('rtl/plugins/iCheck/icheck.min.js') }}"></script> 

<script>
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
            
        $('#check-all').on('ifChecked', function(event) {
            $('.check').iCheck('check');
    
        });
        $('#check-all').on('ifUnchecked', function(event) {
            $('.check').iCheck('uncheck');
            
        });
        // Removed the checked state from "All" if any checkbox is unchecked
        $('#check-all').on('ifChanged', function(event){
            if(!this.changed) {
                this.changed=true;
                $('#check-all').iCheck('check');
            
            } else {
                this.changed=false;
                $('#check-all').iCheck('uncheck');
        
            }
            $('#check-all').iCheck('update');
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
              url: '{{ URL::route("roles.store") }}',
              data:  new FormData($("#form_validation")[0]),
              processData: false,
              contentType: false,
               
              success: function(data) {
                  if ((data.errors)) {              
                    $(':input[type="submit"]').prop('disabled', false);          
                        if (data.errors.name) {
                            $('#name-error').css('display', 'inline-block');
                            $('#name-error').text(data.errors.name);
                        }
                        if (data.errors.permission) {
                            $('#permission-error').css('display', 'inline-block');
                            $('#permission-error').text(data.errors.permission);
                        }
                  } else {
                        window.location.replace("{{route('roles.index')}}");

                     }
            },
          });
        });

</script>
    
@endsection
