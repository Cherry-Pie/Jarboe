
<label class="label">{{ $field->title() }}</label>
<label class="input state-disabled">

    <div class="input-group">
        <input data-placement="bottomLeft" class="form-control icp" value="{{ $model->{$field->name()} }}" type="text" disabled="disabled"/>
        <span class="input-group-addon">
            <i class="fa {{ $model->{$field->name()} }}"></i>
        </span>
    </div>
</label>

