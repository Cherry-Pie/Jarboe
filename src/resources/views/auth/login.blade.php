<!DOCTYPE html>
<html lang="en-us" id="extr-page">
<head>
    <meta charset="utf-8">
    @include('jarboe::inc.meta.title')
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="">

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
    @include('jarboe::inc.meta.favicon')

    <!-- #GOOGLE FONT -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
    @include('jarboe::inc.meta.apple_touch_icon')

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
</head>

<body class="animated fadeInDown">

<header id="header">

    <div id="logo-group">
        <span id="logo">
            @include('jarboe::inc.auth.logo')
        </span>
    </div>

    @if (config('jarboe.admin_panel.registration_enabled'))
        <span id="extr-page-header-space">
            <span class="hidden-mobile hiddex-xs">{{ __('jarboe::auth.login.need_account') }}</span>
            <a href="{{ admin_url('register') }}" class="btn btn-danger">{{ __('jarboe::auth.login.create_account') }}</a>
        </span>
    @endif
</header>

<div id="main" role="main">

    <!-- MAIN CONTENT -->
    <div id="content" class="container">

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-8 hidden-xs hidden-sm">
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

            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
                <div class="well no-padding">
                    <form action="{{ admin_url('login') }}" method="post" id="login-form" class="smart-form client-form">
                        <header>
                            {{ __('jarboe::auth.login.sign_in_header') }}
                        </header>

                        <fieldset>

                            @csrf

                            <section>
                                <label class="label">{{ __('jarboe::auth.login.email') }}</label>
                                <label class="input"> <i class="icon-append fa fa-user"></i>
                                    <input type="email" name="email">
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> {{ __('jarboe::auth.login.email_tooltip') }}</b></label>
                            </section>

                            <section>
                                <label class="label">{{ __('jarboe::auth.login.password') }}</label>
                                <label class="input"> <i class="icon-append fa fa-lock"></i>
                                    <input type="password" name="password">
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> {{ __('jarboe::auth.login.password_tooltip') }}</b></label>
                            </section>

                            <section>
                                <label class="checkbox">
                                    <input type="checkbox" name="remember" checked="">
                                    <i></i>{{ __('jarboe::auth.login.remember_me') }}</label>
                            </section>
                        </fieldset>
                        <footer>
                            <button type="submit" class="btn btn-primary">
                                {{ __('jarboe::auth.login.sign_in_button') }}
                            </button>
                        </footer>
                    </form>

                </div>

                {{--
                <h5 class="text-center"> - {{ __('jarboe::auth.login.or_sign_in_using') }} -</h5>

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
                --}}

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

    $(function() {
        // Validation
        $("#login-form").validate({
            // Rules for form validation
            rules : {
                email : {
                    required : true,
                    email : true
                },
                password : {
                    required : true,
                    minlength : 3,
                    maxlength : 20
                }
            },

            // Messages for form validation
            messages : {
                email : {
                    required : '{{ __('jarboe::auth.login.email_required_message') }}',
                    email : '{{ __('jarboe::auth.login.email_email_message') }}'
                },
                password : {
                    required : '{{ __('jarboe::auth.login.password_required_message') }}'
                }
            },

            // Do not change code below
            errorPlacement : function(error, element) {
                error.insertAfter(element.parent());
            }
        });
    });
</script>


</body>
</html>