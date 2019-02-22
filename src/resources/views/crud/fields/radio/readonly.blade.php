
<label class="label">{{ $field->title() }}</label>

@if ($field->getColumns() > 1)
    <div class="row">
        @foreach (array_chunk($field->getOptions(), round(count($field->getOptions()) / $field->getColumns()), true) as $options)
            <div class="col col-{{ 12 / $field->getColumns() }}">
                @foreach ($options as $option => $title)
                    <label class="radio state-disabled">
                        <input type="radio" name="{{ $field->name() }}" value="{{ $option }}" {{ $field->isCurrentOption($option, $model)  ? 'checked="checked"' : '' }} disabled="disabled">
                        <i></i>
                        {{ $title }}
                    </label>
                @endforeach
            </div>
        @endforeach
    </div>
@else
    <div class="inline-group">
        @foreach ($field->getOptions() as $option => $title)
            <label class="radio state-disabled">
                <input type="radio" name="{{ $field->name() }}" value="{{ $option }}" {{ $field->isCurrentOption($option, $model)  ? 'checked="checked"' : '' }} disabled="disabled">
                <i></i>
                {{ $title }}
            </label>
        @endforeach
    </div>
@endif
