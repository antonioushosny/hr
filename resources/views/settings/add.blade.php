@extends('layouts.index')
@section('style')

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
                    <li class="breadcrumb-item"><a href="{{route('settings',$type)}}"><i class="zmdi zmdi-accounts-add"></i> {{__('admin.settings')}}</a></li>
                    <li class="breadcrumb-item "><a href="javascript:void(0);">{{ !isset($data->id)?__('admin.add_setting'):__('admin.edit_setting') }} </a></li>
                    
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
                            <h2><strong>{{trans('admin.'.$title)}}</strong> {{ !isset($data->id)?__('admin.add_setting'):__('admin.edit_setting') }} </h2>
                        </div>
                        <div class="body">
                            {!! Form::open(['route'=>['storesetting'],'method'=>'post','autocomplete'=>'off', 'id'=>'form_validation', 'enctype'=>'multipart/form-data' ])!!} 

                                <div class="row">
                                    <div class="col-md-1">{{ __('admin.title') }}</div>
                                    <div class="col-md-11">
                                        <!-- for title_ar -->
                                        <div class="form-group form-float">
                                            <input type="text" value="{{ !isset($data->title)?'':$data->title }}" class="form-control" placeholder="{{__('admin.title')}}" name="title" required>
                                            <label id="title-error" class="error" for="title" style="">  </label>
                                        </div>
                                    </div>
                                   
                                    <div class="col-md-1">{{ __('admin.disc') }}</div>
                                    <div class="col-md-11">
                                        <!-- for disc -->
                                        <div class="form-group form-float">
                                            @if($data)
                                            {!! Form::textarea('disc',$data->disc,['class'=>'form-control ','id' => 'disc_field2','rows'=>7,'placeholder' => trans('admin.disc')]) !!}
                                            @else 
                                            {!! Form::textarea('disc','',['class'=>'form-control ','id' => 'disc_field2','rows'=>7,'placeholder' => trans('admin.disc')]) !!}
                                            @endif
                                            
                                            <label id="disc-error" class="error" for="disc" style="">  </label>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <!-- for type -->
                                <div class= "form-group form-float">
                                    {!! Form::hidden('type',$type,['class'=>'form-control show-tick']) !!}
                                    <label id="type-error" class="error" for="type" style="">  </label>
                                </div>

                                <!-- for id -->
                                <div class= "form-group form-float">
                                    {!! Form::hidden('id',!isset($data->id)?null:$data->id ,['class'=>'form-control show-tick']) !!}
                                </div>

                                <!-- {{--  for map      --}}  -->

                                <div class="form-group form-float">
                                            <span style="color: black "> 
                                        {!! Form::label('location[]',trans('admin.placeholder_location')) !!}
                                    </span>
                                    <!-- <label id="location-error" class="error" for="location[]" style=""></label> -->
                                    <input id="pac-input" class="controls" type="text" placeholder="{{trans('admin.Search_Box')}}">

                                    <div class="col-lg-12" id="map" style="width:100%;height:400px;"></div>
                                    <label id="lat-error" class="error" for="lat" style="">  </label>
                                </div><br/>        

                                <div class="form-group">
                                    {{--  {!! Form::label('lat',trans('admin.lat')) !!}  --}}
                                    {!! Form::hidden('lat',$data->lat,['class'=>'form-control', 'id' => 'lat','placeholder' => trans('admin.placeholder_lat')]) !!}

                                    {{--  {!! Form::label('lng',trans('admin.lng')) !!}  --}}
                                    {!! Form::hidden('lng',$data->lng,['class'=>'form-control', 'id' => 'lng','placeholder' => trans('admin.placeholder_lng')]) !!}

                                </div><br/> 
                                <!-- end map -->
                                
                               
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                               
                                <button class="btn btn-raised btn-primary btn-round waves-effect" type="submit">{{__('admin.save')}}
                               
                                </button>
                            </form>
                        </div>
                </div>
            </div>
        </div>
  
    </div>

</section>
  
@endsection 

@section('script')

<script src="{{ asset('assets/plugins/ckeditor/ckeditor.js') }}"></script> <!-- Ckeditor --> 
<script src="{{ asset('assets/js/pages/forms/editors.js') }}"></script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA-A44M149_C_j4zWAZ8rTCFRwvtZzAOBE&libraries=places&signed_in=true&callback=initMap"></script>
<script>
    
function initMap() {
    @if($data->lat != null &&  $data->lng != null)    
        var lat1 = {{$data->lat}};
        var lng1 = {{$data->lng}}
        var haightAshbury = {lat: lat1 , lng:lng1 };
        console.log(haightAshbury) ;
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 10,
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
            var address=$( "#pac-input" ).val();
        $('#address-field1').val(address);
        console.log(address);
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
<script>
    CKEDITOR.replace('disc_field2');
    CKEDITOR.config.height = 300;

    //this for add new record
    $("#form_validation").submit(function(e){
        {{--  alert('hkhk');  --}}
           {{--  $('#addModal').modal('hide');  --}}
           $('.add').disabled =true;
           $(':input[type="submit"]').prop('disabled', true);
          e.preventDefault();
          var form = $(this);
        //    openModal();
          $.ajax({
              type: 'POST',
              url: '{{ URL::route("storesetting") }}',
              data:  new FormData($("#form_validation")[0]),
              processData: false,
              contentType: false,
              success: function(data) {
                  if ((data.errors)) {    
                    $(':input[type="submit"]').prop('disabled', false);                    
                        if (data.errors.title) {
                            $('#title-error').css('display', 'inline-block');
                            $('#title-error').text(data.errors.title);
                        }
                        if (data.errors.lat) {
                            $('#lat-error').css('display', 'inline-block');
                            $('#lat-error').text(data.errors.lat);
                        }
                        
                        if (data.errors.image) {
                            $('#image-error').css('display', 'inline-block');
                            $('#image-error').text(data.errors.image);
                        }
                  } else {
                      
                    $.ajax({
                        type: 'POST',
                        url: '{{ URL::route("storesetting") }}',
                        data:  new FormData($("#form_validation")[0]),
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            if ((data.errors)) {                        
                                  if (data.errors.title) {
                                      $('#title-error').css('display', 'inline-block');
                                      $('#title-error').text(data.errors.title);
                                  }
                                   
                                  if (data.errors.lat) {
                                      $('#lat-error').css('display', 'inline-block');
                                      $('#lat-error').text(data.errors.lat);
                                  }
                                  if (data.errors.image) {
                                      $('#image-error').css('display', 'inline-block');
                                      $('#image-error').text(data.errors.image);
                                  }
                            } else {
                                
                                  location.reload();
                               }
                      },
                    });
                     }
            },
          });
        });

</script>
    
@endsection
