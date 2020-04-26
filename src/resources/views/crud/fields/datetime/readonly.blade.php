<label class="label">{{ $field->title() }}</label>

<div class="input state-disabled {{ $errors->has($field->name()) ? 'state-error' : '' }}">
    <input class="form-control datetimepicker-{{ $field->name() }}"
           type="text"
           placeholder="{{ $field->getPlaceholder() }}"
           value="{{ $field->getAttribute($model) }}"
           disabled="disabled">
    <i class="icon-append fa fa-calendar"></i>
</div>

@foreach ($errors->get($field->name()) as $message)
    <div class="note note-error">{{ $message }}</div>
@endforeach

@include('jarboe::crud.fields.datetime.inc.scripts')
