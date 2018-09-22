<!DOCTYPE html>
 <html lang='en'>
	 <meta charset='utf-8' />
 <head>
	 <title>Amai Lite |Xxxx </title>
	 <link href='http://twitter.github.io/bootstrap/assets/css/bootstrap.css' rel='stylesheet'/>
<link href='http://twitter.github.io/bootstrap/assets/css/bootstrap-responsive.css' rel='stylesheet'/>
	 <link rel='stylesheet' type='text/css' href='<?php echo base_url();?>themes/layout/css/style.css?v=<?php echo $this->config->item('cjsuf');?>' media="screen" />
	 <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js' type='text/javascript'></script>
	 <script type='text/javascript' src="<?php echo base_url();?>themes/layout/js/bootstrap.min.js"></script>
	 <script type='text/javascript' src="<?php echo base_url();?>themes/layout/js/main.js"></script>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
 <?php if (isset($includes)){echo $includes;} ?>
 <body>
 <div class="navbar blue blue2 navbar-fixed-top">
	 <div class='navbar-inner'>
		 <div class='container-fluid'>
			<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
		<a class='brand pull-left'><i class='icon-white icon-leaf'></i> Amai Xxxx </a>  
		<div class='nav-collapse collapse navbar-responsive-collapse'>  			 
			 <ul class='nav'>
				 <li class='active'><a href="#"><i class='icon-white icon-home'></i> Dashboard </a></li>
				 <li class="dropdown">
    				 <a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
    					 <i class='icon-white icon-tasks'></i> Features  <b class="caret"></b>
    				 </a>
			     <ul class="dropdown-menu">
			    	 <li><a href="#">Navbar Colours </a></li>
			    	 <li><a href="#">Smooth Animations </a></li>
			    	 <li><a href="#">.sharp Class </a></li>
			    	 <li><a href="#">Canvas Charts </a></li>
			    	 <li><a href="#">Tasks Mini Xxxxxx </a></li>
			    	 <li><a href="#">Messages </a></li>
			    	 <li><a href="#">Precoded Functions </a></li>

			     </ul>
  				 </li>
			 </ul>
			 <form class="navbar-search pull-left" />
                 <input type="text" class="search-query span3" placeholder="Search" />
                 <div class="icon-search"></div>
             </form> 
			 <ul class='nav pull-right'>
				 <li><a href="#" rel='tooltip' title='User Page'><i class='icon-white icon-user'></i> nanu </a></li>
				 <li><a href="#" rel='tooltip' title='Settings'><i class='icon-white icon-wrench'></i></a></li>
				 <li><a href="#" rel='tooltip' title='Logout'><i class='icon-white icon-off'></i></a></li>
			 </ul>
		 </div>
	 </div>
	</div>
</div>
 <section class='container-fluid pad40'>
	
	 <section class='row-fluid'>
		 <div class="span2 sideBar">
			
			 <br />
			 <ul>
				 <li class="active" data-target='page1'><figure><i class='icon-home'></i> Page &nbsp;&nbsp; <span class='badge badge-warning'>2 </span></figure></li>
				 <hr />
                 <li class='dropper'>
					 <figure><i class='icon-circle-arrow-down'></i> From Sites </figure>
					 <ul class='subSide'>
						 <li data-target='v4kh'><i class='icon-plus'></i> List Xxxx 1 </li>
						 <li><i class='icon-plus'></i> List Xxxx 2 </li>
						 <li><i class='icon-plus'></i> List Xxxx 3 </li>
					 </ul>
				 </li>
				 <li data-target='projects'><figure><i class='icon-book'></i> Projects &nbsp;&nbsp; <span class='badge badge-info'>4 </span></figure></li>
				 <li data-target='tasks'><figure><i class='icon-tasks'></i> Tasks &nbsp;&nbsp; <span class='badge badge-info'>3 </span></figure></li>
				 <li data-target='messages'><figure><i class='icon-envelope'></i> Messages &nbsp;&nbsp; <span class='badge badge-info'>3 </span></figure></li>
				 <hr />
				 <li data-target='functions'><figure><i class='icon-heart'></i> Functions </figure></li>				 
				 <li data-target='charts'><figure><i class='icon-signal'></i> Charts </figure></li>
				 <hr />
				 <li data-target='settings'><figure><i class='icon-wrench'></i> Settings </figure></li>
				 <li data-target='profile'><figure><i class='icon-user'></i> Profile </figure></li>
				 <hr />
				 <li data-target='help'><figure><i class='icon-question-sign'></i> Help </figure></li>
			 </ul>
			 <div class='sideBottom'>
				 <button class='btn btn-primary headblue' data-toggle="collapse" data-target="#tasksSideList">TaskList  <i class='icon-white icon-circle-arrow-down'></i></button>
				 <ul id='tasksSideList' class='collapse in'>
					 <li data-taskid='6' data-taskstate='1'><a href="#">Explore Elements </a></li>
					 <li data-taskid='5' data-taskstate='0'><a href="#">Add New Xxxxxxx </a></li>
					 <li data-taskid='4' data-taskstate='0'><a href="#">Add New Xxxxxxxx </a></li>
					 <li data-taskid='3' data-taskstate='1'><a href="#">Custom Functions </a></li>
					 <li data-taskid='2' data-taskstate='0'><a href="#"></a>Custom CSS </li>
					 <li data-taskid='1' data-taskstate='1'><a href="#">Love this Xxxxx :) </a></li>
				 </ul>
			 </div>
		 </div>
		 <section class='span10 content borBox'>
			<?php echo $content; ?>
		 </section>
	 </section>
 </section>
 </body>
 </html>