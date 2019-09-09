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
                    <li class="breadcrumb-item"><a href="{{route('orders')}}"><i class="zmdi zmdi-group-work"></i> {{__('admin.orders')}}</a></li>
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
                        <h5><strong>{{trans('admin.user_name')}} :- </strong> {{ $order->user->name }}  </h5>
                        <h5><strong>{{trans('admin.mobile_user')}} :- </strong> {{ $order->user->mobile }}  </h5>
                        
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
                            <h5><strong>{{trans('admin.service')}} :- </strong> {{ $order->services->name_ar }}  </h5>
                        @else   
                            <h5><strong>{{trans('admin.service')}} :- </strong> {{ $order->services->name_en }}  </h5>
                        @endif
                        <h5><strong>{{trans('admin.name_tech')}} :- </strong> {{$order->fannie->name}}  </h5>
                        <h5><strong>{{trans('admin.mobile_tech')}} :- </strong> {{$order->fannie->mobile}}  </h5>
                        <h5><strong>{{trans('admin.address')}} :- </strong> {{ $order->address }}  </h5>
                        <h5><strong>{{trans('admin.date')}} :- </strong> {{ $order->created_at }}  </h5>
                        <h5><strong>{{trans('admin.date_order')}} :- </strong> {{ $order->date }}  </h5>
                        <h5><strong>{{trans('admin.time')}} :- </strong> {{ $order->time }}  </h5>
                        <h5><strong>{{trans('admin.notes')}} :- </strong> {{ $order->notes }}  </h5>
                        <h5><strong>{{ trans('admin.status') }} :- </strong> {{ trans('admin.'.$order->status) }}  </h5>
                        @if($order->status=="rejected" || $order->status=="canceled")
                        <h5><strong>{{ trans('admin.rejected_reason') }} :- </strong>{{ $order->rejected_reason }}   </h5>
                        <h5><strong>{{ trans('admin.rejected_date') }} :- </strong>{{ $order->rejected_date }}   </h5>
                        @endif

                        @if($order->status=='accepted'|| $order->status=='completed')
                        <h5><strong>{{ trans('admin.accepted_date') }} :- </strong>{{ $order->accepted_date }}   </h5>
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
