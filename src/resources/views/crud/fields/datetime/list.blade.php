<span class="parse-to-format-moment"
      style="display: none;"
      data-date="{{ $field->getAttribute($model) }}"
      data-format="{{ $field->getDateFormat() }}">
        {{ $field->getAttribute($model) }}
</span>

@pushonce('script_files', <script>
    $('.parse-to-format-moment').each(function() {
        var $ctx = $(this);
        if ($ctx.data('date')) {
            $ctx.text(moment($ctx.data('date')).format($ctx.data('format')));
        }
        $ctx.show();
    });
</script>)
