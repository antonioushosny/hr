@extends('layouts.index')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />

    @if($lang == 'ar')
    <style>
        .dtp ,.datetimepicker, .join_date{
            direction: ltr !important ;
            border-radius: 0 30px 30px 0 !important;
        }
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
                    <li class="breadcrumb-item "><a href="javascript:void(0);">{{__('admin.add_technician')}}</a></li>
                    
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
                            <h2><strong>{{trans('admin.'.$title)}}</strong> {{trans('admin.add_technician')}}  </h2>
                            
                        </div>
                            {!! Form::open(['route'=>['storetechnician'],'method'=>'post','autocomplete'=>'off', 'id'=>'form_validation', 'enctype'=>'multipart/form-data' ])!!} 
                        <div class="body row">
                            <div class="col-lg-6">
                                
                                <!-- for email -->
                                <div class="form-group form-float">
                                <label  for="name" style=""> {{trans('admin.name')}} </label>
                                    <input type="text" class="form-control" placeholder="{{__('admin.placeholder_name')}}" name="name" autocomplete="off" required>
                                    <label id="name-error" class="error" for="name" style=""></label>
                                </div>

                                <div class="form-group form-float">
                                <label  for="email" style=""> {{trans('admin.email')}} </label>
                                    <input type="email" class="form-control" placeholder="{{__('admin.placeholder_email')}}" name="email" autocomplete="off" required>
                                    <label id="email-error" class="error" for="email" style=""></label>
                                </div>

                                <div class="form-group form-float">
                                <label  for="mobile" style=""> {{trans('admin.mobile')}} </label>
                                    <input type="text" class="form-control" placeholder="{{__('admin.placeholder_mobile')}}" name="mobile" maxlength= 14 onkeypress="isNumber(event);" autocomplete="off" required>
                                    <label id="mobile-error" class="error" for="mobile" style=""></label>
                                </div>
                                
                                <div class= "form-group form-float"> 
                                <label  for="service_id" style=""> {{trans('admin.service')}} </label>
                                    {!! Form::select('service_id',$services
                                        ,'',['class'=>'form-control show-tick' ,'placeholder' =>trans('admin.choose_service'),'required']) !!}
                                        <label id="service_id-error" class="error" for="service_id" style="">  </label>
                                </div> 

  

                                <div class= "form-group form-float"> 
                                <label  for="nationality_id" style=""> {{trans('admin.nationality')}} </label>
                                    {!! Form::select('nationality_id',$nationalites
                                        ,'',['class'=>'form-control show-tick' ,'placeholder' =>trans('admin.choose_nationality'),'required']) !!}
                                        <label id="nationality_id-error" class="error" for="nationality_id" style="">  </label>
                                </div>              
                                

                                <div class= "form-group form-float"> 
                                <label  for="renewal_date" style=""> {{trans('admin.renewal_date')}} </label>
                                <input placeholder="{{__('admin.renewal_date')}}" class="textbox-n form-control" type="text" name="renewal_date" onfocus="(this.type='date')" onblur="(this.value == '' ? this.type='text' : this.type='date')" id="date">
                                <!-- <input type="date" class="form-control" placeholder="{{__('admin.renewal_date')}}" name="renewal_date"> -->
                                 <label id="renewal_date-error" class="error" for="renewal_date" style="">  </label>
                                </div>

                                    <div class="form-group">
                                <textarea class="form-control" id="brief" rows="6" name="brief" placeholder="{{__('admin.brief_technician')}}"></textarea>
                                </div>

                                 <!-- for image  -->
                                 <div class="form-group form-float row"  >
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
                                                <span style="color: red " id="image-error" class="image text-user hidden"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-10">
                                        
                                        <img id="changeimage" src="{{asset('images/default.png')}}" width="100px" height="100px" alt=" {{trans('admin.image')}}" />
                                    </div>
                                </div>



                                 <!-- for identity phone  -->
                                 <div class="form-group form-float row"  >
                                    {{--  for identity phone  --}}
                                    <div class= "col-md-3 col-xs-3">
                                        <div class="form-group form-float  " >
                                            <div style="position:relative; ">
                                                <a class='btn btn-primary' href='javascript:;' >
                                                    {{trans('admin.Choose_identity')}}
            
                                                    {!! Form::file('identity',['class'=>'form-control','id' => 'identity_field', 'accept'=>'image/x-png,image/gif,image/jpeg' ,'style'=>'position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;','size'=> '40' ,'onchange' => 'readURL(this,"changeidentity");' ]) !!}
                                                </a>
                                                &nbsp;
                                                <div class='label label-primary' id="upload-file-info" ></div>
                                                <span style="color: red " id="identity-error" class="image text-user hidden"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-9">
                                        
                                    <img id="changeidentity" src="{{asset('images/default.png')}}" width="100px" height="100px" alt=" {{trans('admin.image')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="radio inlineblock m-r-20">
                                        <input type="radio" name="status" id="active" class="with-gap" value="active" checked="">
                                        <label for="active">{{__('admin.active')}}</label>
                                    </div>                                
                                    <div class="radio inlineblock">
                                        <input type="radio" name="status" id="not_active" class="with-gap" value="not_active"  >
                                        <label for="not_active">{{__('admin.not_active')}}</label>
                                    </div>
                                </div>


                                <!-- <div class="form-group">
                                    <div class="radio inlineblock m-r-20">
                                        <input type="radio" name="available" id="online" class="with-gap" value="1" checked="">
                                        <label for="online">{{__('admin.online')}}</label>
                                    </div>                                
                                    <div class="radio inlineblock">
                                        <input type="radio" name="available" id="offline" class="with-gap" value="0"  >
                                        <label for="offline">{{__('admin.offline')}}</label>
                                    </div>
                                </div> -->


                            </div> <!--col-6-->

                            <div class="col-lg-6">
                                <div class= "form-group form-float"> 
                                <label  for="country_id" style=""> {{trans('admin.country')}} </label>
                                    {!! Form::select('country_id',$countries
                                        ,'',['class'=>'form-control show-tick','id'=>'country_info' ,'placeholder' =>trans('admin.choose_country')]) !!}
                                        <label id="country_id-error" class="error" for="country_id" style="">  </label>
                                </div>

                                <div class= "form-group form-float"> 
                                <label  for="city_id" style=""> {{trans('admin.city')}} </label>
                                    {!! Form::select('city_id',[]
                                        ,'',['class'=>'form-control selectpicker  show-tick ','id'=>'city_info' ,'placeholder' =>trans('admin.choose_city')]) !!}
                                        <label id="city_id-error" class="error" for="city_id" style="">  </label>
                                </div>

                                <div class= "form-group form-float"> 
                                <label  for="area_id" style=""> {{trans('admin.area')}} </label>
                                    {!! Form::select('area_id',[]
                                        ,'',['class'=>'form-control show-tick','id'=>'area_info' ,'placeholder' =>trans('admin.choose_area')]) !!}
                                        <label id="area_id-error" class="error" for="area_id" style="">  </label>
                                </div>


                                <div class="form-group form-float">
                                <label  for="address" style=""> {{trans('admin.address')}} </label>
                                    <input type="text" class="form-control" id="address-field1" placeholder="{{__('admin.placeholder_address')}}" name="address"  autocomplete="off">
                                    <label id="address-error" class="error" for="address" style=""></label>
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
                                    {!! Form::hidden('location[0]','',['class'=>'form-control', 'id' => 'lat','placeholder' => trans('admin.placeholder_lat')]) !!}

                                    {{--  {!! Form::label('lng',trans('admin.lng')) !!}  --}}
                                    {!! Form::hidden('location[1]','',['class'=>'form-control', 'id' => 'lng','placeholder' => trans('admin.placeholder_lng')]) !!}

                                </div><br/> 
                                <!-- end map -->

                               


                                
                            </div> <!--col-6-->
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn btn-raised btn-primary btn-round waves-effect" type="submit">{{__('admin.add')}}</button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA-A44M149_C_j4zWAZ8rTCFRwvtZzAOBE&libraries=places&signed_in=true&callback=initMap"></script>
<script>
function initMap() {
    // $('form').on('keyup keypress', function(e) {
    //     var keyCode = e.keyCode || e.which;
    //     if (keyCode === 13) {
    //         e.preventDefault();
    //         return false;
    //     }
    // });
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
 
 infoWindow.setContent('<div>location found</div>');
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
     console.log(event);
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
     console.log("place ",place);
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

}
function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    console.log("jheeee");
infoWindow.setPosition(pos);
infoWindow.setContent(browserHasGeolocation ?
     'The Geolocation service failed.  ' :
     'Your browser doesnt support geolocation. ');
}


