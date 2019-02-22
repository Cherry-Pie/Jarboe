
@foreach ($fields as $field)

    @if ($field->isMarkupRow())
        @include('jarboe::crud.inc.list_headers', [
            'fields' => $field->getFields(),
        ])
    @else
        @if ($field->hidden('list'))
            @continue
        @endif
        <th class="th-field-{{ $field->name() }}"
            @if ($field->getWidth())
            width="{{ $field->getWidth() }}%"
            @endif

            @if ($field->isOrderable())
                @if ($crud->getOrderFilterParam($field->name()) == 'asc')
                    class="sorting sorting_asc"
                    data-url="{{ $crud->orderUrl($field->name(), 'desc') }}"
                @elseif ($crud->getOrderFilterParam($field->name()) == 'desc')
                    class="sorting sorting_desc"
                    data-url="{{ $crud->orderUrl($field->name(), 'asc') }}"
                @else
                    class="sorting"
                    data-url="{{ $crud->orderUrl($field->name(), 'asc') }}"
                @endif
            @endif
        >{{ $field->title() }}</th>
    @endif

@endforeach
