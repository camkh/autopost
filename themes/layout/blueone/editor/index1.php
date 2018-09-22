<?php 
	include "../db_function.php";  
	$table=$_GET['table'];
	$id=$_GET['id'];
	$fild=$_GET['row'];
	$fildID=$_GET['fildID'];
	
	if(isset($_POST['save'])){
		$text=$_POST['text'];
		$text1 = str_replace("\r\n", "", $text);
		$text2 = stripslashes(mysql_real_escape_string($text1));
		$str="UPDATE $table SET $fild='".$text2."' WHERE $fildID=$id ";
		$suse=mysql_query($str);
			if($suse){
				echo '<h2>Successful!</h2>';
			}else{
				echo 'Fail!';
			}
	}else{
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="description" content="Open source WYSIWYG HTML editor for Web brought by Studio 42" />
		<title>elRTE - open source WYSIWYG editor for Web</title>
		<link rel="shortcut icon" href="http://elrte.org/favicon.ico" />
		<!--<link rel="stylesheet" href="http://elrte.org/style/style.css" type="text/css" media="screen" charset="utf-8" />-->
		<link rel="stylesheet" href="css/smoothness/jquery-ui-1.8.7.custom.css" type="text/css" media="screen" charset="utf-8" /> 
		
		
		<link rel="stylesheet" href="css/elrte.min.css"  type="text/css" media="screen" charset="utf-8" />

		<link rel="stylesheet" href="elfinder/css/elfinder.css" type="text/css" media="screen" charset="utf-8" />
		<script src="js/jquery-1.4.4.min.js"          type="text/javascript" charset="utf-8"></script>
		<!--<script src="http://elrte.org/style/jquery.backgroundPosition.js" type="text/javascript" charset="utf-8"></script>-->

		<script src="js/jquery-ui-1.8.7.custom.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/elrte.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/i18n/elrte.en.js" type="text/javascript" charset="utf-8"></script>

	
		<script src="elfinder/js/elfinder.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="elfinder/js/i18n/elfinder.en.js" type="text/javascript" charset="utf-8"></script>

		<script type="text/javascript" charset="utf-8">
		$().ready(function() {
			 
				$('#elFinder a').hover(
					function () {
						$('#elFinder a').animate({
							'background-position' : '0 -45px'
						}, 300);
					},
					function () {
						$('#elFinder a').delay(400).animate({
							'background-position' : '0 0'
						}, 300);
					}
				);

			$('#elFinder a').delay(800).animate({'background-position' : '0 0'}, 300);
			
			var opts = {
				absoluteURLs: false,
				cssClass : 'el-rte',
				lang     : 'en',
				height   : 320,
				toolbar  : 'maxi',
				cssfiles : ['css/elrte-inner.css'],
				fmOpen : function(callback) {
					$('<div id="myelfinder" />').elfinder({
						url : 'elfinder/connectors/php/connector.php',
						lang : 'en',
						dialog : { width : 900, modal : true, title : 'elFinder - file manager for web' },
						closeOnEditorCallback : true,
						editorCallback : callback
					})
				}
			}
			$('#editor').elrte(opts);
		})
		</script>
			
	</head>

<body>
<?php

	$str="SELECT * FROM $table WHERE $fildID='$id'";
	$sql=mysql_query($str);
	while($row=mysql_fetch_array($sql)){
?>
<form action="" method="post" enctype="multipart/form-data">
	<textarea name="text" id="editor" method="post" action="">
    	<?php echo $row[$fild]; ?>
    </textarea>
    <input type="submit" value="Save" name="save" />
</form>
<? }} ?>
</body>
</html>
