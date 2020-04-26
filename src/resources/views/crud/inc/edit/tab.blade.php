@foreach ($fields as $field)
    @if ($field->hidden('edit'))
        @continue
    @endif

    @if ($rowsLeft - $field->getCol() < 0)
        <?php $rowsLeft = 12; ?>
        </div>
    @endif

    @if ($rowsLeft == 12)
        <div class="{{ $field->getCol() == 12 ? '' : 'row' }}">
    @endif



    @if ($rowsLeft - $field->getCol() >= 0)
        <?php $rowsLeft -= $field->getCol(); ?>
    @endif

    <section class="{{ $field->getCol() == 12 ? '' : 'col col-'. $field->getCol() }}">
        {!! $field->getEditFormView($item) !!}
    </section>

    @if (!$field->getCol())
        <?php $rowsLeft = 12; ?>
        </div>
    @endif
@endforeach
