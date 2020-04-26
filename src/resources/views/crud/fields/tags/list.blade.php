@if ($field->isRelationField())
    @foreach($model->{$field->getRelationMethod()} as $relatedModel)
        {{ $relatedModel->{$field->getRelationTitleField()} }}
        <br>
    @endforeach
@else
    @foreach((is_array($field->getAttribute($model)) ? $field->getAttribute($model) : []) as $value)
        {{ $value }}
        <br>
    @endforeach
@endif
