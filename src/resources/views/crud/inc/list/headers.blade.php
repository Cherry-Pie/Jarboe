
@foreach ($fields as $field)

    @if ($field->isMarkupRow())
        @include('jarboe::crud.inc.list.headers', [
            'fields' => $field->getFields(),
        ])
    @else
        @if ($field->hidden('list'))
            @continue
        @endif
        <th
            @if ($field->getWidth())
                width="{{ $field->getWidth() }}%"
            @endif

            @if ($field->isOrderable())
                @if ($crud->getOrderFilterParam($field->name()) == 'asc')
                    class="th-field-{{ $field->name() }} sorting sorting_asc"
                    data-url="{{ $crud->orderUrl($field->name(), 'desc') }}"
                @elseif ($crud->getOrderFilterParam($field->name()) == 'desc')
                    class="th-field-{{ $field->name() }} sorting sorting_desc"
                    data-url="{{ $crud->orderUrl($field->name(), 'asc') }}"
                @else
                    class="th-field-{{ $field->name() }} sorting"
                    data-url="{{ $crud->orderUrl($field->name(), 'asc') }}"
                @endif
            @else
                class="th-field-{{ $field->name() }}"
            @endif
        >{{ $field->title() }}</th>
    @endif

@endforeach
