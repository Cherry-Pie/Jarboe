<div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-custombutton="false">
    <!-- widget options:
        usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

        data-widget-colorbutton="false"
        data-widget-editbutton="false"
        data-widget-togglebutton="false"
        data-widget-deletebutton="false"
        data-widget-fullscreenbutton="false"
        data-widget-custombutton="false"
        data-widget-collapsed="true"
        data-widget-sortable="false"

    -->
    <header>
        <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
        <h2>Edit node </h2>

    </header>


    <div>

        <!-- widget edit box -->
        <div class="jarviswidget-editbox">
            <!-- This area used as dropdown edit box -->

        </div>
        <!-- end widget edit box -->

        <!-- widget content -->
        <div class="widget-body no-padding">

            <form id="edit-node-form" style="display: none;" class="smart-form" novalidate="novalidate" action="{{ admin_url('admin-panel/navigation/update') }}" method="post">
                @csrf
                <input type="hidden" value="" name="id">
                <fieldset>
                    <div class="row">
                        <section class="col col-6">
                            <label class="input">
                                <input type="text" name="name" placeholder="Title">
                            </label>
                        </section>
                        <section class="col col-6">
                            <label class="input">
                                <input type="text" name="slug" placeholder="Slug">
                            </label>
                        </section>
                    </div>

                    <div class="row">
                        <section class="col col-6">
                            <div class="input-group">
                                <input data-placement="bottomLeft" class="form-control icp icp-auto" placeholder="Icon" name="icon" type="text" style="background-color: #fff;padding-left: 10px;"/>
                                <span class="input-group-addon">
                                    <i class="fa " style="margin-left: 10px;"></i>
                                </span>
                            </div>
                        </section>
                        <section class="col col-6">
                            <label class="checkbox">
                                <input type="checkbox" name="is_active">
                                <i></i>Is active node</label>
                        </section>
                    </div>
                </fieldset>

                <footer>
                    <button type="submit" class="btn btn-primary">
                        Update
                    </button>
                </footer>
            </form>


        </div>
        <!-- end widget content -->

    </div>
</div>
