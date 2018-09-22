<?php
$user = $this->session->userdata('username');
$user_type = $this->session->userdata('user_type');
$log_id = $this->session->userdata('log_id');
?>
<!DOCTYPE html>

<!--
This is a minified version of the ThemeForest-theme "Melon - Flat & Responsive Admin Template".

Author: Simon 'Stammi' Stamm <http://themeforest.net/user/Stammi?ref=stammi>

Note: If you buy my template on ThemeForest, you will receive the non-minified and commented/ documentated version!
This is a minified version to prevent stealing.
-->

<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />
        <meta http-equiv="refresh" content="600">
        <title><?php echo $title; ?></title>
        <?php if ($user) { ?>
            <link href="<?php echo base_url(); ?>themes/layout/blueone/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />        
            <link href="<?php echo base_url(); ?>themes/layout/blueone/assets/css/pagespeed.css" rel="stylesheet" type="text/css" />
            <link href="<?php echo base_url(); ?>themes/layout/blueone/assets/css/plugins.css" rel="stylesheet" type="text/css" />

            <!--[if IE 7]><link rel="stylesheet" href="<?php echo base_url(); ?>themes/layout/blueone/assets/css/fontawesome/font-awesome-ie7.min.css" /><![endif]-->
            <!--[if IE 8]><link href="<?php echo base_url(); ?>themes/layout/blueoneassets/css/ie8.css" rel="stylesheet" type="text/css" /><![endif]-->
            <!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/layout/blueone/plugins/jquery-ui/jquery.ui.1.10.2.ie.css" /><![endif]-->

            <script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/assets/js/libs/jquery-1.10.2.min.js"></script>
<script>$(document).ready(function() {
                    App.init();
                    Plugins.init();
                    FormComponents.init()
                });</script>

            <!-- Text editor -->            
        <?php } ?>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <?php
        if (!empty($css)) {
            foreach ($css as $key => $cssscript) {
                ?>
                <link rel="stylesheet" href="<?php echo base_url(); ?><?php echo $cssscript; ?>.css" />
                <?php
            }
        }
        ?>
        <?php
        if (isset($includes)) {
            echo $includes;
        }
        ?>
    </head>
    <body class="<?php echo (!empty($bodyClass) ? $bodyClass : '') ?>">
        <?php
        if ($user) {
            include('blueone/header.php');
            include('blueone/contentLogin.php');
        } else {
            echo $content;
        }
        ?>
        <!-- basic scripts -->
        <?php if ($user) {?>        
            <!-- editor -->
            <link rel="shortcut icon" href="favicon.ico" />            
            <script src="<?php echo base_url(); ?>themes/layout/blueone/editor/js/elrte.min.js" type="text/javascript" charset="utf-8"></script>
            <script src="<?php echo base_url(); ?>themes/layout/blueone/editor/elfinder/js/elfinder.min.js" type="text/javascript" charset="utf-8"></script>
            <script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/bootstrap/js/bootstrap-pagespeed.js"></script>


            <script type="text/javascript">
                //<![CDATA[
                (function($) {
                    var lastSize = 0;
                    var interval = null;
                    $.fn.resetBreakpoints = function() {
                        $(window).unbind('resize');
                        if (interval) {
                            clearInterval(interval);
                        }
                        lastSize = 0;
                    };
                    $.fn.setBreakpoints = function(settings) {
                        var options = jQuery.extend({distinct: true, breakpoints: new Array(320, 480, 768, 1024)}, settings);
                        interval = setInterval(function() {
                            var w = $(window).width();
                            var done = false;
                            for (var bp in options.breakpoints.sort(function(a, b) {
                                return(b - a)
                            })) {
                                if (!done && w >= options.breakpoints[bp] && lastSize < options.breakpoints[bp]) {
                                    if (options.distinct) {
                                        for (var x in options.breakpoints.sort(function(a, b) {
                                            return(b - a)
                                        })) {
                                            if ($('body').hasClass('breakpoint-' + options.breakpoints[x])) {
                                                $('body').removeClass('breakpoint-' + options.breakpoints[x]);
                                                $(window).trigger('exitBreakpoint' + options.breakpoints[x]);
                                            }
                                        }
                                        done = true;
                                    }
                                    $('body').addClass('breakpoint-' + options.breakpoints[bp]);
                                    $(window).trigger('enterBreakpoint' + options.breakpoints[bp]);
                                }
                                if (w < options.breakpoints[bp] && lastSize >= options.breakpoints[bp]) {
                                    $('body').removeClass('breakpoint-' + options.breakpoints[bp]);
                                    $(window).trigger('exitBreakpoint' + options.breakpoints[bp]);
                                }
                                if (options.distinct && w >= options.breakpoints[bp] && w < options.breakpoints[bp - 1] && lastSize > w && lastSize > 0 && !$('body').hasClass('breakpoint-' + options.breakpoints[bp])) {
                                    $('body').addClass('breakpoint-' + options.breakpoints[bp]);
                                    $(window).trigger('enterBreakpoint' + options.breakpoints[bp]);
                                }
                            }
                            if (lastSize != w) {
                                lastSize = w;
                            }
                        }, 250);
                    };
                })(jQuery);
                //]]></script>
            <script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/typeahead.min.js"></script>
            <script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/assets/js/app.js"></script>
            <script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/assets/js/pluginsjs.js"></script>
            
            
            <?php }
        if (!empty($js)) {
            foreach ($js as $key => $jsScript) {
                ?>
                <script src="<?php echo base_url(); ?><?php echo $jsScript; ?>"></script>
                <?php
            }
        }
        ?>
        <script type="text/javascript">
            window.jQuery || document.write("<script src='./assets/js/jquery-1.9.1.min.js'>\x3C/script>");
<?php
if (!empty($addJsScript)) {
    foreach ($addJsScript as $key => $AddJs) {
        echo $AddJs;
    }
}
?>
        </script>        
    </body>
</html>
