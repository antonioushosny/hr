<!DOCTYPE html>

<?php   
    $lang = session('lang');
    App::setLocale($lang);
    $lang = App::getlocale();
    if($lang == null){
        $lang ='ar';
    }
     
?>
@if($lang == 'ar')
<html lang="en" dir="rtl">
@else
<html lang="en" >
@endif

<head>

      
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Khazan App .">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href='https://fonts.googleapis.com/css?family=Reem+Kufi:400&subset=arabic,latin' rel='stylesheet' type='text/css'>
  <title>:: Khazan ::</title>
 <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" >

  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="author" content="">

  <title>New Age - Start Bootstrap Theme</title>

  <!-- Bootstrap core CSS -->
  <link href="{{ asset('front/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

  <!-- Custom fonts for this template -->
  <link href="{{ asset('front/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('front/vendor/simple-line-icons/css/simple-line-icons.css') }}">
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">

  <!-- Plugin CSS -->
  <link rel="stylesheet" href="{{ asset('front/device-mockups/device-mockups.min.css') }}">

  <!-- Custom styles for this template -->
  @if($lang == 'ar')
  <link href="{{ asset('front/css/new-age-ar.css') }}" rel="stylesheet">
  @else
  <link href="{{ asset('front/css/new-age.css') }}" rel="stylesheet">
  @endif
</head>

