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
    <div class="span4">
        <form method="post" action="<?php echo base_url(); ?>label/label/add">
        <div class="widget-box light-border">
			<div class="widget-header header-color-blue">
				<h5 class="smaller">
					Add New Category
				</h5>
				<div class="widget-toolbar">
					<span class="badge badge-important">
						Alert
					</span>
				</div>
			</div>
			<div class="widget-body">
				<div class="widget-main padding-5">
					<label for="txtLabelName" class="control-label">
						Label Name
					</label>
					<input name="txtLabelName" type="text" placeholder="Label Name" id="txtLabelName" class="span12" />
                    <label for="txtLabelSlug" class="control-label">
						Slug
					</label>
					<input name="txtLabelSlug" type="text" placeholder="url slug" id="txtLabelSlug" class="span12" />
                    <em style="font-size: 11px;">The "slug" is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.</em>
                    <div class="clear"></div>
                    <label for="parent">Parent</label>
                    <select class="postform" id="parent" name="parent">
                    	<option value="0">None</option>
                        <?php 
                          if(count($data)>0){
                            foreach($data as $rows){
                        ?>
                    	<option value="<?php echo $rows->label_id;?>" class="level-0"><?php echo $rows->label_name;?></option>
                        <?php }
                        }?>
                    </select>
                    <div class="clear"></div>
                    <em style="font-size: 11px;">Categories, unlike tags, can have a hierarchy. You might have a Jazz category, and under that have children categories for Bebop and Big Band. Totally optional.</em>
                    <br />
                    <div class="clear"></div>
                    <label for="textAreaContent">Description</label>
					<textarea name="textAreaContent" id="textAreaContent" class="span12" rows="8"></textarea>
                    <input type="submit"  value="Publish" name="btnSubmit" class="btn btn-info btn-small" id="btnSubmit"/>
				</div>
			</div>
		</div>
        </form>
    </div>
    <div class="span8">
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
					Name
				</th>
				<th>
					Descriptioin
				</th>
				<th class="hidden-480">
					Slug
				</th>
				<th class="hidden-phone">
					<i class="icon-time hidden-phone">
					</i>
					Post
				</th>
                <th>
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
						<input type='checkbox' name="checkBoxPost[]" id="checkBoxPost" value="<?php echo $rows->label_id;?>" />
						<span class="lbl">
						</span>
					</label>
				</td>
				<td>
					<a href='#'><?php echo $rows->label_name;?></a>
				</td>
				<td>
					socheat
				</td>
				<td class='hidden-480'>
					<?php echo $rows->label_slug;?>
				</td>
				<td class='hidden-phone'>
					5
				</td>
                <td class='hidden-phone'>
					5
				</td>
				<td style="width: 110px;">
					<div class='hidden-phone visible-desktop btn-group'>
							<a class='btn btn-mini btn-info' href="<?php echo base_url(); ?>label/label/edit/<?php echo $rows->label_id;?>"><i class='icon-edit'></i></a>
						<button class='btn btn-mini btn-danger'>
							<i class='icon-trash'>
							</i>
						</button>
					</div>
					<div class='hidden-desktop visible-phone'>
						<div class="inline position-relative">
							<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown">
								<i class="icon-caret-down icon-only">
								</i>
							</button>
							<ul class="dropdown-menu dropdown-icon-only dropdown-yellow pull-right dropdown-caret dropdown-close">
								<li>
									<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit" data-placement="left"><span class="green"><i class="icon-edit"></i></span></a>
								</li>
								<li>
									<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete" data-placement="left"><span class="red"><i class="icon-trash"></i></span>  </a>
								</li>
							</ul>
						</div>
					</div>
				</td>
			</tr>
          <?php }
          }?> 
		</tbody>
	</table>
    </div>
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