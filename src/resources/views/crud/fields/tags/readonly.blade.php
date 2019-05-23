
<label class="label">{{ $field->title() }}</label>
<label class="input {{ $errors->has($field->name()) ? 'state-error' : '' }}">

    <select class="select-2--tags form-control" multiple="multiple" readonly>
        @if ($field->isOptionsHidden())
            @foreach ($field->getSelectedOptions($model) as $id => $value)
                <option value="{{ $value }}" selected>{{ $value }}</option>
            @endforeach
        @else
            @foreach ($field->getOptions() as $id => $value)
                <option value="{{ $value }}" {{ $field->isCurrentOption($value, $model) ? 'selected' : '' }}>{{ $value }}</option>
            @endforeach
        @endif
    </select>

</label>


@foreach ($errors->get($field->name()) as $message)
    <div class="note note-error">{{ $message }}</div>
@endforeach

@pushonce('script_files', <script>
  $(".select-2--tags").select2({
    tags: true,
    disabled: true
  });
</script>)
