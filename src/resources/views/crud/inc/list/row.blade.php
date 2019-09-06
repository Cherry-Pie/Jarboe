
@foreach ($fields as $field)

    @if ($field->isMarkupRow())
        @include('jarboe::crud.inc.list.row', [
            'item'   => $item,
            'fields' => $field->getFields(),
        ])
    @else
        @if ($field->hidden('list'))
            @continue
        @endif
        <td class="td-field-{{ $field->name() }}">{!! $field->getListValue($item) !!}</td>
    @endif

@endforeach
