<?php if ($this->session->userdata('user_type') != 4) { ?>
    <div class="page-header">
        <div class="page-title">
            <h3>
                Dynamic Tables (DataTables)
            </h3>
        </div>
        <ul class="page-stats">
            <li>
                <a class="btn btn-lg btn-primary" href="<?php echo base_url(); ?>post/addblog">Add blogs</a>
            </li>        
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="widget box">
                <div class="widget-header">
                    <h4>
                        <i class="icon-reorder">
                        </i>
                        Managed Table
                    </h4>
                    <div class="toolbar no-padding">
                        <div class="btn-group">
                            <span class="btn btn-xs widget-collapse">
                                <i class="icon-angle-down">
                                </i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="widget-content">
                    <form method="post">
                        <div class="row">
                            <div class="dataTables_header clearfix">
                                <div class="col-md-8">
                                    <label>
                                        <select name="bloginCat" class="form-control" onchange="location = this.value;">
                                            <option value="<?php echo base_url(); ?>post/bloglist">Show all</option>
                                            <?php
                                            $currentCat = (!empty($_GET['catid'])?$_GET['catid']:"");
                                            foreach ($blogcatlist as $value) { ?><option <?php echo ($currentCat == $value->{Tbl_title::id})?' selected="selected"':'';?> value="<?php echo base_url(); ?>post/bloglist/?catid=<?php echo $value->{Tbl_title::id}; ?>"><?php echo $value->{Tbl_title::title}; ?></option><?php } ?>
                                        </select>
                                    </label>
                                    <?php if ($this->session->userdata('user_type') == 1) :?>
                                    <label>                                    
                                        <select name="userid" class="form-control">
                                            <option></option>
                                            <?php foreach ($user_list as $value) { ?>
                                                <option value="<?php echo $value->log_id; ?>"><?php echo $value->name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </label>
                                    <?php endif;?>
                                    <button type="submit" id="multiadd" value="add" name="userblog" class="btn btn-success">Apply</button>
                                    <button type="submit" id="multiadd" value="add" name="filterby" class="btn btn-primary">seach</button>
                                </div>
                                <div class="col-md-4">
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
                                        <input type="checkbox" class="uniform" name="allbox" id="checkAll" />
                                    </th>
                                    <th>
                                        Blog Name
                                    </th>
                                    <th class="hidden-xs">
                                        Blog ID
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bloglist as $value) { ?>
                                    <tr>
                                        <td class="checkbox-column">
                                            <input type="checkbox" id="itemid" name="itemid[]" class="uniform" value="<?php echo $value->{Tbl_title::id}; ?>[=]<?php echo $value->{Tbl_title::title}; ?>[blogid]<?php echo $value->{Tbl_title::object_id}; ?>" />
                                        </td>
                                        <td>
                                            <?php echo $value->{Tbl_title::title}; ?>
                                        </td>
                                        <td class="hidden-xs">
                                            <a href="https://www.blogger.com/blogger.g?blogID=<?php echo $value->{Tbl_title::object_id}; ?>" target="_blank"><?php echo $value->{Tbl_title::object_id}; ?></a>
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
                                        <td style="width: 80px;">
                                            <div class="btn-group">
                                                <button class="btn btn-sm dropdown-toggle" data-toggle="dropdown">
                                                    <i class="icol-cog"></i>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="<?php echo base_url(); ?>post/bloglist/edit/<?php echo $value->{Tbl_title::id}; ?>"><i class="icon-edit"></i> Edit</a>
                                                    </li>
                                                    <li>
                                                        <a data-modal="true" data-text="Do you want to delete this Blog?" data-type="confirm" data-class="error" data-layout="top" data-action="post/delete/deleteblog/<?php echo $value->{Tbl_title::id}; ?>" class="btn-notification"><i class="icon-remove"></i> Remove</a>
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
                                        Showing 1 to <?php echo count($results); ?> of <?php echo $total_rows; ?> entries
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
                                    <button type="submit" id="multidel" value="delele" name="delete" class="btn btn-google-plus pull-right">Delete</button>
                                </div>
                            </div>
                        </div>
                        <!-- end page -->
                    </form>
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
