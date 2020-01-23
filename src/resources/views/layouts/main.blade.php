<!DOCTYPE html>
<html lang="en-us">
<head>
    <meta charset="utf-8">
    <!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

    @include('jarboe::inc.meta.title')

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="author" content="">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- Basic Styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="/vendor/jarboe/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="/vendor/jarboe/css/font-awesome.min.css">

    <!-- SmartAdmin Styles : Caution! DO NOT change the order -->
    <link rel="stylesheet" type="text/css" media="screen" href="/vendor/jarboe/css/smartadmin-production-plugins.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="/vendor/jarboe/css/smartadmin-production.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="/vendor/jarboe/css/smartadmin-skins.min.css">

    <!-- SmartAdmin RTL Support -->
    <link rel="stylesheet" type="text/css" media="screen" href="/vendor/jarboe/css/smartadmin-rtl.min.css">

    <!-- FAVICONS -->
    @include('jarboe::inc.meta.favicon')

    <!-- GOOGLE FONT -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

    <!-- Specifying a Webpage Icon for Web Clip
         Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
    @include('jarboe::inc.meta.apple_touch_icon')


    <style>
        #logo {
            margin-top: 8px;
        }
        #logo img {
            width: 34px;
        }
        .menu-on-top #fullscreen {
            display: none!important;
        }

        .smart-form .col-7 {
            width: 58.33%;
        }
        .smart-form .col-11 {
            width: 91.66%;
        }
        .smart-form .col-0 {
            display: none;
        }

        .select2-search-choice-close,
        .select2-selection__clear {
            display: block;
            width: 15px;
        }
        .select2-selection--multiple .select2-search-choice-close,
        .select2-selection--multiple .select2-selection__clear {
            display: block;
            width: 15px;
            padding-right: 5px;
            height: 20px;
            line-height: 21px;
        }
        .select2-display-none, .select2-hidden-accessible {
            display: none!important;
        }
        .select2-container .select2-choice, .select2-selection {
            /*padding: 0 8px !important;*/
        }
        .select2-results li {
            padding: 4px 2px 4px 8px;
        }
        .select2-selection__arrow {
            display: none;
        }
        .select2-container-multi .select2-choices .select2-search-choice, .select2-selection__choice {
            padding: 1px 28px 1px 8px !important;
            margin: 4px 0 3px 5px !important;
            box-sizing: border-box; !important;
        }
        .select2-selection__choice__remove {
            padding-bottom: 3px;
            padding-left: 6px;
            padding-right: 4px;
            padding-top: 3px;
            margin: 0;
            box-sizing: border-box;
        }
        .select2.select2-container.select2-container--default {
            width: 100% !important;
        }

        table .hasinput input {
            padding: 6px 12px;
            box-sizing: border-box;
        }

        a.clipclip,
        a.clipclip.btn:active {
            position: absolute;
            top: 0;
            right: 0;
            left: auto;
            padding: 1px 6px;
        }

        .editable-clear {
            position: absolute;
            bottom: 10px;
            right: 24px;
        }

        td.smart-form>label.checkbox,
        th.smart-form>label.checkbox {
            margin-bottom: 18px !important;
            padding-left: 18px !important;
        }
        td.smart-form>label.checkbox i,
        th.smart-form>label.checkbox i {
            top: 0px;
        }
        th.check-all-column {
            width: 1px;
        }
        td.mass-checkbox-td {
            width: 1px;
        }

        label.btn.translation-locale-label.focus {
            outline: none;
        }

        td.jarboe-table-actions {
            white-space: nowrap;
        }
        .check-all-column div.tooltip {
            padding: 0 5px;
            opacity: .9;
            background: none;
            line-height: 1.42857143;
        }

        .smart-style-2 #logo img {
            filter: invert(100%);
        }

    </style>

    @stack('style_files')
    @stack('styles')
    @stack('head_scripts')

    @include('jarboe::inc.head_attach')
</head>


<!-- #BODY -->
<!-- Possible Classes

    * 'smart-style-{SKIN#}'
    * 'smart-rtl'         - Switch theme mode to RTL
    * 'menu-on-top'       - Switch to top navigation (no DOM change required)
    * 'no-menu'			  - Hides the menu completely
    * 'hidden-menu'       - Hides the main menu but still accessable by hovering over left edge
    * 'fixed-header'      - Fixes the header
    * 'fixed-navigation'  - Fixes the main menu
    * 'fixed-ribbon'      - Fixes breadcrumb
    * 'fixed-page-footer' - Fixes footer
    * 'container'         - boxed layout mode (non-responsive: will not work with fixed-navigation & fixed-ribbon)
