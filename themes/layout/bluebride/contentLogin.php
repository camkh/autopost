<div class="container-fluid" id="main-container">
    <a href="#" id="menu-toggler"><span></span></a><!-- menu toggler -->

    <div id="sidebar">

        <div id="sidebar-shortcuts">
            <div id="sidebar-shortcuts-large">
                <button class="btn btn-small btn-success"><i class="icon-signal"></i></button>
                <button class="btn btn-small btn-info"><i class="icon-pencil"></i></button>
                <button class="btn btn-small btn-warning"><i class="icon-group"></i></button>
                <button class="btn btn-small btn-danger"><i class="icon-cogs"></i></button>
            </div>
            <div id="sidebar-shortcuts-mini">
                <span class="btn btn-success"></span>
                <span class="btn btn-info"></span>
                <span class="btn btn-warning"></span>
                <span class="btn btn-danger"></span>
            </div>
        </div><!-- #sidebar-shortcuts -->

        <ul class="nav nav-list">

            <li class="active">
                <a href="./index.html">
                    <i class="icon-dashboard"></i>
                    <span>Dashboard </span>

                </a>
            </li>


            <li>
                <a href="./typography.html">
                    <i class="icon-text-width"></i>
                    <span>Typography </span>

                </a>
            </li>


            <li>
                <a href="#" class="dropdown-toggle">
                    <i class="icon-desktop"></i>
                    <span>UI Elements </span>
                    <b class="arrow icon-angle-down"></b>
                </a>
                <ul class="submenu">
                    <li><a href="./elements.html"><i class="icon-double-angle-right"></i> Elements </a></li>
                    <li><a href="./buttons.html"><i class="icon-double-angle-right"></i> Buttons & Icons </a></li>
                </ul>
            </li>


            <li>
                <a href="./tables.html">
                    <i class="icon-list"></i>
                    <span>Tables </span>

                </a>
            </li>


            <li>
                <a href="#" class="dropdown-toggle">
                    <i class="icon-edit"></i>
                    <span>Forms </span>
                    <b class="arrow icon-angle-down"></b>
                </a>
                <ul class="submenu">
                    <li><a href="./form-elements.html"><i class="icon-double-angle-right"></i> Form Elements </a></li>
                    <li><a href="./form-wizard.html"><i class="icon-double-angle-right"></i> Wizard & Validation </a></li>
                </ul>
            </li>


            <li>
                <a href="./widgets.html">
                    <i class="icon-list-alt"></i>
                    <span>Widgets </span>

                </a>
            </li>


            <li>
                <a href="./calendar.html">
                    <i class="icon-calendar"></i>
                    <span>Calendar </span>

                </a>
            </li>


            <li>
                <a href="./gallery.html">
                    <i class="icon-picture"></i>
                    <span>Gallery </span>

                </a>
            </li>


            <li>
                <a href="./grid.html">
                    <i class="icon-th"></i>
                    <span>Grid </span>

                </a>
            </li>


            <li>
                <a href="#" class="dropdown-toggle">
                    <i class="icon-file"></i>
                    <span>Other Pages </span>
                    <b class="arrow icon-angle-down"></b>
                </a>
                <ul class="submenu">
                    <li><a href="./pricing.html"><i class="icon-double-angle-right"></i> Pricing Tables </a></li>
                    <li><a href="./invoice.html"><i class="icon-double-angle-right"></i> Invoice </a></li>
                    <li><a href="./login.html"><i class="icon-double-angle-right"></i> Login & Register </a></li>
                    <li><a href="./error-404.html"><i class="icon-double-angle-right"></i> Error 404 </a></li>
                    <li><a href="./error-500.html"><i class="icon-double-angle-right"></i> Error 500 </a></li>
                    <li><a href="./blank.html"><i class="icon-double-angle-right"></i> Blank Page </a></li>
                </ul>
            </li>


        </ul><!--/.nav-list-->

        <div id="sidebar-collapse"><i class="icon-double-angle-left"></i></div>


    </div><!--/#sidebar-->


    <div id="main-content" class="clearfix">

        <div id="breadcrumbs">
            <ul class="breadcrumb">
                <li><i class="icon-home"></i>  <a href="#">Home </a><span class="divider"><i class="icon-angle-right"></i></span></li>
                <li class="active">Dashboard </li>
            </ul><!--.breadcrumb-->

            <div id="nav-search">
                <form class="form-search" />
                <span class="input-icon">
                    <input autocomplete="off" id="nav-search-input" type="text" class="input-small search-query" placeholder="Search ..." />
                    <i id="nav-search-icon" class="icon-search"></i>
                </span>
                </form> 
            </div><!--#nav-search-->
        </div><!--#breadcrumbs-->

        <div id="page-content" class="clearfix">
            <?php echo $content; ?>
        </div><!--/#page-content-->


        <!--/#page-content-->


        <div id="ace-settings-container">
            <div class="btn btn-app btn-mini btn-warning" id="ace-settings-btn">
                <i class="icon-cog"></i>
            </div>
            <div id="ace-settings-box">
                <div>
                    <div class="pull-left">
                        <select id="skin-colorpicker" class="hidden">
                            <option data-class="default" value="#438EB9" />#438EB9 
                            <option data-class="skin-1" value="#222A2D" />#222A2D 
                            <option data-class="skin-2" value="#C6487E" />#C6487E 
                            <option data-class="skin-3" value="#D0D0D0" />#D0D0D0 
                        </select>
                    </div>
                    <span>&nbsp; Choose Skin </span>
                </div>
                <div><input type="checkbox" class="ace-checkbox-2" id="ace-settings-header" /><label class="lbl" for="ace-settings-header"> Fixed Header </label></div>
                <div><input type="checkbox" class="ace-checkbox-2" id="ace-settings-sidebar" /><label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar </label></div>
            </div>
        </div><!--/#ace-settings-container-->


    </div><!-- #main-content -->


</div><!--/.fluid-container#main-container-->