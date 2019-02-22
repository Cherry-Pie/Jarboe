
@foreach($model->{$field->getRelationMethod()} as $relatedModel)
    {{ $relatedModel->{$field->getRelationTitleField()} }}
    <br>
@endforeach
