<div class="repeater repeater_{{ $repeater->name() }}_{{ $locale ?: 'default' }}">
    <div data-repeater-list="{{ $repeater->name() }}{{ $locale ? '['. $locale .']' : '' }}" class="drag">

        <script data-repeater-item-template type="text/template">
            <div data-repeater-item data-repeater-default-item class="repeater-item">
                <div class="drag-handle {{ $repeater->isSortable() ? 'sortable' : '' }}"></div>

                <fieldset>
                @foreach ($repeater->getFields() as $field)

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
                        {!! $field->getCreateFormValue() !!}
                    </section>

                @endforeach
                </fieldset>

                <a href="javascript:void(0);" class="btn btn-default btn-xs repeater-item-delete" data-repeater-delete>delete</a>
            </div>
        </script>

    </div>
    <button type="button" class="btn btn-default btn-sm" data-repeater-create>add item</button>
</div>


@push('scripts')
    <script>
        $(document).ready(function () {
            'use strict';

            let $repeater = $('.repeater_{{ $repeater->name() }}_{{ $locale ?: 'default' }}').repeater({
                initEmpty: true,
                show: function () {
                    $(this).slideDown();

                    @foreach ($repeater->getFields() as $field)
                        Jarboe.init('{{ $field->name() }}');
                    @endforeach
                },
                hide: function (deleteElement) {
                    const $element = $(this);

                    $.SmartMessageBox({
                        title: "delete repeater item",
                        content: "cant be undone",
                        buttons: '[no][yes]'
                    }, function (ButtonPressed) {
                        if (ButtonPressed === "yes") {
                            $element.slideUp(deleteElement);
                        }
                    });
                },
                ready: function (setIndexes) {

                }
            });
            $repeater.setList({!! json_encode($repeater->oldOrAttribute($model, null, $locale)) !!});
            $repeater.setErrors({!! json_encode($errors) !!});

            @if ($repeater->isSortable())
                $('.repeater_{{ $repeater->name() }}_{{ $locale ?: 'default' }}').find('.drag').sortable({
                    axis: 'y',
                    handle: '.drag-handle',
                    cursor: 'grabbing',
                    opacity: 0.5,
                    placeholder: 'row-dragging',
                    delay: 150,
                    forcePlaceholderSize: true
                });
            @endif
        });
    </script>
@endpush
