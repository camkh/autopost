<?php if ($this->session->userdata('user_type') == 1) { ?>
    <div class="page-header">
        <div class="page-title">
            <h3>
                Dynamic Tables (DataTables)
            </h3>
        </div>
        <ul class="page-stats">
            <li>
                <a class="btn btn-lg btn-primary" href="<?php echo base_url(); ?>user/action">Add user</a>
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
                    <div class="row">
                        <form method="post">
                            <div class="dataTables_header clearfix">
                                <div class="col-md-12">
                                    <select name="userid">
                                        <option></option>
                                        <?php foreach ($user_list as $value) { ?>
                                            <option value="<?php echo $value->log_id; ?>"><?php echo $value->name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <?php
                                    if (!empty($userpage)):
                                        foreach ($userpage as $value):
                                            echo "<li class='listpage' data-id='" . $value->{Tbl_title::id} . "'><input name='pageid[]' class='checkforuser' type='checkbox' value='" . $value->{Tbl_title::id} . "'/> " . $value->{Tbl_title::title} . "</li>";
                                        endforeach;
                                    endif;
                                    ?>
                                </div>
                            </div>
                            <input type="submit" name="Submit" class="btn btn-lg btn-primary"/>
                        </form>
                    </div>                                

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
    <style>
        .checkforuser{float:left;margin-right: 3px!important}
        ul,.listpage {list-style: none;padding: 0;margin: 0}
        li.listpage {margin: 5px 0}
    </style>
    <?php
} else {
    echo '<div class="alert fade in alert-danger" >
                            <strong>You have no permission on this page!...</strong> .
                        </div>';
}
?>
