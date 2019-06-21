
@if ($errors->any())
    <div class="alert alert-danger errors-container">
        <ul>
            @foreach ($errors->messages() as $name => $messages)
                @if ($crud->getFieldByName(explode('.', $name)[0]))
                    @continue
                @endif
                @foreach ($messages as $message)
                    <li>{{ $message  }}</li>
                @endforeach
            @endforeach
        </ul>
    </div>
@endif

@pushonce('style_files', <style>
    .errors-container {
        padding: 0 10px;
    }
    .errors-container > ul > li:first-child {
        padding-top: 10px;
    }
    .errors-container > ul > li:last-child {
        padding-bottom: 10px;
    }
</style>)
