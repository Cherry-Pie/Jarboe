@if ($filter->isSelectField())
    <label class="select {{ $filter->isMultiple() ? 'select-multiple' : '' }}">
        <select name="search[{{ $filter->field()->name() }}]{{ $filter->isMultiple() ? '[]' : '' }}"
                {{ $filter->isMultiple() ? 'multiple' : '' }}
                class="custom-scroll form-control {{ $filter->field()->isSelect2Type() ? 'select2'.$filter->field()->name() : '' }}">
            @if (!$filter->field()->isSelect2Type() && !$filter->isMultiple())
                <option value="{{ $desearch }}">{{ __('jarboe::fields.select.no_filter_value') }}</option>
            @endif


            @if ($filter->field()->isAjax() && $filter->field()->isRelationField() && $filter->field()->isSelect2Type() && !$filter->field()->isGroupedRelation())
                @foreach ($filter->getSelectedOptions($values) as $option => $title)
                    <option selected value="{{ $option }}">{{ $title }}</option>
                @endforeach
            @else
                @if ($filter->field()->isGroupedRelation())
                    @foreach ($filter->getSelectedGroupedOptions() as $group => $options)
                        <optgroup label="{{ $group }}">
                            @foreach ($options as $option => $title)
                                <option selected value="{{ crc32($group) }}~~~{{ $option }}">{{ $title }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                @else
                    @if ($filter->isNullable())
                        <option value="">{{ __('jarboe::fields.select.none') }}</option>
                    @endif
                    @foreach ($filter->field()->getOptions() as $option => $title)
                        <option {{ in_array($option, $values) ? 'selected' : '' }} value="{{ $option }}">{{ $title }}</option>
                    @endforeach
                @endif
            @endif
        </select>

        @if ($filter->field()->isSelect2Type() || !$filter->isMultiple())
            <i></i>
        @endif
    </label>


    @if ($filter->field()->isSelect2Type())
        @pushonce('script_files', <script src="/vendor/jarboe/js/plugin/select2/select2.min.js"></script>)

        @push('scripts')
            <script>
                var options = {
                    placeholder: {
                        id: '{{ $desearch }}',
                        text: '{{ __('jarboe::fields.select.no_filter_value') }}'
                    },
                    allowClear: true,
                    @if ($filter->field()->isAjax() && $filter->field()->isRelationField())
                    ajax: {
                        url: '{{ $filter->field()->getRelationSearchUrl() }}',
                        method: 'post',
                        dataType: 'json',
                        data: function (params) {
                            return {
                                term: params.term || "",
                                page: params.page || 1,
                                field: '{{ $filter->field()->name() }}'
                            };
                        }
                    },
                    @endif
                };
                $('.select2{{ $filter->field()->name() }}').select2(options);
            </script>
        @endpush
    @endif
@else
    <label class="select {{ $filter->isMultiple() ? 'select-multiple' : '' }}">
        <select name="search[{{ $filter->field()->name() }}]{{ $filter->isMultiple() ? '[]' : '' }}"
                {{ $filter->isMultiple() ? 'multiple' : '' }}
                class="custom-scroll form-control">
            @if (!$filter->isMultiple())
                <option value="{{ $desearch }}">{{ __('jarboe::fields.select.no_filter_value') }}</option>
            @endif

            @foreach ($filter->getOptions() as $option => $title)
                <option
                        {{ in_array($option, $values) ? 'selected="selected"' : '' }}
                        value="{{ $option }}">{{ $title }}</option>
            @endforeach
        </select>

        @if (!$filter->isMultiple())
            <i></i>
        @endif
    </label>
@endif
