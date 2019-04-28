
<span class="parse-to-format-moment"
      style="display: none;"
      data-date="{{ $model->{$field->name()} }}"
      data-format="{{ $field->getDateFormat() }}">
        {{ $model->{$field->name()} }}
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
