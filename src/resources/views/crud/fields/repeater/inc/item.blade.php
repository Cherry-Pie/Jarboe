<?php
/** @var \Yaro\Jarboe\Table\Fields\Repeater $repeater */
/** @var \Yaro\Jarboe\Table\Fields\AbstractField $field */
?>
<div class="repeater repeater_{{ $repeater->name() }}_{{ $locale ?: 'default' }}">
    <div data-repeater-list="{{ $repeater->name() }}{{ $locale ? '['. $locale .']' : '' }}" class="drag">

        <div class="progress" data-repeater-progress>
            <div class="progress progress-striped">
                <div class="progress-bar bg-color-purple active" role="progressbar" style="width: 100%; background-color: #4387bf !important;"></div>
            </div>
        </div>

        <script data-repeater-item-template type="text/template">
            <div data-repeater-item data-repeater-default-item class="repeater-item">
                <div class="drag-handle {{ $repeater->isSortable() ? 'sortable' : '' }}"><span data-repeater-heading></span></div>

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
                        {!! $field->getCreateFormView() !!}
                    </section>

                    @if (!$field->getCol())
                        <?php $rowsLeft = 12; ?>
                        </div>
                    @endif
                @endforeach
                </fieldset>

                <div class="repeater-item-btn-container">
                    <a href="javascript:void(0);" class="btn btn-default btn-xs" data-repeater-expand-toggle>
                        <i class="fa fa-compress" aria-hidden="true" data-repeater-compress></i>
                        <i class="fa fa-expand" aria-hidden="true" style="display: none;" data-repeater-expand></i>
                    </a>
                    <a href="javascript:void(0);" class="btn btn-default btn-xs" data-repeater-delete>{{ __('jarboe::fields.repeater.delete_item') }}</a>
                </div>
            </div>
        </script>

    </div>
    <button type="button" class="btn btn-default btn-sm" data-repeater-create>{{ __('jarboe::fields.repeater.add_item') }}</button>
</div>

@push('scripts')
    <script>
        $(document).ready(function () {
            'use strict';

            let $repeater = $('.repeater_{{ $repeater->name() }}_{{ $locale ?: 'default' }}').repeater({
                initEmpty: true,
                renderUrl: '{{ $crud->renderRepeaterItemUrl($repeater->name()) }}',
                locale: '{{ $locale }}',
                heading: '{{ $repeater->getHeadingName() }}',
                show: function () {
                    $(this).slideDown({
                        complete: function () {
                            @foreach ($repeater->getFields() as $field)
                                Jarboe.init('{{ $field->name() }}');
                                console.log('{{ $field->name() }}');
                            @endforeach
                        }
                    });
                },
                hide: function (deleteElement) {
                    const $element = $(this);

                    jarboe.confirmBox({
                        title: "{{ __('jarboe::fields.repeater.confirmbox_delete_title') }}",
                        content: "{{ __('jarboe::fields.repeater.confirmbox_delete_description') }}",
                        buttons: {
                            '{{ __('jarboe::fields.repeater.confirmbox_delete_yes') }}': function() {
                                $element.slideUp(deleteElement);
                            },
                            '{{ __('jarboe::fields.repeater.confirmbox_delete_no') }}': null,
                        },
                    });
                },
                ready: function (setIndexes) {

                }
            });
            $repeater.setList({!! json_encode($data) !!}, {!! json_encode($errors) !!});

            @if ($repeater->isSortable())
                $('.repeater_{{ $repeater->name() }}_{{ $locale ?: 'default' }}').find('.drag').sortable({
                    axis: 'y',
                    handle: '.drag-handle',
                    cursor: 'grabbing',
                    opacity: 0.5,
                    placeholder: 'row-dragging',
                    delay: 150,
                    forcePlaceholderSize: true,
                    items: ".repeater-item",
                });
            @endif
        });
    </script>
@endpush
