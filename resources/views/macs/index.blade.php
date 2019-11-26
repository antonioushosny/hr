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
                    <li class="breadcrumb-item"><a href="javascript:void(0);"><i class="zmdi zmdi-face"></i> {{__('admin.macs')}}</a></li>
                </ul>
            </div>
        </div>
    </div>

     
    <div class="container-fluid">
        
        <!-- Exportable Table -->
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                {!! Form::open(['route'=>['macsdeleteall'],'method'=>'post','autocomplete'=>'off', 'id'=>'macss_form' ])!!}

                        <div class="header">
                            <h2><strong>{{trans('admin.'.$title)}}</strong> </h2>
                            <ul class="header-dropdown">
      
                                </li>
                                    <a href="javascript:void(0);" class=" deleteall-modal btn btn-danger btn-round" title="{{trans('admin.deleteall')}}">
                                        {{trans('admin.deleteall')}}
                                    </a>
                                </li>    
                             </ul>
                        </div>
                        <div class="body">
                        <div class="table-responsive">
                            @if($lang == 'ar')
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable-ar">
                            @else 
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                            @endif
                                <thead>
                                    <tr>
                                           
                                        <th>
                                            <input type="checkbox" class="checkbox icheck" id="check-all" />
                                        </th>
                                         <th>{{trans('admin.employee')}}</th>
                                        <th>{{trans('admin.mac_address')}}</th>
                                        <th>{{trans('admin.reason')}}</th>
                                        <th>{{trans('admin.status')}}</th>
                                        <th>{{trans('admin.action')}}</th>
                                     </tr>
                                </thead>

                                <tbody>
                                    @foreach ($macs as $data)
                                     <tr class="item{{$data->id}}">
                                        <td> 
                                            <input type="checkbox" name="ids[]" value={{$data->id}} class="check icheck">
                                        </td>
                                      
                                        @if($data->employee)
                                        <td>{{ $data->employee->name }}</td>
                                        @else
                                        <td></td>
                                        @endif
                                        <td>{{ $data->mac_address }}</td>
                                        <td>{{ $data->reason }}  </td>
                                        <td>{{ $data->status }}</td>
                                        
        
                                        <td>
                                            @if($data->status == 'pending')
                                            <a href="{{ route('acceptmac',$data->id)}}" class="btn btn-warning waves-effect waves-float waves-green btn-round " title="{{trans('admin.accept')}}">{{trans('admin.accept')}}</a> 

                                            <a href="{{ route('rejectmac',$data->id)}}" class="btn btn-danger waves-effect waves-float waves-green btn-round " title="{{trans('admin.reject')}}">{{trans('admin.reject')}}</a> 
                                            @endif
                                            <a href="javascript:void(0);" class=" delete-modal btn btn-danger waves-effect waves-float waves-red btn-round " title="{{trans('admin.delete')}}" data-id="{{$data->id}}" ><i class="zmdi zmdi-delete"></i></a>
 
                                        </td>
                    
                                    </tr>
                                     @endforeach
                                </tbody>
                            </table>
                            </div>
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
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script> -->

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
    $('#check-all').on('ifmacd', function(event){
        if(!this.macd) {
            this.macd=true;
            $('#check-all').iCheck('check');
        
        } else {
            this.macd=false;
            $('#check-all').iCheck('uncheck');
    
        }
        $('#check-all').iCheck('update');
    });
    
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
                        url: "<?php echo url('/')?>/macs/delete/" + id,
                        data: {
                            '_token': $('input[name=_token]').val(),
                        },
                        success: function(data) {
                            $('.item' + data['id']).remove();
                            swal(Deleted, has_been_deleted, "success");
                            window.location.replace("{{route('macs')}}");

                        }
                    });
                } else {
                    swal(Cancelled, file_is_safe, "error");
                }
            })
    });
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
                        url: '{{ URL::route("macsdeleteall") }}',
                        data:  new FormData($("#macss_form")[0]),
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            for (var i=0;i<data.length;i++){
                                $('.item' + data[i]).remove();
                            }
                            swal(Deleted, has_been_deleted, "success");
                            window.location.replace("{{route('macs')}}");

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
 
    });
    
</script>
    
@endsection
