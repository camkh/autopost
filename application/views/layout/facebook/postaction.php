<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Actoin - Auto Posts FB (Facebook)</title>
	<style type="text/css">
	body{font-size: 18px; font-family: Khmer OS}
	.content{max-width: 900px;margin: 0 Auto}
	.right-text {color:red;}
	</style>
</head>

<body>
	<div class="content">
		<div style="text-align:center;position: fixed;z-index: 99999999;top:1px;left:0;background:#000;color:#fff000;padding:15px;font-size:18px;width:100%;"><img align="middle" style="width:25px;height:25px;margin: -7px 5px 0px 0px;" valign="middle" src="http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif">  Please wait...</div>
		<div style="margin-top:70px">
			<?php if(!empty($_GET['group'])):?>
				<table width="100%" border="0" cellspacing="5" cellpadding="5" align="center">
				  <tbody>
				    <tr>
				      <td width="40%">សរុប/Total </td>
				      <td>: <span class="right-text"><?php echo $_GET['group'];?></span> ក្រុម/groups</td>
				    </tr>
				    <tr>
				      <td width="50%">ប៉ុស្ដិ៍រួចលើ/Posted on group name </td>
				      <td>: <span class="right-text"><?php echo @$_GET['groupname'];?></span></td>
				    </tr>
				    <tr>
				      <td>ប៉ុស្ដិ៍បាន/Posted </td>
				      <td>: <span class="right-text"><?php echo @$_GET['poston'];?>/<?php echo $_GET['group'];?></span>​ groups</td>
				    </tr>
				    <tr>
				      <td>ប៉ុស្ដិ៍សារឡើងវិញ/Loop</td>
				      <td>: <span class="right-text"><?php 
				      if(@$_GET['loop'] == 1) {
				      		if(@$_GET['maxrepleat'] == 0) {
				      			$maxrepleat = 'Consecutively';
				      		} else {
				      			$maxrepleat = @$_GET['maxrepleat'];
				      		}
				      		echo @$_GET['loop'] + 1 . '/'. @$maxrepleat;
				      } else {
				      		echo 'ម្ដង/Once';
				      }
				      ?></span></td>
				    </tr>
				    <tr>
				      <td>ក្នុង១ក្រុមត្រូវរង់ចាំពី/Waiting time for each goups:</td>
				      <td>: <span class="right-text">15 - <?php echo @$_GET['time1'];?> seconds</span> (ដូរចុះដូរឡើង/Random)</td>
				    </tr>
				    <tr>
				      <td>រង់ចាំប៉ុស្ដិ៍ម្ដងទៀត/Waiting for next post:</td>
				      <td>: <span class="right-text"><?php echo @$_GET['time2'];?> seconds</span></td>
				    </tr>
				    <tr>
				      <td>&nbsp;</td>
				      <td>&nbsp;</td>
				    </tr>
				  </tbody>
				</table>
			<?php endif;?>

		</div>
	</div>
</body>
</html>