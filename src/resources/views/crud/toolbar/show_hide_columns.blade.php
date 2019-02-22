
<div style="position: relative;">
    <div class="btn-group">
        <button id="show-hide-columns-btn" class="btn dropdown-toggle btn-xs btn-default">
            Show / hide columns
            <i class="fa fa-caret-down"></i>
        </button>
    </div>

    <ul id="show-hide-columns-list" class="ColVis_collection">
        @foreach ($tool->crud()->getColumnsAsFields() as $field)
            @if ($field->hidden('list'))
                @continue
            @endif

            <li>
                <label>
                    <input type="checkbox" value="{{ $field->name() }}" checked>
                    <span>{{ $field->title() }}</span>
                </label>
            </li>
        @endforeach
    </ul>
</div>

@push('body_end')
    <div id="show-hide-columns-background" class="ColVis_collectionBackground"></div>
@endpush

<script>
    var json = localStorage.getItem('show_hide_columns-{{ $tool->crud()->tableIdentifier() }}');
    var columns = JSON.parse(json) || {};

    Object.keys(columns).map(function(key, index) {
        var input = document.querySelector('input[value="'+ key +'"]');
        if (input) {
            input.checked = columns[key];

            var shouldHide = !columns[key];
            if (shouldHide) {
                var style = '<style>.th-field-'+ key +', .td-field-'+ key +' { display: none; }</style>';
                document.querySelector('head').innerHTML += style;
            }
        }
    });
</script>

@push('scripts')
    <script>
        $(document).mouseup(function(e) {
            var container = $('#show-hide-columns-list');
            // if the target of the click isn't the container nor a descendant of the container
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                container.hide();
                $('#show-hide-columns-background').hide();
            }
        });

        $('#show-hide-columns-list li').on('click', function() {
            var $input = $(this).find('input');
            var input = $input[0];
            input.checked = !input.checked;
            $input.trigger('change');
        });
        $('#show-hide-columns-list input').on('change', function() {
            var $elems = $('.th-field-'+ this.value +', .td-field-'+ this.value);
            if (this.checked) {
                $elems.show();

                var json = localStorage.getItem('show_hide_columns-{{ $tool->crud()->tableIdentifier() }}');
                var columns = JSON.parse(json) || {};
                columns[this.value] = true;
                localStorage.setItem('show_hide_columns-{{ $tool->crud()->tableIdentifier() }}', JSON.stringify(columns));
            } else {
                $elems.hide();

                var json = localStorage.getItem('show_hide_columns-{{ $tool->crud()->tableIdentifier() }}');
                var columns = JSON.parse(json) || {};
                columns[this.value] = false;
                localStorage.setItem('show_hide_columns-{{ $tool->crud()->tableIdentifier() }}', JSON.stringify(columns));
            }
        });


        $('#show-hide-columns-btn').on('click', function() {
            $('#show-hide-columns-background').show();
            $('#show-hide-columns-list').show();
        });
    </script>
@endpush

@push('styles')
    <style>
        #show-hide-columns-list {
            display: none;
            opacity: 1;
            position: absolute;
            width: 100%;
            -webkit-animation-name: flipInX;
            -moz-animation-name: flipInX;
            -o-animation-name: flipInX;
            animation-name: flipInX;
            -webkit-animation-duration: .4s;
            -moz-animation-duration: .4s;
            -o-animation-duration: .4s;
            animation-duration: .4s;
            -webkit-animation-fill-mode: both;
            -moz-animation-fill-mode: both;
            -o-animation-fill-mode: both;
            animation-fill-mode: both;
        }
        #show-hide-columns-list li {
            display: flex;
        }
        #show-hide-columns-list li label {
            line-height: 12px;
        }

        #show-hide-columns-background {
            opacity: 0.1;
            bottom: 0px;
            right: 0px;
            display: none;
        }
    </style>
@endpush
