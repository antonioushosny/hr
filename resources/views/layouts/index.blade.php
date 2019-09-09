<!doctype html>
<?php   
    $lang = session('lang');
    App::setLocale($lang);
    $lang = App::getlocale();
    if($lang == null){
        $lang ='ar';
    }
    if($title){
        $page = $title;
    }
    else {
        $page ='home';
    }
?>
<h4 class="no-js " lang="en">
<head>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Fannie 4U .">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>:: fannie ::</title>
<!-- <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">  -->
<link rel="shortcut icon" href="{{ asset('images/logo.png') }}" >

<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/jvectormap/jquery-jvectormap-2.0.3.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/plugins/morrisjs/morris.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert/sweetalert.css') }}"/>

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">

<!-- Bootstrap Material Datetime Picker Css -->
<link href="{{ asset('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet" />
<!-- Bootstrap Select Css -->
<link href="{{ asset('assets/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet" />




@yield('style')  
<style>

    .select2{
        width: 100%  !important ;
        
    }
    .select2-container--default .select2-selection--single {
        background-color: #fff;
        border: 1px solid #867e7e;
        border-radius: 25px;
        height: 40px;
        max-width: 100% !important ;
    }

    /* for text in select2 */
    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-left: 21px;
        padding-right: 20px;
    }
    /* for arrow in select2 */
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 26px;
        top: 8px;
        right: 8px;
        width: 17px;
    }
    .notificationimage{
        max-width: 15%;
    }
    .unread{
        background-color: #dedede;
    }

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
</style>
<!-- Custom Css -->
@if($lang=='ar')
<link href='https://fonts.googleapis.com/css?family=Cairo:200,300,400,600,700,900&subset=arabic,latin,latin-ext' rel='stylesheet' type='text/css'>
<style>
@import url(https://fonts.googleapis.com/css?family=Cairo:200,300,400,600,700,900&subset=arabic,latin,latin-ext);
body, html { 
    font-family: 'Cairo', sans-serif !important ;
}
.sidebar { 
    font-family: 'Cairo', sans-serif !important ;
    font-weight: bold !important ;
}

</style>
<link rel="stylesheet" href="{{ asset('assets/css/rtl.css')}}">
@endif

<!-- JQuery DataTable Css -->
<link rel="stylesheet" href="{{ asset('assets/plugins/jquery-datatable/dataTables.bootstrap4.min.css') }}">
</head>

<body class="theme-purple rtl">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img class="zmdi-hc-spin" src="{{ asset('images/logo_0.png') }}" width="48" height="48" alt="fannie"></div>
        <p>{{__('admin.Please_wait')}}...</p>        
    </div>
</div>
<!-- Overlay For Sidebars -->
<div class="overlay"></div>

<!-- Top Bar -->
<nav class="navbar p-l-5 p-r-5">
    <ul class="nav navbar-nav navbar-left">
        <li>
            <div class="navbar-header">
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="{{route('home')}}"><img src="{{ asset('assets/images/logo.png') }}" width="30" alt="fannie"><span class="m-l-10">fannie</span></a>
            </div>
        </li>
        <li><a href="javascript:void(0);" class="ls-toggle-btn" data-close="true"><i class="zmdi zmdi-swap"></i></a></li>
        @if($lang == 'ar')
        <li> <a class="" href=" {{route('setlang',['lang'=>'en'])}}">{{trans('admin.en')}}</a> </li>
        @else 
        <li> <a class="" href=" {{route('setlang',['lang'=>'ar'])}}">{{trans('admin.ar')}}</a> </li>
        @endif

        <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle notificaiton" data-toggle="dropdown" role="button" ><i class="zmdi zmdi-notifications"></i>
            <div class="notify"><span class="heartbit" style="color:black;"></span>
                <span class="point"  id="count" style=" right: -9px; top: -38px;font-size: 8px; color: #3d4c5a;">{{count(auth()->user()->unreadnotifications)}}</span>
            </div>
            </a>
            <ul class="dropdown-menu pullDown">
                <li class="body">
                    <ul class="menu list-unstyled " id="showNofication">
                        @if(sizeof(Auth()->user()->notifications) > 0)
                            @foreach(Auth()->user()->notifications as $note)
                              
                                <li>
                                    <a href="javascript:void(0);"  class="{{ $note->read_at == null ? 'unread' : '' }}">
                                        <div class="media">
                                            <img class="media-object notificationimage" src="{{ asset('assets/images/logo.png') }}" alt="">
                                            <div class="media-body">
                                                <span class="name"> <span class="time"> {!! $note->created_at  !!} </span></span><br>
                                                <span class="message">
                                                    @if($lang == 'ar')
                                                    {!! $note->data['data']['ar']  !!}  
                                                    @else 
                                                    {!! $note->data['data']['en']  !!}  
                                                    @endif  </span>                                        
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        @else 

                            <li>
                                <a href="javascript:void(0);">
                                    <div class="media">
                                        <img class="media-object notificationimage" src="{{ asset('assets/images/logo.png') }}" alt="">
                                        <div class="media-body">
                                            {{--  <span class="name">Sophia <span class="time"> </span></span>  --}}
                                            <span class="message">{{__('admin.no_notification_found')}}</span>                                        
                                        </div>
                                    </div>
                                </a>
                            </li>               
                        @endif
                    </ul>
                </li>
                {{--  <li class="footer"> <a href="javascript:void(0);">View All</a> </li>  --}}
            </ul>
        </li>
        <!-- <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"><i class="zmdi zmdi-flag"></i>
            <div class="notify">
                <span class="heartbit"></span>
                <span class="point"></span>
            </div>
            </a>
            <ul class="dropdown-menu pullDown">
                <li class="header">Project</li>
                <li class="body">
                    <ul class="menu tasks list-unstyled">
                        <li>
                            <a href="javascript:void(0);">
                                <div class="progress-container progress-primary">
                                    <span class="progress-badge">eCommerce Website</span>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="86" aria-valuemin="0" aria-valuemax="100" style="width: 86%;">
                                            <span class="progress-value">86%</span>
                                        </div>
                                    </div>                        
                                    <ul class="list-unstyled team-info">
                                        <li class="m-r-15"><small class="text-muted">Team</small></li>
                                        <li>
                                            <img src="{{ asset('assets/images/xs/avatar2.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('assets/images/xs/avatar3.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('assets/images/xs/avatar4.jpg') }}" alt="Avatar">
                                        </li>                            
                                    </ul>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <div class="progress-container progress-info">
                                    <span class="progress-badge">iOS Game Dev</span>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%;">
                                            <span class="progress-value">45%</span>
                                        </div>
                                    </div>
                                    <ul class="list-unstyled team-info">
                                        <li class="m-r-15"><small class="text-muted">Team</small></li>
                                        <li>
                                            <img src="{{ asset('assets/images/xs/avatar10.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('assets/images/xs/avatar9.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('assets/images/xs/avatar8.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('assets/images/xs/avatar7.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('assets/images/xs/avatar6.jpg') }}" alt="Avatar">
                                        </li>
                                    </ul>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <div class="progress-container progress-warning">
                                    <span class="progress-badge">Home Development</span>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="29" aria-valuemin="0" aria-valuemax="100" style="width: 29%;">
                                            <span class="progress-value">29%</span>
                                        </div>
                                    </div>
                                    <ul class="list-unstyled team-info">
                                        <li class="m-r-15"><small class="text-muted">Team</small></li>
                                        <li>
                                            <img src="{{ asset('assets/images/xs/avatar5.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('assets/images/xs/avatar2.jpg') }}" alt="Avatar">
                                        </li>
                                        <li>
                                            <img src="{{ asset('assets/images/xs/avatar7.jpg') }}" alt="Avatar">
                                        </li>                            
                                    </ul>
                                </div>
                            </a>
                        </li>                    
                    </ul>
                </li>
                <li class="footer"><a href="javascript:void(0);">View All</a></li>
            </ul>
        </li> -->
        <!-- <li class="hidden-sm-down">
            <div class="input-group">                
                <input type="text" class="form-control" placeholder="Search...">
                <span class="input-group-addon">
                    <i class="zmdi zmdi-search"></i>
                </span>
            </div>
        </li>  -->
        <li class="float-right">
            <!-- <a href="javascript:void(0);" class="fullscreen hidden-sm-down" data-provide="fullscreen" data-close="true"><i class="zmdi zmdi-fullscreen"></i></a> -->
            <!-- <a href="{{route('logout')}}" class="mega-menu" data-close="true"><i class="zmdi zmdi-power"></i></a> -->
            <a class="mega-menu" href="{{ route('logout') }}" data-close="true" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">  <i class="zmdi zmdi-power"></i></a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
            </form>
            <!-- <a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="zmdi zmdi-settings zmdi-hc-spin"></i></a> -->
        </li>
    </ul>
</nav>

<!-- Left Sidebar -->
<aside id="leftsidebar" class="sidebar">
    <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#dashboard"><i class="zmdi zmdi-home m-r-5"></i>{{__('fannie')}} </a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#user"><i class="zmdi zmdi-account m-r-5"></i> {{Auth::user()->name}} </a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane stretchRight active" id="dashboard">
            <div class="menu">
                <ul class="list">
                    <li>
                        <div class="user-info">
                            @if(Auth::user()->image != '' || Auth::user()->image != null)
                                <div class="image"><a href="javascript:void(0);"><img src="{{ asset('img/'.Auth::user()->image) }}" alt="{{Auth::user()->name}}"></a></div>
                            @else 
                                <div class="image"><a href="javascript:void(0);"><img src="{{ asset('assets/images/logo.png') }}" alt="{{Auth::user()->name}}"></a></div>
                            @endif
                            <div class="detail">
                                <h4>{{Auth::user()->name}}</h4>
                                
                                <small>{{Auth::user()->email}}</small>  
                            </div>
                        </div>
                    </li>
                    <!-- <li class="header">MAIN</li> -->
                    
                    <li <?php echo ($page == 'home') ? "class='active open'" : ""; ?> ><a href="{{ route('home') }}"  ><i class="zmdi zmdi-home"></i> <span> {{trans('admin.dashboard')}}</span></a></li>
                    
                    @can('role_list')
                    <li <?php echo ($page == 'roles') ? "class='active open'" : ""; ?> ><a href="{{ route('roles.index') }}"  ><i class="zmdi zmdi-accounts-add"></i> <span> {{trans('admin.roles')}}</span></a></li>
                    @endcan

                    @can('admin_list')
                    <li <?php echo ($page == 'admins') ? "class='active open'" : ""; ?> ><a href="{{ route('admins') }}"  ><i class="zmdi zmdi-accounts-add"></i> <span> {{trans('admin.admins')}}</span></a></li>
                    @endcan

                    @can('country_list')
                    <li <?php echo ($page == 'countries') ? "class='active open'" : ""; ?> ><a href="{{ route('countries') }}"  ><i class="zmdi zmdi-city"></i> <span> {{trans('admin.countries')}}</span></a></li>
                    @endcan

                    @can('city_list')
                    <li <?php echo ($page == 'cities') ? "class='active open'" : ""; ?> ><a href="{{ route('cities') }}"  ><i class="zmdi zmdi-city"></i> <span> {{trans('admin.cities')}}</span></a></li>
                    @endcan

                    @can('area_list')
                    <li <?php echo ($page == 'areas') ? "class='active open'" : ""; ?> ><a href="{{ route('areas') }}"  ><i class="zmdi zmdi-pin"></i> <span> {{trans('admin.areas')}}</span></a></li>
                    @endcan  

                    @can('nationality_list')
                    <li <?php echo ($page == 'nationalities') ? "class='active open'" : ""; ?> ><a href="{{ route('nationalities') }}"  ><i class="zmdi zmdi-city"></i> <span> {{trans('admin.nationalities')}}</span></a></li>
                    @endcan
                    
                    @can('service_list')
                    <li <?php echo ($page == 'services') ? "class='active open'" : ""; ?> ><a href="{{ route('services') }}"  ><i class="zmdi zmdi-washing-machine"></i> <span> {{trans('admin.services')}}</span></a></li>
                    @endcan
                    
                    @can('reasons_list')
                    <li <?php echo ($page == 'reasons') ? "class='active open'" : ""; ?> ><a href="{{ route('reasons') }}"  ><i class="zmdi zmdi-file-text"></i> <span> {{trans('admin.reasons')}}</span></a></li>
                    @endcan

                    @can('subscription_type_list')
                    <li <?php echo ($page == 'subscriptions') ? "class='active open'" : ""; ?> ><a href="{{ route('subscriptions') }}"  ><i class="zmdi zmdi-money"></i> <span> {{trans('admin.subscriptions')}}</span></a></li>
                    @endcan

                    @can('user_list')                    
                    <li <?php echo ($page == 'users') ? "class='active open'" : ""; ?> ><a href="{{ route('users') }}"  ><i class="zmdi zmdi-accounts"></i> <span> {{trans('admin.users')}}</span></a></li>
                    @endcan

                    
                    @can('technical_list')                    
                    <li <?php echo ($page == 'technicians') ? "class='active open'" : ""; ?> ><a href="{{ route('technicians') }}"  ><i class="zmdi zmdi-accounts-alt"></i> <span> {{trans('admin.technicians')}}</span></a></li>
                    @endcan

                    
                    @can('subscription_list')                    
                    <li <?php echo ($page == 'subscriptions_tech') ? "class='active open'" : ""; ?> ><a href="{{ route('techsubscriptions') }}"  ><i class="zmdi zmdi-money-box"></i> <span> {{trans('admin.subscriptions_tech')}}</span></a></li>
                    @endcan


                    @can('order_list')                    
                    <li <?php echo ($page == 'orders') ? "class='active open'" : ""; ?> ><a href="{{ route('orders') }}"  ><i class="zmdi zmdi-group-work"></i> <span> {{trans('admin.orders')}}</span></a></li>
                    @endcan

                    @can('contact_list')
                    <li <?php echo ($page == 'contacts') ? "class='active open'" : ""; ?> ><a href="{{ route('contacts') }}"  ><i class="zmdi zmdi-email"></i> <span> {{trans('admin.contacts')}}</span></a></li>
                    @endcan

                    @can('static_page_list')
                    <li <?php echo ($page == 'AboutUs' || $page == 'Terms' || $page == 'Policy') ? "class='active open'" : ""; ?> > <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-settings"></i><span>{{trans('admin.settings')}}</span> </a>
                        <ul class="ml-menu">
                            <li <?php echo ($page == 'AboutUs') ? "class='active open'" : ""; ?> ><a href="{{ route('settings','about') }}"  > <span> {{trans('admin.AboutUs')}}</span></a></li>

                            <li <?php echo ($page == 'Terms') ? "class='active open'" : ""; ?> ><a href="{{ route('settings','term') }}"  > <span> {{trans('admin.Terms')}}</span></a></li>

                            <li <?php echo ($page == 'Policy') ? "class='active open'" : ""; ?> ><a href="{{ route('settings','policy') }}"  > <span> {{trans('admin.Policy')}}</span></a></li>
                            
                            <li <?php echo ($page == 'bank') ? "class='active open'" : ""; ?> ><a href="{{ route('settings','bank') }}"  > <span> {{trans('admin.banks_accounts')}}</span></a></li>
                        </ul>
                    </li> 
                    @endcan


                    
                </ul>
            </div>
        </div>
        <div class="tab-pane stretchLeft" id="user">
            <div class="menu">
                <ul class="list">
                    <li>
                        <div class="user-info m-b-20 p-b-15">
                            @if(Auth::user()->image != '' || Auth::user()->image != null)
                                <div class="image"><a href="javascript:void(0);"><img src="{{ asset('img/'.Auth::user()->image) }}" alt="{{Auth::user()->name}}"></a></div>
                            @else 
                                <div class="image"><a href="javascript:void(0);"><img src="{{ asset('assets/images/logo.png') }}" alt="{{Auth::user()->name}}"></a></div>
                            @endif
                            <div class="detail">
                                <h4>{{Auth::user()->name}}</h4>
                                @if(Auth::user()->role == 'provider')
                                <h4>{{Auth::user()->company_name}}</h4>  
                                @endif
                                <small>{{Auth::user()->email}}</small>  
                                             
                                @if(Auth::user()->role == 'provider')
                                <p class="text-muted">{{Auth::user()->address}}</p>
                                @endif
                            
                            </div>
                        </div>
                    </li>
                    <li>
                        <small class="text-muted">{{__('admin.email')}}: </small>
                        <p>{{Auth::user()->email}}</p>
                        <hr>
                        <small class="text-muted">{{__('admin.mobile')}}: </small>
                        <p>{{Auth::user()->mobile}}</p>
                        <hr>
                        <button class="btn btn-raised btn-primary btn-round waves-effect" id="btneditprofile">{{__('admin.edit_profile')}}</button>

                        <ul class="list-unstyled " id="ulformeditprofile" style="display:none">
                            {!! Form::open(['route'=>['editprofile'],'method'=>'post','autocomplete'=>'off', 'enctype'=>'multipart/form-data', 'id'=>'form_validations'])!!} 

                            <li>
                                <input type="hidden" value="{{Auth::user()->id}}" name="id" required>
                            </li>
                            <li>
                                <div>{{__('admin.email')}}</div>
                                <div class="m-t-10 m-b-20">
                                    <input type="email" value="{{Auth::user()->email}}" class="form-control" placeholder="{{__('admin.placeholder_email')}}" name="email" autocomplete="off" >
                                    <label id="emails-error" class="error" for="email" style="font-size: 12px;"></label>
                                </div>
                            </li>
                            <li>
                                <div>{{__('admin.mobile')}}</div>
                                <div class="m-t-10 m-b-20">
                                    <input type="text" value="{{Auth::user()->mobile}}" class="form-control" placeholder="{{__('admin.mobile')}}" name="mobile" >
                                    <label id="mobiles-error" class="error" for="mobile" style="font-size: 12px;">  </label>
                                </div>
                            </li>
                            <li>
                                <div>{{__('admin.password')}}</div>
                                <div class="m-t-10 m-b-20">
                                    <input type="password"  class="form-control" placeholder="{{__('admin.placeholder_password')}}" name="password"  autocomplete="new-password">
                                    <label id="passwords-error" class="error" for="password" style="font-size: 12px;"></label>
                                </div>
                            </li>
                            <li>
                                <div>{{__('admin.image')}}</div>
                                <div class="form-group form-float row" >
                                    {{--  for image  --}}
                                    <div class= "col-md-8 col-xs-12">
                                        <div class="form-group form-float  " >
                                            <div style="position:relative; ">
                                                <a class='btn btn-primary' href='javascript:void(0);'  style="color: white;">
                                                    {{trans('admin.Choose_Image')}}
            
                                                    {!! Form::file('image',['class'=>'form-control','id' => 'images_field', 'accept'=>'image/x-png,image/gif,image/jpeg' ,'style'=>'position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;','size'=> '40' ,'onchange' => 'readURL(this,"changeimages");' ]) !!}
                                                </a>
                                                &nbsp;
                                                <div class='label label-primary' id="upload-file-infos" ></div>
                                                <span style="color: red " class="image text-center hidden"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        
                                        @if(Auth::user()->image)
                                            <img id="changeimages" src="{{asset('img/'.Auth::user()->image)}}" width="100px" height="100px" alt=" {{trans('admin.image')}}" />
                                        @else 
                                            <img id="changeimage" src="{{asset('images/default.png')}}" width="100px" height="100px" alt=" {{trans('admin.image')}}" />
                                        @endif
                                    </div>
                                </div>

                            </li>
                            <li> 
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn btn-raised btn-primary btn-round waves-effect" type="submit">{{__('admin.edit')}}</button>
                            </li>
                        </form>
                        </ul>                        
                    </li>
                </ul>
            </div>
        </div>
    </div>    
</aside>

@yield('content')   

<!-- Jquery Core Js --> 
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js ( jquery.v3.2.1, Bootstrap4 js) --> 
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script> <!-- slimscroll, waves Scripts Plugin Js -->
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>

<!-- // for form validations  -->
@if($lang=='ar')
<script src="{{ asset('assets/plugins/jquery-validation/jquery.validate-ar.js') }}"></script> <!-- Jquery Validation Plugin Css --> 
@else 
<script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.js') }}"></script> <!-- Jquery Validation Plugin Css --> 
@endif
<script src="{{ asset('assets/plugins/jquery-steps/jquery.steps.js') }}"></script> <!-- JQuery Steps Plugin Js --> 
<script src="{{ asset('assets/js/pages/forms/form-validation.js') }}"></script> 
  <!-- //for  dialogs  -->
<script src="{{ asset('assets/plugins/sweetalert/sweetalert.min.js') }}"></script> <!-- SweetAlert Plugin Js --> 
<script src="{{ asset('assets/js/pages/ui/dialogs.js') }}"></script>
 
<script src="{{ asset('assets/plugins/momentjs/moment.js') }}"></script> <!-- Moment Plugin Js --> 
<!-- Bootstrap Material Datetime Picker Plugin Js --> 
<script src="{{ asset('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script> 
<script src="{{ asset('assets/plugins/bootstrap-select/js/bootstrap-select.js') }}"></script> 
<script src="{{ asset('assets/js/pages/forms/basic-form-elements.js') }}"></script> 

<!-- Jquery DataTable Plugin Js --> 
<script src="{{ asset('assets/bundles/datatablescripts.bundle.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-datatable/buttons/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-datatable/buttons/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-datatable/buttons/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-datatable/buttons/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-datatable/buttons/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/tables/jquery-datatable.js') }}"></script> 
    <script>
    function isNumber(e){
            var key = e.charCode;  
            if( key <48 || key >57 )
            {
				if (key != 0)
				{
                e.preventDefault();   
				}
				          
            }
    }
        $('#btneditprofile').on('click',function(){

            $('#btneditprofile').css('display', 'none');
            $('#ulformeditprofile').css('display', 'inline-block');
        })
        function isNumber(e){
            var key = e.charCode;  
            if( key <48 || key >57 )
            {
                if (key != 0)
                {
                e.preventDefault();   
                }
                            
            }
        }
        function readURL(input,imagediv) {
            
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                src = document.getElementById(imagediv).src;    
                imag = "{{asset('images/addimage.png')}}" ;
                if(imag != src){
                    arrayimages = src ;
                }
                reader.onload = function (e) {
                    $('#'+imagediv).attr('src', e.target.result);
                }
        
                reader.readAsDataURL(input.files[0]);
            }
        }
        var message, ShowDiv = $('#showNofication'), count = $('#count'), c;

        $('.notificaiton').on('click' , function(){
            setTimeout( function(){
                count.html(0);
                $('.unread').each(function(){
                    $(this).removeClass('unread');
                });
            }, 5000);
            $.get( "{{route('MarkAllSeen') }}" , function(){});
        });

        function randomPassword(length) {
            var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOP1234567890";
            var pass = "";
            for (var x = 0; x < length; x++) {
                var i = Math.floor(Math.random() * chars.length);
                pass += chars.charAt(i);
            }
            return pass;
        }
        
        function generate() {
            myform.password.value = randomPassword(6);
        }
        $("#form_validations").submit(function(e){
            $('.add').disabled =true;
            e.preventDefault();
            var form = $(this);
             $.ajax({
                type: 'POST',
                url: '{{ URL::route("editprofile") }}',
                data:  new FormData($("#form_validations")[0]),
                processData: false,
                contentType: false,
                
                success: function(data) {
                    $('.name').addClass('hidden');
                    $('.email').addClass('hidden');
                    $('.password').addClass('hidden');
                    $('.image').addClass('hidden');
                     $('.mobile').addClass('hidden');
    
                    if ((data.errors)) {                        
                        // toastr.error('{{trans('admin.Validation_error')}}', '{{trans('admin.Error_Alert')}}', {timeOut: 5000});
                        if (data.errors.mobile) {
                            $('#mobiles-error').css('display', 'inline-block');
                            $('#mobiles-error').text(data.errors.mobile);
                        }
                        if (data.errors.email) {
                            $('#emails-error').css('display', 'inline-block');
                            $('#emails-error').text(data.errors.email);
                        }
                        if (data.errors.password) {
                            $('#passwords-error').css('display', 'inline-block');
                            $('#passwords-error').text(data.errors.password);
                        }
                        
                    } else {
                       location.reload();
    
                        }
            },
            });
        });
   
    </script> 

    

    @yield('script')
    <script src="https://www.gstatic.com/firebasejs/6.2.2/firebase.js"></script>
    <script>
        
        var config = {
            apiKey: "AIzaSyB7plMdLEI9IkHEYQIYHI_btxj5sYElhn8",
            authDomain: "elsalamapp.firebaseapp.com",
            databaseURL: "https://elsalamapp.firebaseio.com",
            projectId: "elsalamapp",
            storageBucket: "elsalamapp.appspot.com",
            messagingSenderId: "844700117021",
            appId: "1:844700117021:web:afdaf9090454799d"
        };
        firebase.initializeApp(config);
        const messaging = firebase.messaging();
        messaging
            .requestPermission()
            .then(function () {
                {{--  console.log("Notification permission granted.");  --}}
                // get the token in the form of promise
                return messaging.getToken()
            })
            .then(function(token) {
                {{--  console.log("token is : " + token);  --}}
                if(token){
                    $.ajax({
                        url: "<?php echo url('/')?>/token/"+token,
                        success: data => {
                        }
                    })
                }
            })
            .catch(function (err) {
                console.log("Unable to get permission to notify.", err);
            });
            messaging.onMessage(function(payload) {
                c = parseInt(count.html());
                count.html(c+1);
                var start = Date.now();
                    ShowDiv.prepend(`<li>
                            <a href="javascript:void(0);"  class="unread">
                                <div class="media">
                                    <img class="media-object notificationimage" src="{{ asset('assets/images/logo.png') }}" alt="">
                                    <div class="media-body">
                                        <span class="name"> <span class="time"> `+payload.data.date +`  </span></span><br>
                                        <span class="message"> `+payload.data.message +  ` </span>                                        
                                    </div>
                                </div>
                            </a>
                        </li> `);
             

            });

    </script>
    <script>
        table =  $('.js-exportable-ar').DataTable({
            "language": {
                "url": "{{asset('datatablelang.json')}}"
            },
            dom: 'Bfrtip',
            // dom: 'Blfrtip',
            buttons: [
                'copy', {
                    extend: 'csv',
                    text: 'csv',
                    charset: 'utf-8',
                    extension: '.csv',
                    filename: 'export',
                    bom: true
                }, 'excel', 'pdf', 'print'
            ],   
        });
     </script>
</body>
</h4>