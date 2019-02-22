<li>
    <a href="#" title="{{ $node->name }}"><i class="fa fa-lg fa-fw {{ $node->icon }}"></i> <span class="menu-item-parent">{{ $node->name }}</span></a>
    <ul>
        @foreach ($node->getImmediateDescendants() as $nodeItem)
            @if ($nodeItem->isLeaf() && $nodeItem->is_active)
                @include('jarboe::inc.navigation_leaf', [
                    'node' => $nodeItem,
                    'root' => $node,
                ])
            @elseif ($nodeItem->is_active)
                @include('jarboe::inc.navigation_branch', [
                    'node' => $nodeItem,
                    'root' => $node,
                ])
            @endif
        @endforeach
    </ul>
</li>