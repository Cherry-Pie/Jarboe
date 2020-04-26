<label class="label">{{ $field->title() }}</label>

<div class="textarea {{ $errors->has($field->name()) ? 'state-error' : '' }}">
    <textarea name="{{ $field->name() }}" class="markdown-field custom-scroll">{!! $field->oldOrDefault() !!}</textarea>
</div>


@foreach ($errors->get($field->name()) as $message)
    <div class="note note-error">{{ $message }}</div>
@endforeach


@include('jarboe::crud.fields.markdown.inc.scripts')
