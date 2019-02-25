<!DOCTYPE html>
<html lang="en-us" id="extr-page">
<head>
    <meta charset="utf-8">
    <title>Jarboe</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- #CSS Links -->
    <!-- Basic Styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="/vendor/jarboe/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="/vendor/jarboe/css/font-awesome.min.css">

    <!-- SmartAdmin Styles : Caution! DO NOT change the order -->
    <link rel="stylesheet" type="text/css" media="screen" href="/vendor/jarboe/css/smartadmin-production-plugins.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="/vendor/jarboe/css/smartadmin-production.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="/vendor/jarboe/css/smartadmin-skins.min.css">

    <!-- SmartAdmin RTL Support -->
    <link rel="stylesheet" type="text/css" media="screen" href="/vendor/jarboe/css/smartadmin-rtl.min.css">

    <!-- We recommend you use "your_style.css" to override SmartAdmin
         specific styles this will also ensure you retrain your customization with each SmartAdmin update.
    <link rel="stylesheet" type="text/css" media="screen" href="/vendor/jarboe/css/your_style.css"> -->

    <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
    <link rel="stylesheet" type="text/css" media="screen" href="/vendor/jarboe/css/demo.min.css">

    <!-- #FAVICONS -->
    <link rel="shortcut icon" href="/vendor/jarboe/img/favicon/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/vendor/jarboe/img/favicon/favicon.ico" type="image/x-icon">

    <!-- #GOOGLE FONT -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">


</head>

<body class="animated fadeInDown">

<header id="header">
    <!--<span id="logo"></span>-->

    <div id="logo-group">
        <span id="logo"> <img src="/vendor/jarboe/img/logo.png" alt="Jarboe"> </span>

        <!-- END AJAX-DROPDOWN -->
    </div>

    <span id="extr-page-header-space"> <span class="hidden-mobile hiddex-xs">{{ __('jarboe::auth.registration.already_registered') }}</span> <a href="{{ admin_url('login') }}" class="btn btn-danger">{{ __('jarboe::auth.registration.sign_in') }}</a> </span>

</header>

<div id="main" role="main">

    <!-- MAIN CONTENT -->
    <div id="content" class="container">

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 hidden-xs hidden-sm">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

            </div>
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                <div class="well no-padding">

                    <form action="{{ admin_url('register') }}" method="post" id="smart-form-register" class="smart-form client-form">
                        <header>
                            {{ __('jarboe::auth.registration.register_header') }}
                        </header>

                        @csrf

                        <fieldset>
                            <section>
                                <label class="input"> <i class="icon-append fa fa-user"></i>
                                    <input type="text" name="name" placeholder="{{ __('jarboe::auth.registration.name_placeholder') }}">
                                    <b class="tooltip tooltip-bottom-right">{{ __('jarboe::auth.registration.name_tooltip') }}</b> </label>
                            </section>

                            <section>
                                <label class="input"> <i class="icon-append fa fa-envelope"></i>
                                    <input type="email" name="email" placeholder="{{ __('jarboe::auth.registration.email_placeholder') }}">
                                    <b class="tooltip tooltip-bottom-right">{{ __('jarboe::auth.registration.email_tooltip') }}</b> </label>
                            </section>

                            <section>
                                <label class="input"> <i class="icon-append fa fa-lock"></i>
                                    <input type="password" name="password" placeholder="{{ __('jarboe::auth.registration.password_placeholder') }}" id="password">
                                    <b class="tooltip tooltip-bottom-right">{{ __('jarboe::auth.registration.password_tooltip') }}</b> </label>
                            </section>

                            <section>
                                <label class="input"> <i class="icon-append fa fa-lock"></i>
                                    <input type="password" name="password_confirmation" placeholder="{{ __('jarboe::auth.registration.confirm_password_placeholder') }}">
                                    <b class="tooltip tooltip-bottom-right">{{ __('jarboe::auth.registration.confirm_password_tooltip') }}</b> </label>
                            </section>
                        </fieldset>

                        <footer>
                            <button type="submit" class="btn btn-primary">
                                {{ __('jarboe::auth.registration.register_button') }}
                            </button>
                        </footer>

                    </form>

                </div>
                <h5 class="text-center">- {{ __('jarboe::auth.registration.or_sign_in_using') }} -</h5>
                <ul class="list-inline text-center">
                    <li>
                        <a href="javascript:void(0);" class="btn btn-primary btn-circle disabled"><i class="fa fa-facebook"></i></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="btn btn-info btn-circle disabled"><i class="fa fa-twitter"></i></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="btn btn-warning btn-circle disabled"><i class="fa fa-linkedin"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>

<!--================================================== -->

<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
<script src="/vendor/jarboe/js/plugin/pace/pace.min.js"></script>

<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
<script src="/vendor/jarboe/js/libs/jquery-3.2.1.min.js"></script>
<script src="/vendor/jarboe/js/libs/jquery-ui.min.js"></script>

<!-- IMPORTANT: APP CONFIG -->
<script src="/vendor/jarboe/js/app.config.js"></script>

<!-- JS TOUCH : include this plugin for mobile drag / drop touch events
<script src="/vendor/jarboe/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->

<!-- BOOTSTRAP JS -->
<script src="/vendor/jarboe/js/bootstrap/bootstrap.min.js"></script>

<!-- JQUERY VALIDATE -->
<script src="/vendor/jarboe/js/plugin/jquery-validate/jquery.validate.min.js"></script>

<!-- JQUERY MASKED INPUT -->
<script src="/vendor/jarboe/js/plugin/masked-input/jquery.maskedinput.min.js"></script>

<!--[if IE 8]>

<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>

<![endif]-->

<!-- MAIN APP JS FILE -->
<script src="/vendor/jarboe/js/app.js"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '<?= csrf_token() ?>'
        }
    });

    runAllForms();


    // Validation
    $(function() {
        // Validation
        $("#smart-form-register").validate({

            // Rules for form validation
            rules : {
                name : {
                    required : true
                },
                email : {
                    required : true,
                    email : true
                },
                password : {
                    required : true,
                    minlength : 3,
                    maxlength : 20
                },
                password_confirmation : {
                    required : true,
                    minlength : 3,
                    maxlength : 20,
                    equalTo : '#password'
                },
            },

            // Messages for form validation
            messages : {
                name : {
                    required : '{{ __('jarboe::auth.registration.name_required_message') }}'
                },
                email : {
                    required : '{{ __('jarboe::auth.registration.email_required_message') }}',
                    email : '{{ __('jarboe::auth.registration.email_email_message') }}'
                },
                password : {
                    required : '{{ __('jarboe::auth.registration.password_required_message') }}'
                },
                password_confirmation : {
                    required : '{{ __('jarboe::auth.registration.password_confirmation_required_message') }}',
                    equalTo : '{{ __('jarboe::auth.registration.password_confirmation_equal_to_message') }}'
                },
            },

            // Ajax form submition
            submitHandler : function(form) {
                $(form).ajaxSubmit({
                    success : function() {
                        $("#smart-form-register").addClass('submited');
                    }
                });
            },

            // Do not change code below
            errorPlacement : function(error, element) {
                error.insertAfter(element.parent());
            }
        });

    });
</script>

<style>
    #extr-page div#main {
        background: url("/vendor/jarboe/img/6-reversed.jpg") #fff;
        background-size: cover;
        min-height: 100vh;
        padding-top: 142px;
        margin-top: -71px !important;
    }
    #logo img {
        height: 48px !important;
        width: auto !important;
        margin-top: -10px !important;
    }
</style>

</body>
</html>