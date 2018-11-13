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
                    <h4>
                        <i class="icon-reorder">
                        </i>
                        Managed Table
                    </h4>
                    <div class="toolbar no-padding">
                    <div class="btn-group">
                        <span class="btn btn-xs widget-collapse"> <i
                            class="icon-angle-down"></i>
                        </span> <span class="btn btn-xs dropdown-toggle"
                            data-toggle="dropdown"> <i class="icon-cog"></i> <i
                            class="icon-angle-down"></i>
                        </span>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="<?php echo base_url() . 'Facebook/addgroup/'; ?>"><i class="fa fa-facebook"></i> Add New Groups</a></li>
                            <li><a href="<?php echo base_url() . 'Facebook/newlist/'; ?>">Create New List</a></li>
                            <li><a href="<?php echo base_url() . 'Facebook/requestgroups/'; ?>"><i class="fa fa-facebook"></i> request Groups by ID</a></li>
                            <li><a href="<?php echo base_url() . 'Facebook/trgroups/'; ?>"><i class="fa fa-facebook"></i> Facebook Group Transfer </a></li>                          
                        </ul>
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
                                    </label>

                                    <label> <select name="Cat" class="select2-offscreen" style="width: 200px"  onChange="top.location.href=this.options[this.selectedIndex].value;">
                                        <option value="<?php echo base_url();?>Facebook/group?cat=none">None</option>
                                        <?php if(!empty($grouplist)):
                                            foreach ($grouplist as $groupList):
                                        ?>
                                            <option <?php echo ($this->session->userdata ( 'cat' ) ==$groupList->l_id) ? 'selected' : '';?> value="<?php echo base_url();?>Facebook/group?cat=<?php echo $groupList->l_id;?>"><?php echo $groupList->lname;?></option>
                                        <?php
                                            endforeach;
                                         endif;?>
                                </select>
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
                                        Type
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
                                            <input type="checkbox" id="itemid" name="itemid[]" class="uniform" value="<?php echo $value->sg_id; ?>" />
                                        </td>
                                        <td>
                                            <a href="https://www.facebook.com/groups/<?php echo $value->sg_page_id; ?>/members/" target="_blank"><?php echo $value->sg_page_id; ?> || <?php echo $value->sg_name; ?></a>
                                        </td> 
                                        <td>
                                            <?php echo $value->sg_type; ?>
                                        </td>
                                        <td>
                                            <?php echo $value->sg_member; ?>
                                        </td>
                                        <td style="width: 80px;">
                                            <div class="btn-group">
                                                <button class="btn btn-sm dropdown-toggle" data-toggle="dropdown">
                                                    <i class="icol-cog"></i>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu" style="right:0;left: inherit;">
                                                    <li>
                                                        <a href="<?php echo base_url(); ?>Facebook/addgroup?id=<?php echo $value->sg_id; ?>"><i class="icon-edit"></i> Addmore</a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo base_url(); ?>Facebook/csv/<?php echo $value->sg_id; ?>"><i class="icon-file-text"></i> Export as CSV</a>
                                                    </li>
                                                    <li>
                                                        <a data-modal="true" data-text="Do you want to delete this Blog?" data-type="confirm" data-class="error" data-layout="top" data-action="Facebook/delete/groupid/<?php echo $value->sg_id; ?><?php echo ($this->session->userdata ( 'cat' )) ? '?cat='.$this->session->userdata ( 'cat' ) : '';?>" class="btn-notification"><i class="icon-remove"></i> Remove</a>
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
                                    <button type="submit" id="multidel" name="delete"
                                    class="btn btn-google-plus pull-right" value="delete">Delete</button>
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