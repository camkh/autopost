<?php if ($this->session->userdata('user_type') != 4) { ?>
<div class="page-header">
	<div class="page-title">
		<h3>
                <?php if (!empty($title)): echo $title; endif; ?>
            </h3>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<a data-toggle="modal" href="#myModal1"
					class="btn btn-facebook pull-right"><i class="icon-plus"></i></a>
				<h4>
					<i class="icon-reorder"> </i> <a href="#"
						title="<?php if (!empty($title)): echo $title; endif; ?>"><?php if (!empty($title)): echo $title; endif; ?></a>
				</h4>
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
								<th>Name</th>
								<th class="hidden-xs">Url</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
    <?php foreach ($dataList as $value) {?>
                                    <tr>
								<td class="checkbox-column"><input type="checkbox" id="itemid"
									name="itemid[]" class="uniform"
									value="<?php echo $value->{Tbl_networkBlog::id}; ?>" /></td>
								<td><a
									href="<?php echo base_url(); ?>managecampaigns/ntblist/<?php echo $value->{Tbl_networkBlog::id}; ?>">
									<?php echo $value->{Tbl_networkBlog::title}; ?>
									</a></td>
								<td class="hidden-xs"><a
									href="<?php echo base_url(); ?>managecampaigns/ntblist/<?php echo $value->{Tbl_networkBlog::id}; ?>">
        								<?php echo $value->{Tbl_networkBlog::url}; ?>
        							</a></td>
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
												data-action="managecampaigns/delete/networkblogs/<?php echo $value->{Tbl_networkBlog::id}; ?>"
												class="btn-notification"><i class="icon-remove"></i> Remove</a>
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
                                        Showing 1 to <?php echo count($dataList); ?> of <?php echo $this->pagination->total_rows; ?> entries
                                    </div>
							</div>
							<div class="col-md-4">
								<div class="dataTables_paginate paging_bootstrap">
									<ul class="pagination">
                                            <?php echo @$this->pagination->create_links(); ?>
                                        </ul>
								</div>
							</div>
							<div class="col-md-2">
								<button type="submit" id="multidel" name="submit"
									class="btn btn-google-plus pull-right">Delete</button>
							</div>
						</div>
					</div>
				</form>
				<!-- end page -->
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="myModal1" aria-hidden="true"
	style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">&times;</button>
				<h4 class="modal-title">URL Networkblogs</h4>
			</div>
			<div class="modal-body row-border form-horizontal">
				<div class="form-group">
					<label class="col-md-2 control-label">Title:</label>
					<div class="col-md-10">
						<input type="text" id="title" class="form-control">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">URL:</label>
					<div class="col-md-10">
						<input type="text" id="url" class="form-control">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="addUrl">Add</button>
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
