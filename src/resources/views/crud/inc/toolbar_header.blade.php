
@if ($tools)
    @foreach ($tools as $tool)
        <div class="widget-toolbar" role="menu">
            {!! $tool->render() !!}
        </div>
    @endforeach
@endif
