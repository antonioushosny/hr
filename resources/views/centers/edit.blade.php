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
                    <li class="breadcrumb-item"><a href="{{route('centers')}}"><i class="zmdi zmdi-accounts-add"></i> {{__('admin.centers')}}</a></li>
                    <li class="breadcrumb-item "><a href="javascript:void(0);">{{__('admin.edit_center')}}</a></li>
                    
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
                            <h2><strong>{{trans('admin.'.$title)}}</strong> {{trans('admin.edit_center')}}  </h2>
                            
                        </div>
                        <div class="body">
                            {!! Form::open(['route'=>['storecenter'],'method'=>'post','autocomplete'=>'off', 'id'=>'form_validation', 'enctype'=>'multipart/form-data' ])!!} 

                                <div class="form-group form-float">
                                    <input type="hidden" value="{{$center->id}}" name="id" required>
                                </div>
                                <!-- for company_name -->
                                @if(Auth::user()->role == 'admin' )
                                    <div class= "form-group form-float">
                                        {!! Form::select('provider_id',$providers
                                            ,$center->provider_id,['class'=>'form-control show-tick select2' ,'placeholder' =>trans('admin.choose_provider'),'required']) !!}
                                        <label id="provider_id-error" class="error" for="provider_id" style="">  </label>
                                    </div>
                                @else 
                                    <div class="form-group form-float">
                                        <input type="hidden" value="{{Auth::user()->id}}" name="provider_id" required>
                                    </div>
                                @endif
                                <!-- for responsible_name -->
                                <div class="form-group form-float">
                                    <input type="text" class="form-control" placeholder="{{__('admin.placeholder_responsible_name')}}" name="responsible_name" value="{{$center->name}}" required>
                                    <label id="responsible_name-error" class="error" for="responsible_name" style="">  </label>
                                </div>
                               
                                <!-- for email -->
                                <div class="form-group form-float">
                                    <input type="email" class="form-control" placeholder="{{__('admin.placeholder_email')}}" name="email" autocomplete="off" value="{{$center->email}}" required>
                                    <label id="email-error" class="error" for="email" style=""></label>
                                </div>

                                <!-- for mobile -->
                                <div class="form-group form-float">
                                    <input type="text" class="form-control" placeholder="{{__('admin.mobile')}}" value="{{$center->mobile}}" name="mobile" onkeypress='isNumber(event); ' >
                                    <label id="mobile-error" class="error" for="mobile" style="">  </label>
                                </div>

                                <!-- for city -->
                                <div class= "form-group form-float">
                                    {!! Form::select('city_id',$cities
                                        ,$center->city_id,['class'=>'form-control show-tick select2' ,'id'=>'city_id','placeholder' =>trans('admin.choose_city'),'required']) !!}
                                    <label id="city_id-error" class="error" for="city_id" style="">  </label>
                                </div>
                                        
                                <!-- for area -->
                                <div class= "form-group form-float area_id_div ">
                                    {!! Form::select('area_id',$areas
                                        ,$center->area_id,['class'=>'form-control show-tick select2' ,'id'=>'area_id','placeholder' =>trans('admin.choose_area'),'required']) !!}
                                    <label id="area_id-error" class="error" for="area_id" style="">  </label>
                                </div>
                                 <!-- for containers  -->
                                 <h3 style="text-align:center">{{__('admin.containers')}}</h3>
                                <fieldset>
                                    <div class="control-group">
                                        <div class="container-fluid containers-container">
                                            <?php $c = 0 ?>
                                            @if(sizeof($center->containers) > 0 )
                                                @foreach($center->containers as $container)
                                                    @if($c == 0)
                                                        <div class="row containers-divrow">
                                                            <div class="col-sm-5">
                                                                <!-- for containers -->
                                                                <div class= "form-group form-float">
                                                                    {!! Form::select('containers[]',$containers
                                                                        ,$container->pivot->container_id,['class'=>'form-control show-tick select2' ,'id'=>'containers','placeholder' =>trans('admin.choose_container'),'required']) !!}
                                                                    <label id="containers-error" class="error" for="containers" style="">  </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-5">
                                                                <!-- for price -->
                                                                <div class= "form-group form-float">
                                                                    <input type="number" step="0.01" class="form-control text-center "  data-rule="currency" placeholder="{{__('admin.price')}}"  name="price[]" value="{{$container->pivot->price}}" required>
                                                                    <label id="price-error" class="error" for="price" style="">  </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <span class="btn btn-raised btn-primary btn-round waves-effect" id="add_container">{{__('admin.add_container')}}</span>

                                                            </div>
                                                        </div>
                                                    @else 
                                                        <div class="row containers-divrow{{$c}}">
                                                            <div class="col-sm-5">
                                                                <!-- for containers -->
                                                                <div class= "form-group form-float">
                                                                    {!! Form::select('containers[]',$containers
                                                                        ,$container->pivot->container_id,['class'=>'form-control show-tick select2' ,'id'=>'containers'.$c,'placeholder' =>trans('admin.choose_container'),'required']) !!}
                                                                    <label id="containers-error" class="error" for="containers" style="">  </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-5">
                                                                <!-- for price -->
                                                                <div class= "form-group form-float">
                                                                    <input type="number" step="0.01" class="form-control text-center "  data-rule="currency" placeholder="{{__('admin.price')}}"  name="price[]" value="{{$container->pivot->price}}" required>
                                                                    <label id="price-error" class="error" for="price" style="">  </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <span class="btn btn-raised btn-warning btn-round waves-effect" id="add_container{{$c}}" onclick="deletecontainer('{{$c}}')">{{__('admin.delete')}}</span>

                                                            </div>
                                                        </div>
                                                    @endif 
                                                    <?php $c ++; ?>
                                                @endforeach
                                            @else 
                                            <div class="row containers-divrow">
                                                <div class="col-sm-5">
                                                    <!-- for containers -->
                                                    <div class= "form-group form-float">
                                                        {!! Form::select('containers[]',$containers
                                                            ,'',['class'=>'form-control show-tick select2' ,'id'=>'containers','placeholder' =>trans('admin.choose_container'),'required']) !!}
                                                        <label id="containers-error" class="error" for="containers" style="">  </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-5">
                                                    <!-- for price -->
                                                    <div class= "form-group form-float">
                                                        <input type="number" step="0.01" class="form-control text-center " value="1" data-rule="currency" placeholder="{{__('admin.price')}}"  name="price[]" required>
                                                        <label id="price-error" class="error" for="price" style="">  </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <span class="btn btn-raised btn-primary btn-round waves-effect" id="add_container">{{__('admin.add_container')}}</span>

                                                </div>
                                            </div>
                                            @endif

                                                  
                                        </div>  
                                    </div>
                                </fieldset>
                                <!-- end for containers -->
                                 <!-- {{--  for map      --}}  -->
                                 <div class="form-group">
                                    <span style="color: black "> 
                                        {!! Form::label('location',trans('admin.location')) !!}
                                    </span>
                                    <input id="pac-input" class="controls" type="text" placeholder="{{trans('admin.Search_Box')}}">

                                    <div class="col-md-12" id="map" style="width:100%;height:400px;"></div>
                                    <label id="lat-error" class="error" for="lat" style="">  </label>
                                </div><br/>        

                                <div class="form-group">
                                    {{--  {!! Form::label('lat',trans('admin.lat')) !!}  --}}
                                    {!! Form::hidden('lat',$center->lat,['class'=>'form-control', 'id' => 'lat','placeholder' => trans('admin.placeholder_lat')]) !!}

                                    {{--  {!! Form::label('lng',trans('admin.lng')) !!}  --}}
                                    {!! Form::hidden('lng',$center->lng,['class'=>'form-control', 'id' => 'lng','placeholder' => trans('admin.placeholder_lng')]) !!}

                                </div><br/> 
                                <!-- end map -->

                                <!-- for image  -->
                                <div class="form-group form-float row" >
                                    {{--  for image  --}}
                                    <div class= "col-md-2 col-xs-3">
                                        <div class="form-group form-float  " >
                                            <div style="position:relative; ">
                                                <a class='btn btn-primary' href='javascript:;' >
                                                    {{trans('admin.Choose_Image')}}
            
                                                    {!! Form::file('image',['class'=>'form-control','id' => 'image_field', 'accept'=>'image/x-png,image/gif,image/jpeg' ,'style'=>'position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;','size'=> '40' ,'onchange' => 'readURL(this,"changeimage");' ]) !!}
                                                </a>
                                                &nbsp;
                                                <div class='label label-primary' id="upload-file-info" ></div>
                                                <span style="color: red " class="image text-center hidden"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-10">
                                        
                                        @if($center->image)
                                            <img id="changeimage" src="{{asset('img/'.$center->image)}}" width="100px" height="100px" alt=" {{trans('admin.image')}}" />
                                        @else 
                                            <img id="changeimage" src="{{asset('images/default.png')}}" width="100px" height="100px" alt=" {{trans('admin.image')}}" />
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="radio inlineblock m-r-20">
                                        <input type="radio" name="status" id="active" class="with-gap" value="active" <?php echo ($center->status == 'active') ? "checked=''" : ""; ?> >
                                        <label for="active">{{__('admin.active')}}</label>
                                    </div>                                
                                    <div class="radio inlineblock">
                                        <input type="radio" name="status" id="not_active" class="with-gap" value="not_active" <?php echo ($center->status == 'not_active') ? "checked=''" : ""; ?> >
                                        <label for="not_active">{{__('admin.not_active')}}</label>
                                    </div>
                                </div>

                               

                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn btn-raised btn-primary btn-round waves-effect" type="submit">{{__('admin.edit')}}</button>
                            </form>
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
          var form = $(this);
          $(':input[type="submit"]').prop('disabled', true);
        //    openModal();
          $.ajax({
              type: 'POST',
              url: '{{ URL::route("storecenter") }}',
              data:  new FormData($("#form_validation")[0]),
              processData: false,
              contentType: false,
               
              success: function(data) {
                  if ((data.errors)) {          
                    $(':input[type="submit"]').prop('disabled', false);              
                        if (data.errors.company_name) {
                            $('#company_name-error').css('display', 'inline-block');
                            $('#company_name-error').text(data.errors.company_name);
                        }
                        if (data.errors.responsible_name) {
                            $('#responsible_name-error').css('display', 'inline-block');
                            $('#responsible_name-error').text(data.errors.responsible_name);
                        }
                        if (data.errors.image) {
                            $('#image-error').css('display', 'inline-block');
                            $('#image-error').text(data.errors.image);
                        }
                        if (data.errors.lat) {
                            $('#lat-error').css('display', 'inline-block');
                            $('#lat-error').text(data.errors.lat);
                        }
                        if (data.errors.mobile) {
                            $('#mobile-error').css('display', 'inline-block');
                            $('#mobile-error').text(data.errors.mobile);
                        }
                  } else {
                        window.location.replace("{{route('centers')}}");

                     }
            },
        });
    });
    $('#city_id').on('change', e => {
        $('#area_id').empty();
        id = $('#city_id').val();

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
    })

    var count = 10000 ;
    $('#add_container').on('click',e => {
        count ++ ;
        $('.containers-container').append(`
                                            <div class="row containers-divrow`+count+`">
                                                <div class="col-sm-5">
                                                    <!-- for containers -->
                                                    <div class= "form-group form-float">
                                                        {!! Form::select('containers[]',$containers
                                                            ,'',['class'=>'form-control show-tick select' ,'id'=>'containers`+count+`','placeholder' =>trans('admin.choose_container'),'required']) !!}
                                                        <label id="containers-error" class="error" for="containers" style="">  </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-5">
                                                    <!-- for price -->
                                                    <div class= "form-group form-float">
                                                        <input type="number" step="0.01" class="form-control text-center " value="1" data-rule="currency" placeholder="{{__('admin.price')}}"  name="price[]" required>
                                                        <label id="price-error" class="error" for="price" style="">  </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <span class="btn btn-raised btn-warning btn-round waves-effect" id="add_container`+count+`" onclick="deletecontainer(`+count+`)">{{__('admin.delete')}}</span>

                                                </div>
                                            </div>  
        `);
        $('#containers'+count).select2();
        // alert('afafafaf');
    });
    function deletecontainer(count){
        // alert(count);
        $('.containers-divrow'+count).remove();
    }
