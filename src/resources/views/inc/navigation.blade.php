<!-- Left panel : Navigation area -->
<!-- Note: This width of the aside area can be adjusted through LESS variables -->
<aside id="left-panel">

    @include('jarboe::inc.user_info')

    <nav>
        <!--
        NOTE: Notice the gaps after each icon usage <i></i>..
        Please note that these links work a bit different than
        traditional href="" links. See documentation for details.
        -->

        <ul>

            <li>
                <a href="{{ admin_url('dashboard') }}">
                    <i class="fa fa-lg fa-fw fa-anchor"></i>
                    <span class="menu-item-parent">Dashboard</span>
                </a>
            </li>

            @if (config('jarboe.admin_panel.default_routes_enabled'))
            <li>
                <a href="#" title="Admin Panel"><i class="fa fa-lg fa-fw fa-cogs"></i> <span class="menu-item-parent">Admin Panel</span></a>
                <ul>
                    <li>
                        <a href="{{ admin_url('admin-panel/admins') }}">
                            <span class="menu-item-parent">Admins</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ admin_url('admin-panel/roles-and-permissions') }}">
                            <span class="menu-item-parent">Roles &amp; Permissions</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif

        </ul>
    </nav>


    <span class="minifyme" data-action="minifyMenu">
        <i class="fa fa-arrow-circle-left hit"></i>
    </span>

</aside>
<!-- END NAVIGATION -->

@push('scripts')
    <script>
        $('#left-panel nav a').each(function() {
            let href = window.location.href;
            let index = href.indexOf("/~/");
            if (~index) {
                href = window.location.href.substring(0, index);
            }
            if (this.href.replace(/\/$/, '') == href.replace(/\/$/, '')) {
                $(this).closest('li').addClass('active');
            }
        });
    </script>
@endpush
