<!-- HEADER -->
<header id="header">
    <div id="logo-group">

        <!-- PLACE YOUR LOGO HERE -->
        <span id="logo">
            @include('jarboe::inc.header.logo')
        </span>
        <!-- END LOGO PLACEHOLDER -->

{{--        @include('jarboe::inc.activity')--}}

    </div>

{{--    @include('jarboe::inc.projects_download')--}}

    </div>
    <!-- end projects dropdown -->

    <!-- pulled right: nav area -->
    <div class="pull-right">

        <!-- collapse menu button -->
        <div id="hide-menu" class="btn-header pull-right">
            <span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
        </div>
        <!-- end collapse menu -->

        <!-- #MOBILE -->
        @include('jarboe::inc.user_info_top_menu')

        <!-- logout button -->
        <div id="logout" class="btn-header transparent pull-right">
            <span> <a href="{{ admin_url('logout') }}" title="Sign Out" data-action="userLogout" data-logout-msg="You can improve your security further after logging out by closing this opened browser"><i class="fa fa-sign-out"></i></a> </span>
        </div>
        <!-- end logout button -->

    {{--
    <!-- search mobile button (this is hidden till mobile view port) -->
    <div id="search-mobile" class="btn-header transparent pull-right">
        <span> <a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a> </span>
    </div>
    <!-- end search mobile button -->

    {{--
    <!-- ifield -->
    <form action="search.html" class="header-search pull-right">
        <input id="search-fld"  type="text" name="param" placeholder="Find reports and more" data-autocomplete='[
                "ActionScript",
                "AppleScript",
                "Asp",
                "BASIC",
                "C",
                "C++",
                "Clojure",
                "COBOL",
                "ColdFusion",
                "Erlang",
                "Fortran",
                "Groovy",
                "Haskell",
                "Java",
                "JavaScript",
                "Lisp",
                "Perl",
                "PHP",
                "Python",
                "Ruby",
                "Scala",
                "Scheme"]'>
        <button type="submit">
            <i class="fa fa-search"></i>
        </button>
        <a href="javascript:void(0);" id="cancel-search-js" title="Cancel Search"><i class="fa fa-times"></i></a>
    </form>
    <!-- end input: search field -->
    --}}

        <!-- fullscreen button -->
        <div id="fullscreen" class="btn-header transparent pull-right">
            <span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Full Screen"><i class="fa fa-arrows-alt"></i></a> </span>
        </div>
        <!-- end fullscreen button -->

        {{--
        <!-- #Voice Command: Start Speech -->
        <div id="speech-btn" class="btn-header transparent pull-right hidden-sm hidden-xs">
            <div>
                <a href="javascript:void(0)" title="Voice Command" data-action="voiceCommand"><i class="fa fa-microphone"></i></a>
                <div class="popover bottom"><div class="arrow"></div>
                    <div class="popover-content">
                        <h4 class="vc-title">Voice command activated <br><small>Please speak clearly into the mic</small></h4>
                        <h4 class="vc-title-error text-center">
                            <i class="fa fa-microphone-slash"></i> Voice command failed
                            <br><small class="txt-color-red">Must <strong>"Allow"</strong> Microphone</small>
                            <br><small class="txt-color-red">Must have <strong>Internet Connection</strong></small>
                        </h4>
                        <a href="javascript:void(0);" class="btn btn-success" onclick="commands.help()">See Commands</a>
                        <a href="javascript:void(0);" class="btn bg-color-purple txt-color-white" onclick="$('#speech-btn .popover').fadeOut(50);">Close Popup</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- end voice command -->
        --}}

        @include('jarboe::inc.locale_selector')
    </div>
    <!-- end pulled right: nav area -->

</header>
<!-- END HEADER -->