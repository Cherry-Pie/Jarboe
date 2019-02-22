<li class="{{ is_current_admin_url($node->slug) ? 'active' : '' }}">
    <a href="{{ admin_url($node->slug) }}">
        @if ($node->icon)
            <i class="fa fa-lg fa-fw {{ $node->icon }}"></i>
        @endif
        <span class="menu-item-parent">{{ $node->name }}</span>
    </a>
</li>