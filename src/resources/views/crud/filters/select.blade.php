
<label class="select {{ $filter->isMultiple() ? 'select-multiple' : '' }}">
    <select name="search[{{ $filter->field()->name() }}]{{ $filter->isMultiple() ? '[]' : '' }}" {{ $filter->isMultiple() ? 'multiple' : '' }} class="custom-scroll form-control">
        @if (!$filter->isMultiple())
            <option value="{{ $desearch }}">No search input applied</option>
        @endif

        @foreach ($filter->getOptions() as $option => $title)
            <option
                    @if ($filter->isMultiple())
                        {{ in_array($option, $value) ? 'selected="selected"' : '' }}
                    @else
                        {{ $option == $value ? 'selected="selected"' : '' }}
                    @endif
                    value="{{ $option }}">{{ $title }}</option>
        @endforeach
    </select>

    @if (!$filter->isMultiple())
        <i></i>
    @endif
</label>

