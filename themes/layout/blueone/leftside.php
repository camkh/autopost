<div id="sidebar" class="sidebar-fixed">
    <div id="sidebar-content">
        <form class="sidebar-search">
            <div class="input-box">
                <button type="submit" class="submit">
                    <i class="icon-search">
                    </i>
                </button>
                <span>
                    <input type="text" placeholder="Search..." />
                </span>
            </div>
        </form>
        <ul id="nav">
            <li class="current"> <a href="<?php echo base_url(); ?>"> <i class="icon-dashboard"></i>  Dashboard</a> </li>
            <?php if ($user_type != 3) { ?>
                <li class="open">
                    <a href="javascript:void(0);"> <i class="icon-cog"></i> Admin sitting<span class="label label-info pull-right">6</span> </a>
                    <ul class="sub-menu" style="overflow: hidden; display: block;">
                        <?php if ($user_type == 1) { ?>
                            <li> <a href="<?php echo base_url(); ?>user"> <i class="icon-edit"></i>User</a></li>
                            <li> <a href="<?php echo base_url(); ?>user/permissionpage"> <i class="icon-bar-chart"></i>Page permission</a> </li>
                            <li> <a href="<?php echo base_url(); ?>user/permission"> <i class="icon-bar-chart"></i>User Page permission</a> </li>
                            <li> <a href="<?php echo base_url(); ?>tv/index"> <i class="icon-bar-chart"></i>TV</a> </li>
                            <li> <a href="<?php echo base_url(); ?>post/download"> <i class="icon-bar-chart"></i>Download</a> </li>
                        <?php } ?>
                        <li> <a href="<?php echo base_url(); ?>licence"> <i class="icon-bar-chart"></i>Licence</a> </li>
                    </ul>
                </li><?php }
                if ($user_type == 1):
                        ?>
                <li class="current">
                    <a href="javascript:void(0);"> <i class="icon-cog"></i> Post<span class="label label-info pull-right">6</span> </a>
                    <ul class="sub-menu">
                        <li><a href="<?php echo base_url(); ?>post/getfromb"> From Blogs </a></li>
                        <li><a href="<?php echo base_url(); ?>post/movies"> Continue Movies </a></li>
                        <li><a href="<?php echo base_url(); ?>music"> Post Music </a></li>
                        <li><a href="<?php echo base_url(); ?>home"> Home Page </a></li>
                        <li><a href="<?php echo base_url(); ?>post/bloglist"> Get all blogs </a></li>
                        <li><a href="<?php echo base_url(); ?>post/blogcate"> Blog Categories </a></li>
                        <li><a href="<?php echo base_url(); ?>post/addblog"> Add blogs </a></li>
                        <li><a href="<?php echo base_url(); ?>post/continues"> Cotinue Movies </a></li>
                        <li><a href="<?php echo base_url(); ?>post/getcode"> Get codes </a></li>
                        <li><a href="<?php echo base_url(); ?>post/blogpassword"> Blog Password </a></li>
                    </ul>
                </li>
                <?php
            endif;
            if (!empty($menuPermission)) {
                ?>
                <li class="current">
                    <a href="javascript:void(0);"> <i class="icon-desktop"></i> Post <span class="label label-info pull-right">6</span> </a>
                    <ul class="sub-menu">
                        <?php foreach ($menuPermission as $menu_value) { ?>
                            <li><a href="<?php echo base_url(); ?><?php echo $menu_value[Tbl_title::value]; ?>"> <?php echo $menu_value[Tbl_title::title]; ?> </a></li>
    <?php } ?>
                    </ul>
                </li>
<?php } ?>         
        </ul>   
    </div>
    <div id="divider" class="resizeable"></div>
</div>