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

            @foreach ($root->getImmediateDescendants() as $node)
                @if ($node->isLeaf() && $node->is_active)
                    @include('jarboe::inc.navigation_leaf')
                @elseif ($node->is_active)
                    @include('jarboe::inc.navigation_branch')
                @endif
            @endforeach


        </ul>
    </nav>


    <span class="minifyme" data-action="minifyMenu">
				<i class="fa fa-arrow-circle-left hit"></i>
			</span>

</aside>
<!-- END NAVIGATION -->