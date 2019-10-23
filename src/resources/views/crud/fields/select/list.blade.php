
@if ($field->isRelationField())

    @if ($field->isGroupedRelation())
        @foreach ($field->getSelectedGroupedOptions($model) as $group => $options)
            @if ($options)
                <b>{{ $group }}:</b>
                <ul>
                @foreach($options as $relatedKey => $relatedTitle)
                    <li>{{ $relatedTitle }}</li>
                @endforeach
                </ul>
            @endif
        @endforeach
    @else
        @foreach (collect($model->{$field->getRelationMethod()}) as $relatedModel)
            {{ $relatedModel->{$field->getRelationTitleField()} }}
            <br>
        @endforeach
    @endif

@else
    @if ($field->isMultiple())
        @foreach($model->{$field->name()} as $option)
            {{ $options[$option] ?? '' }}
            <br>
        @endforeach
    @else
        {{ $options[$model->{$field->name()}] ?? '' }}
    @endif
@endif
