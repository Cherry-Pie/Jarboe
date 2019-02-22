<div class="jarviswidget" id="wid-id-2" data-widget-editbutton="false" data-widget-custombutton="false">
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
        <h2>Create node </h2>

    </header>


    <div>

        <!-- widget edit box -->
        <div class="jarviswidget-editbox">
            <!-- This area used as dropdown edit box -->

        </div>
        <!-- end widget edit box -->

        <!-- widget content -->
        <div class="widget-body no-padding">

            <form id="create-node-form" class="smart-form" novalidate="novalidate" action="{{ admin_url('admin-panel/navigation/create') }}" method="post">
                @csrf
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



                <fieldset>
                    <div class="row">
                        <section class="col col-12" style="width: 100%;">

                            <label class="label">Roles</label>

                            <label class="select select-multiple ">
                                <select name="permissions[]"
                                        multiple
                                        class="custom-scroll select2permissions"
                                        style=&quot;width:100%&quot;>

                                    <option  value="1">test</option>
                                </select>

                                <i></i>
                            </label>

                            <div class="note">
                                <strong>Note:</strong> allow with any of this roles.
                            </div>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-12" style="width: 100%;">
                                <label class="label">Permissions</label>

                                <label class="select select-multiple ">
                                    <select name="permissions[]"
                                            multiple
                                            class="custom-scroll select2permissions"
                                            style=&quot;width:100%&quot;>

                                        <option  value="1">test</option>
                                    </select>

                                    <i></i>
                                </label>

                            <div class="note">
                                    <strong>Note:</strong> allow with any of this permissions.
                                </div>
                        </section>
                    </div>

                </fieldset>

                <footer>
                    <button type="submit" class="btn btn-primary">
                        Create
                    </button>
                </footer>
            </form>

        </div>
        <!-- end widget content -->

    </div>
</div>

@push('scripts')
    <script>
        $('.select2permissions').select2();


        $(function() {

            // Validation
            $("#create-node-form").validate({
                // Rules for form validation
                rules : {
                    name : {
                        required : true
                    },
                    slug : {
                        required : true
                    },
                },

                // Messages for form validation
                messages : {
                    name : {
                        required : 'Title is required'
                    },
                    slug : {
                        required : 'Slug is required'
                    },
                },

                // Do not change code below
                errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });

    </script>
@endpush



@pushonce('style_files', <link rel="stylesheet" type="text/css" href="/vendor/jarboe/js/plugin/fontawesome-iconpicker/fontawesome-iconpicker.min.css">)
@pushonce('script_files', <script src="/vendor/jarboe/js/plugin/fontawesome-iconpicker/fontawesome-iconpicker.min.js"></script>)

@push('styles')
    <style>
        div.iconpicker-popover.popover {
            width: 330px;
        }
        input[type=text].icp:focus+.input-group-addon {
            color: #555;
            background-color: #eee;
        }
        input[type=text].icp+.input-group-addon i {
            min-width: 15px;
        }
    </style>
@endpush

@push('scripts')

    <script>
        $(document).ready(function() {
            $('.icp-auto').iconpicker();
        })
    </script>
@endpush