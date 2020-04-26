<label class="label">{{ $field->title() }}</label>

<div class="textarea {{ $errors->has($field->name()) ? 'state-error' : '' }}">
    <textarea class="markdown-field custom-scroll" disabled="disabled">{!! $field->getAttribute($model) !!}</textarea>
</div>


@foreach ($errors->get($field->name()) as $message)
    <div class="note note-error">{{ $message }}</div>
@endforeach

@include('jarboe::crud.fields.markdown.inc.scripts')
