
<label class="select">
    <select name="search[{{ $filter->field()->name() }}]" class="form-control">
        @if (!$filter->field()->isMultiple())
            <option {{ $value == $desearch ? 'selected' : '' }} value="{{ $desearch }}">{{ $filter->getDesearchTitle() }}</option>
        @endif

        <option {{ $value != $desearch && !$value ? 'selected' : '' }} value="0">{{ $filter->getUncheckedTitle() }}</option>
        <option {{ $value != $desearch && $value ? 'selected' : '' }} value="1">{{ $filter->getCheckedTitle() }}</option>
    </select>

    @if (!$filter->field()->isMultiple())
        <i></i>
    @endif
</label>

