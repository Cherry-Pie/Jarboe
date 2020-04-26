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
        @foreach (collection_wrap($model->{$field->getRelationMethod()})->filter() as $relatedModel)
            {{ $relatedModel->{$field->getRelationTitleField()} }}
            <br>
        @endforeach
    @endif

@else
    @if ($field->isMultiple())
        @foreach($field->getAttribute($model) as $option)
            {{ $options[$option] ?? '' }}
            <br>
        @endforeach
    @else
        {{ $options[$field->getAttribute($model)] ?? '' }}
    @endif
@endif
