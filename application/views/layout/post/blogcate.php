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
        <form method="post">
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-header">
                        <h4>
                            <i class="icon-reorder">
                            </i>
                            Add New Post
                        </h4>                     
                        <div class="toolbar no-padding">
                        </div>
                    </div>
                    <div class="widget-content">
                        <div class="row" style="margin-bottom:10px;">
                            <div class="form-group">                            
                                <div class="col-md-12">
                                    <?php
                                    if (!empty($blogcatEdit)) {
                                        foreach ($blogcatEdit as $value):
                                            $title = $value->{Tbl_title::title};
                                            $id = $value->{Tbl_title::id};
                                        endforeach;
                                    }
                                    ?>
                                    <div class="row">
                                        <div class="col-md-10">
                                            <input name="name" class="form-control" value="<?php if (!empty($blogcatEdit)) {echo $title;}?>"/>
                                            <?php if (!empty($blogcatEdit)) { ?>
                                            <input type="hidden" name="id" class="form-control" value="<?php echo $id;?>"/>
                                            <?php } ?>
                                        </div>                                        
                                        <div class="col-md-2">
                                            <input type="submit" name="<?php if (!empty($blogcatEdit)) {echo 'update';} else { echo 'submit';}?>" class="btn btn-primary pull-right" value="<?php if (!empty($blogcatEdit)) {echo 'update';} else { echo 'Submit';}?>"/>
                                        </div>
                                    </div>

                                </div>
                            </div> 
                        </div>

                    </div>
                </div>
            </div>
        </form>
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
                                <th class="checkbox-column">
                                    <input type="checkbox" class="uniform" />
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
                            <?php foreach ($blogcatlist as $value) { ?>
                                <tr>
                                    <td class="checkbox-column">
                                        <input type="checkbox" class="uniform" value="<?php echo $value->{Tbl_title::id}; ?>" />
                                    </td>
                                    <td>
                                        <?php echo $value->{Tbl_title::title}; ?>
                                    </td>
                                    <td class="hidden-xs">
                                        <?php echo $value->{Tbl_title::object_id}; ?>
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
                                                    <a href="<?php echo base_url(); ?>post/blogcate/edit/<?php echo $value->{Tbl_title::id}; ?>"><i class="icon-edit"></i> Edit</a>
                                                </li>
                                                <li>
                                                    <a data-modal="true" data-text="Do you want to delete this Blog?" data-type="confirm" data-class="error" data-layout="top" data-action="post/delete/delblogcat/<?php echo $value->{Tbl_title::id}; ?>" class="btn-notification"><i class="icon-remove"></i> Remove</a>
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
                                    Showing 1 to 5 of 8 entries
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="dataTables_paginate paging_bootstrap">
                                    <ul class="pagination">
                                        <li class="prev disabled">
                                            <a href="#">? Previous</a>
                                        </li>
                                        <li class="active">
                                            <a href="#">1</a>
                                        </li>
                                        <li>
                                            <a href="#">2</a>
                                        </li>
                                        <li class="next">
                                            <a href="#">Next ? </a>
                                        </li>
                                    </ul>
                                </div>
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
