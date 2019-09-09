@extends('layouts.index')

@section('style')
<link rel="stylesheet" href="{{ asset('rtl/plugins/iCheck/square/blue.css') }}">
 
@endsection
@section('content')
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
                        <li class="breadcrumb-item"><a href="javascript:void(0);"><i class="zmdi zmdi-accounts-add"></i> {{__('admin.roles')}}</a></li>
                    </ul>
                </div>
            </div>
        </div>
        

        <div class="container-fluid">
            <!-- Exportable Table -->
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                    {!! Form::open(['route'=>['rolesdeleteall'],'method'=>'post','autocomplete'=>'off', 'id'=>'roless_form' ])!!}
    
                        <div class="header">
                            <h2><strong>{{trans('admin.'.$title)}}</strong> </h2>
                            <ul class="header-dropdown">
                                @can('role_create')
                                </li>
                                    <a href="{{route('roles.create')}}" class=" add-modal btn btn-success btn-round" title="{{trans('admin.add_role')}}">
                                        {{trans('admin.add_role')}}
                                    </a>
                                </li>  
                                @endcan
                                @can('role_delete')
                                </li>
                                    <a href="javascript:void(0);" class=" deleteall-modal btn btn-danger btn-round" title="{{trans('admin.deleteall')}}">
                                        {{trans('admin.deleteall')}}
                                    </a>
                                </li>     
                                @endcan

                            </ul>
                        </div>
                        @if ($message = Session::get('success'))

                            <div class="alert alert-success">
                    
                                <p>{{ $message }}</p>
                    
                            </div>
                
                        @endif
                        <div class="body">
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
                                        <th>{{trans('admin.no')}}</th>
                                        <th>{{trans('admin.Name')}}</th>
                                        <th>{{trans('admin.actions')}}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                      
                                           
                                    @foreach ($roles as $key => $role)
                                
                                    <tr class="item{{$role->id}}">
                                        {{--  @if($role->id != '1')  --}}
                                            <td> 
                                                <input type="checkbox" name="ids[]" value={{$role->id}} class="check icheck">
                                            </td>
                                            <td>{{ ++$i }}</td>
                                    
                                            <td>{{ $role->name }}</td>
                                    
                                            <td>
                                                @can('role_edit')
                                                    <a href="{{route('roles.edit',$role->id)}}" class="btn btn-info waves-effect waves-float waves-blue btn-round " title="{{trans('admin.edit')}}"><i class="zmdi zmdi-edit"></i></a>  
                                                @endcan
                                                @can('role_delete')
                                                    <a href="javascript:void(0);" class=" delete-modal btn btn-danger waves-effect waves-float waves-red btn-round " title="{{trans('admin.delete')}}" data-id="{{$role->id}}" ><i class="zmdi zmdi-delete"></i></a>
                                                @endcan
                                            </td>
                                        {{--  @endif  --}}
                                
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
                    url: "<?php echo url('/')?>/roles/delete/" + id,
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
                        url: '{{ URL::route("rolesdeleteall") }}',
                        data:  new FormData($("#roless_form")[0]),
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