</script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA-A44M149_C_j4zWAZ8rTCFRwvtZzAOBE&libraries=places&signed_in=true&callback=initMap"></script>
<script>


function initMap() {
@if($center->lat != null &&  $center->lng != null)    
    var lat1 = {{$center->lat}};
    var lng1 = {{$center->lng}}
    var haightAshbury = {lat: lat1 , lng:lng1 };
    console.log(haightAshbury) ;
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 18,
        center: haightAshbury,
        mapTypeId: 'terrain'
    });

    var marker = new google.maps.Marker({
        position: haightAshbury,
        map: map
    });
    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    // Bias the SearchBox results towards current map's viewport.
    map.addListener('bounds_changed', function() {
        searchBox.setBounds(map.getBounds());
    });

    map.addListener('click', function(event) {
        //clear previous marker
        marker.setMap(null);
        //set new marker
        marker = new google.maps.Marker({
        position: event.latLng,
        map: map
        });
        document.getElementById('lat').value = event.latLng.lat();
        document.getElementById('lng').value = event.latLng.lng();
    });
    var markers = [];
    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox.addListener('places_changed', function() {
    var places = searchBox.getPlaces();

    if (places.length == 0) {
    return;
    }

    // Clear out the old markers.
    markers.forEach(function(marker) {
    marker.setMap(null);
    });
    markers = [];

    // For each place, get the icon, name and location.
    var bounds = new google.maps.LatLngBounds();
    places.forEach(function(place) {
    var icon = {
    url: place.icon,
    size: new google.maps.Size(71, 71),
    origin: new google.maps.Point(0, 0),
    anchor: new google.maps.Point(17, 34),
    scaledSize: new google.maps.Size(25, 25)
    };

    // Create a marker for each place.
    markers.push(new google.maps.Marker({
    map: map,
    icon: icon,
    title: place.name,
    position: place.geometry.location
    }));

    document.getElementById('lat').value = place.geometry.location.lat();
    document.getElementById('lng').value = place.geometry.location.lng();
    if (place.geometry.viewport) {
    // Only geocodes have viewport.
    bounds.union(place.geometry.viewport);
    } else {
    bounds.extend(place.geometry.location);
    }
    });
    map.fitBounds(bounds);
    });

