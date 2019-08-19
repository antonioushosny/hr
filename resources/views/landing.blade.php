<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
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
  <link href="{{ asset('front/css/new-age.css') }}" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
    <div class="container" >
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav  ">
                <li class="nav-item ">
                    <a class="nav-link js-scroll-trigger  " href="#info"> <img src="{{ asset('front/img/logo-en.png') }}" class="img-fluid" alt=""> </a>
                </li>
             
                <li class="nav-item navright">
                    <a class="nav-link js-scroll-trigger navright" href="#about-us">About us</a>
                </li>
                <li class="nav-item navright">
                    <a class="nav-link js-scroll-trigger navright" href="#features">Features</a>
                </li>
                <li class="nav-item navright">
                    <a class="nav-link js-scroll-trigger navright" href="#download">Download</a>
                </li>
                <li class="nav-item navright">
                    <a class="nav-link js-scroll-trigger navright" href="#contact">Contact</a>
                </li>
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

   <header class="masthead" id="info">
    <div class="container h-100">
      <div class="row h-100">
        <div class="col-lg-7 my-auto">
          <div class="header-content mx-auto">
            <h1> <span class="khazantilte"> khazan </span> <span> App </span></h1>  
            <h6 class="mb-5"> To deliver drinking water of all kinds  </h6>
            <h3 class="mb-5"> Download App Now </h3>
           
           
            <a href="#download" > <img src="{{ asset('front/img/android.png') }}"  alt=""> </a>
            <a href="#download" > <img src="{{ asset('front/img/apple.png') }}"    alt=""> </a>
          </div>
        </div>
        <div class="col-lg-5 my-auto">
          <div class="device-container">
            <div class="">
              <div class="device">
                <div class="screen">
                  <!-- Demo image for screen mockup, you can put an image here, some HTML, an animation, video, or anything else! -->
                  <img src="{{ asset('front/img/hero_banner.png') }}" class="img-fluid" alt="">
                </div>
                <div class="button">
                  <!-- You can hook the "home button" to some JavaScript events or just remove it -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <section class="download bg-primary" id="about-us">
    <div class="container">
      <div class="row">
        <div class="col-md-8 mx-auto  text-center">
          <h2 class="section-heading">About App</h2>
          <img src="{{ asset('front/img/heading-bg.png') }}" class="img-fluid" alt="">
  
        </div>
        <div class="col-md-6 about-section-desc">
                <h3>with khazan App</h3>
                <h6 class="mb-3">with khazan App with khazan App with khazan App with khazan App with khazan App with khazan App with khazan App with khazan App with khazan App  </h6>

                <h6 class="mb-5">with khazan App with khazan App with khazan App with khazan App with khazan App with khazan App with khazan App with khazan App with khazan App with khazan App with khazan App with khazan App with khazan App with khazan App with khazan App with khazan App with khazan App with khazan App with khazan App with khazan App</h6>
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
        <h2>Unlimited Features </h2>
        <img src="{{ asset('front/img/heading-bg.png') }}" class="img-fluid" alt="">
        <hr>
      </div>
       
      <div class="row">
          
        <div class="col-lg-4 my-auto">
            <div class="container-fluid">
                <div class="row">
                <div class="col-lg-6">
                    <div class="feature-item">
                    <i class="icon-screen-smartphone text-primary"></i>
                    <h3>Device Mockups</h3>
                    <p class="text-muted">Ready to use HTML/CSS device mockups, no Photoshop required!</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="feature-item">
                    <i class="icon-camera text-primary"></i>
                    <h3>Flexible Use</h3>
                    <p class="text-muted">Put an image, video, animation, or anything else in the screen!</p>
                    </div>
                </div>
                </div>
                <div class="row">
                <div class="col-lg-6">
                    <div class="feature-item">
                    <i class="icon-present text-primary"></i>
                    <h3>Free to Use</h3>
                    <p class="text-muted">As always, this theme is free to download and use for any purpose!</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="feature-item">
                    <i class="icon-lock-open text-primary"></i>
                    <h3>Open Source</h3>
                    <p class="text-muted">Since this theme is MIT licensed, you can use it commercially!</p>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4  ">
            <div class=" device-container text-center">
                <div class="  white">
                <div >
                    <div class="screen">
                     <img src="{{ asset('front/img/how_phone.png') }}" class="img-fluid" alt="">
                    </div>
                    <div class="button">
                    <!-- You can hook the "home button" to some JavaScript events or just remove it -->
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4  ">
          <div class=" ">
            <div class="row">
                <div class="col-lg-12">
                    <div class="feature-item feature-div">
                        <span class="text-muted feature-text-number">1</span>
                        <h3 class=" feature-text">Device Mockups</h3>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="feature-item feature-div feature-middle">
                        <span class="text-muted feature-text-number">2</span>
                        <h3 class=" feature-text">Device Mockups</h3>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="feature-item feature-div">
                        <span class="text-muted feature-text-number">3</span>
                    <h3 class=" feature-text">Device Mockups</h3>
                    </div>
                </div
            </div>
             
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="cta">
    <div class="cta-content">
      <div class="container">
        <h2>Stop waiting.<br>Start building.</h2>
        <a href="#contact" class="btn btn-outline btn-xl js-scroll-trigger">Let's Get Started!</a>
      </div>
    </div>
    <div class="overlay"></div>
  </section>

  <section class="contact bg-primary" id="contact">
    <div class="container">
      <h2>We
        <i class="fas fa-heart"></i>
        new friends!</h2>
      <ul class="list-inline list-social">
        <li class="list-inline-item social-twitter">
          <a href="#">
            <i class="fab fa-twitter"></i>
          </a>
        </li>
        <li class="list-inline-item social-facebook">
          <a href="#">
            <i class="fab fa-facebook-f"></i>
          </a>
        </li>
        <li class="list-inline-item social-google-plus">
          <a href="#">
            <i class="fab fa-google-plus-g"></i>
          </a>
        </li>
      </ul>
    </div>
  </section>

  <footer>
    <div class="container">
      <p>&copy; Your Website 2019. All Rights Reserved.</p>
      <ul class="list-inline">
        <li class="list-inline-item">
          <a href="#">Privacy</a>
        </li>
        <li class="list-inline-item">
          <a href="#">Terms</a>
        </li>
        <li class="list-inline-item">
          <a href="#">FAQ</a>
        </li>
      </ul>
    </div>
  </footer>
 
  <!-- Bootstrap core JavaScript -->
  <script src="{{ asset('front/vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('front/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  <!-- Plugin JavaScript -->
  <script src="{{ asset('front/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

  <!-- Custom scripts for this template -->
  <script src="{{ asset('front/js/new-age.min.js') }}"></script>

</body>

</html>
