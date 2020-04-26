<label class="label">{{ $field->title() }}</label>

<label class="input {{ $errors->has($field->name()) ? 'state-error' : '' }}">
    <i class="icon-append fa fa-calendar"></i>
    <input type="hidden"
           name="{{ $field->name() }}"
           value="{{ $field->oldOrDefault() }}"
           class="datepicker-value-field">
    <input type="text"
           value="{{ $field->oldOrDefault() }}"
           placeholder="{{ $field->getPlaceholder() }}"
           class="datepicker-field"
           data-date-format="{{ $field->getDateFormat() }}"
           data-number-of-months="{{ $field->getMonths() }}"
           autocomplete="off">
</label>


@foreach ($errors->get($field->name()) as $message)
    <div class="note note-error">{{ $message }}</div>
@endforeach


@push('scripts')
    <script>
        Jarboe.add('{{ $field->name() }}', function() {
            $('.datepicker-field').not('.hasDatepicker').each(function() {
                let $this = $(this);

                $this.datepicker({
                    dateFormat: $this.data(jarboe.kebabCase('dateFormat')),
                    numberOfMonths: $this.data(jarboe.kebabCase('numberOfMonths')),
                    prevText: '<i class="fa fa-chevron-left"></i>',
                    nextText: '<i class="fa fa-chevron-right"></i>',
                }).on('change', function() {
                    var d = $(this).datepicker('getDate');
                    var date = [
                        d.getFullYear(),
                        ('0' + (d.getMonth() + 1)).slice(-2),
                        ('0' + d.getDate()).slice(-2)
                    ].join('-');

                    $(this).parent().find('input.datepicker-value-field').val(date);
                });

                if ($this.val()) {
                    $this.val(
                        moment($this.val()).format(
                            $this.data(jarboe.kebabCase('dateFormat'))
                        )
                    );
                }
            });
        }, '{{ $locale ?? 'default' }}');

        $(document).ready(function () {
            Jarboe.init('{{ $field->name() }}', '{{ $locale ?? 'default' }}');
        });
    </script>
@endpush
