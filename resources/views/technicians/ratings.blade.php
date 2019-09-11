@extends('layouts.index')
@section('style')
<link rel="stylesheet" href="{{ asset('rtl/plugins/iCheck/square/blue.css') }}">
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script> -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

<style>
.checked {
  color: orange;
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
                    <li class="breadcrumb-item"><a href="{{route('technicians')}}"><i class="zmdi zmdi-accounts-alt"></i> {{__('admin.technicians')}}</a></li>
                    <li class="breadcrumb-item "><a href="javascript:void(0);">{{__('admin.rating_technician')}}</a></li>
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
                            <h2><strong>{{trans('admin.'.$title)}}</strong> {{trans('admin.rating_technician')}} ({{$user->name}})</h2>
                            <ul class="header-dropdown">
                                                            
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
                                        
                                        <th>{{trans('admin.image')}}</th>
                                        <th>{{trans('admin.user_name')}}</th>
                                        <th>{{trans('admin.av_rate')}}</th>
                                        <th>{{trans('admin.contact_rate')}}</th>

                                        <th>{{trans('admin.time_rate')}}</th>
                                        <th>{{trans('admin.work_rate')}}</th>
                                        <th>{{trans('admin.cost_rate')}}</th>
                                        <th>{{trans('admin.general_character')}}</th>
                                        <th>{{trans('admin.notes')}}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($ratings as $data)
                                    <tr class="item{{$data->id}}">
                                       
                                        @if($data->image)
                                            <td><img src="{{asset('img/').'/'.$data->evaluatorfrom->image }}" width="50px" height="50px"></td>
                                        @else 
                                            <td><img src="{{asset('images/default.png') }}" width="50px" height="50px"></td>
                                        @endif
                                        <td>{{ $data->evaluatorfrom->name }}</td>
                                        <td>
                                        
                                        @foreach(range(1,5) as $i)
                                            @if($data->rate >0)
                                                @if($data->rate >0.5)
                                                    <i class="fa fa-star checked"></i>
                                                @else
                                                    <i class="fa fa-star-half-o"></i>
                                                @endif
                                            @else
                                                <i class="fa  fa-star-o"></i>
                                            @endif
                                            <?php $data->rate--; ?>
                                            @endforeach 
                                        
                                        
                                        </td>
                                        <td>
                                        @foreach(range(1,5) as $i)
                                            @if($data->contact_rate >0)
                                                @if($data->contact_rate >0.5)
                                                    <i class="fa fa-star checked"></i>
                                                @else
                                                    <i class="fa fa-star-half-o"></i>
                                                @endif
                                            @else
                                                <i class="fa  fa-star-o"></i>
                                            @endif
                                            <?php $data->contact_rate--; ?>
                                            @endforeach 
                                       
                                        </td>
                                        <td>
                                        @foreach(range(1,5) as $i)
                                            @if($data->time_rate >0)
                                                @if($data->time_rate >0.5)
                                                    <i class="fa fa-star checked"></i>
                                                @else
                                                    <i class="fa fa-star-half-o"></i>
                                                @endif
                                            @else
                                                <i class="fa  fa-star-o"></i>
                                            @endif
                                            <?php $data->time_rate--; ?>
                                            @endforeach
                                                                                                            
                                        
                                        </td>          
                                        
                                        <td>
                                        @foreach(range(1,5) as $i)
                                            @if($data->work_rate >0)
                                                @if($data->work_rate >0.5)
                                                    <i class="fa fa-star checked"></i>
                                                @else
                                                    <i class="fa fa-star-half-o"></i>
                                                @endif
                                            @else
                                                <i class="fa  fa-star-o"></i>
                                            @endif
                                            <?php $data->work_rate--; ?>
                                            @endforeach
                                        
                                        
                                        </td>
                                        <td>
                                        @foreach(range(1,5) as $i)
                                            @if($data->cost_rate >0)
                                                @if($data->cost_rate >0.5)
                                                    <i class="fa fa-star checked"></i>
                                                @else
                                                    <i class="fa fa-star-half-o"></i>
                                                @endif
                                            @else
                                                <i class="fa  fa-star-o"></i>
                                            @endif
                                            <?php $data->cost_rate--; ?>
                                            @endforeach
                                        
                                        
                                        </td>   
                                        <td>
                                        
                                        @foreach(range(1,5) as $i)
                                            @if($data->general_character >0)
                                                @if($data->general_character >0.5)
                                                    <i class="fa fa-star checked"></i>
                                                @else
                                                    <i class="fa fa-star-half-o"></i>
                                                @endif
                                            @else
                                                <i class="fa  fa-star-o"></i>
                                            @endif
                                            <?php $data->general_character--; ?>
                                            @endforeach
                                        
                                        
                                        
                                        </td>
                                        <td>{{$data->notes}}</td>
                                        
                                    </tr>
                                    
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
        </div>
  
    </div>
</section>
  
@endsection 

@section('script')

<script src="{{ asset('rtl/plugins/iCheck/icheck.min.js') }}"></script> 
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.2/js/star-rating.min.js"></script> -->

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
    //this for delete
    $(document).on('click', '.delete-modal', function() {

        titlet ="{{__('admin.alert_title')}}" ;
        textt ="{{__('admin.alert_text')}}" ;
        typet ="{{__('admin.warning')}}" ;
        confirmButtonTextt ="{{__('admin.confirmButtonText')}}" ;
        cancelButtonTextt ="{{__('admin.cancelButtonText')}}" ;
        Deleted ="{{__('admin.Deleted!')}}" ;
        has_been_deleted = "{{__('admin.has_been_deleted')}}" ;
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
                    url: "<?php echo url('/')?>/users/delete/" + id,
                    data: {
                        '_token': $('input[name=_token]').val(),
                    },
                    success: function(data) {
                        $('.item' + data['id']).remove();
                        swal(Deleted, has_been_deleted, "success");
                        location.reload();
                    }
                });
            } else {
                swal(Cancelled, file_is_safe, "error");
            }
        });
        // $('#deleteModal').modal('show');
        // id = $('#id_delete').val();
    });
    //this for delete all selected
    $(document).on('click', '.deleteall-modal', function() {

        titlet ="{{__('admin.alert_title')}}" ;
        textt ="{{__('admin.alert_text')}}" ;
        typet ="{{__('admin.warning')}}" ;
        confirmButtonTextt ="{{__('admin.confirmButtonText')}}" ;
        cancelButtonTextt ="{{__('admin.cancelButtonText')}}" ;
        Deleted ="{{__('admin.Deleted!')}}" ;
        has_been_deleted = "{{__('admin.has_been_deleted')}}" ;
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
                        url: '{{ URL::route("usersdeleteall") }}',
                        data:  new FormData($("#technicians_form")[0]),
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            for (var i=0;i<data.length;i++){
                                $('.item' + data[i]).remove();
                            }
                            swal(Deleted, has_been_deleted, "success");
                            location.reload();
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