$('#country_info').change(function(){
    console.log($('#country_info').val());
    $('#city_info').empty();
    $('#city_info').append(`<option value="">{{trans('admin.choose_city')}}</option>`); 
    $('#area_info').empty();
    $('#area_info').append(`<option value="">{{trans('admin.choose_area')}}</option>`); 
    cityids=[];
    console.log("{{$allcities}}");
    @foreach ($allcities as $data)
    console.log("{{$data->country_id}}");
    if("{{$data->country_id}}"==$('#country_info').val())
    {
        $('#city_info').append(`<option value="{{$data->id}}">{{$data->name}}</option>`); 
    }
            
        @endforeach
        $('#city_info').selectpicker('refresh');
    $('#city_info').selectpicker('render');
    $('#area_info').selectpicker('refresh');
    $('#area_info').selectpicker('render');
        
});

$('#city_info').change(function(){
    $('#area_info').empty();
    $('#area_info').append(`<option value="">{{trans('admin.choose_area')}}</option>`); 
    @foreach ($allareas as $data)
    if("{{$data->city_id}}"==$('#city_info').val())
    {
        $('#area_info').append(`<option value="{{$data->id}}">{{$data->name}}</option>`); 
    }
            
        @endforeach
    $('#area_info').selectpicker('refresh');
    $('#area_info').selectpicker('render');
        
});

    //this for add new record
    $("#form_validation").submit(function(e){
           {{--  $('#addModal').modal('hide');  --}}
           $('.add').disabled =true;
           $(':input[type="submit"]').prop('disabled', true);
          e.preventDefault();
          var form = $(this);
        //    openModal();
          $.ajax({
              type: 'POST',
              url: '{{ URL::route("storetechnician") }}',
              data:  new FormData($("#form_validation")[0]),
              processData: false,
              contentType: false,
               
              success: function(data) {
                  console.log(data);
                  if ((data.errors)) {   
                    $(':input[type="submit"]').prop('disabled', false); 
                    if (data.errors.email) {
                            $('#email-error').css('display', 'inline-block');
                            $('#email-error').text(data.errors.email);
                        }                    
                        if (data.errors.address) {
                            $('#address-error').css('display', 'inline-block');
                            $('#address-error').text(data.errors.address);
                        }
                        if (data.errors.mobile) {
                            $('#mobile-error').css('display', 'inline-block');
                            $('#mobile-error').text(data.errors.mobile);
                        }
                        if (data.errors.email) {
                            $('#email-error').css('display', 'inline-block');
                            $('#email-error').text(data.errors.email);
                        }
                        if (data.errors.name) {
                            $('#name-error').css('display', 'inline-block');
                            $('#name-error').text(data.errors.name);
                        }
                        if (data.errors.status) {
                            $('#status-error').css('display', 'inline-block');
                            $('#status-error').text(data.errors.status);
                        }
                        if (data.errors["location."+0]) {
                            $('#location-error').css('display', 'inline-block');
                            $('#location-error').text( data.errors["location."+0]);
                        }
                        if (data.errors.country_id) {
                            $('#country_id-error').css('display', 'inline-block');
                            $('#country_id-error').text(data.errors.country_id);
                        }
                        if (data.errors.city_id) {
                            $('#city_id-error').css('display', 'inline-block');
                            $('#city_id-error').text(data.errors.city_id);
                        }
                        if (data.errors.area_id) {
                            $('#area_id-error').css('display', 'inline-block');
                            $('#area_id-error').text(data.errors.area_id);
                        }
                        if (data.errors.nationality_id) {
                            $('#nationality_id-error').css('display', 'inline-block');
                            $('#nationality_id-error').text(data.errors.nationality_id);
                        }
                  } 
                  else {
                        window.location.replace("{{route('technicians')}}");

                     }
            },
        });
    });
    // $('.select2').select2();
   
</script>
@endsection
