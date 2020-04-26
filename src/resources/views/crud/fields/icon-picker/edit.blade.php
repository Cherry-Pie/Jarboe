<label class="label">{{ $field->title() }}</label>
<label class="input {{ $errors->has($field->name()) ? 'state-error' : '' }}">
    <div class="input-group">
        <input data-placement="bottomLeft" class="form-control icp icp-auto" value="{{ $field->oldOrAttribute($model) }}" name="{{ $field->name() }}" type="text" readonly="readonly"/>
        <span class="input-group-addon">
            <i class="fa {{ $field->oldOrAttribute($model) }}"></i>
        </span>
    </div>
</label>


@foreach ($errors->get($field->name()) as $message)
    <div class="note note-error">{{ $message }}</div>
@endforeach


@pushonce('style_files', <link rel="stylesheet" type="text/css" href="/vendor/jarboe/js/plugin/fontawesome-iconpicker/fontawesome-iconpicker.min.css">)
@pushonce('script_files', <script src="/vendor/jarboe/js/plugin/fontawesome-iconpicker/fontawesome-iconpicker.min.js"></script>)

@push('styles')
    <style>
        div.iconpicker-popover.popover {
            width: 330px;
        }
        input[type=text].icp:focus+.input-group-addon {
            color: #555;
            background-color: #eee;
        }
        input[type=text].icp+.input-group-addon i {
            min-width: 15px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        Jarboe.add('{{ $field->name() }}', function() {
            $('.icp-auto').iconpicker();
        }, '{{ $locale ?? 'default' }}');

        $(document).ready(function () {
            Jarboe.init('{{ $field->name() }}', '{{ $locale ?? 'default' }}');
        });
    </script>
@endpush