<body id="page-top" class="rtl">
        <button onclick="topFunction()" id="myBtn" title="Go to top"> <i class="fa fa-arrow-up"  ></i>
        </button>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
    <div class="container" >
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav  ">
                <li class="nav-item ">
                    <a class="nav-link js-scroll-trigger  " href="#info"> <img src="{{ asset('front/img/logo-en.png') }}" class="img-fluid" alt=""> </a>
                </li>
             
                <li class="nav-item navright">
                    <a class="nav-link js-scroll-trigger navright" href="#about-us"> {{ __('admin.about_us') }}</a>
                </li>
                <li class="nav-item navright">
                    <a class="nav-link js-scroll-trigger navright" href="#features">{{ __('admin.Features') }}</a>
                </li>
                <li class="nav-item navright">
                    <a class="nav-link js-scroll-trigger navright" href="#download">{{ __('admin.Download') }}</a>
                </li>
                <li class="nav-item navright">
                    <a class="nav-link js-scroll-trigger navright" href="#contact">{{ __('admin.Contact_us') }}</a>
                </li>
                @if($lang == 'ar')
                <li class="nav-item navright"> <a class="nav-link js-scroll-trigger navright" href=" {{route('setlang',['lang'=>'en'])}}">{{trans('admin.en')}}</a> </li>
                @else 
                <li class="nav-item navright"> <a class="nav-link js-scroll-trigger navright" href=" {{route('setlang',['lang'=>'ar'])}}">{{trans('admin.ar')}}</a> </li>
                @endif
            </ul>
        </div>
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        Menu
        <i class="fas fa-bars"></i>
      </button>
            <a class="nav-link js-scroll-trigger" href="#"> <i class="fab fa-facebook-f"></i> </a>
            <a class="nav-link js-scroll-trigger" href="#"> <i class="fab fa-instagram"></i> </a>
            <a class="nav-link js-scroll-trigger" href="#"> <i class="fab fa-skype"></i> </a>
            <a class="nav-link js-scroll-trigger" href="#"> <i class="fab fa-twitter"></i> </a>
            <a class="nav-link js-scroll-trigger" href="#"> <i class="fab fa-youtube"></i> </a>
        
     </div>
  </nav>

   <header class="masthead bg-primary" id="info">
    <div class="container h-100">
      <div class="row h-100">
        <div class="col-lg-7 my-auto">
          <div class="header-content mx-auto">
            @if($lang =='ar')
            <h1>  <span> {{ __('admin.App') }}  </span> <span class="khazantilte"> {{ __('admin.khazan') }} </span></h1>
            @else  
            <h1> <span class="khazantilte"> {{ __('admin.khazan') }} </span> <span> {{ __('admin.App') }}  </span></h1>  
            @endif
            <h6 class="mb-5"> {{ __('admin.To_deliver_drinking') }}   </h6>
            <h3 class="mb-5">  {{ __('admin.Download_App_Now') }} </h3>
           
           
            <a href="#" > <img src="{{ asset('front/img/android.png') }}"  alt=""> </a>
            <a href="#" > <img src="{{ asset('front/img/apple.png') }}"    alt=""> </a>
          </div>
        </div>
        <div class="col-lg-5 my-auto">
          <div class="device-container">
            <div class="">
              <div class="device">
                <div class="screen">
                  
                  <img src="{{ asset('front/img/hero_banner.png') }}" class="img-fluid" alt="">
                </div>
                 
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <section class="about-us  bg-about" id="about-us">
    <div class="container">
      <div class="row">
        <div class="col-md-8 mx-auto  text-center">
          <h2 class="section-heading">{{ __('admin.About_app') }}</h2>
          <img src="{{ asset('front/img/heading-bg.png') }}" class="img-fluid" alt="">
  
        </div>
        <div class="col-md-6 about-section-desc">
                <h3>{{ __('admin.with_khazan') }} </h3>
                <h6 class="mb-3">{{ __('admin.you_can_now_order') }} 
                    </h6>

                <h6 class="mb-5"> {{ __('admin.he_application_of_the_tank') }}  
                     </h6>
        </div>
        <div class="col-md-6">
                <img src="{{ asset('front/img/about.png') }}" class="img-fluid" alt="">
        </div>
      </div>
    </div>
  </section>

  <section class="features" id="features">
    <div class="container">
      <div class="section-heading text-center">
        <h2>{{ __('admin.How_the_app_works') }} </h2>
        <img src="{{ asset('front/img/heading-bg.png') }}" class="img-fluid" alt="">
        <hr>
      </div>
       
      <div class="row">
          
        <div class="col-lg-4 ">
            <div class="container-fluid ">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="feature-item">
                        <span class="feature-text-number"> 1 </span>
                        <h3 class=" feature-text">{{__('admin.Download_the_app')}}</h3>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="feature-item feature-middle-left"  >
                        <span class="feature-text-number"> 2 </span>
                        <h3 class=" feature-text">{{__('admin.Record_your_data')}}</h3>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="feature-item">
                        <span  class="feature-text-number"> 3</span>
                        <h3 class=" feature-text" >{{__('admin.Browse_products')}}</h3>
                        </div>
                    </div>
                 
                </div>
                
            </div>
        </div>
        <div class="col-lg-4  ">
            <div class=" device-container text-center">
                <div class="white">
                <div>
                    <div class="screen">
                     <img src="{{ asset('front/img/how_phone.png') }}" class="img-fluid" alt="">
                    </div>
                   
                </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4  ">
          <div class="container-fluid  ">
            <div class="row">
                <div class="col-lg-12">
                    <div class="feature-item">
                    <span class="feature-text-number"> 4 </span>
                    <h3 class=" feature-text">{{__('admin.Choose_your_favorite')}}</h3>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="feature-item feature-middle-right"  >
                    <span class="feature-text-number"> 5 </span>
                    <h3 class=" feature-text">{{__('admin.Confirm_your_purchase')}}</h3>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="feature-item">
                    <span class="feature-text-number"> 6 </span>
                    <h3 class=" feature-text">{{__('admin.We_deliver_your_request')}}</h3>
                    </div>
                </div>
            </div>
             
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="download bg-download" id="download">
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto  text-center mb-5">
                <h3 class="section-heading">{{__('admin.Download_App_Now')}}</h3>
                <img src="{{ asset('front/img/heading-bg.png') }}" class="img-fluid" alt="">
        
            </div>
            
            <div class="col-md-8 mx-auto  text-center">
                <a href="#" > <img src="{{ asset('front/img/android.png') }}"  alt=""> </a>
                <a href="#" > <img src="{{ asset('front/img/apple.png') }}"    alt=""> </a>
        
            </div>
        </div>
    </div>
    </section>

  <section class="contact bg-contact" id="contact">
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto  text-center mb-5">
                <h3 class="section-heading">{{__('admin.Contact_us')}} </h3>
                <img src="{{ asset('front/img/heading-bg.png') }}" class="img-fluid" alt="">
        
            </div>
            
            <div class="col-md-8 mx-auto  text-center">
                    <div class="form-box">
                            
                          {!! Form::open(['route'=>['contact_us'],'method'=>'post','autocomplete'=>'off', 'enctype'=>'multipart/form-data' ])!!} 
                            <div class="form-group">
                             <input class="form-control" id="name" type="text" name="name" placeholder="{{__('admin.name')}}" required>
                            </div>
                            <div class="form-group">
                              <input class="form-control" id="title" type="text" name="title" placeholder="{{__('admin.title')}}" required>
                            </div>
                            <div class="form-group">
                             <input class="form-control" id="email" type="email" name="email" placeholder="{{__('admin.email')}}" required>
                            </div>
                            <div class="form-group">
                             <textarea class="form-control" id="message" rows="6" name="message" placeholder="{{__('admin.message')}}" required></textarea>
                            </div>
                            <input class="btn btn-primary" type="submit" value="{{__('admin.send')}}" />
                            </div>
                        </form>
                    </div>
        
            </div>
        </div>
    </div>
  </section>
    <section class="more bg-more" id="more">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mx-auto  text-center mb-3">
                    <h2 class="section-heading "> {{__('admin.For_mor_information')}}   </h2>
                </div>
                
                <div class="col-md-8 mx-auto  text-center">
                    <h3 class="section-heading"> {{__('admin.Contact_us')}}    <span style="color:#002c8f ;font-weight: bold;"> +966 123 45 68 </span> </h3>

                </div>
            </div>
        </div>
    </section>
  <footer>
    <div class="container">
      <p>&copy; {{__('admin.copyRights')}}.</p>
       
    </div>
  </footer>
 
  <!-- Bootstrap core JavaScript -->
  <script src="{{ asset('front/vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('front/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  <!-- Plugin JavaScript -->
  <script src="{{ asset('front/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

  <!-- Custom scripts for this template -->
  <script src="{{ asset('front/js/new-age.min.js') }}"></script>
  <script>
        window.onscroll = function() {scrollFunction()};

        function scrollFunction() {
          if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            document.getElementById("myBtn").style.display = "block";
          } else {
            document.getElementById("myBtn").style.display = "none";
          }
        }
        
        // When the user clicks on the button, scroll to the top of the document
        function topFunction() {
          document.body.scrollTop = 0;
          document.documentElement.scrollTop = 0;
        }
  </script>
</body>

</html>
