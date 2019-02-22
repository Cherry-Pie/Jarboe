
<label class="label">{{ $field->title() }}</label>

<div class="input {{ $errors->has($field->name()) ? 'state-error' : '' }}">
    <input class="form-control clockpicker-{{ $field->name() }}"
           type="text"
           name="{{ $field->name() }}"
           placeholder="{{ $field->getPlaceholder() }}"
           value="{{ $field->oldOrDefault() }}"
           data-autoclose="true"
           autocomplete="off">
    <i class="icon-append fa fa-clock-o"></i>
</div>

@foreach ($errors->get($field->name()) as $message)
    <div class="note note-error">{{ $message }}</div>
@endforeach

@pushonce('script_files', <script src="/vendor/jarboe/js/plugin/clockpicker/clockpicker.min.js"></script>)

@push('scripts')
    <script>
        $(document).ready(function() {
            $(".clockpicker-{{ $field->name() }}").clockpicker({
                placement: '{{ $field->getPlacement() }}',
                donetext: 'Done'
            });
        })
    </script>
@endpush