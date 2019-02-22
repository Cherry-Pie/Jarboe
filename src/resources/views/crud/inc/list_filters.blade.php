
@foreach ($fields as $field)

    @if ($field->isMarkupRow())
        @include('jarboe::crud.inc.list_filters', [
            'fields' => $field->getFields(),
        ])
    @else
        @if ($field->hidden('list'))
            @continue
        @endif

        <th class="hasinput th-field-{{ $field->name() }}">
            {!! !$field->filter() ? '' : $field->filter()->render() !!}
        </th>
    @endif

@endforeach
