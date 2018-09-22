

<div class="page-header position-relative">
	<h1>
		Posts
		<small>
			<i class="icon-double-angle-right"></i>
			List Post
		</small>
	</h1>
</div>
<!--/page-header-->
<div class="row-fluid">
	<h3 class="header smaller lighter blue">
		jQuery dataTables
	</h3>

	<table id="table_report" class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th class="center">
					<label>
						<input type="checkbox" />
						<span class="lbl">
						</span>
					</label>
				</th>
				<th>
					Title
				</th>
				<th>
					Author
				</th>
				<th class="hidden-480">
					Tags
				</th>
				<th class="hidden-phone">
					<i class="icon-time hidden-phone">
					</i>
					Categories
				</th>
				<th class="hidden-480">
					Status
				</th>
				<th>
				</th>
			</tr>
		</thead>
		<tbody>
        <?php 
          if(count($data)>0){
            foreach($data as $rows){

        ?>
			<tr>
				<td class='center'>
					<label>
						<input type='checkbox' name="checkBoxPost[]" id="checkBoxPost" value="<?php echo $rows->ID; ?>" />
						<span class="lbl">
						</span>
					</label>
				</td>
				<td>
					<a href='<?php echo base_url(); ?>post/listpost/<?php  echo $rows->ID; ?>'><?php echo $rows->slug; ?> </a>
				</td>
				<td>
					socheat
				</td>
				<td class='hidden-480'>
					khmer1
				</td>
				<td class='hidden-phone'>
					in khmer
				</td>
				<td class='hidden-480'>
					<span class='label label-warning'>
						Expiring
					</span>
				</td>
				<td style="width: 110px;">
					<div class='hidden-phone visible-desktop btn-group'>
						
                          <a class='btn btn-mini btn-info' href="<?php echo base_url(); ?>post/edit/<?php  echo $rows->ID; ?>"><i class='icon-edit'>
							</i></a>
                            
                             <a class='btn btn-mini btn-danger' onclick="return confirm('Are you sure want to delete?');"  href="<?php echo base_url(); ?>post/delete/delete/<?php  echo $rows->ID; ?>"><i class='icon-trash'>
							</i></a>
						 <a class='btn btn-mini btn-warning' href="<?php echo base_url(); ?>post/listpost/<?php  echo $rows->ID; ?>"><i class='icon-flag'>
							</i></a>
						
						
					</div>
					<div class='hidden-desktop visible-phone'>
						<div class="inline position-relative">
							<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown">
								<i class="icon-caret-down icon-only">
								</i>
							</button>
							<ul class="dropdown-menu dropdown-icon-only dropdown-yellow pull-right dropdown-caret dropdown-close">
								<li>
									<a href="<?php echo base_url(); ?>post/listpost/<?php  echo $rows->ID; ?>" class="tooltip-success" data-rel="tooltip" title="Edit" data-placement="left"><span class="green"><i class="icon-edit"></i></span></a>
								</li>
								<li>
									<a href="<?php echo base_url(); ?>post/listpost/<?php  echo $rows->ID; ?>" class="tooltip-warning" data-rel="tooltip" title="Flag" data-placement="left"><span class="blue"><i class="icon-flag"></i></span>  </a>
								</li>
								<li>
									<a href="<?php echo base_url(); ?>post/listpost/<?php  echo $rows->ID; ?>" class="tooltip-error" data-rel="tooltip" title="Delete" data-placement="left"><span class="red"><i class="icon-trash"></i></span>  </a>
								</li>
							</ul>
						</div>
					</div>
				</td>
			</tr>
           <?php 
              }
            }
           ?>
		</tbody>
	</table>
</div>

		<!--page specific plugin scripts-->
		<script src="<?php echo base_url();?>themes/layout/bluebride/assets/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url();?>themes/layout/bluebride/assets/js/jquery.dataTables.bootstrap.js"></script>

<!--inline scripts related to this page-->

		<script type="text/javascript">
			$(function() {
				var oTable1 = $('#table_report').dataTable( {
				"aoColumns": [
			      { "bSortable": false },
			      null, null,null, null, null,
				  { "bSortable": false }
				] } );
				
				
				$('table th input:checkbox').on('click' , function(){
					var that = this;
					$(this).closest('table').find('tr > td:first-child input:checkbox')
					.each(function(){
						this.checked = that.checked;
						$(this).closest('tr').toggleClass('selected');
					});
						
				});
			
				$('[data-rel=tooltip]').tooltip();
			})
		</script>