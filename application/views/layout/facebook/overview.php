    <div class="page-header">
        <div class="page-title">
            <h3>
                <?php if (!empty($title)): echo $title;
                endif; ?>
            </h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="widget box">
                <div class="widget-header">
                    <a href="<?php echo base_url() . 'Facebook/addgroup/'; ?>"  class="btn btn-facebook pull-right"><i class="fa fa-facebook"></i> Add New Groups</a>
                    <a href="<?php echo base_url() . 'Facebook/newlist/'; ?>"  class="btn btn-warning pull-right">Create New List</a>
                    
                    <h4>
                        <i class="icon-reorder">
                        </i>
                        <?php if (!empty($title)): echo $title;
                endif; ?>
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
                                    <form method="post">
                                    <label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="icon-search">
                                                </i>
                                            </span>
                                            <input type="text" aria-controls="DataTables_Table_0" class="form-control" name="filtername" />
                                        </div>
                                    </label>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form method="post">
                        <table class="table table-striped table-bordered table-hover table-checkable datatable">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" class="uniform" name="allbox" id="checkAll" />
                                    </th>
                                    <th>
                                        Group Name
                                    </th>
                                    <th>
                                        Category
                                    </th>
                                    <th>
                                        count
                                    </th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
    <?php foreach ($list as $value) { ?>
                                    <tr>
                                        <td class="checkbox-column">
                                            <input type="checkbox" id="itemid" name="itemid[]" class="uniform" value="<?php echo $value->l_id; ?>" />
                                        </td>
                                        <td>
                                            <a href="<?php echo base_url(); ?>Facebook/overview/<?php echo $value->l_id; ?>"><?php echo $value->lname; ?></a>
                                        </td> 
                                        <td>
                                            <?php echo $value->l_category; ?>
                                        </td>
                                        <td>
                                            <?php echo $value->l_count; ?>
                                        </td>
                                        <td style="width: 80px;">
                                            <div class="btn-group">
                                                <button class="btn btn-sm dropdown-toggle" data-toggle="dropdown">
                                                    <i class="icol-cog"></i>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="<?php echo base_url(); ?>Facebook/addgroup?id=<?php echo $value->l_id; ?>"><i class="icon-edit"></i> Addmore</a>
                                                    </li>
                                                    <li>
                                                        <a data-modal="true" data-text="Do you want to delete this Blog?" data-type="confirm" data-class="error" data-layout="top" data-action="Facebook/delete/grouplist/<?php echo $value->l_id; ?>" class="btn-notification"><i class="icon-remove"></i> Remove</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
    <?php } ?>
                            </tbody>
                        </table>

                        <!-- page -->
                        <div class="row">
                            <div class="dataTables_footer clearfix">
                                <div class="col-md-6">
                                    <div class="dataTables_info" id="DataTables_Table_0_info">
                                        Showing 1 to <?php echo count($list); ?> of <?php echo $total_rows; ?> entries
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="dataTables_paginate paging_bootstrap">
                                        <ul class="pagination">
                                            <?php echo $links; ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" id="multidel" name="submit" class="btn btn-google-plus pull-right">Delete</button>
                                </div>
                            </div>
                        </div>
                    </form>
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