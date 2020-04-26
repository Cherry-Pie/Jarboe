<label class="label">{{ $field->title() }}</label>

<div class="input {{ $errors->has($field->name()) ? 'state-error' : '' }}">
    <input class="form-control clockpicker-field"
           type="text"
           name="{{ $field->name() }}"
           placeholder="{{ $field->getPlaceholder() }}"
           value="{{ $field->oldOrAttribute($model) }}"
           data-placement="{{ $field->getPlacement() }}"
           data-autoclose="true"
           autocomplete="off">
    <i class="icon-append fa fa-clock-o"></i>
</div>

@foreach ($errors->get($field->name()) as $message)
    <div class="note note-error">{{ $message }}</div>
@endforeach

@include('jarboe::crud.fields.time.inc.scripts')
