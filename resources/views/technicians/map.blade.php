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
                    <li class="breadcrumb-item"><a href="{{route('technicians')}}"><i class="zmdi zmdi-accounts-alt"></i> {{__('admin.technicians')}}</a></li>
                    <li class="breadcrumb-item "><a href="javascript:void(0);">{{__('admin.show_technician')}}</a></li>
                    
                </ul>
            </div>
        </div>
    </div>

     
    <div class="container-fluid">
        
        <!-- Exportable Table -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                <div class="header">
                            <h2><strong>{{trans('admin.'.$title)}}</strong> {{trans('admin.show_technician')}}  </h2>
                            
                        </div>
                    <div class="body">

                        <!-- {{--  for map      --}}  -->
                            <div class="form-group">
                                <span style="color: black "> 
                                    {{-- {!! Form::label('location',trans('admin.location')) !!} --}}
                                </span>
                           
                                <div class="col-md-12" id="map" style="width:100%;height:400px;"></div>
                                <label id="lat-error" class="error" for="lat" style="">  </label>
                            </div><br/>        

                        <!-- end map -->
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
jQuery(function($) {
// Asynchronously Load the map API 
var script = document.createElement('script');
script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyA-A44M149_C_j4zWAZ8rTCFRwvtZzAOBE&libraries=places&signed_in=true&callback=initialize";
document.body.appendChild(script);
});
 
function initialize() {
var map;
var bounds = new google.maps.LatLngBounds();
var mapOptions = {
     mapTypeId: 'roadmap'
};
                 
// Display a map on the page
map = new google.maps.Map(document.getElementById("map"), mapOptions);
map.setTilt(45);
     

// Multiple Markers
var markers = [
//   ['Mumbai', 19.0760,72.8777],
//   ['Pune', 18.5204,73.8567],
//   ['Bhopal ', 23.2599,77.4126],
//   ['Agra', 27.1767,78.0081],
//   ['Delhi', 28.7041,77.1025],
];
// Info Window Content
var infoWindowContent = [
    // ['<div class="info_content">' +
    // '<h3>Mumbai</h3>' +
    // '<p>Lorem Ipsum  Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum</p>' +'</div>'],
    // ['<div class="info_content">' +
    // '<h3>Pune</h3>' +
    // '<p>Lorem Ipsum  Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum</p>' +'</div>'],
    // ['<div class="info_content">' +
    // '<h3>Bhopal</h3>' +
    // '<p>Lorem Ipsum  Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum</p>' +'</div>'],  
    // ['<div class="info_content">' +
    // '<h3>Agra</h3>' +
    // '<p>Lorem Ipsum  Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum</p>' +'</div>'],
    // ['<div class="info_content">' +
    // '<h3>Delhi</h3>' +
    // '<p>Lorem Ipsum  Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum</p>' +'</div>'],
];
@foreach($technicians as $data)
if("{{$data->image}}")
{
    markers.push(["{{$data->name}}","{{$data->lat}}","{{$data->lng}}","{{asset('img/'.$data->image)}}"]);

}
else
{
    markers.push(["{{$data->name}}","{{$data->lat}}","{{$data->lng}}","{{asset('images/person.png')}}"]);
}
infoWindowContent.push(['<div class="info_content" style="padding-top:5px ;">' +
     '<p>{{$data->address}}</p>' +'</div>']);
@endforeach
                     


// Display multiple markers on a map
var infoWindow = new google.maps.InfoWindow(), marker, i;
 
// Loop through our array of markers &amp; place each one on the map  
for( i = 0; i < markers.length; i++ ) {
    var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
    bounds.extend(position);
    var icon = {
    url: markers[i][3], // url
    scaledSize: new google.maps.Size(50, 50), // scaled size
    origin: new google.maps.Point(0,0), // origin
    anchor: new google.maps.Point(0, 0) // anchor
};
    marker = new google.maps.Marker({
        position: position,
        icon:icon,
        map: map,
        label:   markers[i][0] ,
        title: markers[i][0]
    });
     
    // Each marker to have an info window    
    google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
            infoWindow.setContent(infoWindowContent[i][0]);
            infoWindow.open(map, marker);
        }
    })(marker, i));
 
    // Automatically center the map fitting all markers on the screen
    map.fitBounds(bounds);
}
 
// Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
    this.setZoom(5);
    google.maps.event.removeListener(boundsListener);
});
 
}
</script>





@endsection
