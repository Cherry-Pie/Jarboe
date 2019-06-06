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
    @include('jarboe::inc.auth.styles')
</head>

<body class="animated fadeInDown">

@yield('header')

@yield('content')

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

@yield('scripts')

</body>
</html>