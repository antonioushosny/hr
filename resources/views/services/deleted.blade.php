@extends('layouts.index')
@section('style')
<link rel="stylesheet" href="{{ asset('rtl/plugins/iCheck/square/blue.css') }}">
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script> -->

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
                    <li class="breadcrumb-item"><a href="{{route('services')}}"><i class="zmdi zmdi-washing-machine"></i> {{__('admin.services')}}</a></li>
                    <li class="breadcrumb-item "><a href="javascript:void(0);">{{__('admin.services_deleted')}}</a></li>
                </ul>
            </div>
        </div>
    </div>

     
    <div class="container-fluid">
        <!-- Exportable Table -->
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                {!! Form::open(['route'=>['servicesrestoreall'],'method'=>'post','autocomplete'=>'off', 'id'=>'servicess_form' ])!!}

                        <div class="header">
                            <h2><strong>{{__('admin.services')}}</strong> {{trans('admin.'.$title)}}</h2>
                            <ul class="header-dropdown">
                            @can('service_edit')
                                </li>
                                    <a href="javascript:void(0);" class=" deleteall-modal btn btn-success btn-round" title="{{trans('admin.restoreall')}}">
                                        {{trans('admin.restoreall')}}
                                    </a>
                                </li>     
                                @endcan                   
                            </ul>
                        </div>
                        <div class="body">
                            @if($lang == 'ar')
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable-ar">
                            @else 
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                            @endif
                                <thead>
                                    <tr>
                                        @can('service_edit')
                                        <th>
                                            <input type="checkbox" class="checkbox icheck" id="check-all" />
                                        </th>@endcan
                                        <th>{{trans('admin.name_ar')}}</th>
                                        <th>{{trans('admin.name_en')}}</th>
                                        <th>{{trans('admin.image')}}</th>
                                        <th>{{trans('admin.status')}}</th>
                                        <th>{{trans('admin.counttechnicians')}}</th>
                                        <th>{{trans('admin.deleted_at')}}</th>
                                        <th>{{trans('admin.actions')}}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($services as $data)
                                    <tr class="item{{$data->id}}">
                                        @can('service_edit')
                                        <td> 
                                            <input type="checkbox" name="ids[]" value={{$data->id}} class="check icheck">
                                        </td>
                                        @endcan
                                        <td>{{ $data->name_ar }}</td>
                                        <td>{{ $data->name_en }}</td>
                                        @if($data->image)
                                        <td><img src="{{asset('img/').'/'.$data->image }}" width="50px" height="50px"></td>
                                        @else 
                                        <td><img src="{{asset('images/default.png') }}" width="50px" height="50px"></td>
                                        @endif
                                        @if($data->status == 'deleted')
                                        <td style="text-align:center"><span  class="col-green">{{ trans('admin.deleted')}}</span></td> 
                                        @elseif($data->status == 'not_active')
                                        <td style="text-align:center"><span  class="col-red">{{ trans('admin.not_active')}}</span></td> 
                                        @endif

                                        <td>{{count($data->fannies)}}</td>

                                        <td>{{ $data->deleted_at }}</td>
                                        <td>
                                            @can('service_edit')
                                            <!-- <a href="{{route('editservice',$data->id)}}" class="btn btn-info waves-effect waves-float waves-green btn-round " title="{{trans('admin.edit')}}"><i class="zmdi zmdi-edit"></i></a>
                                           
                                            <a href="{{route('serviceshowtechnicians',$data->id)}}" class="btn btn-secondary waves-effect waves-float waves-green btn-round " title="{{trans('admin.showtechnicians')}}"><i class="zmdi zmdi-format-list-numbered"></i></a>  -->
                                            
                                            <a href="javascript:void(0);" class=" delete-modal btn btn-success waves-effect waves-float waves-red btn-round " title="{{trans('admin.restore')}}" data-id="{{$data->id}}" ><i class="zmdi zmdi-time-restore"></i></a>
                                            
                                            @endcan
                                        </td>
                                    </tr>
                                    
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
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

    $(document).on('click', '.delete-modal', function() {

        titlet ="{{__('admin.alert_title')}}" ;
        textt ="{{__('admin.alert_text2')}}" ;
        typet ="{{__('admin.warning')}}" ;
        confirmButtonTextt ="{{__('admin.confirmButtonText2')}}" ;
        cancelButtonTextt ="{{__('admin.cancelButtonText')}}" ;
        Deleted ="{{__('admin.Restored!')}}" ;
        has_been_deleted = "{{__('admin.has_been_restored')}}" ;
        success ="{{__('admin.success')}}" ;
        Cancelled ="{{__('admin.Cancelled')}}" ;
        file_is_safe ="{{__('admin.file_is_safe')}}" ;
        no_elemnet_selected ="{{__('admin.no_elemnet_selected')}}" ;
        error ="{{__('admin.error')}}" ;
        id = $(this).data('id') ;
        swal({
            title: titlet,
            text: textt,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: confirmButtonTextt,
            cancelButtonText: cancelButtonTextt,
            closeOnConfirm: false,
            closeOnCancel: false
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    type: 'GET',
                    url: "<?php echo url('/')?>/services/restore/" + id,
                    data: {
                        '_token': $('input[name=_token]').val(),
                    },
                    success: function(data) {
                        $('.item' + data['id']).remove();
                        swal(Deleted, has_been_deleted, "success");
                        window.location.replace("{{route('services')}}");
                    }
                });
            } else {
                swal(Cancelled, file_is_safe, "error");
            }
        });
        // $('#deleteModal').modal('show');
        // id = $('#id_delete').val();
    });
    $(document).on('click', '.deleteall-modal', function() {
        titlet ="{{__('admin.alert_title')}}" ;
        textt ="{{__('admin.alert_text2')}}" ;
        typet ="{{__('admin.warning')}}" ;
        confirmButtonTextt ="{{__('admin.confirmButtonText2')}}" ;
        cancelButtonTextt ="{{__('admin.cancelButtonText')}}" ;
        Deleted ="{{__('admin.Restored!')}}" ;
        has_been_deleted = "{{__('admin.has_been_restored')}}" ;
        success ="{{__('admin.success')}}" ;
        Cancelled ="{{__('admin.Cancelled')}}" ;
        file_is_safe ="{{__('admin.file_is_safe')}}" ;
        no_elemnet_selected ="{{__('admin.no_elemnet_selected')}}" ;
        error ="{{__('admin.error')}}" ;
        swal({
            title: titlet,
            text: textt,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: confirmButtonTextt,
            cancelButtonText: cancelButtonTextt,
            closeOnConfirm: false,
            closeOnCancel: false
        }, function (isConfirm) {
            if (isConfirm) {
                var choices = [];
                checkboxes = document.getElementsByName('ids[]');
                for (var i=0;i<checkboxes.length;i++){
                    if ( checkboxes[i].checked ) {
                    choices.push(checkboxes[i].value);
                    }
                }
                if(choices.length >= 1){
                    var form = $(this);
                    $.ajax({
                        type: 'POST',
                        url: '{{ URL::route("servicesrestoreall") }}',
                        data:  new FormData($("#servicess_form")[0]),
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            for (var i=0;i<data.length;i++){
                                $('.item' + data[i]).remove();
                            }
                            swal(Deleted, has_been_deleted, "success");
                            window.location.replace("{{route('services')}}");
                        },
                    });
                }
                else{
                    swal(Cancelled, no_elemnet_selected, "error");
                }

            } else {
                swal(Cancelled, file_is_safe, "error");
            }
        });
        // $('#deleteModal').modal('show');
        // id = $('#id_delete').val();
    });
    
</script>
    
@endsection
