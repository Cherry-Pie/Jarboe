@foreach ($messages as $message)
    <div class="note note-error">{{ $message }}</div>
@endforeach


@pushonce('styles', <style>
    label.state-error div.note-editor{
        background: #fff0f0;
        border-color: #A90329 !important;
    }
</style>)
