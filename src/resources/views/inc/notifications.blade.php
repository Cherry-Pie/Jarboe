@if (session('jarboe_notifications.small'))
    <script>
        $(document).ready(function() {
            @foreach (session('jarboe_notifications.small', []) as $message)
            $.smallBox(
                    {!! json_encode(array_filter($message)) !!}
            );
            @endforeach
        });
    </script>
@endif

@if (session('jarboe_notifications.big'))
    <script>
        $(document).ready(function() {
            @foreach (session('jarboe_notifications.big', []) as $message)
            $.bigBox(
                    {!! json_encode(array_filter($message)) !!}
            );
            @endforeach
        });
    </script>
@endif
