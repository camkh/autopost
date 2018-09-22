    <div class="page-header">
        <div class="page-title">
            <h3>
                <?php
                if (!empty($title)): echo $title;
                endif;
                ?>
            </h3>
        </div>
        <ul class="page-stats">
            <li>

            </li>        
        </ul>
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
                    <form method="post">
                        <table class="table table-striped table-bordered table-hover table-checkable datatable">
                            <thead>
                                <tr>
                                    <th style="width: 20px;">
                                        <input type="checkbox" class="uniform" name="allbox" id="checkAll" />
                                    </th>
                                    <th style="width: 250px;">
                                        Singer Name
                                    </th>
                                    <th class="hidden-xs">
                                        Image
                                    </th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                                <?php if (empty($edititem)): ?>
                                    <tr>
                                        <th>

                                        </th>
                                        <th>
                                            <input type="text" name="singername" value="" class="form-control"/>
                                        </th>
                                        <th class="hidden-xs">
                                            <input type="text" name="singerimage" value="" class="form-control"/>
                                        </th>
                                        <th>
                                            <input type="submit" name="submit" value="Add" class="btn btn-info"/>
                                        </th>
                                    </tr>
                                <?php endif; ?>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($singerlist as $value):
                                    if (!empty($edititem) && $edititem == $value->{Tbl_singer::id}):
                                        $edit = 1;
                                    else:
                                        $edit = 0;
                                    endif;
                                    ?>
                                    <tr>
                                        <td class="checkbox-column">
                                            <input type="checkbox" id="itemid" name="itemid[]" class="uniform" value="<?php echo $value->{Tbl_singer::id}; ?>" />
                                        </td>
                                        <td>                                        
                                            <?php
                                            if ($edit == 1):
                                                echo '<input type="text" name="singername" value="' . $value->{Tbl_singer::name} . '" class="form-control"/>';
                                                echo '<input type="hidden" name="singerid" value="' . $value->{Tbl_singer::id} . '" class="form-control"/>';
                                            else:
                                                echo $value->{Tbl_singer::name};
                                            endif;
                                            ?>
                                        </td>
                                        <td class="hidden-xs">                                            
                                            <?php
                                            if ($edit == 1):
                                                echo '<input type="text" name="singerimage" value="' . $value->{Tbl_singer::image} . '" class="form-control"/>';
                                            else:
                                                echo '<img src="'.$value->{Tbl_singer::image}.'" style="width:100px;float:left;margin-right: 5px;"/>';
                                                //echo $value->{Tbl_singer::image};                                                
                                            endif;
                                            ?>
                                        </td>
                                        <td style="width: 80px;">
                                            <?php
                                            if ($edit == 1):
                                                echo '<input type="submit" name="submit" value="Update" class="btn btn-warning"/>';
                                            else:
                                                ?>                                            
                                                <div class="btn-group">
                                                    <button class="btn btn-sm dropdown-toggle" data-toggle="dropdown">
                                                        <i class="icol-cog"></i>
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a href="<?php echo base_url(); ?>post/singerlist/edit/<?php echo $value->{Tbl_singer::id}; ?>" target="_blank"><i class="icon-edit"></i> Edit</a>
                                                        </li>
                                                        <li>
                                                            <a data-modal="true" data-text="Do you want to delete this Blog?" data-type="confirm" data-class="error" data-layout="top" data-action="post/delete/delsinger/<?php echo $value->{Tbl_singer::id}; ?>" class="btn-notification"><i class="icon-remove"></i> Remove</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php
                                endforeach;
                                ?>
                            </tbody>
                        </table>

                        <!-- page -->
                        <div class="row">
                            <div class="dataTables_footer clearfix">
                                <div class="col-md-6">
                                    <div class="dataTables_info" id="DataTables_Table_0_info">
                                        Showing 1 to 5 of <?php if(!empty($total_rows)): echo $total_rows; endif;?> entries
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="dataTables_paginate paging_bootstrap">
                                        <?php
                                        if ($edit != 1):
                                        echo $this->pagination->create_links();
                                    endif;
                                        ?>
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