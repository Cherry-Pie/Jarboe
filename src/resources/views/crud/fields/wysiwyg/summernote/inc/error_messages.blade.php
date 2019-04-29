@foreach ($messages as $message)
    <div class="note note-error">{{ $message }}</div>
@endforeach


@push('styles')
    @if (!empty($messages))
        <style>
            div.note-editor{
                background: #fff0f0;
                border-color: #A90329 !important;
            }
        </style>
    @endif
@endpush