@else 
    var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 29.967176910157654, lng: 31.21215951392594},
        zoom: 18,
        mapTypeId: 'terrain'
    });
    var marker = new google.maps.Marker({
        position: {lat: 29.967176910157654, lng: 31.21215951392594},
        map: map
    });
    var infoWindow = new google.maps.InfoWindow({map: map});

    // Try HTML5 geolocation.
    if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
    var pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
    };
    document.getElementById('lat').value = position.coords.latitude;
    document.getElementById('lng').value = position.coords.longitude;
    infoWindow.setPosition(pos);
    
    infoWindow.setContent('location found');
    map.setCenter(pos);
    }, function() {
    handleLocationError(true, infoWindow, map.getCenter());
    });

    } else {
    // Browser doesn't support Geolocation
    handleLocationError(false, infoWindow, map.getCenter());
    }

    // Create the search box and link it to the UI element.
    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    // Bias the SearchBox results towards current map's viewport.
    map.addListener('bounds_changed', function() {
    searchBox.setBounds(map.getBounds());
    });

    map.addListener('click', function(event) {
    //clear previous marker
    marker.setMap(null);
    //set new marker
    marker = new google.maps.Marker({
    position: event.latLng,
    map: map
    });
    document.getElementById('lat').value = event.latLng.lat();
    document.getElementById('lng').value = event.latLng.lng();
    });
    var markers = [];
    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox.addListener('places_changed', function() {
    var places = searchBox.getPlaces();

    if (places.length == 0) {
    return;
    }

    // Clear out the old markers.
    markers.forEach(function(marker) {
    marker.setMap(null);
    });
    markers = [];

    // For each place, get the icon, name and location.
    var bounds = new google.maps.LatLngBounds();
    places.forEach(function(place) {
    var icon = {
    url: place.icon,
    size: new google.maps.Size(71, 71),
    origin: new google.maps.Point(0, 0),
    anchor: new google.maps.Point(17, 34),
    scaledSize: new google.maps.Size(25, 25)
    };

    // Create a marker for each place.
    markers.push(new google.maps.Marker({
    map: map,
    icon: icon,
    title: place.name,
    position: place.geometry.location
    }));

    document.getElementById('lat').value = place.geometry.location.lat();
    document.getElementById('lng').value = place.geometry.location.lng();
    if (place.geometry.viewport) {
    // Only geocodes have viewport.
    bounds.union(place.geometry.viewport);
    } else {
    bounds.extend(place.geometry.location);
    }
    });
    map.fitBounds(bounds);
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
