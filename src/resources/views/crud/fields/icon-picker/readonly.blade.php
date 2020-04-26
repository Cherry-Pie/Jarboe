<label class="label">{{ $field->title() }}</label>
<label class="input state-disabled {{ $errors->has($field->name()) ? 'state-error' : '' }}">
    <div class="input-group">
        <input data-placement="bottomLeft" class="form-control icp" value="{{ $field->getAttribute($model) }}" type="text" disabled="disabled"/>
        <span class="input-group-addon">
            <i class="fa {{ $field->getAttribute($model) }}"></i>
        </span>
    </div>
</label>

@foreach ($errors->get($field->name()) as $message)
    <div class="note note-error">{{ $message }}</div>
@endforeach
