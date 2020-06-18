<?php
/**
 * @var \Yaro\Jarboe\Table\Fields\Checkbox $field
 */
?>
@if ($field->isInline())
    <?php $id = uniqid('onoffswitch'); ?>
    <span class="onoffswitch">
        <input type="checkbox" name="{{ $field->name() }}" class="onoffswitch-checkbox" id="{{ $id }}" {{ $field->getAttribute($model) ? 'checked' : '' }}>
        <label class="onoffswitch-label" for="{{ $id }}">
            <span class="onoffswitch-inner" data-swchon-text="{{ __('jarboe::fields.checkbox.yes') }}" data-swchoff-text="{{ __('jarboe::fields.checkbox.no') }}"></span>
            <span class="onoffswitch-switch"></span>
        </label>
    </span>

    @push('scripts')
        <script>
            $('#{{ $id }}').on('change', function (e) {
                const input = this;
                const isChecked = this.checked;

                input.disabled = true;

                $.ajax({
                    method: "POST",
                    url: '{{ $field->getInlineUrl() }}',
                    dataType: "json",
                    data: {
                        '_pk': {{ $model->getKey() }},
                        '_name': this.name,
                        '_value': isChecked ? 1 : 0,
                        '_locale': '',
                    },
                }).done(function (response) {
                    input.checked = response.value;
                }).fail(function(jqxhr) {
                    input.checked = !isChecked;
                    if (jqxhr.status === 406) {
                        return;
                    }

                    jarboe.bigToastDanger(
                        "{{ $field->title()  }}",
                        jqxhr.responseJSON.errors["{{ $field->name() }}"].join("\n"),
                        4000
                    );
                }).always(function () {
                    input.disabled = false;
                });
            });
        </script>
    @endpush
@else
    @if ($field->getAttribute($model))
        <i class="fa fa-check-square-o"></i>
    @else
        <i class="fa fa-square-o"></i>
    @endif
@endif
