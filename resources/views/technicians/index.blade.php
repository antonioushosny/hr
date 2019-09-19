@extends('layouts.index')
@section('style')
<link rel="stylesheet" href="{{ asset('rtl/plugins/iCheck/square/blue.css') }}">
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script> -->
<style>
.zmdi {
    font-size: 15px !important;
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
                    <li class="breadcrumb-item"><a href="javascript:void(0);"><i class="zmdi zmdi-accounts-alt"></i> {{__('admin.technicians')}}</a></li>
                </ul>
            </div>
        </div>
    </div>

     
    <div class="container-fluid">
        <!-- Exportable Table -->
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                {!! Form::open(['route'=>['techniciansdeleteall'],'method'=>'post','autocomplete'=>'off', 'id'=>'technicians_form' ])!!}

                        <div class="header">
                            <h2><strong>{{trans('admin.'.$title)}}</strong> </h2>
                            <ul class="header-dropdown">
                                </li>
                                    <a href="{{route('techniciansmaps')}}" class=" add-modal btn btn-primary btn-round" title="{{trans('admin.show_technician')}}">
                                        {{trans('admin.show_technician')}}
                                    </a>
                                </li>

                                @can('technical_create')
                                </li>
                                    <a href="{{route('addtechnician')}}" class=" add-modal btn btn-success btn-round" title="{{trans('admin.add_user')}}">
                                        {{trans('admin.add_technician')}}
                                    </a>
                                </li>
                                @endcan
                                @can('technical_edit')
                                </li>
                                    <a href="javascript:void(0);" class=" deleteall-modal btn btn-danger btn-round" title="{{trans('admin.deleteall')}}">
                                        {{trans('admin.deleteall')}}
                                    </a>
                                </li>  
                                @endcan                              
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
                                        <th>{{trans('admin.name')}}</th>
                                        <th>{{trans('admin.mobile')}}</th>
                                        <th>{{trans('admin.email')}}</th>
                                        <!-- <th>{{trans('admin.city')}}</th>
                                        <th>{{trans('admin.area')}}</th> -->
                                        <th>{{trans('admin.renewal_date')}}</th>
                                        <th>{{trans('admin.image')}}</th>
                                        <th>{{trans('admin.status')}}</th>
                                        <th>{{trans('admin.actions')}}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($technicians as $data)
                                    <tr class="item{{$data->id}}">
                                        <td> 
                                            <input type="checkbox" name="ids[]" value={{$data->id}} class="check icheck">
                                        </td>
                                        <td>{{ $data->name }}</td>
                                        <td>{{ $data->mobile }}</td>
                                        <td>{{ $data->email }}</td>          
                                        <!-- @if($data->City)
                                            @if($lang == 'ar')
                                                <td>{{ $data->City->name_ar }}</td> 
                                            @else 
                                                <td>{{ $data->City->name_en }}</td> 
                                            @endif
                                        @else 
                                            <td> </td> 
                                        @endif
                                        @if($data->Area)
                                            @if($lang == 'ar')
                                                <td>{{ $data->Area->name_ar }}</td> 
                                            @else 
                                                <td>{{ $data->Area->name_en }}</td> 
                                            @endif
                                        @else 
                                            <td> </td> 
                                        @endif -->
                                        <td>{{ $data->technician->renewal_date }}</td>   
                                        @if($data->image)
                                            <td><img src="{{asset('img/').'/'.$data->image }}" width="50px" height="50px"></td>
                                        @else 
                                            <td><img src="{{asset('images/default.png') }}" width="50px" height="50px"></td>
                                        @endif
                                        @can('technical_edit')
                                        @if($data->status == 'active')
                                            <td style="text-align:user">
                                                <a href="{{route('techicianstatus',$data->id)}}" class="btn btn-success waves-effect waves-float waves-green  " title="{{trans('admin.active')}}"><span  >{{ trans('admin.active')}}</span></a>
                                                
                                            </td> 
                                        @elseif($data->status == 'not_active')
                                            <td style="text-align:user">
                                                <a href="{{route('techicianstatus',$data->id)}}" class="btn btn-danger waves-effect waves-float waves-green  "title="{{trans('admin.active')}}"><span >{{ trans('admin.not_active')}}</span></a>
                                                
                                            </td> 
                                        @endif

                                        @else
                                            @if($data->status == 'active')
                                                <td style="text-align:user">
                                                    <mark class="btn btn-success waves-effect waves-float waves-green  " title="{{trans('admin.active')}}"><span  >{{ trans('admin.active')}}</span></mark>
                                                    
                                                </td> 
                                            @elseif($data->status == 'not_active')
                                                <td style="text-align:user">
                                                    <mark class="btn btn-danger waves-effect waves-float waves-green  "title="{{trans('admin.active')}}"><span >{{ trans('admin.not_active')}}</span></mark>
                                                    
                                                </td> 
                                            @endif

                                        @endcan
                                        <td>
                                            @can('technical_edit')
                                            <a href="{{route('edittechnician',$data->id)}}" class="btn btn-info waves-effect waves-float waves-green btn-round " title="{{trans('admin.edit')}}"><i class="zmdi zmdi-edit"></i></a> 
                                            
                                            <a href="javascript:void(0);" class=" delete-modal btn btn-danger waves-effect waves-float waves-red btn-round " title="{{trans('admin.delete')}}" data-id="{{$data->id}}" ><i class="zmdi zmdi-delete"></i></a>
                                            @endcan

                                            @can('order_list')
                                            <a href="{{route('techniciansorders',$data->id)}}" class="btn btn-secondary waves-effect waves-float waves-green btn-round " title="{{trans('admin.showorders')}}"><i class="zmdi zmdi-format-list-numbered"></i></a> 
                                            @endcan

                                            @can('rate_list')
                                            <a href="{{route('techniciansratings',$data->id)}}" class="btn btn-warning waves-effect waves-float waves-green btn-round " title="{{trans('admin.ratings')}}"><i class="zmdi zmdi-star"></i></a>  
                                            @endcan
                                            
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
                    url: "<?php echo url('/')?>/technicians/delete/" + id,
                    data: {
                        '_token': $('input[name=_token]').val(),
                    },
                    success: function(data) {
                        $('.item' + data['id']).remove();
                        swal(Deleted, has_been_deleted, "success");
                        //location.reload();
                        window.location.replace("{{route('technicians')}}");
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
                        url: '{{ URL::route("techniciansdeleteall") }}',
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
