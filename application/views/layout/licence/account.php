<?php
if ($this->session->userdata('user_type') != 4) { ?>
<div class="page-header">
	<div class="page-title">
		<h3>
                <?php
    if (!empty($title)):
        echo $title;
    endif; ?>
            </h3>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4>
					<i class="icon-reorder"> </i> <a href="#"
						title="<?php
    if (!empty($title)):
        echo $title;
    endif; ?>"><?php
    if (!empty($title)):
        echo $title;
    endif; ?></a>
				</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"> <i
							class="icon-angle-down"></i>
						</span>
						<a href="<?php echo base_url();?>licence/add"><span class="btn btn-xs btn-warning"> <i class="icon-plus"></i> Add
						</span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<div class="row">
					<div class="dataTables_header clearfix">
						<div class="col-md-6">
							<div id="DataTables_Table_0_length" class="dataTables_length">
								<label> <select name="DataTables_Table_0_length" size="1"
									aria-controls="DataTables_Table_0" class="select2-offscreen"
									tabindex="-1">
										<option value="5" selected="selected">5</option>
										<option value="10">10</option>
										<option value="25">25</option>
										<option value="50">50</option>
										<option value="-1">All</option>
								</select> records per page
								</label>
							</div>
						</div>
						<div class="col-md-6">
							<div class="dataTables_filter" id="DataTables_Table_0_filter">
								<form method="post">
									<label>
										<div class="input-group">
											<span class="input-group-addon"> <i class="icon-search"> </i>
											</span> <input type="text" aria-controls="DataTables_Table_0"
												class="form-control" name="filtername" />
										</div>
									</label>
								</form>
							</div>
						</div>
					</div>
				</div>
				<form method="post">
					<table
						class="table table-striped table-bordered table-hover table-checkable datatable">
						<thead>
							<tr>
								<th><input type="checkbox" class="uniform" name="allbox"
									id="checkAll" /></th>
								<th>ID</th>
								<th>Name</th>
								<th class="hidden-xs">Start date</th>
								<th class="hidden-xs">End date</th>
								<th class="hidden-xs">Price</th>
								<th class="hidden-xs">Type</th>
								<th class="hidden-xs">Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
					    <?php
    foreach ($results as $value):
?>
                                    <tr>
								<td class="checkbox-column"><input type="checkbox" id="itemid"
									name="itemid[]" class="uniform"
									value="<?php
        echo $value->l_id; ?>" /></td>
        						<td><?php echo $value->l_id; ?></td>
								<td><?php
        echo $value->l_name; ?>
								</td>
								<td><?php
        echo date('d-m-Y',$value->l_start_date); ?></td>
								<td class="hidden-xs">
        <?php
        echo date('d-m-Y',$value->l_end_date); ?>
                                        </td>
                                <td class="hidden-xs">$<?php echo $value->l_price;?></td>
								<td class="hidden-xs">
        <?php
        echo $value->l_type; ?>
                                        </td>
								<td>
        <?php if(time()>=$value->l_end_date):?>
        	<span class="label label-danger"> Expired </span>
        <?php else:?>
        <?php if ($value->l_status == 1) { ?>
                                                <span
									class="label label-success"> Active </span>
        <?php
        } else if ($value->l_status == 2) { ?>
                                                <span
									class="label label-warning"> Pending </span>
        <?php
        } 
        endif;
        ?>
                                        </td>
								<td style="width: 80px;">
									<div class="btn-group">
										<button class="btn btn-sm dropdown-toggle"
											data-toggle="dropdown">
											<i class="icol-cog"></i> <span class="caret"></span>
										</button>
										<ul class="dropdown-menu">
											<li><a data-modal="true"
												data-text="Do you want to delete this Blog?"
												data-type="confirm" data-class="error" data-layout="top"
												data-action="licence/delete?id=<?php
        echo $value->l_id; ?>"
												class="btn-notification"><i class="icon-remove"></i> Remove</a>
											</li>
										</ul>
									</div>
								</td>
							</tr>
    <?php
    endforeach;
?>
                            </tbody>
					</table>					
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
                            window.location = "<?php
    echo base_url(); ?>user/delete/" + id;
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
} 
else {
    echo '<div class="alert fade in alert-danger" >
                            <strong>You have no permission on this page!...</strong> .
                        </div>';
}
?>
