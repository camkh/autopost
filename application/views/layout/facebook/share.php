<?php if ($this->session->userdata('user_type') != 4) {
    $action = $this->input->get('post');
 ?>
    <style>
        .radio-inline{}
        .error {color: red}
        #defaultCountdown { width: 340px; height: 100px; font-size: 20pt;margin-bottom: 20px}
    </style>
    <div class="page-header">
    </div>
    <div class="ror">
        <div class="col-md-12">
            <center><div id="defaultCountdown"></div></center>
            <div class="progress progress-striped active"> <div id="timer" class="progress-bar progress-bar-danger" style="width: 0%"></div> </div>
        </div>
    </div>
    <div class="row">        
        <div class="col-md-12">
           <div class="widget box">
                <div class="widget-header">
                    <h4><i class="icon-reorder"></i> Color Pickers</h4>
                </div>
                <div class="widget-content">
                    <form class="form-horizontal row-border" action="#">
                        <div class="form-group"> <label class="col-md-3 control-label">HEX format:</label>
                            <div class="col-md-9"> <input type="text" name="regular" class="form-control bs-colorpicker" value="#8fff00" data-color-format="hex" data-colorpicker-guid="1"> </div>
                        </div>
                        <div class="form-group"> <label class="col-md-3 control-label">RGBa format:</label>
                            <div class="col-md-9"> <input type="text" name="regular" class="form-control bs-colorpicker" value="rgb(0,194,255,0.78)" data-color-format="rgba" data-colorpicker-guid="2"> </div>
                        </div>
                        <div class="form-group"> <label class="col-md-3 control-label">Using events:</label>
                            <div class="col-md-9"> <a href="#" class="btn btn-default btn-block" id="colorpicker-event" data-color-format="hex" data-color="#fff" data-colorpicker-guid="3">Change background color</a> </div>
                        </div>
                    </form>
                </div>
            </div>    
        </div>
    </div>
    <link rel="stylesheet" href="<?php echo base_url(); ?>themes/layout/css/jquery.countdown.css">
    <link href="<?php echo base_url(); ?>themes/layout/blueone/plugins/nestable/nestable.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>themes/layout/blueone/assets/css/plugins/bootstrap-switch.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url(); ?>themes/layout/js/jquery.plugin.min.js"></script>
    <script src="<?php echo base_url(); ?>themes/layout/js/jquery.countdown.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/nestable/jquery.nestable.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/bootstrap-switch/bootstrap-switch.min.js"></script>    
    <script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/nprogress/nprogress.js"></script>
    <meta content='text/html; charset=UTF-8' http-equiv='Content-Type'/>    
    <?php //var_dump($sharePost);?>     
   <?php if(!empty($action)):
    if(!empty($sharePost->pcount)):
        $pTitle = $sharePost->title;
        $pLink = urlencode($sharePost->link);
        $group_id = $sharePost->group_id;
        $pid = $sharePost->pid;
        $sid = $sharePost->sid;
    ?>
    <div id="ptitle" style="display: none;"><?php echo $pTitle;?></div>
    <code id="codeB" style="width:300px;overflow:hidden;display:none"></code>
    <code id="examplecode5" style="width:300px;overflow:hidden;display:none">var i, retcode,retcodes;var report;var codedefault2=&quot;SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 300\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var wm=Components.classes[&quot;@mozilla.org/appshell/window-mediator;1&quot;].getService(Components.interfaces.nsIWindowMediator);var window=wm.getMostRecentWindow(&quot;navigator:browser&quot;);var setLink = &quot;<?php echo $pLink;?>&quot;, homeUrl = &quot;http://localhost/test/php/CI/autopost/&quot;, gid = &quot;<?php echo $group_id;?>&quot;, pid = &quot;<?php echo $pid;?>&quot;,sid=&quot;<?php echo $sid;?>&quot;;</code>
    <?php 
    endif;
endif;?>   
    <script>
        $( document ).ready(function() {
        <?php
            $year = $month = $day = $hour = $minute = $second = 0;
            $setDayFormat = $setDay = '';
            if($sharePost->option->share_schedule ==1) {            
                if ($sharePost->diff->invert == 1) {
                    $year = $sharePost->diff->y;
                    $month = $sharePost->diff->m;
                    $day = $sharePost->diff->d;
                    $hour = $sharePost->diff->h;
                    $minute = $sharePost->diff->i;
                    $second = $sharePost->diff->s;
                }
                if($day>0) {
                    $setDay = '+' . $day . 'd '; 
                    $setDayFormat = '%m days'; 
                }
            }
            if(!empty($action)):
                switch ($action) {
                    case 'gwait':
                        $waiting = $sharePost->option->wait_group;
                        $styleA = $waiting * 10;
                        break;
                    
                    case 'nexpost':
                        $waiting = $sharePost->option->wait_post;
                        $styleA = $waiting * (60 * 10);
                        $waiting = $waiting * 60;
                        break;
                    case '1':
                        $styleA = $waiting = 0;
                        break;                        
                }
            endif;?>
            <?php if(!empty($action)):?>
            $('#defaultCountdown').countdown({until: '+0h +0m +<?php echo $waiting;?>s', format: '<?php echo $setDayFormat;?>%H:%M:%S'});
            <?php endif;?>             

            /*timer progress*/
            var elem = document.getElementById("timer");   
            var width = 1;
            //600 = 1minute;
            //10 = 1 second;            
              var id = setInterval(frame, <?php echo $styleA;?>);
              function frame() {
                if (width >= 100) {
                  clearInterval(id);
                  //complete here
                  //window.location = "share.php?do=share";
                  load_contents("http://postautofb.blogspot.com/feeds/posts/default/-/postToGroupByPost");
                } else {
                  width++; 
                  elem.style.width = width + '%'; 
                  elem.innerHTML = width + '%'; 
                }
              };
            /*End timer progress*/            
        });

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
    <?php
} else {
    echo '<div class="alert fade in alert-danger" >
                            <strong>You have no permission on this page!...</strong> .
                        </div>';
}
?>