-->
<body class="{{ $themeClass }} {{ $menuOnTop ? 'menu-on-top' : '' }} {{ $_COOKIE['body_class'] ?? '' }} fixed-page-footer">

@stack('body_start')

@include('jarboe::inc.header')
@include('jarboe::inc.navigation')

<!-- MAIN PANEL -->
<div id="main" role="main">

    <!-- RIBBON -->
    <div id="ribbon">

        <!-- breadcrumb -->
        @yield('breadcrumbs')
        <!-- end breadcrumb -->

        <!-- You can also add more buttons to the
        ribbon for further usability

        Example below:

        <span class="ribbon-button-alignment pull-right">
        <span id="search" class="btn btn-ribbon hidden-xs" data-title="search"><i class="fa-grid"></i> Change Grid</span>
        <span id="add" class="btn btn-ribbon hidden-xs" data-title="add"><i class="fa-plus"></i> Add</span>
        <span id="search" class="btn btn-ribbon" data-title="search"><i class="fa-search"></i> <span class="hidden-mobile">Search</span></span>
        </span> -->
        @include('jarboe::inc.reset_panel_settings')
    </div>
    <!-- END RIBBON -->

    <!-- MAIN CONTENT -->
    <div id="content">
        @yield('content')
    </div>
    <!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->

@include('jarboe::inc.footer')

{{--
@include('jarboe::inc.shortcut')
--}}

<!--================================================== -->

<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
<script data-pace-options='{ "restartOnRequestAfter": true }' src="/vendor/jarboe/js/plugin/pace/pace.min.js"></script>

<script src="/vendor/jarboe/js/libs/jquery-3.2.1.min.js"></script>
<script src="/vendor/jarboe/js/libs/jquery-ui.min.js"></script>

<!-- IMPORTANT: APP CONFIG -->
<script src="/vendor/jarboe/js/app.config.js"></script>

<!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
<script src="/vendor/jarboe/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script>

<!-- BOOTSTRAP JS -->
<script src="/vendor/jarboe/js/bootstrap/bootstrap.min.js"></script>

<!-- CUSTOM NOTIFICATION -->
<script src="/vendor/jarboe/js/notification/SmartNotification.min.js"></script>

<!-- JQUERY MASKED INPUT -->
<script src="/vendor/jarboe/js/plugin/masked-input/jquery.maskedinput.min.js"></script>

<!-- JQUERY SELECT2 INPUT -->
<script src="/vendor/jarboe/js/plugin/select2/select2.min.js"></script>

<!-- JQUERY UI + Bootstrap Slider -->
<script src="/vendor/jarboe/js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>

<!-- browser msie issue fix -->
<script src="/vendor/jarboe/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>

<!-- FastClick: For mobile devices -->
<script src="/vendor/jarboe/js/plugin/fastclick/fastclick.min.js"></script>

<script src="/vendor/jarboe/js/plugin/moment/moment.min.js"></script>
<script src="/vendor/jarboe/js/plugin/clipboard.js/2.0.0/clipboard.min.js"></script>


<link rel="stylesheet" href="/vendor/jarboe/js/plugin/lity/2.3.1/lity.css">
<script src="/vendor/jarboe/js/plugin/lity/2.3.1/lity.js"></script>

<script src="/vendor/jarboe/js/plugin/js.cookie/js.cookie.js"></script>


<audio preload="auto" autobuffer="auto" style="display: none;">
    <source src="/vendor/jarboe/sound/messagebox.mp3" type="audio/mpeg">
</audio>
<audio preload="auto" autobuffer="auto" style="display: none;">
    <source src="/vendor/jarboe/sound/bigbox.mp3" type="audio/mpeg">
</audio>
<audio preload="auto" autobuffer="auto" style="display: none;">
    <source src="/vendor/jarboe/sound/smallbox.mp3" type="audio/mpeg">
</audio>


<!--[if IE 8]>

<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>

<![endif]-->

<!-- MAIN APP JS FILE -->
<script src="/vendor/jarboe/js/app.js"></script>

<script src="/vendor/jarboe/js/jarboe.js"></script>

