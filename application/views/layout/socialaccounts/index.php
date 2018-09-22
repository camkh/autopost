<?php if ($this->session->userdata('user_type') != 4) { ?>
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
                    <a href="<?php echo base_url(); ?>socialaccounts/logoutall" title="Login to your Facebook account"><img src="<?php echo base_url(); ?>img/social/face.png"/></a>
                    <a href="<?php echo base_url(); ?>socialaccounts/logoutall/Google.html" title="Login to your Google plus account"><img src="<?php echo base_url(); ?>img/social/g+.png"/></a>
                  	<a href="<?php echo base_url(); ?>socialaccounts/logoutall/Twitter.html" title="Login to your Twitter account"><img src="<?php echo base_url(); ?>img/social/twit.png"/></a>
                  	<a href="<?php echo base_url(); ?>socialaccounts/logoutall/LinkedIn.html" title="Login to your LinkedIn account"><img src="<?php echo base_url(); ?>img/social/in.png"/></a>
                    <?php $provider = $this->session->userdata('provider');
                    if(!empty($provider)):?>
                    <h4>
                        <i class="icon-reorder">
                        </i>
                        <a href="<?php echo base_url(); ?>socialaccounts/logoutall/<?php echo $provider;?>.html" title="Login to your Facebook account">Logout</a>
                    </h4>
                    <?php endif;?>
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
                                        Name
                                    </th>
                                    <th class="hidden-xs">
                                        Email
                                    </th>
                                    <th class="hidden-xs">
                                        Type
                                    </th>
                                    <th class="hidden-xs" style="width: 60px;">
                                        Groups
                                    </th>
                                    <th class="hidden-xs">
                                        Status
                                    </th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
    <?php foreach ($socialList as $value) { ?>
                                    <tr>
                                        <td class="checkbox-column">
                                            <input type="checkbox" id="itemid" name="itemid[]" class="uniform" value="<?php echo $value->{Tbl_social::s_id}; ?>" />
                                        </td>
                                        <td>
                                            <a href="<?php echo base_url(); ?>post/movieview/<?php echo $value->{Tbl_social::s_id}; ?>"><?php echo $value->{Tbl_social::s_name}; ?></a>
                                        </td>
                                        
                                        <td class="hidden-xs">
        <?php echo $value->{Tbl_social::s_email}; ?>
                                        </td>
                                        <td class="hidden-xs">
        <?php echo $value->{Tbl_social::s_type}; ?>
                                        </td>
                                        <td>
                                            <?php echo $value->group; ?>
                                        </td>
                                        <td>
        <?php if ($value->{Tbl_social::s_status} == 1) { ?>
                                                <span class="label label-success">
                                                    Active
                                                </span>
        <?php } elseif ($value->{Tbl_social::s_status} == 0) { ?>
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
                                                        <a href="<?php echo base_url(); ?>post/continues/<?php echo $value->{Tbl_social::s_id}; ?>"><i class="icon-edit"></i> Add Group</a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo base_url(); ?>post/getcode/edit/<?php echo $value->{Tbl_social::s_id}; ?>"><i class="icon-edit"></i> Other blogs</a>
                                                    </li>
                                                    <li>
                                                        <a data-modal="true" data-text="Do you want to delete this Blog?" data-type="confirm" data-class="error" data-layout="top" data-action="socialaccounts/delete/deletesocial/<?php echo $value->{Tbl_social::s_id}; ?>" class="btn-notification"><i class="icon-remove"></i> Remove</a>
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
    <?php
} else {
    echo '<div class="alert fade in alert-danger" >
                            <strong>You have no permission on this page!...</strong> .
                        </div>';
}
?>
