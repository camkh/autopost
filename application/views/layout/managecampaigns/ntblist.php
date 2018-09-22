<?php
if ($this->session->userdata ( 'user_type' ) != 4) {
	$next = ($this->uri->segment ( 4 )) ? $this->uri->segment ( 4 ) : 0;
	$ntbId = ($this->uri->segment ( 3 )) ? $this->uri->segment ( 3 ) : 0;
	$getPage = ($this->uri->segment ( 5 )) ? $this->uri->segment ( 5 ) : 1;
	?>
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
    <?php
	
	foreach ( $dataList as $value ) {
		?>
                                    <tr>
								<td class="checkbox-column"><input type="checkbox" id="itemid"
									name="itemid[]" class="uniform"
									value="<?php //echo $value->{Tbl_networkBlog::id}; ?>" /></td>
								<td>
									<div class="media">
										<div class="media-left pull-left">
												<img class="media-object"
												src="<?php echo resize_image($value['image'], '120-h100-c'); ?>"
												style="width: 120px; height: 100px" />
										</div>
										<div class="media-body">
											<h4 class="media-heading"><?php echo $value['title']; ?></h4>
											<span class="label label-primary" style="margin-right: 10px">
												<a style="color: #fff"
												href="<?php echo $value['realLink']; ?>" target="_blank">Real
													Link</a>
											</span><span class="label label-warning"> <a
												style="color: #fff"
												href="http://www.networkedblogs.com/p/<?php echo $value['ntbLink']; ?>?ref=source"
												target="_blank">Networkblogs Link</a>
											</span>
										</div>
									</div>
								</td>
								<td class="hidden-xs">
        <?php //echo $value->{Tbl_networkBlog::url}; ?>
                                        </td>
								<td style="width: 80px;">
									<button type="button" class="btn btn-sm btn-primary"
										data-loading-text="Loading..."
										onclick="addTo('http://www.networkedblogs.com/p/<?php echo $value['ntbLink']; ?>?ref=source','<?php echo $value['image'];?>','<?php echo addslashes($value['title']);?>');">
										<i class="icon-plus"></i> Add
									</button>
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
                                        Showing 1 to <?php echo count($dataList); ?> of <?php //echo $this->pagination->total_rows; ?> entries
                                    </div>
							</div>
							<div class="col-md-6">
								<div class="dataTables_paginate paging_bootstrap">
									<ul class="pagination pull-right">
										<?php if(empty($next)):?>
										<li><a href="#" class="disabled">Previous</a></li>
										<?php
		$getPageOn = 2;
		$offset = 10;
	 else :
		if ($next > 20) :
			$offsetPrev = $next - 10;
			$getPagePrev = $getPage - 1;
		 else :
			$offsetPrev = '';
			$getPagePrev = '';
		endif;
		?>
										<li><a
											href="<?php echo base_url(); ?>managecampaigns/ntblist/<?php echo $ntbId;?>/<?php echo $offsetPrev;?>/<?php echo $getPagePrev;?>">Previous</a></li>
										<?php
		$getPageOn = $getPage + 1;
		$offset = $getPageOn * 10;
	endif;
	if (count ( $dataList > 0 )) :
		?>
										<li><a
											href="<?php echo base_url(); ?>managecampaigns/ntblist/<?php echo $ntbId;?>/<?php echo $offset;?>/<?php echo $getPageOn;?>">Next</a></li>
										<?php else:?>
										<li><a href="#">Next</a></li>
										<?php endif;?>
									
									</ul>
								</div>
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
		function addTo(link, image, title) {
			if(link && image && title) {
				$.ajax
				({
					type: "POST",
					url: "<?php echo base_url () . 'managecampaigns/ajax?p=addToPost';?>",
					data: {l: link,t: title,i: image},
					cache: false,
					datatype: 'json',
					success: function(data)
					{
						var json = $.parseJSON(data);
						if(json.result) {
							window.location = "<?php echo base_url () . 'managecampaigns/add?id=';?>" + json.result;
						}
					}
				});
			}
		}
    </script>
<?php
} else {
	echo '<div class="alert fade in alert-danger" >
                            <strong>You have no permission on this page!...</strong> .
                        </div>';
}
function resize_image($url, $imgsize) {
	if (preg_match ( '/blogspot/', $url ) || preg_match ( '/googleusercontent/', $url )) {
		// inital value
		$newsize = "s" . $imgsize;
		$newurl = "";
		// Get Segments
		$path = parse_url ( $url, PHP_URL_PATH );
		$segments = explode ( '/', rtrim ( $path, '/' ) );
		// Get URL Protocol and Domain
		$parsed_url = parse_url ( $url );
		$domain = $parsed_url ['scheme'] . "://" . $parsed_url ['host'];
		
		$newurl_segments = array (
				$domain . "/",
				$segments [1] . "/",
				$segments [2] . "/",
				$segments [3] . "/",
				$segments [4] . "/",
				$newsize . "/", // change this value
				$segments [6] 
		);
		$newurl_segments_count = count ( $newurl_segments );
		for($i = 0; $i < $newurl_segments_count; $i ++) {
			$newurl = $newurl . $newurl_segments [$i];
		}
		return $newurl;
	} else {
		return $url;
	}
}
?>
