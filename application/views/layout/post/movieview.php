<?php if ($this->session->userdata('user_type') != 4) { ?>
    <div class="page-header">
        <div class="page-title">
            <h3>
                <?php
                if (!empty($title)): echo $title;
                endif;
                ?>
            </h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="widget box">
                <div class="widget-header">
                    <a href="<?php echo base_url() . 'post/getfromb/'; ?>"  class="btn btn-warning pull-right">Blog</a>
                    <a href="<?php echo base_url() . 'post/vdokh/'; ?>"  class="btn btn-info pull-right">vdo4kh</a>                                    
                    <a href="<?php echo base_url() . 'post/khmermove/'; ?>"  class="btn btn-google-plus pull-right">khmermo</a>
                    <a href="<?php echo base_url() . 'post/khmerbe/'; ?>"  class="btn btn-facebook pull-right">khmerbe</a>
                    <h4>
                        <i class="icon-reorder">
                        </i>
                        Managed Table
                    </h4>
                </div>
                <div class="widget-content">
                    <div class="row">
                        <div class="dataTables_header clearfix">
                            <div class="col-md-6">
                                <div id="DataTables_Table_0_length" class="dataTables_length">
                                    <label>                                    
                                        <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" class="select2-offscreen" tabindex="-1">
                                            <option value="5" selected="selected">
                                                5
                                            </option>
                                            <option value="10">
                                                10
                                            </option>
                                            <option value="25">
                                                25
                                            </option>
                                            <option value="50">
                                                50
                                            </option>
                                            <option value="-1">
                                                All
                                            </option>
                                        </select>
                                        records per page
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="dataTables_filter" id="DataTables_Table_0_filter">
                                    <label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="icon-search">
                                                </i>
                                            </span>
                                            <input type="text" aria-controls="DataTables_Table_0" class="form-control">
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table class="table table-striped table-bordered table-hover table-checkable datatable">
                        <thead>
                            <tr>
                                <th>
                                    Movie Name
                                </th>
                                <th class="hidden-xs">
                                    Post ID
                                </th>
                                <th>
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bloglist as $value) { ?>
                                <tr>
                                    <td>
                                        <?php echo $value->{Tbl_title::title}; ?>
                                    </td>
                                    <td class="hidden-xs">

                                        <?php foreach ($post_id as $values) : ?>
                                            <form method="post">
                                                <input type="hidden" value="<?php echo $value->{Tbl_title::id}; ?>" name="movieid"/>
                                                <div class="form-group">
                                                    <div class="col-md-8">
                                                        <input type="hidden" value="<?php echo $values->{Tbl_meta::id}; ?>" name="movie_pid"/>
                                                        <input type="text" value="<?php echo $values->{Tbl_meta::key}; ?>" name="idpost" class="form-control"/>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <button type="submit" id="multidel" name="submit" class="btn btn-info pull-right">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                            <?php
                                        endforeach;
                                        ?>

                                    </td>
                                    <td>
                                        <?php if ($value->{Tbl_title::status} == 1) { ?>
                                            <span class="label label-success">
                                                Active
                                            </span>
                                        <?php } elseif ($value->{Tbl_title::status} == 0) { ?>
                                            <span class="label label-danger">
                                                Inactive
                                            </span>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <div class="row">
                            <div class="dataTables_footer clearfix">
                                <div class="col-md-12">
                                    <a href="<?php echo base_url() . 'post/movies/'; ?>" class="btn btn-facebook pull-right">Back</a>
                                </div>
                            </div>
                        </div>
                    <!-- end page -->

                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function Confirms(text, layout, id, type) {
            var n = noty({
                text: text,
                type: type,
                dismissQueue: true,
                layout: layout,
                theme: 'defaultTheme',
                modal: true,
                buttons: [
                    {addClass: 'btn btn-primary', text: 'Ok', onClick: function($noty) {
                            $noty.close();
                            window.location = "<?php echo base_url(); ?>user/delete/" + id;
                        }
                    },
                    {addClass: 'btn btn-danger', text: 'Cancel', onClick: function($noty) {
                            $noty.close();
                        }
                    }
                ]
            });
            console.log('html: ' + n.options.id);
        }
        function generate(type) {
            var n = noty({
                text: type,
                type: type,
                dismissQueue: false,
                layout: 'top',
                theme: 'defaultTheme'
            });
            console.log(type + ' - ' + n.options.id);
            return n;
        }

        function generateAll() {
            generate('alert');
            generate('information');
            generate('error');
            generate('warning');
            generate('notification');
            generate('success');
        }

    </script>
    <?php
} else {
    echo '<div class="alert fade in alert-danger" >
                            <strong>You have no permission on this page!...</strong> .
                        </div>';
}
?>
