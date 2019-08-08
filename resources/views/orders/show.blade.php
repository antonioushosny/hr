@extends('layouts.index')
@section('style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />

    @if($lang == 'ar')
    <style>
        .dtp ,.datetimepicker, .join_date{
            direction: ltr !important ;
            border-radius: 0 30px 30px 0 !important;
        }
        /* style for map  */
        #map {
            height: 100%;
        }
        .controls {
            margin-top: 10px;
            border: 1px solid transparent;
            border-radius: 2px 0 0 2px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            height: 32px;
            outline: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        }

        #pac-input {
            background-color: #fff;
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
            margin-left: 12px;
            padding: 0 11px 0 13px;
            text-overflow: ellipsis;
            width: 300px;
        }

        #pac-input:focus {
            border-color: #4d90fe;
        }

        .pac-container {
            font-family: Roboto;
        }
        /* end style for map */

        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 1.6px solid #aaa;
            border-radius: 13px;
            max-width: 97%;
            /* border: 1px solid; */
        }
      
    </style>
    @endif
    <style>
       
        h5{
            color: #1e2967;
            background-color: #8c99e01f;
            border-radius: 30px;
            padding: 4px;
            padding-right: 25px;
            box-shadow: 2px 3px #6b6464;
            font-size: 15px;
        }
        h6{
            color: #1e2967;
            padding: 4px;
            padding-right: 25px;
            font-size: 16px;
            border-radius: 30px;
            text-align: center;
            box-shadow: 2px 3px #6b6464;
            background-color: #8c99e01f;
            margin-right: 10rem;
            margin-left: 10rem;
            
        }
        .hidden{
            display: none;
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
                    <small>{{__('admin.Welcome to Khazan')}}</small>
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
                    <li class="breadcrumb-item"><a href="{{route('orders')}}"><i class="zmdi zmdi-accounts-add"></i> {{__('admin.orders')}}</a></li>
                    <li class="breadcrumb-item "><a href="javascript:void(0);">{{__('admin.order_detail')}}</a></li>
                    
                </ul>
            </div>
        </div>
    </div>

     
    <div class="container-fluid">
        
        <!-- Exportable Table -->
        <div class="row clearfix">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>{{trans('admin.client_detail')}}  </strong> </h2>

                    </div>
                    <div class="body">
                        <h5><strong>{{trans('admin.user_name')}} :- </strong> {{ $order->user_name }}  </h5>
                        <h5><strong>{{trans('admin.user_mobile')}} :- </strong> {{ $order->user_mobile }}  </h5>
                        @if($order->user && $order->user->City)
                            @if($lang == 'ar')
                            <h5><strong>{{trans('admin.city')}} :- </strong> {{ $order->user->City->name_ar }}  </h5>
                            <h5><strong>{{trans('admin.area')}} :- </strong> {{ $order->user->Area->name_ar }}  </h5>
                            @else 
                            <h5><strong>{{trans('admin.city')}} :- </strong> {{ $order->user->City->name_en }}  </h5>
                            <h5><strong>{{trans('admin.area')}} :- </strong> {{ $order->user->Area->name_en }}  </h5>
                            @endif
                        @else 
                        <h5><strong>{{trans('admin.city')}} :- </strong> {{ $order->city }}  </h5>
                        <h5><strong>{{trans('admin.area')}} :- </strong> {{ $order->area }}  </h5>
                        @endif
                        <h6><strong>{{trans('admin.location')}} </strong> </h6>

                        <!-- {{--  for map      --}}  -->
                            <div class="form-group">
                                <span style="color: black "> 
                                    {{-- {!! Form::label('location',trans('admin.location')) !!} --}}
                                </span>
                                {{-- <input id="pac-input" class="controls" type="text" placeholder="{{trans('admin.Search_Box')}}"> --}}

                                <div class="col-md-12" id="map" style="width:100%;height:400px;"></div>
                                <label id="lat-error" class="error" for="lat" style="">  </label>
                            </div><br/>        

                            <div class="form-group">
                                {{--  {!! Form::label('lat',trans('admin.lat')) !!}  --}}
                                {!! Form::hidden('lat','',['class'=>'form-control', 'id' => 'lat','placeholder' => trans('admin.placeholder_lat')]) !!}

                                {{--  {!! Form::label('lng',trans('admin.lng')) !!}  --}}
                                {!! Form::hidden('lng','',['class'=>'form-control', 'id' => 'lng','placeholder' => trans('admin.placeholder_lng')]) !!}

                            </div><br/> 
                        <!-- end map -->
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2><strong>{{trans('admin.order_detail')}}</strong>   </h2>
                        
                    </div>
                    <div class="body">
                        <h5><strong>{{trans('admin.order_no')}} :- </strong> {{ $order->id }}  </h5>
                        @if($lang == 'ar')
                            <h5><strong>{{trans('admin.container')}} :- </strong> {{ $order->container_name_ar }}  </h5>
                        @else   
                            <h5><strong>{{trans('admin.container')}} :- </strong> {{ $order->container_name_en }}  </h5>
                        @endif
                        <h5><strong>{{trans('admin.container_size')}} :- </strong> {{ __('admin.'.$order->container_size) }}  </h5>
                        <h5><strong>{{trans('admin.price')}} :- </strong> {{ $order->price }}  </h5>
                        <h5><strong>{{trans('admin.no_container')}} :- </strong> {{ $order->no_container }}  </h5>
                        <h5><strong>{{trans('admin.total')}} :- </strong> {{ $order->total }}  </h5>
                        <h5><strong>{{trans('admin.date')}} :- </strong> {{ $order->created_at }}  </h5>
                        <h5><strong>{{trans('admin.notes')}} :- </strong> {{ $order->notes }}  </h5>
                        <h5><strong>{{ trans('admin.status') }} :- </strong> {{ trans('admin.'.$order->status) }}  </h5>
                        @foreach($order->centers as $center)
                           @if(Auth::user()->role == 'center')  
                                @if(Auth::user()->id == $center->center->id )  
                                <table class="table table-striped">
                                    <thead>
                                        <th>{{ __('admin.center') }}</th>
                                        <th>{{ __('admin.status') }}</th>
                                        @if($center->status == 'accept')
                                        <th>{{ __('admin.accept_date') }}</th>
                                        @elseif($center->status == 'decline')
                                        <th>{{ __('admin.decline_date') }}</th>
                                        <th>{{ __('admin.reason') }}</th>
                                        @endif
                                        
                                    </thead>
                                    <tbody>
                                        <td>{{$center->center->name}}</td>
                                        <td>{{ __('admin.'.$center->status) }}</td>
                                        @if($center->status == 'accept')
                                        <td>{{ $center->accept_date }}</td>
                                        @elseif($center->status == 'decline')
                                        <td>{{ $center->decline_date  }}</td>
                                        <td>{{ $center->reason  }}</td>
                                        
                                        @endif
                                        
                                    </tbody>
                                </table>
                                @endif
                            @else 
                                <table class="table table-striped">
                                    <thead>
                                        <th>{{ __('admin.center') }}</th>
                                        <th>{{ __('admin.status') }}</th>
                                        @if($center->status == 'accept')
                                        <th>{{ __('admin.accept_date') }}</th>
                                        @elseif($center->status == 'decline')
                                        <th>{{ __('admin.decline_date') }}</th>
                                        <th>{{ __('admin.reason') }}</th>
                                        @endif
                                        
                                    </thead>
                                    <tbody>
                                        <td>{{$center->center->name}}</td>
                                        <td>{{ __('admin.'.$center->status) }}</td>
                                        @if($center->status == 'accept')
                                        <td>{{ $center->accept_date }}</td>
                                        @elseif($center->status == 'decline')
                                        <td>{{ $center->decline_date  }}</td>
                                        <td>{{ $center->reason  }}</td>
                                        
                                        @endif
                                        
                                    </tbody>
                                </table>
                            @endif
                        @endforeach
                        @if($order->status == 'pending' && $order->center_id == Auth::user()->id)
                            <h4><strong>{{ trans('admin.take_action') }} :- </strong>  </h4>
                            {!! Form::open(['route'=>['actionfororder'],'method'=>'post','autocomplete'=>'off', 'id'=>'form_validation', 'enctype'=>'multipart/form-data' ])!!} 

                                <div class="form-group form-float">
                                    <input type="hidden" value="{{$order->id}}" name="order_id" required>
                                </div>

                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                <div class="form-group">

                                    <div class="radio inlineblock m-r-20">
                                        <input type="radio" name="status" id="accept" class="with-gap" value="accept"  >
                                        <label for="accept">{{__('admin.accept')}}</label>
                                    </div>

                                    <div class="radio inlineblock">
                                        <input type="radio" name="status" id="decline" class="with-gap" value="decline"  >
                                        <label for="decline">{{__('admin.decline')}}</label>
                                    </div>

                                </div>
                                <div id="action_div">
                                        
                                </div>

                            </form>
                        @elseif(sizeof($order->drivers) > 0 )    
                            <?php  $n = false ; ?>
                            

                            @foreach($order->drivers as $driver)
                                @if($driver->status == 'accept' || $driver->status == 'pending')
                                    <?php $n = true ; ?>
                                    {{--  <a href="#" class="btn btn-round btn-info">{{ __('admin.asssign') }}</a>   --}}
                                @endif
                                
                                <table class="table table-striped">
                                    <thead>
                                        <th>{{ __('admin.driver') }}</th>
                                        <th>{{ __('admin.status') }}</th>
                                        @if($driver->status == 'accept')
                                        <th>{{ __('admin.accept_date') }}</th>
                                        @elseif($driver->status == 'decline')
                                        <th>{{ __('admin.decline_date') }}</th>
                                        <th>{{ __('admin.reason') }}</th>
                                        @endif
                                        {{--  <th>{{ __('admin.action') }}</th>  --}}
                                        
                                    </thead>
                                    <tbody>
                                        <td>{{$driver->driver->name}}</td>
                                        <td>{{ __('admin.'.$driver->status) }}</td>
                                        @if($driver->status == 'accept')
                                        <td>{{ $driver->accept_date }}</td>
                                        @elseif($driver->status == 'decline')
                                        <td>{{ $driver->decline_date  }}</td>
                                        <td>{{ $driver->reason  }}</td>
                                        
                                        @endif
                                        {{--  <td> 
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary">Sony</button>
                                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li><a href="#">Tablet</a></li>
                                                    <li><a href="#">Smartphone</a></li>
                                                </ul>
                                            </div>
                                        </td>  --}}
                                    </tbody>
                                </table>
                                {{--  {{ $driver }}  --}}
                                
                            @endforeach
                            @if($n == false)
                                <a href="#" class="btn btn-round btn-info" id="reassign">{{ __('admin.reassign') }}</a>
                                <a href="#" class="btn btn-round btn-warning" id="decline_btn">{{ __('admin.decline_order') }}</a>
                            @endif
                            <div> 

                                {!! Form::open(['route'=>['assignDriver'],'method'=>'post','autocomplete'=>'off', 'id'=>'form_re_assign', 'enctype'=>'multipart/form-data' ])!!} 
                                <div class="form-group form-float">
                                        <input type="hidden" value="{{$order->id}}" name="order_id" required>
                                        
                                    </div>

                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div id="action_div_assigndriver">
                                        
                                    </div>
    
                                </form>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            
        </div>
  
    </div>

</section>
  
@endsection 

@section('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>

<script>
    $('.select2').select2();
    //this for add new record
    $("#form_validation").submit(function(e){
          e.preventDefault();
          $(':input[type="submit"]').prop('disabled', true);
          var form = $(this);
        //    openModal();
          $.ajax({
              type: 'POST',
              url: '{{ URL::route("actionfororder") }}',
              data:  new FormData($("#form_validation")[0]),
              processData: false,
              contentType: false,
               
              success: function(data) {
                  if ((data.errors)) {             
                    $(':input[type="submit"]').prop('disabled', false);           
                        if (data.errors.driver_id) {
                            $('#driver_id-error').css('display', 'inline-block');
                            $('#driver_id-error').text(data.errors.driver_id);
                        }
                        if (data.errors.reason) {
                            $('#reason-error').css('display', 'inline-block');
                            $('#reason-error').text(data.errors.reason);
                        }
                        if (data.errors.status) {
                            $('#status-error').css('display', 'inline-block');
                            $('#status-error').text(data.errors.status);
                        }
                  } else {
                        {{--  console.log(data)  --}}
                        window.location.replace("{{route('orders')}}");

                     }
            },
        });
    });  

    $("#form_re_assign").submit(function(e){
        e.preventDefault();
        var form = $(this);
      //    openModal();
        $.ajax({
            type: 'POST',
            url: '{{ URL::route("assignDriver") }}',
            data:  new FormData($("#form_re_assign")[0]),
            processData: false,
            contentType: false,
             
            success: function(data) {
                if ((data.errors)) {                        
                    if (data.errors.driver_id) {
                        $('#driver_id-error').css('display', 'inline-block');
                        $('#driver_id-error').text(data.errors.driver_id);
                    }
                    if (data.errors.reason) {
                        $('#reason-error').css('display', 'inline-block');
                        $('#reason-error').text(data.errors.reason);
                    }
                     
                } else {
                      {{--  console.log(data)  --}}
                      window.location.replace("{{route('orders')}}");

                   }
          },
      });
  });  
    $('#accept').on('change',function(event) {
        $('#action_div').html(`
            <!-- for drivers -->
            <div class= "form-group form-float">
                <label for="choose_driver" class="driver_id ">{{__('admin.choose_driver')}}</label>

                {!! Form::select('driver_id',$drivers
                    ,'',['class'=>'form-control show-tick select2 ' ,'id'=>'driver_id','placeholder' =>trans('admin.choose_driver'),'required']) !!}
                <label id="driver_id-error" class="error" for="driver_id" style="">  </label>
            </div>
            <button class="btn btn-raised btn-primary btn-round waves-effect" type="submit">{{__('admin.submit')}}</button>
        `);
        {{--  console.log('accept change')  --}}
        $('.select2').select2();
    })
    $('#decline').on('change',function(event) {
        $('#action_div').html(`
            <!-- for reason -->
            <div class="form-group form-float">
                <label for="reason" class="reason ">{{__('admin.placeholder_reason')}}</label>

                <textarea rows="4" name="reason"  class="form-control no-resize reason "  placeholder="{{__('admin.placeholder_reason')}}" required> </textarea>

                <label id="reason-error" class="error" for="reason" style="">  </label>
            </div>
            <button class="btn btn-raised btn-primary btn-round waves-effect" type="submit">{{__('admin.submit')}}</button>
        `);
        {{--  console.log('decline change')  --}}
    })
 
    $('#reassign').on('click',function(event) {
        $('#action_div_assigndriver').html(`
            <!-- for drivers -->
            <input type="hidden" value="reassign" name="type" required>
            <div class= "form-group form-float">
                <label for="choose_driver" class="driver_id ">{{__('admin.choose_driver')}}</label>

                {!! Form::select('driver_id',$drivers
                    ,'',['class'=>'form-control show-tick select2 ' ,'id'=>'driver_id','placeholder' =>trans('admin.choose_driver')]) !!}
                <label id="driver_id-error" class="error" for="driver_id" style="">  </label>
            </div>
            <button class="btn btn-raised btn-primary btn-round waves-effect" type="submit">{{__('admin.submit')}}</button>
        `);
        $('html,body').animate({ scrollTop: 9999 }, 'slow');
        {{--  console.log('accept change')  --}}
        $('.select2').select2();
    })
    $('#decline_btn').on('click',function(event) {
        $('#action_div_assigndriver').html(`
            <!-- for reason -->
                <input type="hidden" value="decline" name="type" required>
            <div class="form-group form-float">
                <label for="reason" class="reason ">{{__('admin.placeholder_reason')}}</label>

                <textarea rows="4" name="reason"  class="form-control no-resize reason "  placeholder="{{__('admin.placeholder_reason')}}" > </textarea>

                <label id="reason-error" class="error" for="reason" style="">  </label>
            </div>
            <button class="btn btn-raised btn-primary btn-round waves-effect" type="submit">{{__('admin.submit')}}</button>
        `);
        $('html,body').animate({ scrollTop: 9999 }, 'slow');
        {{--  console.log('decline change')  --}}
    })
    
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA-A44M149_C_j4zWAZ8rTCFRwvtZzAOBE&libraries=places&signed_in=true&callback=initMap"></script>
<script>


function initMap() {
@if($order->lat != null &&  $order->lng != null)    
    var lat1 = {{$order->lat}};
    var lng1 = {{$order->lng}}
    var haightAshbury = {lat: lat1 , lng:lng1 };
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 8,
        center: haightAshbury,
        mapTypeId: 'terrain'
    });

    var marker = new google.maps.Marker({
        position: haightAshbury,
        map: map
    });

@endif

}
function handleLocationError(browserHasGeolocation, infoWindow, pos) {
infoWindow.setPosition(pos);
infoWindow.setContent(browserHasGeolocation ?
        'The Geolocation service failed.' :
        'Your browser doesnt support geolocation. ');
}
</script>
@endsection
