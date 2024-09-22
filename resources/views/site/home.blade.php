<!doctype html>
<html lang="en" dir="ltr">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Sash – Bootstrap 5  Admin & Dashboard Template">
    <meta name="author" content="Spruko Technologies Private Limited">
    <meta name="keywords"
        content="admin,admin dashboard,admin panel,admin template,bootstrap,clean,dashboard,flat,jquery,modern,responsive,premium admin templates,responsive admin,ui,ui kit.">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('../assets/images/brand/favicon.ico') }}" />

    <!-- TITLE -->
    <title>{{ env('APP_NAME') }} – CRM</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="{{ asset('../assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />

    <!-- STYLE CSS -->
    <link href="{{ asset('../assets/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('../assets/css/dark-style.css') }}" rel="stylesheet" />

    <!--- FONT-ICONS CSS -->
    <link href="{{ asset('../assets/css/icons.css') }}" rel="stylesheet" />

    <!-- COLOR SKIN CSS -->
    <link id="theme" rel="stylesheet" type="text/css" media="all"
        href="{{ asset('../assets/colors/color1.css') }}" />

</head>

<body class="app ltr landing-page horizontal">

    <!-- GLOBAL-LOADER -->
    <div id="global-loader">
        <img src="{{ asset('../assets/images/loader.svg') }}" class="loader-img" alt="Loader">
    </div>
    <!-- /GLOBAL-LOADER -->

    <!-- PAGE -->
    <div class="page">
        <div class="page-main">

            <!-- app-Header -->
            <div class="hor-header header">
                <div class="container main-container">
                    <div class="d-flex">
                        <!-- sidebar-toggle-->
                        <a class="logo-horizontal " href="index.html">
                            <img src="../assets/images/brand/logo.png" class="header-brand-img desktop-logo"
                                alt="logo">
                            <img src="../assets/images/brand/logo-3.png" class="header-brand-img light-logo1"
                                alt="logo">
                        </a>
                        <!-- LOGO -->
                        <div class="d-flex order-lg-2 ms-auto header-right-icons">
                            <button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto" type="button"
                                data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4"
                                aria-controls="navbarSupportedContent-4" aria-expanded="false"
                                aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon fe fe-more-vertical"></span>
                            </button>
                            <div class="navbar navbar-collapse responsive-navbar p-0">
                                <div class="collapse navbar-collapse bg-white px-0" id="navbarSupportedContent-4">
                                    <!-- SEARCH -->
                                    <div class="header-nav-right p-5">
                                        <a href="login.html" class="btn ripple btn-min w-sm btn-primary me-2 my-auto"
                                            target="_blank">Login
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /app-Header -->

            <div class="landing-top-header overflow-hidden">
                <div class="top sticky overflow-hidden">
                    <!--APP-SIDEBAR-->
                    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
                    <div class="app-sidebar bg-transparent horizontal-main">
                        <div class="container">
                            <div class="row">
                                <div class="main-sidemenu navbar px-0">
                                    <a class="navbar-brand ps-0 d-none d-lg-block" href="index.html">
                                        <img alt="" class="logo-2"
                                            src="{{ asset('../assets/images/brand/logo-3.png') }}">
                                        <img src="{{ asset('../assets/images/brand/logo.png') }}" class="logo-3"
                                            alt="logo">
                                    </a>
                                    <div class="header-nav-right d-none d-lg-flex">
                                        @if (Auth::check())
                                            <a href="{{ route('admin.dashboard') }}"
                                                class="btn ripple btn-min w-sm btn-primary me-2 my-auto d-lg-none d-xl-block d-block">
                                                Dashboard
                                            </a>
                                        @else
                                            <a href="{{ route('admin.view.login') }}"
                                                class="btn ripple btn-min w-sm btn-primary me-2 my-auto d-lg-none d-xl-block d-block">
                                                Login
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/APP-SIDEBAR-->
                </div>
                <div class="demo-screen-headline main-demo main-demo-1 spacing-top overflow-hidden reveal"
                    id="home">
                    <div class="container px-sm-0">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 mb-5 pb-5 animation-zidex pos-relative">
                                <h4 class="fw-semibold mt-7">Manage Your Business</h4>
                                <h1 class="text-start fw-bold">We Help to Build Your Dream Project with <span
                                        class="text-primary animate-heading">Sash</span></h1>
                                <h6 class="pb-3">
                                    Sash - Now you can use this admin template to design stunning dashboards
                                    that will wow your target viewers or users to no end. To create a good and
                                    well-structured dashboard,
                                    you need to start from scratch with HTML, SCSS, CSS, and JS and with lots of coding,
                                    but by using this Sash-Admin template.</h6>

                                <a href="https://themeforest.net/item/sash-bootstrap-5-admin-dashboard-template/35183671"
                                    target="_blank" class="btn ripple btn-min w-lg mb-3 me-2 btn-primary"><i
                                        class="fe fe-play me-2"></i> Get Started
                                </a>
                                <a href="https://themeforest.net/user/spruko/portfolio"
                                    class="btn ripple btn-min w-lg btn-outline-primary mb-3 me-2" target="_blank"><i
                                        class="fe fe-eye me-2"></i>Discover More
                                </a>
                            </div>
                            <div class="col-xl-6 col-lg-6 my-auto">
                                <img src="{{ asset('../assets/images/landing/market4.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- FOOTER OPEN -->
        <div class="demo-footer">
            <div class="container">
                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            {{-- <div class="top-footer">
                                <div class="row">
                                    <div class="col-lg-4 col-sm-12 col-md-12 reveal revealleft">
                                        <h6>About</h6>
                                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium
                                            doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore
                                            veritatis et quasi architecto beatae vitae dicta sunt
                                            explicabo.
                                        </p>
                                        <p class="mb-5 mb-lg-2">Duis aute irure dolor in reprehenderit in voluptate
                                            velit esse cillum dolore eu fugiat nulla pariatur Excepteur sint occaecat .
                                        </p>
                                    </div>
                                    <div class="col-lg-2 col-sm-6 col-md-4 reveal revealleft">
                                        <h6>Pages</h6>
                                        <ul class="list-unstyled mb-5 mb-lg-0">
                                            <li><a href="index.html">Dashboard</a></li>
                                            <li><a href="alerts.html">Elements</a></li>
                                            <li><a href="form-elements.html">Forms</a></li>
                                            <li><a href="charts.html">Charts</a></li>
                                            <li><a href="datatable.html">Tables</a></li>
                                            <li><a href="file-attachments.html">Other Pages</a></li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-2 col-sm-6 col-md-4 reveal revealleft">
                                        <h6>Information</h6>
                                        <ul class="list-unstyled mb-5 mb-lg-0">
                                            <li><a href="about.html">Our Team</a></li>
                                            <li><a href="about.html">Contact US</a></li>
                                            <li><a href="about.html">About</a></li>
                                            <li><a href="services.html">Services</a></li>
                                            <li><a href="blog.html">Blog</a></li>
                                            <li><a href="terms.html">Terms and Services</a></li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-4 col-sm-12 col-md-4 reveal revealleft">
                                        <div class="">
                                            <a href="index.html"><img loading="lazy" alt="" class="logo-2 mb-3"
                                                    src="../assets/images/brand/logo-3.png"></a>
                                            <a href="index.html"><img src="../assets/images/brand/logo.png"
                                                    class="logo-3" alt="logo"></a>
                                            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum
                                                dolore eu fugiat nulla pariatur Excepteur sint occaecat.</p>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="text" class="form-control"
                                                        placeholder="Enter your email"
                                                        aria-label="Example text with button addon"
                                                        aria-describedby="button-addon1">
                                                    <button class="btn btn-primary" type="button"
                                                        id="button-addon2">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="btn-list mt-6">
                                            <button type="button" class="btn btn-icon rounded-pill"><i
                                                    class="fa fa-facebook"></i></button>
                                            <button type="button" class="btn btn-icon rounded-pill"><i
                                                    class="fa fa-youtube"></i></button>
                                            <button type="button" class="btn btn-icon rounded-pill"><i
                                                    class="fa fa-twitter"></i></button>
                                            <button type="button" class="btn btn-icon rounded-pill"><i
                                                    class="fa fa-instagram"></i></button>
                                        </div>
                                        <hr>
                                    </div>
                                </div>
                            </div> --}}
                            <footer class="main-footer px-0 pb-0 text-center">
                                <div class="row ">
                                    <div class="col-md-12 col-sm-12">
                                        Copyright © <span id="year"></span> <a
                                            href="javascript:void(0)">Sash</a>.
                                        Designed with <span class="fa fa-heart text-danger"></span> by <a
                                            href="javascript:void(0)"> Spruko </a> All rights reserved.
                                    </div>
                                </div>
                            </footer>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FOOTER CLOSED -->
    </div>

    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!-- JQUERY JS -->
    <script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>

    <!-- BOOTSTRAP JS -->
    <script src="{{ asset('public/assets/plugins/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- COUNTERS JS -->
    <script src="{{ asset('public/assets/plugins/counters/counterup.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/counters/waypoints.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/counters/counters-1.js') }}"></script>

    <!-- Perfect SCROLLBAR JS -->
    <script src="{{ asset('public/assets/plugins/owl-carousel/owl.carousel.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/company-slider/slider.js') }}"></script>

    <!-- Star Rating JS -->
    <script src="{{ asset('public/assets/plugins/rating/jquery-rate-picker.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/rating/rating-picker.js') }}"></script>

    <!-- Star Rating-1 JS -->
    <script src="{{ asset('public/assets/plugins/ratings-2/jquery.star-rating.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/ratings-2/star-rating.js') }}"></script>

    <!-- Sticky JS -->
    <script src="{{ asset('public/assets/js/sticky.js') }}"></script>

    <!-- CUSTOM JS -->
    <script src="{{ asset('public/assets/js/landing.js') }}"></script>

</body>

</html>
