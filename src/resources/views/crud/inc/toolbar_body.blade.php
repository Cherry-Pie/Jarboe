
@if ($tools)
<div class="widget-body-toolbar">

    <div class="row">

        @foreach ($tools as $tool)
            {!! $tool->render() !!}
        @endforeach
        {{--
        <div class="col-xs-9 col-sm-5 col-md-5 col-lg-5">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                <input class="form-control" id="prepend" placeholder="Filter" type="text">
            </div>
        </div>
        <div class="col-xs-3 col-sm-7 col-md-7 col-lg-7 text-right">

            <button class="btn btn-success">
                <i class="fa fa-plus"></i> <span class="hidden-mobile">Add New Row</span>
            </button>

        </div>
        --}}
    </div>

</div>
@endif
