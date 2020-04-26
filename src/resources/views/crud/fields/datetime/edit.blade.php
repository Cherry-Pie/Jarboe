<label class="label">{{ $field->title() }}</label>

<div class="input {{ $errors->has($field->name()) ? 'state-error' : '' }}">
    <input type="hidden"
           name="{{ $field->name() }}"
           value="{{ $field->oldOrAttribute($model) }}"
           class="datetimepicker-value-field">
    <input class="form-control datetimepicker-field"
           type="text"
           data-format="{{ $field->getDateFormat() }}"
           data-default-date="{{ $field->getDefault() }}"
           placeholder="{{ $field->getPlaceholder() }}"
           value="{{ $field->oldOrAttribute($model) }}">
    <i class="icon-append fa fa-calendar"></i>
</div>


@foreach ($errors->get($field->name()) as $message)
    <div class="note note-error">{{ $message }}</div>
@endforeach


@include('jarboe::crud.fields.datetime.inc.scripts')
