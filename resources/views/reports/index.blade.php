@extends('layouts.index')
@section('style')
<link rel="stylesheet" href="{{ asset('rtl/plugins/iCheck/square/blue.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('assets/plugins/jquery-spinner/css/bootstrap-spinner.css') }}">

<style>
    .select2-container--default .select2-selection--single {
        background-color: #fff;
        border: 1.6px solid #aaa;
        border-radius: 13px;
        max-width: 97%;
        /* border: 1px solid; */
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
                    <li class="breadcrumb-item"><a href="javascript:void(0);"><i class="zmdi zmdi-accounts-add"></i> {{__('admin.reports')}}</a></li>
                </ul>
            </div>
        </div>
    </div>

     
    <div class="container-fluid">

            <div class="row clearfix">
                    <div class="col-lg-12">
                        <div class="card">
                       
        
                                <div class="header">
                                    <h2><strong>{{trans('admin.filter')}}</strong>   </h2>
                                    
                                </div>
                                <div class="body">
                                    {!! Form::open(['route'=>['reportfilter'],'method'=>'post','autocomplete'=>'off', 'id'=>'form_validation', 'enctype'=>'multipart/form-data' ])!!} 
        
                                        <div class="row">
                                            @if(Auth::user()->role == 'admin' )
                                                <div class="col-md-1">
                                                    {{__('admin.provider')}}
                                                </div> 
                                                <div class="col-md-2"> 
                                                    <!-- for provider_id -->
                                                    <div class= "form-group form-float">
                                                        {!! Form::select('provider_id',$providers
                                                            ,!isset($provider_id)?null:$provider_id,['class'=>'form-control show-tick select2' ,'placeholder' =>trans('admin.all')]) !!}
                                                        <label id="provider_id-error" class="error" for="provider_id" style="">  </label>
                                                    </div>
                                                </div>
                                            @elseif(Auth::user()->role == 'provider' )
                                                <div class="col-md-1">
                                                    {{__('admin.center')}}
                                                </div> 
                                                <div class="col-md-2">
                                                    <!-- for center_id -->
                                                    <div class= "form-group form-float">
                                                        {!! Form::select('center_id',$centers
                                                            ,!isset($center_id)?null:$center_id,['class'=>'form-control show-tick select2' ,'placeholder' =>trans('admin.all')]) !!}
                                                        <label id="center_id-error" class="error" for="center_id" style="">  </label>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-md-1">
                                                    {{__('admin.driver')}}
                                                </div> 
                                                <div class="col-md-2">
                                                    <!-- for driver_id -->
                                                    <div class= "form-group form-float">
                                                        {!! Form::select('driver_id',$drivers
                                                            ,!isset($driver_id)?null:$driver_id,['class'=>'form-control show-tick select2' ,'placeholder' =>trans('admin.all')]) !!}
                                                        <label id="driver_id-error" class="error" for="driver_id" style="">  </label>
                                                    </div>
                                                
                                                </div>
                                            @endif
                                            
                                            {{--  <div class="col-md-1">
                                                {{__('admin.city')}}
                                            </div> 
                                            <div class="col-md-3"> 
                                                <!-- for city -->
                                                <div class= "form-group form-float">
                                                    {!! Form::select('city_id',$cities
                                                        ,!isset($city_id)?null:$city_id,['class'=>'form-control show-tick select2' ,'id'=>'city_id','placeholder' =>trans('admin.all')]) !!}
                                                    <label id="city_id-error" class="error" for="city_id" style="">  </label>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                {{__('admin.area')}}
                                            </div> 
                                            <div class="col-md-3">
                                                <!-- for area -->
                                                <div class= "form-group form-float area_id_div ">
                                                    {!! Form::select('area_id',[ ]
                                                        ,!isset($area_id)?null:$area_id,['class'=>'form-control show-tick select2' ,'id'=>'area_id','placeholder' =>trans('admin.all')]) !!}
                                                    <label id="area_id-error" class="error" for="area_id" style="">  </label>
                                                </div>
                                            </div>  --}}
                                            <div class="col-md-1">
                                                {{__('admin.status')}}
                                            </div> 
                                            <div class="col-md-2">
                                                <!-- for status -->
                                                <div class= "form-group form-float">
                                                    {!! Form::select('status',$status
                                                        ,!isset($stat)?null:$stat,['class'=>'form-control show-tick select2' ,'placeholder' =>trans('admin.all')]) !!}
                                                    <label id="status-error" class="error" for="status" style="">  </label>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                {{__('admin.date_from')}}
                                            </div> 
                                            <div class="col-md-2">
                                                <!-- for date -->
                                                <div class= "form-group form-float  ">
                                                    {!! Form::date('date_from',!isset($date_from)?null:$date_from,['class'=>'form-control ' ,'id'=>'date_from','placeholder' =>trans('admin.placeholder_date_from')]) !!}
                                                    <label id="date_from-error" class="error" for="date_from" style="">  </label>
                                                </div>
                                                
                                            </div>
                                            <div class="col-md-1">
                                                {{__('admin.date_to')}}
                                            </div> 
                                            <div class="col-md-2">
                                                <!-- for date -->
                                                <div class= "form-group form-float  ">
                                                    {!! Form::date('date_to',!isset($date_to)?null:$date_to,['class'=>'form-control ' ,'id'=>'date_to','placeholder' =>trans('admin.placeholder_date_to')]) !!}
                                                    <label id="date_to-error" class="error" for="date_to" style="">  </label>
                                                </div>
                                                
                                            </div>
                                           
                                        </div>

                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button class="btn btn-raised btn-primary btn-round waves-effect" type="submit">{{__('admin.search')}}</button>
                                    </form>
                                </div>
                        </div>
                    </div>
                </div>

        <!-- Exportable Table -->
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">

                    <div class="header">
                        <h2><strong>{{trans('admin.'.$title)}}</strong> </h2>
                        <ul class="header-dropdown">

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
                                        {{--  <th>
                                            <input type="checkbox" class="checkbox icheck" id="check-all" />
                                        </th>  --}}
                                        <th>{{trans('admin.order_no')}}</th>
                                        <th>{{trans('admin.user_name')}}</th>
                                        <th>{{trans('admin.usermobile')}}</th>
                                        <th>{{trans('admin.container_name')}}</th>
                                        <th>{{trans('admin.price')}}</th>
                                        <th>{{trans('admin.no_containers')}}</th>
                                        <th>{{trans('admin.total')}}</th>
                                        {{--  <th>{{trans('admin.city')}}</th>  --}}
                                        {{--  <th>{{trans('admin.area')}}</th>  --}}
                                        <th>{{trans('admin.date')}}</th>
                                        @if(Auth::user()->role == 'admin')
                                            <th>{{trans('admin.provider')}}</th>
                                        @elseif(Auth::user()->role == 'provider')
                                            <th>{{trans('admin.center')}}</th>
                                        @else 
                                            <th>{{trans('admin.driver')}}</th>
                                        @endif
                                        <th>{{trans('admin.status')}}</th>
                                        <th>{{trans('admin.actions')}}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($reports as $data)
                                    <tr class="item{{$data->id}}">
                                        {{--  <td> 
                                            <input type="checkbox" name="ids[]" value={{$data->id}} class="check icheck">
                                        </td>  --}}
                                        <td>{{ $data->id }}</td>
                                        <td>{{ $data->user_name }}</td>
                                        <td>{{ $data->user_mobile }}</td>
                                        <td>{{ $data->container_name_ar }}</td>   
                                        <td>{{ $data->price }}</td>     
                                        <td>{{ $data->no_container }}</td>  
                                        <td>{{ $data->total }}</td>     
                                        {{--  <td>{{ $data->city }}</td>       --}}
                                        {{--  <td>{{ $data->area }}</td>       --}}
                                        <td>{{ $data->created_at->format('Y-m-d') }}  </td>     
                                        @if(Auth::user()->role == 'admin')
                                            @if($data->provider)
                                                <td>{{ $data->provider->company_name }}</td>
                                            @else 
                                                <td></td>
                                            @endif
                                        @elseif(Auth::user()->role == 'provider')
                                            @if($data->center)
                                                <td>{{ $data->center->name }}</td>
                                            @else 
                                                <td></td>
                                            @endif
                                        @else 
                                            @if($data->driver)
                                                <td>{{ $data->driver->name }}</td>
                                            @else 
                                                <td></td>
                                            @endif
                                        @endif    
                            
                                        

                                        @if($data->status == 'pending')
                                            <td style="text-align:report"><span  class="col-green">{{ trans('admin.pending')}}</span></td> 
                                        @else
                                            <td style="text-align:report"><span  class="col-red">{{ trans('admin.'.$data->status)}}</span></td> 
                                        @endif
                                        <td>
                                            <a href="{{route('reportdetail',$data->id)}}" class="btn btn-info waves-effect waves-float waves-green btn-round " title="{{trans('admin.show')}}"><i class="zmdi zmdi-eye"></i></a>

                                        </td>
                                    </tr>
                                    
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{--  </form>  --}}
                </div>
            </div>
        </div>
  
    </div>
