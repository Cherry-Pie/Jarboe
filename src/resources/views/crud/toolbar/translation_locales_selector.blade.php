
<div class="btn-group" data-toggle="buttons">
    @foreach ($locales as $locale => $title)
        <label class="btn btn-default btn-xs {{ $tool->isCurrentLocale($locale) ? 'active' : '' }} translation-locale-label" data-locale="{{ $locale }}">
            <input type="radio" name="translation-locale-{{ $locale }}" id="translation-locale-{{ $locale }}">
            {{ $title }}
        </label>
    @endforeach
</div>

@push('scripts')
    <script>
        $('label.translation-locale-label').on('click', function() {
            var locale = $(this).data('locale');

            $.ajax({
                url: '{{ $tool->getUrl() }}',
                data: { locale: locale },
                type: "POST"
            });

            $('.locale-field').hide();
            $('.locale-field-'+ locale).show();
        });
    </script>
@endpush
