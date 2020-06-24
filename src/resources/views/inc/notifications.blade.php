@if (session('jarboe_notifications.small'))
    <script>
        $(document).ready(function() {
            @foreach (session('jarboe_notifications.small', []) as $message)
                jarboe.smallToast(
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
            jarboe.bigToast(
                {!! json_encode(array_filter($message)) !!}
            );
            @endforeach
        });
    </script>
@endif

<?php
session()->forget('jarboe_notifications');
?>
