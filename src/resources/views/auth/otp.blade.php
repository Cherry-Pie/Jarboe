<!DOCTYPE html>
<html lang="en-us" id="lock-page">
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
    <!-- page related CSS -->
    <link rel="stylesheet" type="text/css" media="screen" href="/vendor/jarboe/css/lockscreen.min.css">

    <!-- #FAVICONS -->
    @include('jarboe::inc.meta.favicon')

    <!-- #GOOGLE FONT -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
    @include('jarboe::inc.meta.apple_touch_icon')

    <style>
        .lockscreen div.qr-code {
            width: 180px;
            height: 180px;
        }
    </style>
</head>

<body>

    <div id="main" role="main">
        <div class="lockscreen animated flipInY">
            <div class="logo"></div>
            <div>
                <div class="qr-code pull-left">
                    {!! $svg !!}
                </div>
                <div>
                    <h3>{{ $secret }}</h3>
                    <p>{{ __('jarboe::auth.otp.description') }}</p>
                    <hr>
                    <a href="{{ admin_url() }}" class="btn btn-default pull-right">{{ __('jarboe::auth.otp.proceed_button') }}</a>
                </div>

            </div>
            <p class="font-xs margin-top-5">
            </p>
        </div>
    </div>

</body>
</html>
