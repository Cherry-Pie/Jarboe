<li class="dd-item dd3-item" data-id="{{ $node->getKey() }}" data-root_id="{{ $root->getKey() }}">
    <div class="dd-handle dd3-handle">
        Drag
    </div>
    <div class="dd3-content">
        {{ $node->name }}

        <form method="post" action="{{ admin_url('admin-panel/navigation/delete') }}" style="display: inline;">
            @csrf
            <input type="hidden" name="id" value="{{ $node->getKey() }}">
            <a href="javascript:void(0);" data-name="{{ $node->name }}" style="margin-left: 6px;" class="btn btn-default btn-xs pull-right delete-node"><i class="fa fa-times"></i></a>
        </form>

        <a href="javascript:void(0);" style="margin-left: 12px;" class="btn btn-default btn-xs pull-right edit-node" data-id="{{ $node->getKey() }}">
            <i class="fa fa-pencil"></i>
        </a>

        <span class="pull-right">
            <span class="onoffswitch">
                <input type="checkbox" id="node{{ $node->getKey() }}" name="is_active" class="onoffswitch-checkbox" {{ $node->is_active ? 'checked' : '' }} data-id="{{ $node->getKey() }}">
                <label class="onoffswitch-label" for="node{{ $node->getKey() }}">
                    <span class="onoffswitch-inner" data-swchon-text="ON" data-swchoff-text="OFF"></span>
                    <span class="onoffswitch-switch"></span>
                </label>
            </span>
        </span>
    </div>
</li>

@push('scripts')
    <script>
        nodes[{{ $node->getKey() }}] = {!! json_encode(['id' => $node->getKey(), 'name' => $node->name, 'icon' => $node->icon, 'slug' => $node->slug, 'is_active' => $node->is_active]) !!};
    </script>
@endpush