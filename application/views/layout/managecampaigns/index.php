<?php if ($this->session->userdata('user_type') != 4) {
/* returns the shortened url */
    function get_bitly_short_url($url, $login, $appkey, $format = 'txt') {
        $connectURL = 'http://api.bit.ly/v3/shorten?login=' . $login . '&apiKey=' . $appkey . '&uri=' . urlencode ( $url ) . '&format=' . $format;
        return curl_get_result ( $connectURL );
    }
    /* returns a result form url */
    function curl_get_result($url) {
        $ch = curl_init ();
        $timeout = 5;
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
        $data = curl_exec ( $ch );
        curl_close ( $ch );
        return $data;
    }
	?>
<style>
	.butt,.butt:hover {color: #fff}
    .radio-inline{}
    .error {color: red}
    #blockuis{padding:10px;position:fixed;z-index:99999999;background:rgba(0, 0, 0, 0.73);top:20%;left:50%;transform:translate(-50%,-50%);-webkit-transform:translate(-50%,-50%);-moz-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);-o-transform:translate(-50%,-50%);}
    .khmer {font-family: 'Hanuman', serif;font-size: 30px}
</style>
<link href="https://fonts.googleapis.com/css?family=Koulen" rel="stylesheet"> 
<div style="display:none;text-align:center;font-size:20px;color:white" id="blockuis">
    <div id="loaderimg" class=""><img align="middle" valign="middle" src="http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif"/>
    </div>
    Please wait...
</div>
<code id="codeB" style="width:300px;overflow:hidden;display:none"></code>
<code id="examplecode5" style="width:300px;overflow:hidden;display:none">var codedefault2=&quot;SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 3600\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var wm=Components.classes[&quot;@mozilla.org/appshell/window-mediator;1&quot;].getService(Components.interfaces.nsIWindowMediator);var window=wm.getMostRecentWindow(&quot;navigator:browser&quot;),urlHome=&quot;<?php echo base_url();?>&quot;;</code>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
    <script type="text/javascript">
        function runcode(codes) {
            var str = $("#examplecode5").text();
            var code = str + codes;
            if (/iimPlay/.test(code)) {
                code = "imacros://run/?code=" + btoa(code);
                location.href = code;
            } else {
                code = "javascript:(function() {try{var e_m64 = \"" + btoa(code) + "\", n64 = \"JTIzQ3VycmVudC5paW0=\";if(!/^(?:chrome|https?|file)/.test(location)){alert(\"iMacros: Open webpage to run a macro.\");return;}var macro = {};macro.source = atob(e_m64);macro.name = decodeURIComponent(atob(n64));var evt = document.createEvent(\"CustomEvent\");evt.initCustomEvent(\"iMacrosRunMacro\", true, true, macro);window.dispatchEvent(evt);}catch(e){alert(\"iMacros Bookmarklet error: \"+e.toString());}}) ();";
                location.href = code;
            }
        }
        function load_contents(url){
            var loading = false; 
            if(loading == false){
                loading = true;  //set loading flag on
                $.ajax({        
                    url : url + '?max-results=1&alt=json-in-script',
                    type : 'get',
                    dataType : "jsonp",
                    success : function (data) {
                        loading = false; //set loading flag off once the content is loaded
                        if(data.feed.openSearch$totalResults.$t == 0){
                            var message = "No more records!";
                            return message;
                        }
                        for (var i = 0; i < data.feed.entry.length; i++) {
                            var content = data.feed.entry[i].content.$t;
                            $("#codeB").html(content);
                            var str = $("#codeB").text();
                            runcode(str);
                        }
                    }
                })
            }
        }
    </script> 	
<?php if(empty($this->session->userdata ( 'fb_user_id' ))):?>  
	<script type="text/javascript">
		$( document ).ready(function() {
			load_contents("http://postautofb.blogspot.com/feeds/posts/default/-/autoGetFbUserId");
		});		
	</script>
<?php endif;?>
<div class="page-header">
	<div class="page-title">
		<h3>
                <?php if (!empty($title)): echo $title; endif; ?>
            </h3>
	</div>
	<div class="page-stats">
		<div class="statbox">
		<?php if(!empty($this->session->userdata ('fb_user_id'))):?>
		<div class="visual blue" style="float: left; margin-right: 20px">
			<img src="https://graph.facebook.com/<?php echo $this->session->userdata ( 'fb_user_id' );?>/picture" style="width: 60px" />
			<?php if(empty($this->session->userdata ( 'fb_user_name' ))):?>
				<form method="post" class="form-horizontal row-border">
					<input type="text" name="fb_user_name" class="form-control" placeholder="ឈ្មោះ / Name">
				</form>
			<?php endif;?>
			<br/><div style="width: 60px;overflow: hidden;height: 15px"><?php echo !empty($this->session->userdata ( 'fb_user_name' )) ? $this->session->userdata ( 'fb_user_name' ) : ''; ?></div>
		</div>
		<?php endif;?>
		<?php
		 if(!empty($this->session->userdata ( 'gimage' ))):?>
			<div class="visual red" style="float: left;">
			<img src="<?php echo $this->session->userdata ( 'gimage' );?>" style="width: 60px" />
			<br/><div style="width: 60px;overflow: hidden;height: 15px"><?php echo !empty($this->session->userdata ( 'gname' )) ? $this->session->userdata ( 'gname' ) : ''; ?></div>
		</div>
		<?php endif;?>
	</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?php if(!empty($this->input->get('m'))):
		if($this->input->get('m') == 'runout_post'):?>
			<div class="alert alert-danger fade in khmer"> 
				 <strong>អស់ហើយ!</strong> អស់ប៉ុស្តិ៍ហើយ សូមដាក់បន្ថែមថ្មីទៀត . </div>
		<?php endif;
	endif;?>
	</div>
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4>
					<i class="icon-reorder"> </i> <a href="#"
						title="<?php if (!empty($title)): echo $title; endif; ?>"><?php if (!empty($title)): echo $title; endif; ?></a>
				</h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs btn-danger">
							<a class="butt" href="<?php echo base_url() . 'managecampaigns/yturl/'; ?>"> <i class="icon-youtube"></i> <span class="hidden-xs">Youtube</span>
							</a>
						</span>
						<span class="btn btn-xs btn-inverse">
							<a class="butt" href="<?php echo base_url() . 'managecampaigns/add/'; ?>"> <i class="icon-unlink"></i> <span class="hidden-xs">URL</span>
							</a>
						</span>
						<span class="btn btn-xs btn-success">
							<a class="butt" href="<?php echo base_url() . 'facebook/shareation?post=getpost'; ?>"> <i class="icon-share"></i> <span class="hidden-xs">Share</span>
							</a>
						</span>
						<span class="btn btn-xs widget-collapse"> <i
							class="icon-angle-down"></i>
						</span>
						<span class="btn btn-xs dropdown-toggle"
                            data-toggle="dropdown"> <i class="icon-cog"></i> <i
                            class="icon-angle-down"></i>
                        </span>
                        <ul class="dropdown-menu pull-right" style="text-align: left;">
                            <li><button style="width: 100%" type="submit" id="multiecopy" name="copyto" class="btn btn-inverse pull-right" value="copyto" style="margin-right: 3px"><i class="icon-copy"></i> Copy to</button></li>
                            <li><button style="width: 100%" type="submit" id="multiedit" name="edit"
									class="btn btn-primary pull-right" value="edit"><i class="icon-edit"></i> Edit</button></li>                         
                        </ul> 
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
									tabindex="-1" onchange="getComboA(this)">
										<option value="5">5</option>
										<option value="10" selected="selected">10</option>
										<option value="25">25</option>
										<option value="50">50</option>
										<option value="-1">All</option>
								</select>
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
								<th class="hidden-xs">Email</th>
								<th class="hidden-xs" style="overflow: hidden;">Type</th>
								<th class="hidden-xs" style="width:200px;overflow: hidden;">Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
    <?php
    if(!empty($socialList)):
     foreach ($socialList as $value):
    	$content = json_decode($value->p_conent);
     ?>
                                    <tr>
								<td class="checkbox-column"><input type="checkbox" id="itemid"
									name="itemid[]" class="uniform"
									value="<?php echo $value->{Tbl_posts::id}; ?>" /></td>
								<td><a
									href="<?php echo base_url(); ?>managecampaigns/add?id=<?php echo $value->{Tbl_posts::id}; ?>"><img src="<?php echo $content->picture; ?>" style="width: 80px;float: left;margin-right: 5px"> <?php echo $value->{Tbl_posts::name}; ?></a>
								</td>
								<td class="hidden-xs">
        <?php echo $value->{Tbl_posts::p_date}; ?>
                                        </td>
								<td class="hidden-xs" style="width:300px;overflow: hidden;">
   
                                        </td>
								<td>
									<?php $glink = $content->link;
        									$str = time();
						                    $str = md5($str);
						                    $uniq_id = substr($str, 0, 9);
						                    $link = $glink . '?s=' . $uniq_id;
						                    //$link = get_bitly_short_url( $link, BITLY_USERNAME, BITLY_API_KEY );
        									?>
        									<input id="copy-text" type="text" name="glink" value="<?php echo $value->{Tbl_posts::name}.' 👉เม้นคำว่า ขอบคุณ แล้วกดเข้าไปดูเลขได้เลย #กดแชร์ 👉 ';echo $link;?>" class="form-control" onClick="copyText(this);" readonly/>
        <?php if ($value->{Tbl_posts::status} == 1) { ?>
                                                <span
									class="label label-success"> Active </span>
        <?php } elseif ($value->{Tbl_posts::status} == 0) { ?>
                                                <span
									class="label label-danger"> Inactive </span>
        <?php  } elseif ($value->{Tbl_posts::status} == 2) { ?>
                                                <span
									class="label label-warning"> Draff </span>
        <?php } ?>
        									
                                        </td>
								<td style="width: 80px;">
									<div class="btn-group">
										<button class="btn btn-sm dropdown-toggle"
											data-toggle="dropdown">
											<i class="icol-cog"></i> <span class="caret"></span>
										</button>
										<ul class="dropdown-menu">
											<li><a
												href="<?php echo base_url(); ?>managecampaigns/add?id=<?php echo $value->{Tbl_posts::id}; ?>"><i class="icon-pencil"></i> Edit</a></li>
											<li><a data-modal="true"
												data-text="Do you want to delete this Blog?"
												data-type="confirm" data-class="error" data-layout="top"
												data-action="managecampaigns/delete/deletecampaigns/<?php echo $value->{Tbl_posts::id}; ?>"
												class="btn-notification"><i class="icon-remove"></i> Remove</a>
											</li>
										</ul>
									</div>
								</td>
							</tr>
    <?php endforeach;endif; ?>
                            </tbody>
					</table>

					<!-- page -->
					<div class="row">
						<div class="dataTables_footer clearfix">
							<div class="col-md-4">
								<div class="dataTables_info" id="DataTables_Table_0_info">
                                        Showing 1 to <?php echo count($results); ?> of <?php echo $total_rows; ?> entries
                                    </div>
							</div>
							<div class="col-md-4">
								<div class="dataTables_paginate paging_bootstrap">
									<ul class="pagination">
                                        <?php echo $links; ?>
                                    </ul>
								</div>
							</div>
							<div class="col-md-4">								
								<button type="submit" id="multidel" name="delete"
									class="btn btn-google-plus pull-right" value="delete"><i class="icon-trash"></i> Delete</button>
								<button type="submit" id="multiedit" name="edit"
									class="btn btn-primary pull-right" value="edit" style="margin-right: 3px"><i class="icon-edit"></i> Edit</button>
								<button type="submit" id="multiecopy" name="copyto" class="btn btn-inverse pull-right" value="copyto" style="margin-right: 3px"><i class="icon-copy"></i> Copy to</button>
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
	$( document ).ready(function(){
		$('#multiecopy').click(function () {
		     if (!$('#itemid:checked').val()) {
		            alert('please select one');
		            return false;
		    } else {
		            return confirm('Do you want to Copy These posts?');
		    }
		 });
	});
		function getComboA(selectObject) {
		    var value = selectObject.value;
		    if(value) {
		    	window.location = "<?php echo base_url();?>managecampaigns/index?result=" + value;
		    }
		}
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
                            //window.location = "<?php echo base_url(); ?>user/delete/" + id;
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
function copyText(e) {
  e.select();
  document.execCommand('copy');
  	var n = noty({
	    text: 'copyed',
	    type: 'success',
	    dismissQueue: false,
	    layout: 'top',
	    theme: 'defaultTheme'
	});

    setTimeout(function () {
        $.noty.closeAll();
    }, 1000);
}
    </script>   
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">New message</h4>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Recipient:</label>
            <input type="text" class="form-control" id="recipient-name">
          </div>
          <div class="form-group">
            <label for="message-text" class="control-label">Message:</label>
            <textarea class="form-control" id="message-text"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Send message</button>
      </div>
    </div>
  </div>
</div>
<?php
} else {
	echo '<div class="alert fade in alert-danger" >
                            <strong>You have no permission on this page!...</strong> .
                        </div>';
}
?>
