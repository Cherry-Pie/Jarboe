<!-- multiple lang dropdown : find all flags in the flags page -->
@if ($localeHelper->all())
<ul class="header-dropdown-list hidden-xs">
    <li>
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span> {{ $localeHelper->getCurrentTitle() }} </span>
            <i class="fa fa-angle-down"></i>
        </a>
        <ul class="dropdown-menu pull-right">
            @foreach ($localeHelper->all() as $locale => $title)
                <li>
                    <a href="{{ strpos(url()->current(), '?') !== false ? '&__jarboe-locale='. $locale : '?__jarboe-locale='. $locale }}">{{ $title }}</a>
                </li>
            @endforeach
        </ul>
    </li>
</ul>
@endif
<!-- end multiple lang -->