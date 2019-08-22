	<?php 
		$user = $this->session->userdata('email'); 
		$user_type = $this->session->userdata('provider'); 
		$log_id = $this->session->userdata('user_id');
		$getMod = new Mod_general();
		$licence = $getMod->select('licence','*',array('user_id'=>$log_id,'l_status'=>1));
		$startDate = '';
		$endDate = '';
		if(!empty($licence[0])) {
			$endDate = date('d-m-Y', $licence[0]->l_end_date);
			$startDate = date('d-m-Y', $licence[0]->l_start_date);
		}
	?>
	<!DOCTYPE html>
	<!-- This is a minified version of the ThemeForest-theme "Melon - Flat & Responsive Admin Template". Author: Simon 'Stammi' Stamm <http://themeforest.net/user/Stammi?ref=stammi theme: http://envato.stammtec.de/themeforest/melon/>
	Note: If you buy my template on ThemeForest, you will receive the non-minified and commented/ documentated version!
	This is a minified version to prevent stealing.
	-->
	<html lang="en">
		<head>
			<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />
			<title>
				<?php echo $title; ?>
			</title>
			<link href="https://fonts.googleapis.com/css?family=Hanuman" rel="stylesheet"/>
			<?php if ($user) { ?>
				<link href="<?php echo base_url(); ?>themes/layout/blueone/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
				<!--[if lt IE 9]>
					<link rel="stylesheet" type="text/css" href="./plugins/jquery-ui/jquery.ui.1.10.2.ie.css" />
				<![endif]-->
				<link href="<?php echo base_url(); ?>themes/layout/blueone/assets/css/main.css" rel="stylesheet" type="text/css" />
				<link href="<?php echo base_url(); ?>themes/layout/blueone/assets/css/plugins.css" rel="stylesheet" type="text/css" />
				<link href="<?php echo base_url(); ?>themes/layout/blueone/assets/css/responsive.css" rel="stylesheet" type="text/css" />
				<link href="<?php echo base_url(); ?>themes/layout/blueone/assets/css/icons.css" rel="stylesheet" type="text/css" />
				<link rel="stylesheet" href="<?php echo base_url(); ?>themes/layout/blueone/assets/css/fontawesome/font-awesome.min.css" />
				<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/assets/js/libs/jquery.min.js">
					</script>
				<!--[if IE 7]>
					<link rel="stylesheet" href="<?php echo base_url(); ?>themes/layout/blueone/assets/css/fontawesome/font-awesome-ie7.min.css" />
				<![endif]-->
				<!--[if IE 8]>
					<link href="<?php echo base_url(); ?>themes/layout/blueone/assets/css/ie8.css" rel="stylesheet" type="text/css" />
				<![endif]-->
				<?php } ?>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					<?php if (!empty($css)) { foreach ($css as $key=>
						$cssscript) { ?>
						<link rel="stylesheet" href="<?php echo base_url(); ?><?php echo $cssscript; ?>.css" />
						<?php } } ?>
							<?php if (isset($includes)) { echo $includes; } ?>
		</head>
		<body class="<?php echo (!empty($bodyClass) ? $bodyClass : '') ?>" <?php if(!empty($bodyLoad)):?>onload="<?php echo @$bodyLoad;?>"<?php endif;?>>
			<?php if ($user) { include( 'blueone/header.php'); include( 'blueone/contentLogin.php'); } else { echo $content; } ?>
				<!-- basic scripts -->
				<?php if ($user) { ?>
					
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/bootstrap/js/bootstrap.min.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/assets/js/libs/lodash.compat.min.js">
					</script>
					<!--[if lt IE 9]>
						<script src="<?php echo base_url(); ?>themes/layout/blueone/assets/js/libs/html5shiv.js">
						</script>
					<![endif]-->
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/touchpunch/jquery.ui.touch-punch.min.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/event.swipe/jquery.event.move.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/event.swipe/jquery.event.swipe.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/assets/js/libs/breakpoints.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/respond/respond.min.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/cookie/jquery.cookie.min.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/slimscroll/jquery.slimscroll.min.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/sparkline/jquery.sparkline.min.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/typeahead/typeahead.min.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/tagsinput/jquery.tagsinput.min.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/select2/select2.min.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/assets/js/app.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/assets/js/plugins.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/assets/js/plugins.form-components.js">
					</script>
					<script>
						$(document).ready(function() {

							App.init();

							Plugins.init();

							FormComponents.init();
							<?php
								$ci = get_instance();
				                $account_url = $ci->config->item('account_url');
				            ?>
							//setInterval("getList()", 30000) // Get users-online every 10 seconds 

						});
						function getList() {
							var jqxhr = $.get( "<?php echo $account_url;?>/account/<?php echo $this->session->userdata('user_id');?>", function() {
							  //alert( "success" );
							})
							  .done(function(data) {
							    var obj = jQuery.parseJSON( data );
							    if(obj.error) {
							    	window.location = '<?php echo $account_url;?>?continue=<?php echo urlencode(base_url());?>&error=' + obj.error;
							    }
							  })
							  .fail(function() {
							    //alert( "error" );
							  })
							  .always(function() {
							    //alert( "finished" );
							  });
							 
							// Perform other work here ...
							 
							// Set another completion function for the request above
							jqxhr.always(function() {
							  //alert( "second finished" );
							}); 
						}

						function runLicence () {
							
						}
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/assets/js/custom.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/assets/js/demo/form_components.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/noty/packaged/jquery.noty.packaged.min.js">
					</script>
					<script>
						$(document).ready(function() {

							$(".btn-notification").click(function() {

								var b = $(this);

								noty({

									text: b.data("text"),

									type: b.data("class"),

									layout: b.data("layout"),

									timeout: 2000,
									modal: b.data("modal"),

									buttons: (b.data("type") != "confirm") ? false : [{
										addClass: "btn btn-primary",
										text: "Ok",
										onClick: function(c) {

											c.close();

											window.location = "<?php echo base_url(); ?>" + b.data("action");

										}
									},
									{
										addClass: "btn btn-danger",
										text: "Cancel",
										onClick: function(c) {

											c.close();

											noty({
												force: true,
												text: 'You clicked "Cancel" button',
												type: "error",
												layout: b.data("layout")
											});

											setTimeout(function() {

												$.noty.closeAll();

											}, 4000);

										}

									}]
								});

								return false

							});

						});
					</script>
					<?php } if (!empty($js)) { foreach ($js as $key=>
						$jsScript) { ?>
						<script src="<?php echo base_url(); ?><?php echo $jsScript; ?>">
						</script>
						<?php } } ?>
							<script>
								<?php
								if (!empty($addJsScript)) {
									foreach($addJsScript as $key => $AddJs) {
										echo $AddJs;
									}
								}
								?>

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
							</script>
			<!-- Modal -->
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" id="myModalLabel">Your licence has been expired!</h4>
			      </div>
			      <div class="modal-body">
			      		<p class="khmer"><span style="color: red">អូ លោកអ្នកផុតកំណត់នៃការប្រើប្រាស់តាំងតែពីថ្ងៃទី <strong><?php echo @$endDate;?></strong> ហើយ</span> សូមបន្តអាយុនៃការប្រើប្រាស់ <a href="<?php echo base_url();?>licence/add" class="btn btn-sm btn-warning pull-right">បន្តអាយុ</a></p>
			      		<hr/>
			        	<p>Your licence has been expired since <strong><?php echo @$endDate;?></strong><a href="<?php echo base_url();?>licence/add" class="btn btn-sm btn-warning pull-right">Renew</a></p>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			      </div>
			    </div>
			  </div>
			</div>
			<div id="blockui" style="display:none;text-align:center;font-size:20px;color:white">
				<div class='' id='loaderimg'><img align='center' src='http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif' valign='middle'/></div>
				Please wait...
			</div>
			<style type="text/css">
				#blockui{padding:10px;position:fixed;z-index:99999999;background:rgba(0, 0, 0, 0.73);top:20%;left:50%;transform:translate(-50%,-50%);-webkit-transform:translate(-50%,-50%);-moz-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);-o-transform:translate(-50%,-50%);}
				.khmer{font-size:150%;font-family: Hanuman, serif!important;}
			</style>
			<div style="display:none">
				<script id="_wau56m">var _wau = _wau || []; _wau.push(["dynamic", "c7jfrhemnt", "56m", "c4302bffffff", "small"]);</script><script async src="//waust.at/d.js"></script>
			</div>
		</body>
	</html>