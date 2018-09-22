<div class="page-header position-relative">
	<h1>
		Form Wizard
		<small>
			<i class="icon-double-angle-right">
			</i>
			and Validation
		</small>
	</h1>
</div>
<!--/page-header-->
<form action="<?php echo base_url(); ?>post/edit/<?php echo $this->uri->segment(3);?>" method="post">
    <?php
    $txtposttitle = '';
    $textAreaContent = '';
    $txtTypePost='';
    $txttAreaValuePost='';
    $selectLabelPost = '';
      if(count($data)>0){
          foreach($data as $rows){
            $txtposttitle = isset($rows->post_title)?$rows->post_title:"";
            $textAreaContent = isset($rows->post_content)?$rows->post_content:"";
            $txtTypePost=isset($rows->par_type)?$rows->par_type:"";
            $txttAreaValuePost=isset($rows->par_video_id)?$rows->par_video_id:"";
            $selectLabelPost = isset($rows->label_id)?$rows->label_id:""; 
          }
          
      }
     ?>
	<div class="row-fluid">
		<div class="span8">
			<div class="row-fluid">
				<div class="widget-box light-border">
					<div class="widget-header header-color-blue">
						<h5 class="smaller">
							Add New Post
						</h5>
						<div class="widget-toolbar">
							<span class="badge badge-important">
								Alert
							</span>
						</div>
					</div>
					<div class="widget-body">
						<div class="widget-main padding-5">
							<label for="PostTitle" class="control-label">
								Title
							</label>
							<input value="<?php echo $txtposttitle;?>" name="txtposttitle" type="text" placeholder="PostTitle" id="txtposttitle" class="span12" />
							<textarea name="textAreaContent" id="textAreaContent" class="span12" rows="8"><?php echo $textAreaContent; ?></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="widget-box light-border">
						<div class="widget-header header-color-dark">
							<h5 class="smaller">
								Custom Fields
							</h5>
						</div>
						<div class="widget-body">
							<div class="widget-main padding-5">
                                <div class="row-fluid">
                                    <div class="span5">
                                        	<label for="PostTitle" class="control-label">
            									Name
            								</label>
                                        <input  type="text" value="<?php echo $txtTypePost; ?>" name="txtTypePost" placeholder="MetaKey" id="txtTypePost" class="span12" />
                                    </div>
                                    <div class="span7">
                                        <label for="PostTitle" class="control-label">
        									Value
        								</label>
                                        <textarea name="txttAreaValuePost" class="span12" placeholder="Put the value here" id="txttAreaValuePost" style="height: 30px;"><?php echo $txttAreaValuePost; ?></textarea>
                                    </div>
                                    <div class="clear"></div>
                                </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="span4">
			<div class="row-fluid">
				<div class="widget-box">
					<div class="widget-header">
						<h4>
							Inline Forms
						</h4>
					</div>
					<div class="widget-body">
						<div class="widget-main">
							<input type="submit"  value="Update" name="btnSubmitNewPost" class="btn btn-info btn-small" id="btnSubmitNewPost"/>
                            <div id="flash" align="left"  ></div>
								
						</div>
					</div>
				</div>
			</div>
			<div class="space-6">
			</div>
			<div class="row-fluid">
				<div class="widget-box">
					<div class="widget-header widget-header-small">
						<h5 class="lighter">
							Search Form
						</h5>
					</div>
					<div class="widget-body">
						<div class="widget-main">
							<label for="form-field-select-1">
								Categories
							</label>
							<select  name="selectLabelPost" id="selectLabelPost">
								<option <?php echo ($selectLabelPost=='1')?"selected='selected'":""; ?> value='1'>
							  Label1
                                </option>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>    