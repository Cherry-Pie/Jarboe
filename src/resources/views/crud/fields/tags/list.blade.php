@if ($field->isRelationField())
    @foreach($model->{$field->getRelationMethod()} as $relatedModel)
        {{ $relatedModel->{$field->getRelationTitleField()} }}
        <br>
    @endforeach
@else
    @foreach((is_array($model->{$field->name()}) ? $model->{$field->name()} : []) as $value)
        {{ $value }}
        <br>
    @endforeach
@endif
