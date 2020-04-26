@push('scripts')
    <script>
        Jarboe.add('{{ $field->name() }}', function() {
            $(".select-2--tags").select2({
                tags: true
            });
        }, '{{ $locale ?? 'default' }}');

        $(document).ready(function () {
            Jarboe.init('{{ $field->name() }}', '{{ $locale ?? 'default' }}');
        });
    </script>
@endpush
