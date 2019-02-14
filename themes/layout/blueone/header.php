<?php if (isset($_SESSION['valid'])) { ?>
    <script type="text/javascript">
        idleTime = 0;
        $(document).ready(function() {
            //Increment the idle time counter every minute.
            var idleInterval = setInterval(timerIncrement, 10000); // 1 minute

            //Zero the idle timer on mouse movement.
            $(this).mousemove(function(e) {
                idleTime = 0;
            });
            $(this).keypress(function(e) {
                idleTime = 0;
            });
        });

        function timerIncrement() {
            idleTime = idleTime + 1;
            if (idleTime > 1000) {
                //window.location.reload();
                window.location = "/post/logout.php";
            }
        }
    </script> 
<?php }
 ?>
<header class="header navbar navbar-fixed-top" role="banner">        
    <div class="container">
        <ul class="nav navbar-nav">
            <li class="nav-toggle"><a href="javascript:void(0);" title=""><i class="icon-reorder"></i></a></li>
        </ul>
        <a class="navbar-brand" href="<?php echo base_url(); ?>"> <img src="<?php echo !empty($this->session->userdata ( 'fb_user_id' )) ? 'https://graph.facebook.com/'.$this->session->userdata ( 'fb_user_id' ).'/picture' : base_url().'themes/layout/blueone/assets/img/logo.png'; ?>" alt="logo" style="max-height: 40px;" /> <?php echo !empty($this->session->userdata ( 'fb_user_name' )) ? $this->session->userdata ( 'fb_user_name' ) : '<strong>AD</strong>MIN'; ?> </a> <a href="#" class="toggle-sidebar bs-tooltip" data-placement="bottom" data-original-title="Toggle navigation"> <i class="icon-reorder"></i> </a>
        <ul class="nav navbar-nav navbar-left hidden-xs hidden-sm">
            <li class="dropdown user"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-facebook-sign"></i> <span class="username">Facebook</span> <i class="icon-caret-down small"></i> </a>
                <ul class="dropdown-menu">
                    <li> <a href="<?php echo base_url() . 'Facebook/group'; ?>"><i class="icon-facebook"></i> Facebook Groups</a> </li>
                    <li><a href="<?php echo base_url(); ?>"><i class="icon-plus"></i> Remove all Groups</a></li>  
                    <li><a href="<?php echo base_url(); ?>Facebook/removegroup"><i class="icon-plus"></i> Remove all Groups by ID</a></li>  
                    <li><a href="<?php echo base_url(); ?>"><i class="icon-plus"></i> Find groups by keyword<li><a href="<?php echo base_url(); ?>"><i class="icon-plus"></i> Facebook Group Transfer</a></li>  
                    <li><a href="<?php echo base_url(); ?>"><i class="icon-plus"></i> Post on Facebook Groups</a></li>  
                    <li class="divider"></li>
                    <li><a href="javascript:;"><i class="icon-off"></i> Invitation tools</a></li>
                    <li><a href="<?php echo base_url() . 'Facebook/invitelikepage?action=1'; ?>"><i class="icon-facebook"></i> Invite Your Friends To Like Your Page</a></li>
                    <li><a href="<?php echo base_url() . 'Facebook/addfriendgroup?action=1'; ?>"><i class="icon-facebook"></i> Invite Your Friends To Join Your Group</a></li>
                    <li class="divider"></li> 
                    <li><a href="javascript:;"><i class="icon-plus"></i> Extraction tools</a></li>
                    <li><a href="<?php echo base_url() . 'Facebook/checknum'; ?>"><i class="icon-phone"></i> Get public phone numbers of all Facebook friends</a></li>
                    <li><a href="<?php echo base_url() . 'Facebook/friendlist';?>"><i class="icon-th-list"></i> Get Friend list</a></li>
                    <li><a href="<?php echo base_url() . 'Facebook/fblist'; ?>"><i class="icon-facebook-sign"></i> Facebook usable</a></li>
                    <li><a href="<?php echo base_url() . 'Facebook/fbid'; ?>"><i class="icon-key"></i> Find Facebook ID</a></li>
                    <li><a href="<?php echo base_url(); ?>"><i class="icon-key"></i>  Accept All Friend Requests At Once</a></li>
                    <li class="divider"></li>
                    <li><a href="javascript:;"><i class="icon-plus"></i> Remove Tools</a></li>
                    <li><a href="<?php echo base_url() . 'Facebook/unfriend'; ?>"><i class="icon-plus"></i> Remove all Friends</a></li>
                    <li><a href="<?php echo base_url(); ?>"><i class="icon-plus"></i> Remove all Groups</a></li> 
                </ul>
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-left hidden-xs hidden-sm">
            <li class="dropdown user"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-facebook-sign"></i> <span class="username">Facebook (User agent)</span> <i class="icon-caret-down small"></i> </a>
                <ul class="dropdown-menu">
                    <li> <a href="<?php echo base_url() . 'Facebook/group'; ?>"><i class="icon-facebook"></i> Facebook Groups</a> </li>
                    <li><a href="<?php echo base_url(); ?>"><i class="icon-plus"></i> Remove all Groups</a></li>  
                    <li><a href="<?php echo base_url(); ?>Facebook/removegroup"><i class="icon-plus"></i> Remove all Groups by ID</a></li>  
                    <li><a href="<?php echo base_url(); ?>"><i class="icon-plus"></i> Find groups by keyword<li><a href="<?php echo base_url(); ?>"><i class="icon-plus"></i> Facebook Group Transfer</a></li>  
                    <li><a href="<?php echo base_url(); ?>"><i class="icon-plus"></i> Post on Facebook Groups</a></li>  
                    <li class="divider"></li>
                    <li><a href="javascript:;"><i class="icon-off"></i> Invitation tools</a></li>
                    <li><a href="<?php echo base_url() . 'Facebook/invitelikepage?action=1'; ?>"><i class="icon-facebook"></i> Invite Your Friends To Like Your Page</a></li>
                    <li><a href="<?php echo base_url() . 'Facebook/addfriendgroup?action=1'; ?>"><i class="icon-facebook"></i> Invite Your Friends To Join Your Group</a></li>
                    <li class="divider"></li> 
                    <li><a href="javascript:;"><i class="icon-plus"></i> Extraction tools</a></li>
                    <li><a href="<?php echo base_url() . 'Facebook/checknum'; ?>"><i class="icon-phone"></i> Get public phone numbers of all Facebook friends</a></li>
                    <li><a href="<?php echo base_url() . 'Facebook/friendlist';?>"><i class="icon-th-list"></i> Get Friend list</a></li>
                    <li><a href="<?php echo base_url() . 'Facebook/fblist'; ?>"><i class="icon-facebook-sign"></i> Facebook usable</a></li>
                    <li><a href="<?php echo base_url() . 'Facebook/fbid'; ?>"><i class="icon-key"></i> Find Facebook ID</a></li>
                    <li><a href="<?php echo base_url(); ?>"><i class="icon-key"></i>  Accept All Friend Requests At Once</a></li>
                    <li class="divider"></li>
                    <li><a href="javascript:;"><i class="icon-plus"></i> Remove Tools</a></li>
                    <li><a href="<?php echo base_url() . 'Facebook/unfriend'; ?>"><i class="icon-plus"></i> Remove all Friends</a></li>
                    <li><a href="<?php echo base_url(); ?>"><i class="icon-plus"></i> Remove all Groups</a></li> 
                </ul>
            </li>
        </ul>        
        <ul class="nav navbar-nav">
            <li class="dropdown user"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-th-large"></i> <span class="username">
                         Post</span> <i class="icon-caret-down small"></i> </a>
                <ul class="dropdown-menu"> 
                    <li><a href="<?php echo base_url();?>managecampaigns"><i class="icon-th-list"></i> Post list</a></li>
                    <li><a href="<?php echo base_url();?>managecampaigns/yturl?renew=1"><i style="color:red" class="icon-youtube-play"></i> Post from Youtube URL</a></li>
                    <li><a href="<?php echo base_url();?>managecampaigns/add"><i class="icon-share"></i> Post by Url</a></li>
                    <li><a href="<?php echo base_url();?>facebook/shareation?post=getpost"><i class="icon-share"></i> Share now</a></li>
                    <li class="divider"></li>
                    <li><a href="<?php echo base_url();?>managecampaigns/setting"><i class="icon-wrench"></i> Setting</a></li>
                </ul>
            </li>            
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown user"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-male"></i> <span class="username">
                         <?php
                        $user = $this->session->userdata('email');
                        echo @$user;?></span> <i class="icon-caret-down small"></i> </a>
                <ul class="dropdown-menu">
                    <li> <a href="<?php echo base_url(); ?>licence"><i class="icon-key"></i> Licence</a> </li>                     
                    <li><a href="<?php echo base_url(); ?>home/logout"><i class="icon-off"></i> Log Out</a></li>
                </ul>
            </li>
        </ul>
    </div>
</header>