</section>
  
@endsection 

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<script src="{{asset('assets/plugins/jquery-spinner/js/jquery.spinner.js') }}"></script> <!-- Jquery Spinner Plugin Js --> 
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
                    url: "<?php echo url('/')?>/reports/delete/" + id,
                    data: {
                        '_token': $('input[name=_token]').val(),
                    },
                    success: function(data) {
                        $('.item' + data['id']).remove();
                        swal(Deleted, has_been_deleted, "success");
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
                        data:  new FormData($("#reportss_form")[0]),
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            for (var i=0;i<data.length;i++){
                                $('.item' + data[i]).remove();
                            }
                            swal(Deleted, has_been_deleted, "success");
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
        
 
    @if(isset($area_id)) 
        {{--  area = "{{$area_id}}" ;
        area = parseInt(area) ;
        console.log(area) ;
        $('#area_id').val(area);
           --}}
    @endif
    $('.select2').select2();
    $('#city_id').on('change', e => {
        $('#area_id').empty();
        $('#area_id').append(`<option value="">{{__('admin.all')}}</option>`)
        id = $('#city_id').val();
        if(id !=''){
            $.ajax({
                type: 'GET',
                url: "<?php echo url('/')?>/cities/"+id+"/areas",
                success: data => {
                    if(data.areas.length <= 0){
                        alert("{{trans('admin.notfoundarea')}}");
                    }
                    data.areas.forEach(area =>
                        // console.log(area.name)
                        $('#area_id').append(`<option value="${area.id}">${area.name}</option>`)
                    )
                }
            })
        }
    });
</script>
    
@endsection
