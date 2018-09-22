
<script>
    $(function(){
        $('#btnSubmitNewPost').click(function(){  
        var txtposttitle         = $('#txtposttitle').val();
        var textAreaContent      = $('#textAreaContent').val();
        var txtTypePost          = $('#txtTypePost').val();
        var txttAreaValuePost    = $('#txttAreaValuePost').val();
        var selectLabelPost      = $('#selectLabelPost').val();
        if(txtposttitle==''){
            alert("Please input title of post");
            return false;
        }else if (txtTypePost==''){
            alert("Please input type of post");
            return false;
        }else if(txttAreaValuePost==''){
            alert("Please input value of post");
            return false;
        }else if(selectLabelPost==''){
            alert("Please input label of post");
            return false;
        }
      });  
    });
    
</script>
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
<form action="<?php echo base_url(); ?>post/newpost" method="post">
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
							<input name="txtposttitle" type="text" placeholder="PostTitle" id="txtposttitle" class="span12" />
							<textarea name="textAreaContent" id="textAreaContent" class="span12" rows="8"></textarea>
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
                                        <input type="text" name="txtTypePost" placeholder="MetaKey" id="txtTypePost" class="span12" />
                                    </div>
                                    <div class="span7">
                                        <label for="PostTitle" class="control-label">
        									Value
        								</label>
                                        <textarea name="txttAreaValuePost" class="span12" placeholder="Put the value here" id="txttAreaValuePost" style="height: 30px;"></textarea>
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
							<input type="submit"  value="Publish" name="btnSubmitNewPost" class="btn btn-info btn-small" id="btnSubmitNewPost"/>
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
								<option value="" />
								<option value='1' />
								Nevada
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>    