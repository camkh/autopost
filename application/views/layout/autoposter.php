<link href='https://fonts.googleapis.com/css?family=Hanuman|Koulen' rel='stylesheet' type='text/css'>
<header class="header navbar navbar-fixed-top" role="banner">
    <div class="container">
        <ul class="nav navbar-nav">
            <li class="nav-toggle">
                <a href="javascript:void(0);" title="">
                    <i class="icon-reorder">
                    </i>
                </a>
            </li>
        </ul>
        <a class="navbar-brand" href="http://localhost/test/php/CI/autopost/">
            <img src="<?php echo base_url();?>themes/layout/blueone/assets/img/logo.png" alt="logo" style="max-height: 40px;">
            <strong>
                AUTO
            </strong>
            POSTER
        </a>
        <ul class="nav navbar-nav navbar-left hidden-xs hidden-sm">
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li>
                <a class="btn btn-warning" href="<?php echo base_url();?>">
                    Register
                </a>
            </li>
        </ul>
    </div>
</header>
<div id="" class="" style="margin-top:50px;overflow-x:hidden">
    <div class="row">
        <div class="col-lg-12">
        <?php include 'feature.php';?>
        </div>
    </div>
</div>

<div id="container">
    <div id="">
        <div class="container">
            <div class="row">
                <center><h2 style="margin:-10px auto 10px auto;padding:0">Top userful tool</h2></center>
            </div>
            <div class="row row-bg" style="display: block;">
                <div class="col-sm-6 col-md-3 col-xs-6">
                    <div class="statbox widget box box-shadow">
                        <div class="widget-content">
                            <div class="visual" style="padding:0px;height:85px">
                                <img src="https://lh3.googleusercontent.com/-r7lBmhtv2M8/Vjjn69culiI/AAAAAAAAOhA/gIeTa8Zpl3c/s86-Ic42/remove-groups.png"/>
                            </div>
                            <div class="title" style="color:red">
                                Remove All
                            </div>
                            <div class="value">
                                Facebook Groups
                                <div style="clear:both">
                                </div>
                            </div>
                            <a class="more" href="javascript:void(0);">
                                Remove All Facebook Groups
                                <i class="pull-right icon-angle-right">
                                </i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 col-xs-6">
                    <div class="statbox widget box box-shadow">
                        <div class="widget-content">
                            <div class="visual" style="padding:0px;height:85px">
                                <img src="https://lh3.googleusercontent.com/-JGxIXSIiss8/VjjfiMLtPII/AAAAAAAAOgc/PmfGJRrDuEI/s86-Ic42/sreach-group.png"/>
                            </div>
                            <div class="title">
                                by keyword
                            </div>
                            <div class="value">
                                Find groups
                                <div style="clear:both">
                                </div>
                            </div>
                            <a class="more" href="javascript:void(0);">
                                Find groups and Join by keyword
                                <i class="pull-right icon-angle-right">
                                </i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 col-xs-6">
                    <div class="statbox widget box box-shadow">
                        <div class="widget-content">
                            <div class="visual" style="padding:0px;height:85px">
                                <img src="https://lh3.googleusercontent.com/-L1_YOygZWdw/Vjjhc9nNcUI/AAAAAAAAOgw/lF_A6DloOKA/s86-Ic42/trranfer.png"/>
                            </div>
                            <div class="title">
                                Facebook
                            </div>
                            <div class="value">
                                Group Transfer
                                <div style="clear:both">
                                </div>
                            </div>
                            <a class="more" href="javascript:void(0);">
                                Facebook Group Transfer
                                <i class="pull-right icon-angle-right">
                                </i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 col-xs-6">
                    <div class="statbox widget box box-shadow">
                        <div class="widget-content">
                            <div class="visual" style="padding:0px;height:85px">
                                <img src="https://lh3.googleusercontent.com/-i9cYIP_vfic/VjjfiBwkYDI/AAAAAAAAOgY/irP2FUuSbx4/s80-Ic42/post-group.png"/>
                            </div>
                            <div class="title">
                                Post on
                            </div>
                            <div class="value">
                                Facebook Groups
                                <div style="clear:both">
                                </div>
                            </div>
                            <a class="more" href="javascript:void(0);">
                                Post On Multiple Groups At Once
                                <i class="pull-right icon-angle-right">
                                </i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div style="cear:both"></div>
             
            <div class="row">
                <div class="col-md-12"><img src="<?php echo base_url();?>img/step3.jpg"></div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="widget box">
                        <div class="widget-header">
                            <h4>
                                <i class="icon-reorder">
                                </i>
                                Tools
                            </h4>
                        </div>
                        <div class="widget-content">
                            <div class="row">
                                <div class="col-md-4">
                                    <a onclick="runCode();" class="btn btn-primary btn-block" href="javascript:;"><i class="icon-rocket"></i> Post On Multiple Groups At Once</a>
                                    <a onclick="runCode();" class="btn btn-primary btn-block" href="javascript:;"><i class="icon-mail-reply-all"></i> Facebook Group Transfer</a>
                                    <a class="btn btn-primary btn-block" href="javascript:void(0);" onclick="runR();"><i class="icon-remove" style="color:red"></i> Remove All Facebook Groups</a>
                                    <a class="btn btn-primary btn-block" href="javascript:void(0);" onclick="runF();"><i class="icon-search"></i> Find groups and Join by keyword and member</a> 
                                </div>
                                <div class="col-md-4">
                                    <a onclick="runF();" class="btn btn-primary btn-block" href="javascript:;"><i class="icon-thumbs-down-alt"></i> Unlike All Facebook Pages At Once</a>
                                    <a onclick="runF();" class="btn btn-primary btn-block" href="javascript:;"><i class="icon-user-md"></i> Unfriend All Friends At Once</a>
                                    <a onclick="runF();" class="btn btn-primary btn-block" href="javascript:;"><i class="icon-rss-sign"></i> Unfollow All Facebook Friends At Once</a>
                                    <a onclick="runF();" class="btn btn-primary btn-block" href="javascript:;"><i class="icon-rss-sign"></i> Unfollow All Facebook Groups At Once</a>
                                </div>
                                <div class="col-md-4">
                                    <a onclick="runF();" class="btn btn-primary btn-block" href="javascript:;"><i class="icon-facebook"></i> Find Facebook ID</a>
                                    <a onclick="runF();" class="btn btn-primary btn-block" href="javascript:;"><i class="icon-comments-alt"></i> Message All Friends At Once</a>
                                    <a onclick="runF();" class="btn btn-primary btn-block" href="javascript:;"><i class="icon-facebook"></i> Invite Your Friends To Like Your Page</a>
                                    <a onclick="runF();" class="btn btn-block" href="javascript:;"><i class="icon-facebook"></i> and more...</a>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   

</div>


<footer class="blog-footer">
  <p><?php echo $title; ?> <a href="<?php echo base_url();?>">Home</a></p>
  <p>
    <a href="#">Back to top</a>
  </p>
</footer>
<style type="text/css">
    .container{ max-width: 1170px!important}
    #container{height: auto;}
    .navbar {background: #222;border-color: #444}
.container .jumbotron{padding:10px 10px 0 10px;margin-left: -20px;margin-right: -20px;}
.blog-footer {
  padding: 40px 0;
  color: #999;
  text-align: center;
  background-color: #f9f9f9;
  border-top: 1px solid #e5e5e5;
}
</style>
