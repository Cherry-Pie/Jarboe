<label class="label">{{ $field->title() }}</label>

<label class="select {{ $field->isMultiple() ? 'select-multiple' : '' }} {{ $errors->has($field->name()) ? 'state-error' : '' }}">
    <select name="{{ $field->name() }}{{ $field->isMultiple() ? '[]' : '' }}"
            {{ $field->isMultiple() ? 'multiple' : '' }}
            class="custom-scroll {{ $field->isSelect2Type() ? 'select2-field' : '' }}"
            data-relation-search-url="{{ $field->isAjax() && $field->isRelationField() ? $field->getRelationSearchUrl() : '' }}"
            data-original-name="{{ $field->name() }}"
            {!! $field->isSelect2Type() ? 'style="width:100%"' : '' !!}>

        @if ($field->isAjax() && $field->isRelationField() && $field->isSelect2Type())
            @if ($field->isGroupedRelation())
                @foreach ($field->getSelectedGroupedOptions($model) as $group => $options)
                    <optgroup label="{{ $group }}">
                        @foreach ($options as $option => $title)
                            <option selected value="{{ crc32($group) }}~~~{{ $option }}">{{ $title }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            @else
                @foreach ($field->getSelectedOptions($model) as $option => $title)
                    <option selected value="{{ $option }}">{{ $title }}</option>
                @endforeach
            @endif
        @else
            @if ($field->isGroupedRelation())
                @foreach ($field->getGroupedOptions() as $group => $options)
                    <optgroup label="{{ $group }}">
                        @foreach ($options as $option => $title)
                            <option {{ $field->isCurrentOption($option, $model, $loop->parent->index) ? 'selected' : '' }} value="{{ crc32($group) }}~~~{{ $option }}">{{ $title }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            @else
                @if ($field->isNullable())
                    <option value="">{{ __('jarboe::fields.select.none') }}</option>
                @endif
                @foreach ($field->getOptions() as $option => $title)
                    @if (is_array($title))
                        <optgroup label="{{ $option }}">
                            @foreach ($title as $groupItemOption => $groupOptionTitle)
                                <option {{ $field->isCurrentOption($groupItemOption, $model) ? 'selected' : '' }} value="{{ $groupItemOption }}">{{ $groupOptionTitle }}</option>
                            @endforeach
                        </optgroup>
                    @else
                        <option {{ $field->isCurrentOption($option, $model) ? 'selected' : '' }} value="{{ $option }}">{{ $title }}</option>
                    @endif
                @endforeach
            @endif
        @endif
    </select>

    @if ($field->isSelect2Type() || !$field->isMultiple())
        <i></i>
    @endif
</label>

@foreach ($errors->get($field->name()) as $message)
    <div class="note note-error">{{ $message }}</div>
@endforeach

@if ($field->isSelect2Type())
    @include('jarboe::crud.fields.select.inc.select2_scripts')
@endif
