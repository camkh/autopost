<?php if ($this->session->userdata('user_type') != 4) {
    $action = $this->input->get('post');
    $uerAgent = $this->input->get('agent');
    $log_id = $this->session->userdata('user_id');
    $suid = $this->session->userdata ( 'sid' );  
 ?>
    <style>
        .radio-inline{}
        .error {color: red}
        #defaultCountdown { width: 340px; height: 100px; font-size: 20pt;margin-bottom: 20px}
        .khmer {font-family: 'Koulen', cursive;font-size: 30px}
        .table tbody tr.trbackground,tr.trbackground {background:#0000ff!important;}
        .trbackground a,.trbackground {color:red;}
    </style>
    
    <div class="page-header">
    </div>
    <div class="ror">
        <div class="col-md-12">
            <center><div id="defaultCountdown"></div></center>
            <div class="progress progress-striped active"> <div id="timer" class="progress-bar progress-bar-danger" style="width: 0%"></div> </div>
        </div>
    </div>
    <?php if(!empty($action) && $action != 'checkpost'):?>
    <div class="row">
        <div class="col-md-12">
            Group ID: <b><?php echo $sharePost->group_id;?></b> || <b>Post ID:</b> <?php echo $sharePost->pid;?> || <b>Share counts:</b> <?php echo $sharePost->count_shared;?>/<?php echo $sharePost->totalGroups;?>
        </div>      
        <div class="col-md-12">
           <table class="table table-striped table-bordered table-hover table-checkable table-tabletools datatable dataTable">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="uniform" name="allbox"
                                    id="checkAll" /></th>
                                <th>Name</th>
                                <th class="hidden-xs">Date time</th>
                                <th class="hidden-xs">Status</th>
                            </tr>
                        </thead>
                        <tbody>
    <?php
    if(count($sharePost->posts_list)<1):?>
        <script type="text/javascript">
        window.location = "<?php echo base_url();?>facebook/shareation?post=getpost"
        </script>
    <?php endif;
     foreach ($sharePost->posts_list as $value) { ?>
                                    <tr class="<?php echo ($sharePost->pid == $value->{Tbl_posts::id}) ? 'trbackground' : '';?>">
                                <td class="checkbox-column"><input type="checkbox" id="itemid"
                                    name="itemid[]" class="uniform"
                                    value="<?php echo $value->{Tbl_posts::id}; ?>" <?php echo ($sharePost->pid == $value->{Tbl_posts::id}) ? 'checked' : '';?> /></td>
                                <td><a
                                    href="<?php echo base_url(); ?>managecampaigns/add?id=<?php echo $value->{Tbl_posts::id}; ?>"><?php echo $value->{Tbl_posts::name}; ?></a>
                                </td>
                                <td class="hidden-xs">
        <?php echo $value->{Tbl_posts::p_date}; ?>
                                        </td>
                                <td>
                                    <div class="progress progress-striped active"> <div class="progress-bar progress-bar-info" style="width: 100%"></div> </div>
                                </td>
                            </tr>
    <?php } ?>
                            </tbody>
                    </table>   
        </div>
    </div>
<?php endif;?>
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
   <?php if(!empty($action) && $action != 'checkpost'):
    if(!empty($sharePost->pcount)):
        $pTitle = $sharePost->title;
        if(!empty($uerAgent)) {
            $pLink = $sharePost->link;
        } else {
            $pLink = urlencode($sharePost->link);
        }
        $group_id = $sharePost->group_id;
        $pid = $sharePost->pid;
        $sid = $sharePost->sid;
        //$sharePost->share_id
        $checkImage = $sharePost->count_shared;
        if(!empty($sharePost->notcheckimage) && $sharePost->notcheckimage ==1) {
            $checkImage = 1;
        }
    ?>
    <div id="ptitle" style="display: none;"><?php echo $pTitle;?></div>
    <code id="codeB" style="width:300px;overflow:hidden;display:none"></code>
    <code id="examplecode5" style="width:300px;overflow:hidden;display:none">var i, retcode,retcodes,report,uid=&quot;<?php echo $log_id;?>&quot;,suid=&quot;<?php echo $suid;?>&quot;;var codedefault2=&quot;SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 300\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var wm=Components.classes[&quot;@mozilla.org/appshell/window-mediator;1&quot;].getService(Components.interfaces.nsIWindowMediator);var window=wm.getMostRecentWindow(&quot;navigator:browser&quot;);var setLink = &quot;<?php echo $pLink;?>&quot;, homeUrl = &quot;<?php echo base_url();?>&quot;, gid = &quot;<?php echo $group_id;?>&quot;, pid = &quot;<?php echo $pid;?>&quot;,sid=&quot;<?php echo $sid;?>&quot;,shareid=&quot;<?php echo $sharePost->share_id;?>&quot;,sharechount=&quot;<?php echo $checkImage;?>&quot;;</code>
    <?php 
    endif;
    elseif($action == 'checkpost'):?>
        <code id="codeB" style="width:300px;overflow:hidden;display:none"></code>
        <code id="examplecode5" style="width:300px;overflow:hidden;display:none">var wm = Components.classes[&quot;@mozilla.org/appshell/window-mediator;1&quot;].getService(Components.interfaces.nsIWindowMediator);var window = wm.getMostRecentWindow(&quot;navigator:browser&quot;);var limit = 40,clear = 0,timedelay= 5,homeUrl = &quot;<?php echo base_url();?>&quot;;</code>
    <?php endif;?> 
    <script>
        $( document ).ready(function() {
            <?php 
            if($action != 'checkpost'):
            if($sharePost->option->share_schedule ==1):?>
            setInterval(function() {            
                closeOnLoad("<?php echo base_url();?>facebook/shareation?post=getpost");
              }, 1800000);<?php endif;endif;?> 
        <?php if(!empty($action) && $action != 'checkpost'):?>    
        <?php
            $year = $month = $day = $hour = $minute = $second = 0;
            $setDayFormat = $setDay = '';
            
            if(!empty($action)):
                switch ($action) {
                    case 'gwait':
                        $waiting = $sharePost->option->wait_group;
                        $styleA = (int) $waiting * 10;
                        if($sharePost->randomLink == 1) {
                            if($sharePost->count_shared > 15) {
                                $waiting = $sharePost->option->wait_post;
                                $styleA = $waiting * (60 * 10);
                                $waiting = $waiting * 60;
                            }
                        } 
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
                if($sharePost->option->share_schedule ==1) {
                    $d1 = new DateTime($sharePost->timeStart);
                    $d2 = new DateTime(date('Y-m-d H:i:s'));
                    if($d1 > $d2) {          
                        if ($sharePost->diff->invert == 1) {
                            $year = $sharePost->diff->y;
                            $month = $sharePost->diff->m;
                            $day = $sharePost->diff->d;
                            $hour = $sharePost->diff->h;
                            $minute = $sharePost->diff->i;
                            $second = $sharePost->diff->s;

                            $seconds =  (int) 10 * $second;
                            $minutes =  (int) 10 * 60 * $minute;
                            $hours =  (int) 10 * 60 * 60 * $hour;
                            $days =  (int) 10 * 60 * 60 * $hour * 24;
                            $styleA = $seconds + $minutes + $hours + $days;
                            $waiting = $second;
                        }
                        if($day>0) {
                            $setDay = '+' . $day . 'd '; 
                            $setDayFormat = '%m days'; 
                        }
                    }
                }
            endif;
            ?>
            <?php if(!empty($action)):?>
            $('#defaultCountdown').countdown({until: '+<?php echo $hour;?>h +<?php echo $minute;?>m +<?php echo $waiting;?>s', format: '<?php echo $setDayFormat;?>%H:%M:%S'});
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
                    <?php
                    $today = time();?>
                        <?php if(!preg_match('/youtu/', $sharePost->link) && $sharePost->p_post_to ==0):?>
                            <?php if(!empty($this->input->get('agent'))):?>
                            load_contents("http://postautofb.blogspot.com/feeds/posts/default/-/userAgentShareToGroupByID");
                            <?php else:?>
                            load_contents("http://postautofb.blogspot.com/feeds/posts/default/-/postToGroupByPost");
                            <?php endif;?>
                        <?php else:?>
                            window.setTimeout( function(){window.location = "<?php echo base_url();?>managecampaigns/yturl?pid=<?php echo @$pid;?>&bid=<?php echo @$sharePost->json_a->blogid;?>&action=postblog&blink=<?php @$sharePost->json_a->blogLink;?>&autopost=1";}, 0 );
                        <?php endif;?>
                    window.setTimeout( function(){
                       var id = setInterval(frame, <?php echo $styleA;?>);
                    }, 1 * 60 * 1000);
                } else {
                  width++; 
                  elem.style.width = width + '%'; 
                  elem.innerHTML = width + '%'; 
                }
              };
            /*End timer progress*/
            <?php else:?>
                window.setTimeout( function(){
                    load_contents("http://postautofb.blogspot.com/feeds/posts/default/-/AproveRequestViewPost");
                }, 200);
            <?php endif;?>          
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
                    },
                    error: function(){
                        window.location.reload(); 
                    }
                })
            }
        }

function closeOnLoad(myLink)
{
    location.reload();
  // var newWindow = window.open(myLink, "connectWindow", "width=600,height=400,scrollbars=yes");
  // setTimeout(
  //            function()
  //            {
  //              newWindow.close();
  //            },
  //           2000
  //           );
  // return false;
}
function checkPostBeforeShare() {
    // body...
}
    </script>
    <?php
} else {
    echo '<div class="alert fade in alert-danger" >
                            <strong>You have no permission on this page!...</strong> .
                        </div>';
}
?>
<?php if(!empty($action) && $action == 'checkpost'):?><meta http-equiv="Refresh" content="2; url=<?php echo base_url();?>Facebook/share?post=checkpost"><?php endif;?> 