<!-- PAGE RELATED PLUGIN(S) -->


<script>
    (function($) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    })(jQuery);

    $.fn.removeClassPrefix = function(prefix) {
        this.each(function(i, el) {
            var classes = el.className.split(" ").filter(function(c) {
                return c.lastIndexOf(prefix, 0) !== 0;
            });
            el.className = classes.join(" ");
        });
        return this;
    };

    $.datepicker.parseDate = function(format, value) {
        if (value) {
            var date = moment(value, format).toDate();
            return date == 'Invalid Date' ? new Date() : date;
        }
        return new Date();
    };
    $.datepicker.formatDate = function (format, value) {
        var date = moment(value).format(format);
        return date == 'Invalid Date' ? new Date() : date;
    };

    (new ClipboardJS('.clipclip')).on('success', function(e) {
        jarboe.smallToast({
            title : '{{ __('jarboe::common.successfully_copied') }}',
            content : htmlspecialchars(e.text, 'ENT_QUOTES', 'UTF-8', true),
            color : "#739e73",
            timeout : 4000
        });
    });

    function htmlspecialchars(string, quote_style, charset, double_encode) {
        //       discuss at: http://phpjs.org/functions/htmlspecialchars/
        //      original by: Mirek Slugen
        //      improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        //      bugfixed by: Nathan
        //      bugfixed by: Arno
        //      bugfixed by: Brett Zamir (http://brett-zamir.me)
        //      bugfixed by: Brett Zamir (http://brett-zamir.me)
        //       revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        //         input by: Ratheous
        //         input by: Mailfaker (http://www.weedem.fr/)
        //         input by: felix
        // reimplemented by: Brett Zamir (http://brett-zamir.me)
        //             note: charset argument not supported
        //        example 1: htmlspecialchars("<a href='test'>Test</a>", 'ENT_QUOTES');
        //        returns 1: '&lt;a href=&#039;test&#039;&gt;Test&lt;/a&gt;'
        //        example 2: htmlspecialchars("ab\"c'd", ['ENT_NOQUOTES', 'ENT_QUOTES']);
        //        returns 2: 'ab"c&#039;d'
        //        example 3: htmlspecialchars('my "&entity;" is still here', null, null, false);
        //        returns 3: 'my &quot;&entity;&quot; is still here'

        var optTemp = 0,
            i = 0,
            noquotes = false;
        if (typeof quote_style === 'undefined' || quote_style === null) {
            quote_style = 2;
        }
        string = string.toString();
        if (double_encode !== false) { // Put this first to avoid double-encoding
            string = string.replace(/&/g, '&amp;');
        }
        string = string.replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');

        var OPTS = {
            'ENT_NOQUOTES': 0,
            'ENT_HTML_QUOTE_SINGLE': 1,
            'ENT_HTML_QUOTE_DOUBLE': 2,
            'ENT_COMPAT': 2,
            'ENT_QUOTES': 3,
            'ENT_IGNORE': 4
        };
        if (quote_style === 0) {
            noquotes = true;
        }
        if (typeof quote_style !== 'number') { // Allow for a single string or an array of string flags
            quote_style = [].concat(quote_style);
            for (i = 0; i < quote_style.length; i++) {
                // Resolve string input to bitwise e.g. 'ENT_IGNORE' becomes 4
                if (OPTS[quote_style[i]] === 0) {
                    noquotes = true;
                } else if (OPTS[quote_style[i]]) {
                    optTemp = optTemp | OPTS[quote_style[i]];
                }
            }
            quote_style = optTemp;
        }
        if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
            string = string.replace(/'/g, '&#039;');
        }
        if (!noquotes) {
            string = string.replace(/"/g, '&quot;');
        }

        return string;
    }

    // DO NOT REMOVE : GLOBAL FUNCTIONS!
    $(document).ready(function() {
        pageSetUp();
    });


    $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
        if (jqxhr.status !== 406) {
            return;
        }

        jarboe.smallToast({
            title: jqxhr.responseJSON.title,
            content: jqxhr.responseJSON.description || jqxhr.responseJSON.title,
            color: '#C46A69',
            icon: 'fa fa-warning shake animated',
            timeout: 6000,
        });
    });
</script>


@include('jarboe::inc.notifications')

@stack('script_files')
@stack('scripts')


@stack('body_end')

@include('jarboe::inc.body_attach')

</body>

</html>
