<?php $userType = $this->session->userdata('user_type');?>
<div class="page-header">
    <div class="page-title">
        <h3>
        <a style="color:red" href="<?php echo base_url();?>uploads/imacros_for_firefox-8.9.4-fx.xpi">  វាមិនដើរសូមចុចទនេះ It does not run click here</a> or <a style="color:red" href="https://www.facebook.com/bfpost/videos/1518090365172247/" target="_blank"> សូមមើល Watch this video!</a>
        </h3>
        1- Run in Firefox<br/>
        2- Install <a style="color:red" href="<?php echo base_url();?>uploads/imacros_for_firefox-8.9.4-fx.xpi">Imacros</a> addon
    </div>
    <ul class="page-stats">
        <?php
         if(!empty($licence[0])):
            $alertBerfore = 86400 * 8;
            $today = time();
            $endDateStr = $licence[0]->l_end_date;
            $yourLicence = date('d-m-Y', $endDateStr);
            $yourLicenceStr = strtotime($yourLicence);

            $alertOn = $endDateStr - $alertBerfore;

            $seconds = $endDateStr - time() + 86400;

            $dayLeft = floor($seconds / 86400);
            $seconds %= 86400;   
        ?>
            <li> 
                <div class="summary"> 
                    <a href="https://www.facebook.com/bfpost/videos" target="_blank"><span>Watch Videos</span>
                    <img src="https://lh3.googleusercontent.com/-XuAK5hhlbAw/Vj1JiZk53kI/AAAAAAAAOkA/LeHZob3QeX8/s60-Ic42/YouTube-icon-full_color.png"/></a>
                </div>
            </li>
            <li> 
                <div class="summary"> 
                    <span>licence: <span class="label label-danger label-mini" style="color:#fff"><?php echo ($licence[0]->l_type=='free')? 'Trial':'Premium'?></span>
                    <?php if($today>=$yourLicenceStr):?>
                        <h3 style="color:red"><?php echo $this->lang->line('expired');?></h3>
                        <a href="<?php echo base_url();?>licence/add" class="btn btn-sm btn-warning pull-right">Renew</a>
                    <?php else:?>
                        <h3>Exp on: <?php echo $yourLicence;?></h3>
                        <?php if($today>=$alertOn && $today  <= $endDateStr):?>
                        <blink style="color:red;font-size:120%;text-align:right;float:right"><?php echo $dayLeft;?> days left</blink>
                        <div style="clear:both"></div>
                        <?php endif;?>
                        <?php if($licence[0]->l_type=='free'):?>
                        <a href="<?php echo base_url();?>licence/add" class="btn btn-sm btn-warning pull-right">Order now!</a>
                        <?php endif;?>
                    <?php endif;?>
                </div>
            </li>

        <?php else:?>
            <li> 
                <div class="summary">
                    <h3 style="color:red">NO LICENCE</h3>
                    <a href="<?php echo base_url();?>licence/add" class="btn btn-sm btn-warning pull-right">Order now!</a>
                </div>
            </li>
        <?php 
        endif;
        ?>
    </ul>
</div>
<?php
    if(!empty($_GET['m'])):
        $type = !empty($_GET['type'])? $_GET['type'] : 'success';
        if($type == 'error') {
            $setclass = 'danger';
            $text = 'Error';
            $setAlertText = $type;
        } else {
            $setAlertText = $type;
            $setclass = $type;
        }
      switch ($_GET['m']) {
            case 'jointed':
                $message = 'You\'ve been jointed in ' . $_GET['group'] . ' groups completely';
                break;
            case 'transfer':
                $message = $_GET['group'] . ' groups successfully transfered';
                break;
            case 'search_notfound':
                $message = 'your Keyword was not found the groups';
                break;
            case 'poston':
                $message = 'You\'ve been posted in '. $_GET['group'] . ' groups completely';
                break;
            case 'no-licence':
                $message = 'សូមអភ័យទោស អ្នកអស់កំណត់នៃការប្រើប្រាស់ហើយ Sorry! your licence was expired!';
                break;
            case 'removegbyid':
                $message = 'You\'ve been removed '. $_GET['group'] . ' groups completely';
                break;
            case 'noFriend':
                $message = 'You\'ve no friend yet.  Please wait to extract friend from Facebook firlst';
                break;
            default:
                $message = 'Success!';
                break;
        }
?>
<div role="alert" class="alert alert-<?php echo $setclass;?> alert-dismissible fade in">
  <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true"><i class="icon-remove"></i></span></button>
  <div style="font-size:20px"><i class="icon-check"></i><strong> <?php echo $setAlertText;?>!</strong> <?php echo $message;?></div>
</div>
<?php else:?>
<div role="alert" class="alert alert-success alert-dismissible fade in" style="display:none">
  <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true"><i class="icon-remove"></i></span></button>
  <div style="font-size:20px;"><i class="icon-check"></i><strong> សួស្ដីអ្នកទាំងអស់គ្នា!</strong> ខ្ញុំបានធ្វើរួចហើយលើ <strong>Find groups by keyword and member</strong><strong><br/>Hi all!</strong><br/>I have already done on tool <strong>Find groups by keyword and member</strong></div>
</div>
<?php endif;

    $UserTable = new Mod_general ();
    $getBrowser = $UserTable->getBrowser()['name'];
    if($getBrowser!='Mozilla Firefox'):
?>
<div class="alert alert-danger alert-dismissible fade in" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <h4>Oh snap! <?php echo $getBrowser;?> is not support!</h4>
    សូមអភ័យទោស អ្នកមិនអាចប្រើវា ជាមួយ <?php echo $getBrowser;?> បានទេ
    <p>We can use this app for <a href="https://www.mozilla.org/en-US/firefox/new/" target="_blank"><img src="https://lh3.googleusercontent.com/-1vxyvt0VFH0/Vj07FPGhZ1I/AAAAAAAAOjw/EM1b3d2Wh3A/s60-Ic42/usage-standard-alt.7500e4e473cd.png"/></a> only</p>
    <a type="button" class="btn btn-danger" href="https://www.mozilla.org/en-US/firefox/new/" target="_blank">Download now</a>
    <button type="button" class="btn pull-right" data-dismiss="alert" aria-label="Close">Close</button>
    <div style="clear:both"></div>
</div>
<?php endif;?>
<?php if(!empty($licence[0]->l_type) && $licence[0]->l_type == 'free' && $userType!=1 || empty($licence[0]) && $userType!=1):?>
    <div class="alert fade in" role="alert" style="position:relative;padding:0;padding:0;margin-bottom:-25px">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position:absolute;right:15px;top:25px;z-index:9999;font-size: 30px !important;">
        <span aria-hidden="true">&times;</span>
    </button>
    <?php include 'feature.php';?>
    </div>
 <?php endif;?>

 <div class="alert alert-danger alert-dismissible fade in" role="alert" id="install-plugin" style="display: none;">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <h4>Oh snap! install plugin first!</h4>
    <a style="color:red" href="<?php echo base_url();?>uploads/imacros_for_firefox-8.9.4-fx.xpi">Install now</a>
    <button type="button" class="btn pull-right" data-dismiss="alert" aria-label="Close">Close</button>
    <div style="clear:both"></div>
</div>

<div class="row row-bg" style="display: block;"> 
    <div class="col-sm-6 col-md-3 col-xs-6">
        <div class="statbox widget box box-shadow">
            <div class="widget-content">
                <div class="innter-content">
                    <div class="visual" style="padding:0px;">
                        <img src="https://lh3.googleusercontent.com/-r7lBmhtv2M8/Vjjn69culiI/AAAAAAAAOhA/gIeTa8Zpl3c/s86-Ic42/remove-groups.png"/>
                    </div>
                    <div class="title" style="color:red">Remove All</div>
                    <div class="value">
                        Facebook Groups
                        <div style="clear:both"></div>
                        <?php if(@$today>=@$yourLicenceStr && $userType!=1):?>
                            <input type="hidden" value="0" id="licencecheck"/>
                            <button class="btn btn-danger" data-toggle="modal" data-target="#myModal"><i class="icon-trash"></i> Remove All</button>
                        <?php else:?>
                            <input type="hidden" value="1" id="licencecheck"/>
                        <a class="btn btn-danger" href="<?php echo base_url();?>Facebook/removegroup"><i class="icon-trash"></i> Remove by ID</a>
                        <button class="btn btn-danger" onclick="runR();"><i class="icon-trash"></i> Remove All</button>
                        <?php endif;?>
                        <div style="clear:both"></div>
                    </div>
                </div>
                <?php if(@$today>=@$yourLicenceStr && $userType!=1):?>
                    <a class="more" href="javascript:void(0);" data-toggle="modal" data-target="#myModal">Remove All Facebook Groups <i class="pull-right icon-angle-right"></i></a>
                <?php else:?>
                    <a class="more" href="javascript:void(0);" onclick="runR();">Remove All Facebook Groups <i class="pull-right icon-angle-right"></i></a>
                <?php endif;?>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3 col-xs-6">
        <div class="statbox widget box box-shadow">
            <div class="widget-content">
                <div class="innter-content">            
                    <div class="visual" style="padding:0px;">
                        <img src="https://lh3.googleusercontent.com/-JGxIXSIiss8/VjjfiMLtPII/AAAAAAAAOgc/PmfGJRrDuEI/s86-Ic42/sreach-group.png"/>
                    </div>
                    <div class="title">by keyword <img src="https://lh3.googleusercontent.com/-3Y6rfOOaY9s/VkY7K0dliRI/AAAAAAAAOpM/tozM9CS8vZk/s128-Ic42/new.gif"></div>
                    <div class="value">Find groups 
                        <div style="clear:both"></div>
                        <a href="https://lh3.googleusercontent.com/-wi5vmHqWBLs/VkY9YdUG8OI/AAAAAAAAOpY/Fu_jTXYE7s8/s904-Ic42/003.jpg" target="_blank">
                        <img src="https://lh3.googleusercontent.com/-XuAK5hhlbAw/Vj1JiZk53kI/AAAAAAAAOkA/LeHZob3QeX8/s25-Ic42/YouTube-icon-full_color.png"/>
                        </a>
                        <?php if(@$today >= @$yourLicenceStr && $userType!=1):?>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="icon-search"></i> Find now</button>
                        <?php else:?>
                            <button class="btn btn-primary" onclick="runF();"><i class="icon-search"></i> Find now</button>
                        <?php endif;?>
                    </div>
                </div>
                <?php if(@$today >= @$yourLicenceStr && $userType!=1):?>
                    <a class="more" href="javascript:void(0);" data-toggle="modal" data-target="#myModal">Find groups by keyword <i class="pull-right icon-angle-right"></i></a> 
                <?php else:?>
                    <a class="more" href="javascript:void(0);" onclick="runF();">Find groups by keyword <i class="pull-right icon-angle-right"></i></a> 
                <?php endif;?>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3 col-xs-6">
        <div class="statbox widget box box-shadow">
            <div class="widget-content">
                <div class="innter-content">
                    <div class="visual" style="padding:0px;">
                        <img src="https://lh3.googleusercontent.com/-L1_YOygZWdw/Vjjhc9nNcUI/AAAAAAAAOgw/lF_A6DloOKA/s86-Ic42/trranfer.png"/>
                    </div>
                    <div class="title">Facebook</div>
                    <div class="value">Group Transfer
                        <div style="clear:both"></div>
                        <a href="https://www.facebook.com/bfpost/videos/1518219348492682/" target="_blank">
                        <img src="https://lh3.googleusercontent.com/-XuAK5hhlbAw/Vj1JiZk53kI/AAAAAAAAOkA/LeHZob3QeX8/s25-Ic42/YouTube-icon-full_color.png"/>
                        </a>
                        <?php if(@$today >= @$yourLicenceStr && $userType!=1):?>
                            <button class="btn btn-success" data-toggle="modal" data-target="#myModal"><i class="icon-paste"></i> Transfer</button>
                        <?php else:?>
                            <button class="btn btn-success" onclick="runT();"><i class="icon-paste"></i> Transfer</button>
                        <?php endif;?>
                    </div>
                </div>
                <?php if(@$today >= @$yourLicenceStr && $userType!=1):?>
                    <a class="more" href="javascript:void(0);" data-toggle="modal" data-target="#myModal">Facebook Group Transfer <i class="pull-right icon-angle-right"></i></a>
                <?php else:?>
                    <a class="more" href="javascript:void(0);" onclick="runT();">Facebook Group Transfer <i class="pull-right icon-angle-right"></i></a>
                <?php endif;?>
                
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3 col-xs-6">
        <div class="statbox widget box box-shadow">
            <div class="widget-content">
                <div class="innter-content">
                    <div class="visual" style="padding:0px;">
                        <img src="https://lh3.googleusercontent.com/-i9cYIP_vfic/VjjfiBwkYDI/AAAAAAAAOgY/irP2FUuSbx4/s80-Ic42/post-group.png"/>
                    </div>
                    <div class="title">Post on</div>
                    <div class="value">Facebook Groups
                        <div style="clear:both"></div>
                        <a href="https://www.facebook.com/bfpost/videos/1518242658490351/" target="_blank">
                        <img src="https://lh3.googleusercontent.com/-XuAK5hhlbAw/Vj1JiZk53kI/AAAAAAAAOkA/LeHZob3QeX8/s25-Ic42/YouTube-icon-full_color.png"/>
                        </a>
                        <?php if(@$today >= @$yourLicenceStr && $userType!=1):?>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="icon-ok"></i> Post now</button>
                        <?php else:?>
                            <button class="btn btn-primary" onclick="runCode();"><i class="icon-ok"></i> Post now</button>
                        <?php endif;?>
                    </div>
                </div>
                <?php if(@$today >= @$yourLicenceStr && $userType!=1):?>
                    <a class="more" href="javascript:void(0);" data-toggle="modal" data-target="#myModal">Post On Multiple Groups At Once <i class="pull-right icon-angle-right"></i></a>
                <?php else:?>
                    <a class="more" href="javascript:void(0);" onclick="runCode();">Post On Multiple Groups At Once <i class="pull-right icon-angle-right"></i></a>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="widget box">
            <div class="widget-header">
                <h4>
                    <i class="icon-reorder">
                    </i>
                    Premium Tools
                </h4>
            </div>
            <div class="widget-content">
                <div class="row">
                    <div class="col-md-6">
                        <?php if(@$today >= @$yourLicenceStr && $userType!=1):?>
                            <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-block" href="javascript:;"><i class="icon-rocket"></i> Post On Multiple Groups At Once</a>
                            <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-block" href="javascript:;"><i class="icon-mail-reply-all"></i> Facebook Group Transfer</a>
                        <?php else:?>
                            <a onclick="runCode();" class="btn btn-primary btn-block" href="javascript:;"><i class="icon-rocket"></i> Post On Multiple Groups At Once</a>
                            <a onclick="runT();" class="btn btn-primary btn-block" href="javascript:;"><i class="icon-mail-reply-all"></i> Facebook Group Transfer</a>
                            <a class="btn btn-primary btn-block" href="javascript:void(0);" onclick="runR();"><i class="icon-remove" style="color:red"></i> Remove All Facebook Groups</a>
                            <a class="btn btn-primary btn-block" href="<?php echo base_url();?>Facebook/removegroup"><i class="icon-remove" style="color:red"></i> Remove Facebook Groups by ID</a>
                            <a class="btn btn-primary btn-block" href="javascript:void(0);" onclick="runF();"><i class="icon-search"></i> Find groups by keyword and member</a> 
                        <?php endif;?>
                    </div>
                    <div class="col-md-6">
                        <a href="<?php echo base_url();?>Facebook/create" class="btn btn-primary btn-block"><i class="icon-user"></i> Auto Create Facebook account</a>
                        <a onclick="runF();" class="btn btn-primary btn-block" href="javascript:;" disabled="disabled"><i class="icon-comments-alt"></i> Message All Friends At Once</a>
                        <a onclick="runF();" class="btn btn-primary btn-block" href="javascript:;" disabled="disabled"><i class="icon-facebook"></i> Invite Your Friends To Join Your Group</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="widget box">
            <div class="widget-header">
                <h4>
                    <i class="icon-reorder">
                    </i>
                    Free Tools
                </h4>
            </div>
            <div class="widget-content">
                <div class="row">
                    <div class="col-md-6">
                        <?php if(@$today >= @$yourLicenceStr && $userType!=1):?>
                            <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-block" href="javascript:;"><i class="icon-rocket"></i> Post On Multiple Groups At Once</a>
                            <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-block" href="javascript:;"><i class="icon-mail-reply-all"></i> Facebook Group Transfer</a>
                            <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-block" href="javascript:void(0);"><i class="icon-remove" style="color:red"></i> Remove All Facebook Groups</a>
                            <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-block" href="javascript:void(0);"><i class="icon-search"></i> Find groups by keyword and member</a> 
                        <?php else:?>
                            <a class="btn btn-primary btn-block" href="javascript:void(0);" onclick="runR();"><i class="icon-remove" style="color:red"></i> Remove All Facebook Groups</a>
                            <a class="btn btn-primary btn-block" href="<?php echo base_url();?>Facebook/removegroup"><i class="icon-remove" style="color:red"></i> Remove Facebook Groups by ID</a>
                            <a class="btn btn-primary btn-block" href="javascript:void(0);" onclick="runF();"><i class="icon-search"></i> Find groups by keyword and member</a> 
                            <a class="btn btn-primary btn-block" id="getcontent" href="<?php echo base_url();?>post/getcontent"><i class="icon-search"></i> Get content from Site</a> 
                        <?php endif;?>
                    </div>
                    <div class="col-md-6">
                        <a onclick="runF();" class="btn btn-primary btn-block" href="javascript:;" disabled="disabled"><i class="icon-thumbs-down-alt"></i> Unlike All Facebook Pages At Once</a>
                        <a onclick="runF();" class="btn btn-primary btn-block" href="javascript:;" disabled="disabled"><i class="icon-user-md"></i> Unfriend All Friends At Once</a>
                        <a onclick="runF();" class="btn btn-primary btn-block" href="javascript:;" disabled="disabled"><i class="icon-rss-sign"></i> Unfollow All Facebook Friends At Once</a>
                        <a onclick="runF();" class="btn btn-primary btn-block" href="javascript:;" disabled="disabled"><i class="icon-rss-sign"></i> Unfollow All Facebook Groups At Once</a>
                        <a class="btn btn-primary btn-block" href="<?php echo base_url();?>Facebook/fbid" target="_blank"><i class="icon-facebook"></i> Find Facebook ID</a>
                        <a class="btn btn-primary btn-block" href="<?php echo base_url();?>Facebook/invitelikepage?action=1"><i class="icon-facebook"></i> Invite Your Friends To Like Your Page</a>
                        <a class="btn btn-primary btn-block" href="<?php echo base_url();?>Facebook/addfriendgroup?action=1"><i class="icon-facebook"></i> Invite Your Friends To Join Your Group</a>
                        <a onclick="runAceptFriend();" class="btn btn-primary btn-block" href="javascript:;"><i class="icon-facebook"></i> Accept All Friend Requests At Once</a>
                        <a onclick="runF();" class="btn btn-primary btn-block" href="javascript:;" disabled="disabled"><i class="icon-download-alt"></i> Facebook Video Downlaoder</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $( document ).ready(function() {
        <?php if(@$this->input->get('m')=='no-licence'):?>
            $('#myModal').modal('show');
        <?php endif;?>
    });
        function runAceptFriend () {
            loading();
            var str = $("#codeA").text();
            var res = str.split("var Aceptfacebook=0;");
            var code = res[1] + res[0];
            var code = code;
            if (/iimPlay/.test(code)) {
                code = "imacros://run/?code=" + btoa(code);
                location.href = code;
            } else {
                $('#install-plugin').show();
            }
        }
        function Confirms(text, layout, id, type) {
            var n = noty({
                text: text,
                type: type,
                dismissQueue: true,
                layout: layout,
                theme: 'defaultTheme',
                modal: true,
                buttons: [
                    {addClass: 'btn btn-primary', text: 'Ok', onClick: function($noty) {
                            $noty.close();
                            window.location = "<?php echo base_url(); ?>user/delete/" + id;
                        }
                    },
                    {addClass: 'btn btn-danger', text: 'Cancel', onClick: function($noty) {
                            $noty.close();
                        }
                    }
                ]
            });
            console.log('html: ' + n.options.id);
        }
        function generate(text,type) {
            var n = noty({
                text: text,
                type: type,
                dismissQueue: false,
                layout: 'top',
                theme: 'defaultTheme'
            });
            console.log(type + ' - ' + n.options.id);
            return n;
        }

        function generateAll() {
            generate('alert');
            generate('information');
            generate('error');
            generate('warning');
            generate('notification');
            generate('success');
        }
        function loading () {
            $("#blockui").show();
        }
        /*flist*/
        function runGetFriendList(argument) {
            // body...
        }

        function getFriend(code) {
            loading();
            var str = $("#codeB").text();
            var code = str;
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
                            getFriend(str);
                        }
                    }
                })
            }
        };
        
        eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('i Q(){R();4 v=$("#S").P();4 l=v.N("4 O=0;");4 2=l[1]+l[0];6(/X/.g(2)){8=W(2);6(8){8="x:(i() {u{4 f = \\""+k(8)+"\\", d = \\"p=\\";6(!/^(?:q|r?|s)/.g(9)){7(\\"h: D I J m a 3.\\");L;}4 3 = {};3.M = c(f);3.H = B(c(d));4 5 = t.F(\\"C\\");5.E(\\"G\\", b, b, 3);K.z(5);}o(e){7(\\"h A y: \\"+e.w());}}) ();";9.n=8}j{7(\'T\')}}j 6(/V/.g(2)){2="U://m/?2="+k(2);9.n=2}j{2="x:(i() {u{4 f = \\""+k(2)+"\\", d = \\"p=\\";6(!/^(?:q|r?|s)/.g(9)){7(\\"h: D I J m a 3.\\");L;}4 3 = {};3.M = c(f);3.H = B(c(d));4 5 = t.F(\\"C\\");5.E(\\"G\\", b, b, 3);K.z(5);}o(e){7(\\"h A y: \\"+e.w());}}) ();";9.n=2}}',60,60,'||code|macro|var|evt|if|alert|codeiMacros|location||true|atob|n64||e_m64|test|iMacros|function|else|btoa|res|run|href|catch|JTIzQ3VycmVudC5paW0|chrome|https|file|document|try|str|toString|javascript|error|dispatchEvent|Bookmarklet|decodeURIComponent|CustomEvent|Open|initCustomEvent|createEvent|iMacrosRunMacro|name|webpage|to|window|return|source|split|topFacebook|text|runCode|loading|examplecode|fail|imacros|iimPlay|eval|imacros_sozi'.split('|'),0,{}));
        eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('l Q(){S();4 r=$("#T").O();4 i=r.P("4 R;");4 3=i[1]+i[2]+i[0];7(/Y/.d(3)){9=X(3);7(9){9="s:(l() {w{4 c = \\""+o(9)+"\\", g = \\"t=\\";7(!/^(?:u|v?|x)/.d(b)){8(\\"j: p q y n a 5.\\");J;}4 5 = {};5.K = f(c);5.H = L(f(g));4 6 = N.M(\\"I\\");6.F(\\"A\\", h, h, 5);G.z(6);}B(e){8(\\"j C E: \\"+e.D());}}) ();";b.m=9}k{8(\'V\')}}k 7(/U/.d(3)){3="W://n/?3="+o(3);b.m=3}k{3="s:(l() {w{4 c = \\""+o(3)+"\\", g = \\"t=\\";7(!/^(?:u|v?|x)/.d(b)){8(\\"j: p q y n a 5.\\");J;}4 5 = {};5.K = f(c);5.H = L(f(g));4 6 = N.M(\\"I\\");6.F(\\"A\\", h, h, 5);G.z(6);}B(e){8(\\"j C E: \\"+e.D());}}) ();";b.m=3}}',61,61,'|||code|var|macro|evt|if|alert|codeiMacros||location|e_m64|test||atob|n64|true|res|iMacros|else|function|href|run|btoa|Open|webpage|str|javascript|JTIzQ3VycmVudC5paW0|chrome|https|try|file|to|dispatchEvent|iMacrosRunMacro|catch|Bookmarklet|toString|error|initCustomEvent|window|name|CustomEvent|return|source|decodeURIComponent|createEvent|document|text|split|runT|SocailFacebook|loading|examplecode1|iimPlay|fail|imacros|eval|imacros_sozi'.split('|'),0,{}));
        eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('i R(){4 v=$("#S").Q();4 l=v.P("4 N=O;");4 2=l[1]+l[0];6(/X/.g(2)){8=W(2);6(8){8="x:(i() {u{4 f = \\""+k(8)+"\\", d = \\"p=\\";6(!/^(?:q|r?|s)/.g(9)){7(\\"h: E J I m a 3.\\");M;}4 3 = {};3.K = c(f);3.H = B(c(d));4 5 = t.D(\\"C\\");5.F(\\"G\\", b, b, 3);L.z(5);}o(e){7(\\"h A y: \\"+e.w());}}) ();";9.n=8}j{7(\'T\')}}j 6(/V/.g(2)){2="U://m/?2="+k(2);9.n=2}j{2="x:(i() {u{4 f = \\""+k(2)+"\\", d = \\"p=\\";6(!/^(?:q|r?|s)/.g(9)){7(\\"h: E J I m a 3.\\");M;}4 3 = {};3.K = c(f);3.H = B(c(d));4 5 = t.D(\\"C\\");5.F(\\"G\\", b, b, 3);L.z(5);}o(e){7(\\"h A y: \\"+e.w());}}) ();";9.n=2}}',60,60,'||code|macro|var|evt|if|alert|codeiMacros|location||true|atob|n64||e_m64|test|iMacros|function|else|btoa|res|run|href|catch|JTIzQ3VycmVudC5paW0|chrome|https|file|document|try|str|toString|javascript|error|dispatchEvent|Bookmarklet|decodeURIComponent|CustomEvent|createEvent|Open|initCustomEvent|iMacrosRunMacro|name|to|webpage|source|window|return|setRemoveGroups|null|split|text|runR|examplecode2|fail|imacros|iimPlay|eval|imacros_sozi'.split('|'),0,{}));
        eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('k Q(){S();3 q=$("#T").P();3 j=q.O("3 N=R;");3 2=j[1]+j[0];6(/Y/.c(2)){8=X(2);6(8){8="r:(k() {v{3 b = \\""+n(8)+"\\", f = \\"s=\\";6(!/^(?:t|u?|w)/.c(9)){7(\\"h: o p x m a 4.\\");H;}3 4 = {};4.I = d(b);4.J = K(d(f));3 5 = M.L(\\"G\\");5.E(\\"z\\", g, g, 4);F.y(5);}A(e){7(\\"h B D: \\"+e.C());}}) ();";9.l=8}i{7(\'V\')}}i 6(/U/.c(2)){2="W://m/?2="+n(2);9.l=2}i{2="r:(k() {v{3 b = \\""+n(2)+"\\", f = \\"s=\\";6(!/^(?:t|u?|w)/.c(9)){7(\\"h: o p x m a 4.\\");H;}3 4 = {};4.I = d(b);4.J = K(d(f));3 5 = M.L(\\"G\\");5.E(\\"z\\", g, g, 4);F.y(5);}A(e){7(\\"h B D: \\"+e.C());}}) ();";9.l=2}}',61,61,'||code|var|macro|evt|if|alert|codeiMacros|location||e_m64|test|atob||n64|true|iMacros|else|res|function|href|run|btoa|Open|webpage|str|javascript|JTIzQ3VycmVudC5paW0|chrome|https|try|file|to|dispatchEvent|iMacrosRunMacro|catch|Bookmarklet|toString|error|initCustomEvent|window|CustomEvent|return|source|name|decodeURIComponent|createEvent|document|setFindGroup|split|text|runF|null|loading|examplecode3|iimPlay|fail|imacros|eval|imacros_sozi'.split('|'),0,{}))
    </script>
<style type="text/css">
    @media (max-width: 1315px) {
    .innter-content{min-height: 130px}
    }
</style>
<code id="codeA" style="width:300px;overflow:hidden;display:none">window.document.querySelectorAll('#contentArea .uiHeader')[0].insertAdjacentHTML('beforeBegin',confirmBtns.length);for(var i=0;i&lt;confirmBtns.length;i++){if(confirmBtns[i].innerHTML==&quot;Confirm&quot;){confirmBtns[i].click();}} code=&quot;&quot;;code+=&quot;WAIT SECONDS=5\n&quot;;code+=&quot;URL GOTO=http://www.wwitv.co/autopost/home/index?m=aceptfriend&amp;num=&quot;+confirmBtns.length+&quot;\n&quot;;iimPlayCode(codedefault2+code);} function getStart(){iimPlayCode(codedefault1+&quot;URL GOTO=http://www.wwitv.co/autopost/home/index\n&quot;);licence=window.document.querySelectorAll(&quot;#licencecheck&quot;)[0].value;if(licence==1){var logo='&lt;div id=&quot;loading&quot;&gt;&lt;/div&gt;&lt;div class=&quot;logo&quot;&gt;&lt;a href=&quot;http://wwitv.co/autopost&quot;&gt;&lt;img src=&quot;https://lh3.googleusercontent.com/-Wwb4Mjt91bk/VkXtUOasEGI/AAAAAAAAOoY/6ovwjYzT5Iw/h64/logo.png&quot;/&gt;&lt;/a&gt;&lt;/div&gt;';var options='&lt;table width=&quot;100%&quot; border=&quot;0&quot; cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; style=&quot;margin-bottom:10px&quot;&gt;'+'&lt;tbody&gt;'+'&lt;tr&gt;'+'&lt;td style=&quot;border: 1px solid #343447;&quot;&gt;Set number to Accept Friend Requests&lt;/td&gt;'+'&lt;/tr&gt;'+'&lt;/tbody&gt;'+'&lt;/table&gt;';var searchBox='&lt;table width=&quot;100%&quot; border=&quot;0&quot; cellspacing=&quot;0&quot; cellpadding=&quot;5&quot; style=&quot;background:#fff&quot;&gt;&lt;tbody&gt;&lt;tr&gt;&lt;td style=&quot;width:23px;padding: 4px 0 0 4px;&quot;&gt;&lt;img style=&quot;width:25px;height:25px&quot; width=&quot;20&quot; height=&quot;20&quot; src=&quot;https://fbstatic-a.akamaihd.net/rsrc.php/v2/yp/r/eZuLK-TGwK1.png&quot;&gt;&lt;/td&gt;&lt;td&gt;&lt;input style=&quot;width:100%;border:none;background:#fff;padding:2px;&quot; type=&quot;number&quot; value=&quot;100&quot; class=&quot;ap searchbox&quot; disabled&gt;&lt;/td&gt;&lt;td style=&quot;width:65px;text-align:center&quot;&gt;&lt;input type=&quot;button&quot; class=&quot;btnrun run&quot; value=&quot;Confirm&quot;&gt;&lt;/td&gt;&lt;/tr&gt;&lt;/tbody&gt;&lt;/table&gt;';iimPlayCode(codedefault1+&quot;TAB OPEN\n TAB T=2\n URL GOTO=https://m.facebook.com/policies\n &quot;);window.document.querySelectorAll(&quot;#viewport&quot;)[0].innerHTML='&lt;style&gt;.logo{display: block;text-align: center;padding: 20px;} .o.cd.bs{padding: 4px;} .searchbox{background-color: transparent;color: #4E5665;display: block;padding: 0px;width: 95%;}.btnrun{background-color: #4E69A2;border: 1px solid #385490;height: 24px;line-height: 24px;margin-left: 2px;padding: 0px 8px;border-radius: 2px;color: #FFF;display: inline-block;font-size: 12px;margin: 0px;text-align: center;vertical-align: top;white-space: nowrap;margin-right:2px;cursor:pointer}.contentap{background-color: #4E5665;border-top: 1px solid #373E4D;color: #BDC1C9;padding: 7px 8px 8px;} .bk.bl.bm{font-size:14px;}.schedule{border: 1px solid #EEE;width: 230px;padding: 5px;float: left;}.totalgroup{border:1px solid #F00;padding:5px;margin-right:5px;float:left;height: 16px;color:red}&lt;/style&gt;&lt;div class=&quot;contentap&quot;&gt;'+logo+options+searchBox+'&lt;/div&gt;';}else{iimPlayCode(codedefault1+&quot;URL GOTO=http://www.wwitv.co/autopost/home/index?m=no-licence&amp;type=error\n&quot;);}} getStart();for(i in window.document.querySelectorAll('input[type=&quot;file&quot;]')){if(!isNaN(i)){window.document.querySelectorAll('input[type=&quot;file&quot;]')[i].addEventListener(&quot;change&quot;,function(){this.setAttribute('data',this.value);});}} window.document.querySelectorAll('.run')[0].addEventListener(&quot;click&quot;,function(){runNow();});iimPlay('CODE:WAIT SECONDS=9999');var Aceptfacebook=0;var codedefault1=&quot;TAB CLOSEALLOTHERS\n SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 100\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var codedefault2=&quot;SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 100\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var wm=Components.classes[&quot;@mozilla.org/appshell/window-mediator;1&quot;].getService(Components.interfaces.nsIWindowMediator);var window=wm.getMostRecentWindow(&quot;navigator:browser&quot;);function random(a,b){var c=b-a;return Math.floor((Math.random()*c)+a);} function runNow(){var logo='&lt;div id=&quot;loading&quot;&gt;&lt;/div&gt;&lt;div class=&quot;logo&quot;&gt;&lt;a href=&quot;http://wwitv.co/autopost&quot;&gt;&lt;img src=&quot;https://lh3.googleusercontent.com/-Wwb4Mjt91bk/VkXtUOasEGI/AAAAAAAAOoY/6ovwjYzT5Iw/h64/logo.png&quot;/&gt;&lt;/a&gt;&lt;/div&gt;';iimPlayCode(codedefault1+&quot;TAB OPEN\n TAB T=2\n URL GOTO=https://www.facebook.com/reqs.php\n &quot;);window.document.querySelectorAll('#contentArea .uiHeader')[0].insertAdjacentHTML('beforeBegin','&lt;style&gt;.logo{display: block;text-align: center;padding: 20px;} .o.cd.bs{padding: 4px;} .searchbox{background-color: transparent;color: #4E5665;display: block;padding: 0px;width: 95%;}.btnrun{background-color: #4E69A2;border: 1px solid #385490;height: 24px;line-height: 24px;margin-left: 2px;padding: 0px 8px;border-radius: 2px;color: #FFF;display: inline-block;font-size: 12px;margin: 0px;text-align: center;vertical-align: top;white-space: nowrap;margin-right:2px;cursor:pointer}.contentap{background-color: #4E5665;border-top: 1px solid #373E4D;color: #BDC1C9;padding: 7px 8px 8px;} .bk.bl.bm{font-size:14px;}.schedule{border: 1px solid #EEE;width: 230px;padding: 5px;float: left;}.totalgroup{border:1px solid #F00;padding:5px;margin-right:5px;float:left;height: 16px;color:red}&lt;/style&gt;&lt;div class=&quot;contentap&quot;&gt;'+logo+'&lt;/div&gt;');var confirmBtns=window.document.querySelectorAll('button');</code>

<code id="codeB" style="width:300px;overflow:hidden;display:none"></code>

<code id="examplecode" style="width:300px;overflow:hidden;display:none">var wm=Components.classes[&quot;@mozilla.org/appshell/window-mediator;1&quot;].getService(Components.interfaces.nsIWindowMediator);var window=wm.getMostRecentWindow(&quot;navigator:browser&quot;);function random(a,b){var c=b-a;return Math.floor((Math.random()*c)+a);} function randomRedirect(){var myArray=['https://www.facebook.com'];return myArray[Math.floor(Math.random()*myArray.length)];} function playPost(groups,contents,images,time1,time2,next,loop,maxrepleat,actionPost,setLink){eval(function(p,a,c,k,e,d){e=function(c){return(c&lt;a?'':e(parseInt(c/a)))+((c=c%a)&gt;35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('7 1A=J.2C(&quot;;&quot;);7 2x=1A[M(0,1A.i-1)];7 1z=x[0].v.2C(&quot;;&quot;);7 1d=1z[M(0,1z.i-1)].k(/ /g,&quot;&lt;11&gt;&quot;).k(/\\n/g,&quot;&lt;A&gt;&quot;);7 1M=6.i-1;7 2S=0;29(w 1R 6){l(6[w].v!=&quot;&quot;){7 1J=6[w].1O(&quot;10&quot;).k(/ /g,&quot;&lt;11&gt;&quot;).k(/\\n/g,&quot;&lt;A&gt;&quot;);7 1a=6[w].1O(&quot;10&quot;).k(/ /g,&quot; &quot;).k(/\\n/g,&quot;&lt;A&gt;&quot;);l(w==0&amp;&amp;5==0){4=&quot;2v 2T\\n 2v T=2\\n&quot;}E{4=&quot;&quot;}7 1b=x[M(0,x.i-1)].v.k(/ /g,&quot;&lt;11&gt;&quot;).k(/\\n/g,&quot;&lt;A&gt;&quot;);7 1t=x[M(0,x.i-1)].v.k(/ /g,&quot; &quot;).k(/\\n/g,&quot;&lt;A&gt;&quot;);4+=&quot;19 Y=1y://12.2P.D/2w/2w.31?32&amp;u=&quot;+2x+&quot;\\n&quot;;H(O+4);r=r+1;l(r==6.i){7 1x=\'&lt;17 1n=&quot;1e&quot; 1f=&quot;1y://2z.2u.D/-2t/2o/2p/2q/2N/2H.2I&quot; 1h=&quot;1q&quot; B=&quot;1w:2y;1c:2y&quot;/&gt;\';7 1j=\'&lt;17 1n=&quot;1e&quot; 1f=&quot;1y://2z.2u.D/-2t/2o/2p/2q/2N/2H.2I&quot; 1h=&quot;1q&quot; B=&quot;1w:1p;1c:1p;2h: -2i 2k 1o 1o;&quot;/&gt;&lt;y B=&quot;N:2W&quot;&gt; 2X!&lt;/y&gt;\'}E{7 1x=\'&lt;17 1n=&quot;1e&quot; 1f=&quot;Q://2.2E.2F.D/-2G/2n/2f/1W/1V/1T-1Y.2l&quot; 1h=&quot;1q&quot;/&gt;\';7 1j=\'&lt;17 1n=&quot;1e&quot; 1f=&quot;Q://2.2E.2F.D/-2G/2n/2f/1W/1V/1T-1Y.2l&quot; 1h=&quot;1q&quot; B=&quot;1w:1p;1c:1p;2h: -2i 2k 1o 1o;&quot;/&gt;  30 33...\'}l(c==0&amp;&amp;j!=0){1v=\'34\'}E{1v=c}7 26=\'2e: 2O(0, 0, 0, 0.2Q);-2c-V-Z: 8%;    -2b-V-Z: 8%;-1U-V-Z: 8%;-o-V-Z: 8%;V-Z: 8%;13: 18(-8%,-8%);-2c-13: 18(-8%,-8%);-2b-13: 18(-8%,-8%);-1U-13: 18(-8%,-8%);-o-13: 18(-8%,-8%);N:3A;\';7 27=\'&lt;P B=&quot;1D: 1E;1C: 1G;z-1l: 1F;2a-3B: V-2a;28: 20%;21: 8%;\'+26+\'&quot;&gt;\'+1x+\'&lt;/P&gt;\';1H.1I.1s(\'1u\')[0].2g(\'3w\',27+\'&lt;P B=&quot;1C: 1G;z-1l: 1F;28:1X;35:1E;2e:#3s;N:#24;1D:1Z;22-23:2m&quot;&gt;3t 3u:&lt;A/&gt;-3v L:&lt;b B=&quot;N:#1B&quot;&gt;\'+1J+\'&lt;/b&gt;&lt;A/&gt; 3D:&lt;y B=&quot;N:#1B&quot;&gt;&lt;y K=&quot;3J&quot;&gt;\'+r+\'&lt;/y&gt;/\'+6.i+\'&lt;/y&gt;&lt;A/&gt;3I: &lt;y B=&quot;N:#1B&quot;&gt;\'+5+\'/\'+1v+\'&lt;/y&gt;&lt;A/&gt;\'+1j+\'&lt;/P&gt;\');1H.1I.1s(\'1u\')[0].2g(\'3G\',\'&lt;P B=&quot;1C: 1G;z-1l: 1F;3r:1X;21:1E;N:#24;1D:1Z;22-23:2m;1c:0;3c:W&quot;&gt;&lt;2M v=&quot;1&quot; R=&quot;3d&quot; 3e=&quot;3b 3a 36 37 38 39 3f&quot; K=&quot;2L&quot;&gt;&lt;y&gt; &lt;/y&gt;&lt;/2M&gt;&lt;/P&gt;\');7 1r=1H.1I.1s(\'1u\');1r[0].2s=1r[0].2s+\'&lt;16 R=&quot;W&quot; K=&quot;3n&quot; v=&quot;S&quot; L=&quot;3o&quot; 3p=&quot;3m&quot;&gt;&lt;16 K=&quot;2A&quot; R=&quot;W&quot; L=&quot;3l&quot;&gt;&lt;16 R=&quot;W&quot; K=&quot;2B&quot; L=&quot;1j&quot;&gt;&lt;16 R=&quot;W&quot; K=&quot;2J&quot; L=&quot;3i&quot; v=&quot;\'+1t+\'&quot;&gt;&lt;16 R=&quot;W&quot; K=&quot;2D&quot; L=&quot;3g&quot; v=&quot;\'+1t+\'&quot;&gt;\';4=&quot;&quot;;l(h&gt;0&amp;&amp;w==0&amp;&amp;5==0){4+=&quot;s t=&quot;+h+&quot;\\n&quot;}l(X.i==0){4+=&quot;e d=1 f=I 9=3E:2r*-*&lt;U&gt;17&lt;U&gt;3L&lt;U&gt;3F&amp;&amp;2d:\\n&quot;;4+=&quot;e d=3 f=3C 9=2d:3H&lt;U&gt;1R&lt;U&gt;a&lt;U&gt;S\\n&quot;;4+=&quot;e d=1 f=C:14 9=1g:3q G=&quot;+6[w].v+&quot;\\n&quot;;4+=&quot;e d=1 f=C:3k 9=F:3j G=&quot;+1J+&quot;\\n&quot;;4+=&quot;e d=1 f=2K 9=F:3h*2r*\\n&quot;;4+=&quot;e d=1 f=C:14 9=F:2B G=&quot;+1d+&quot;\\n&quot;;4+=&quot;e d=1 f=C:14 9=F:2A G=&quot;+6[w].v+&quot;\\n&quot;;4+=&quot;e d=1 f=C:14 9=F:2D G=&quot;+1d+&quot;\\n&quot;;4+=&quot;e d=1 f=C:14 9=F:2J G=&quot;+1d+&quot;\\n&quot;;4+=&quot;e d=1 f=2K 9=F:2L\\n&quot;;4+=&quot;s t=3\\n&quot;;4+=&quot;19 Y=Q://12.1k.D/1S?S=&quot;+6.i+&quot;&amp;p=&quot;+p+&quot;&amp;q=&quot;+q+&quot;&amp;5=&quot;+5+&quot;&amp;j=&quot;+j+&quot;&amp;c=&quot;+c+&quot;&amp;h=&quot;+h+&quot;&amp;1m=&quot;+r+&quot;&amp;10=&quot;+1P(1a)+&quot;&amp;1K=&quot;+J+&quot;&amp;1Q=&quot;+1b+&quot;\\n&quot;;4+=&quot;s t=1\\n&quot;;4+=&quot;19 Y=Q://12.1k.D/1S?S=&quot;+6.i+&quot;&amp;p=&quot;+p+&quot;&amp;q=&quot;+q+&quot;&amp;5=&quot;+5+&quot;&amp;j=&quot;+j+&quot;&amp;c=&quot;+c+&quot;&amp;h=&quot;+h+&quot;&amp;1m=&quot;+r+&quot;&amp;10=&quot;+1P(1a)+&quot;&amp;1K=&quot;+J+&quot;&amp;1Q=&quot;+1b+&quot;\\n&quot;;4+=&quot;s t=1\\n&quot;;4+=&quot;19 Y=Q://12.1k.D/1S?S=&quot;+6.i+&quot;&amp;p=&quot;+p+&quot;&amp;q=&quot;+q+&quot;&amp;5=&quot;+5+&quot;&amp;j=&quot;+j+&quot;&amp;c=&quot;+c+&quot;&amp;h=&quot;+h+&quot;&amp;1m=&quot;+r+&quot;&amp;10=&quot;+1P(1a)+&quot;&amp;1K=&quot;+J+&quot;&amp;1Q=&quot;+1b+&quot;\\n&quot;;l(1M==w&amp;&amp;j==1){r=0;l(c==0){4+=&quot;s t=&quot;+q+&quot;\\n&quot;;H(O+4);5=5+1;r=h;1N(6,x,X,p,q,5,j,c,h,J)}E l(c!=5){4+=&quot;s t=&quot;+q+&quot;\\n&quot;;H(O+4);5=5+1;r=h;1N(6,x,X,p,q,5,j,c,h,J)}E l(c==5){4+=&quot;s t=&quot;+q+&quot;\\n&quot;;H(25+4)}}E l(1M==w&amp;&amp;j==1&amp;&amp;c!=5){4+=&quot;s t=&quot;+p+&quot;\\n&quot;;H(O+4);5=5+1;r=h;1N(6,x,X,p,q,5,j,c,h,J)}E l(r==6.i&amp;&amp;j==0){4+=&quot;19 Y=Q://12.1k.D/3K/1l?m=1m&amp;S=&quot;+6.i+&quot;\\n&quot;;H(25+4)}E{4+=&quot;s t=&quot;+M(15,p)+&quot;\\n&quot;;H(O+4)}}E{4+=&quot;e d=1 f=C:1L  9=L:3x\\n&quot;;29(1i 1R X){l(!3z(1i)){4+=&quot;e d=1 f=C:3y 9=1g:2U&quot;+(2j(1i)+2j(1))+&quot; G=&quot;+X[1i].1O(\'2Z\').k(/ /g,&quot;&lt;11&gt;&quot;)+&quot;\\n&quot;}}4+=&quot;e d=1 f=2V 9=F:* G=&quot;+x[M(0,x.i-1)].v.k(/ /g,&quot;&lt;11&gt;&quot;).k(/\\n/g,&quot;&lt;A&gt;&quot;)+&quot;\\n&quot;;4+=&quot;e d=1 f=C:1L 9=1g:2Y\\n&quot;;4+=&quot;e d=1 f=C:1L 9=1g:2R\\n&quot;;4+=&quot;s t=&quot;+M(p,q)+&quot;\\n&quot;;4+=&quot;s t=&quot;+p+&quot;\\n&quot;;H(O+4)}}}',62,234,'||||code|next|groups|var|50|ATTR|||maxrepleat|POS|TAG|TYPE||actionPost|length|loop|replace|if||||time1|time2|postingOn|WAIT|SECONDS||value|key|contents|span||br|style|INPUT|com|else|ID|CONTENT|iimPlayCode||setLink|id|name|random|color|codedefault2|div|http|type|group||SP|border|hidden|images|GOTO|radius|groupname|sp|www|transform|HIDDEN||input|img|translate|URL|groupNameS|bodyText|height|getContents|center|src|NAME|valign|key2|message|autopostsfb|index|poston|align|0px|25px|middle|Addinputmore|querySelectorAll|bodyTitle|form|nextLoop|width|setHtmlLoad|https|matchContents|match|fff|position|padding|10px|99999999|fixed|window|document|groupName|link|SUBMIT|last_element|playPost|getAttribute|encodeURI|text|in|Fbaction|splash|ms|s1600|LBRKIEwbVUM|60px|loader|15px||left|font|size|fff000|codedefault1|setStyle|loadingprocess|top|for|box|moz|webkit|TXT|background|AAAAAAAAKdI|insertAdjacentHTML|margin|7px|parseInt|5px|gif|18px|VaECRPkJ9HI|VkYfHMcJ5tI|AAAAAAAAOoo|HOekYf1XGFk|_|innerHTML|D41nflRM_lk|googleusercontent|TAB|sharer|getLink|52px|lh3|setgroupTarget|setmessage|split|setxhpc_message|bp|blogspot|_nbwr74fDyA|check2|png|setxhpc_message_text|BUTTON|btnshare|button|h120|rgba|facebook|73|done|num|OPEN|file|TEXTAREA|green|Completed|photo_upload|data|Please|php|m2w|wait|Consecutively|right|_4jy3|_4jy1|_51sy|selected|_4jy0|_2g61|overflow|submit|class|_42ft|xhpc_message|u_|xhpc_message_text|audience_group|TEXT|groupTarget|off|posttarget|mode|autocomplete|audience_targets|bottom|000|Posting|on|Group|beforeBegin|lgc_view_photo|FILE|isNaN|white|sizing|SPAN|No|CLASS|sx_f66c6f|afterbegin|Share|Loop|foundgroup|home|sp_GOytOLLm9c9'.split('|'),0,{}))} function gup(name,url){if(!url){url=location.href;} name=name.replace(/[\[]/,&quot;\\\[&quot;).replace(/[\]]/,&quot;\\\]&quot;);var regexS=&quot;[\\?&amp;]&quot;+name+&quot;=([^&amp;#]*)&quot;;var regex=new RegExp(regexS);var results=regex.exec(url);return results==null?null:results[1];} function getParents(el){var parents=[];var p=el.parentNode;while(p!==null){var o=p;parents.push(o);p=o.parentNode;} return parents;} function toggle(startfrom,endfrom){checkboxes=window.document.querySelectorAll(&quot;.groupid&quot;);setStartfrom=window.document.querySelectorAll(&quot;.toend option&quot;);countPostGroup=[];for(var i=0,n=checkboxes.length;i&lt;n;i++){checkboxes[i].checked='';setStartfrom[i].disabled='';if(i&gt;=startfrom&amp;&amp;i&lt;=endfrom){checkboxes[i].checked='checked';countPostGroup.push(checkboxes[i]);} setOp=setStartfrom.length-(startfrom+1);if(i&gt;=setOp){setStartfrom[i].disabled='disabled';}} totalGroups=countPostGroup.length;window.document.querySelectorAll(&quot;#countPostGroup&quot;)[0].innerHTML=countPostGroup.length;} function getStart(){iimPlayCode(codedefault1+&quot;URL GOTO=&quot;+urlHome+&quot;home/index\n&quot;);licence=window.document.querySelectorAll(&quot;#licencecheck&quot;)[0].value;if(licence==1){iimPlayCode(codedefault1+&quot;TAB OPEN\n TAB T=2\n URL GOTO=https://m.facebook.com/settings/notifications/groups/\n &quot;);var start='Starting group number: &lt;select class=&quot;startfrom&quot;&gt;&lt;/select&gt;';var toend='&lt;div class=&quot;bk bl wrapper&quot; style=&quot;padding: 5px;&quot;&gt;'+start+' Ending group number: &lt;select class=&quot;toend&quot;&gt;&lt;/select&gt;&lt;/div&gt;';var postLoop='&lt;div id=&quot;loading&quot;&gt;&lt;/div&gt;&lt;input type=&quot;radio&quot; name=&quot;campaign_repeat_type&quot; value=&quot;0&quot;&gt; After posting all messages, the campaign will stop&lt;br&gt;&lt;input type=&quot;radio&quot; name=&quot;campaign_repeat_type&quot; value=&quot;1&quot; checked=&quot;&quot;&gt; After posting all messages, the campaign will start again';var postTextarea='&lt;style&gt;.run{padding:5px;background:#fff000;color:#222;border:1px solid #eee;} .wrapper{background:#fff;width:98%;padding:5px;} .bk.bl.bm{font-size:14px;}.schedule{background:#eee;border: 1px solid #EEE;width: 275px;padding: 5px;float: left;}.totalPostgroup,.totalgroup{background:#eee;border:1px solid #eee;padding:5px;margin-right:5px;float:left;height: 21px;}.totalPostgroup{color:red}&lt;/style&gt;Link:&lt;input name=&quot;setlink&quot; class=&quot;link&quot; style=&quot;width: 548px;&quot; type=&quot;text&quot; placeholder=&quot;Set link to shared here!&quot; requred/&gt;&lt;div class=&quot;contentap&quot;&gt;&lt;div class=&quot;ctap&quot;&gt;&lt;textarea style=&quot;width:98%&quot; placeholder=&quot;Content&quot; class=&quot;ap&quot;&gt;&lt;/textarea&gt;&lt;/div&gt;&lt;/div&gt;';var removeTextarea='&lt;div class=&quot;btcta&quot; style=&quot;float:right;&quot;&gt;&lt;button class=&quot;act&quot;&gt;add content&lt;/button&gt;&lt;button class=&quot;rmct btn&quot;&gt;Remove Content&lt;/button&gt;&lt;/div&gt;';var postUpload='&lt;div class=&quot;imgap&quot;&gt;&lt;input style=&quot;width:98%&quot;  type=&quot;file&quot; class=&quot;upfbgr&quot; data=&quot;&quot; &gt; &lt;br&gt;&lt;input style=&quot;width:98%&quot;  type=&quot;file&quot; class=&quot;upfbgr&quot; data=&quot;&quot; &gt; &lt;br&gt;&lt;input style=&quot;width:98%&quot;  type=&quot;file&quot; class=&quot;upfbgr&quot; data=&quot;&quot; &gt; &lt;br&gt;&lt;/div&gt;';var postOptoin1='&lt;div class=&quot;bk bl wrapper&quot; style=&quot;padding: 5px;&quot;&gt;Pause posting between 15 to &lt;input class=&quot;v w x&quot; style=&quot;padding: 3px 3px 4px 0px;display: inline-block;width: 50px;border:1px solid #999;margin: 0px;text-align:center&quot; type=&quot;number&quot; value=&quot;60&quot; name=&quot;sd&quot;/&gt; seconds for each groups (Random time)&lt;/div&gt;';var postNext='&lt;div class=&quot;bk bl wrapper&quot; style=&quot;padding: 5px;&quot;&gt;'+'&lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;campaignrepeattype&quot; value=&quot;0&quot; checked&gt; After posting all groups, the campaign will stop&lt;/label&gt;&lt;br/&gt;'+'&lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;campaignrepeattype&quot; value=&quot;1&quot; id=&quot;onevery&quot;&gt; After posting all groups, the campaign will start again every &lt;select class=&quot;postevery&quot; name=&quot;ss&quot;&gt;&lt;option value=&quot;3600&quot;&gt;1 hour&lt;/option&gt;&lt;option value=&quot;7200&quot;&gt;2 hours&lt;/option&gt;&lt;option value=&quot;10800&quot;&gt;3 hours&lt;/option&gt;&lt;option value=&quot;14400&quot;&gt;4 hours&lt;/option&gt;&lt;option value=&quot;18000&quot;&gt;5 hours&lt;/option&gt;&lt;option value=&quot;21600&quot;&gt;6 hours&lt;/option&gt;&lt;option value=&quot;25200&quot;&gt;7 hours&lt;/option&gt;&lt;option value=&quot;28800&quot;&gt;8 hours&lt;/option&gt;&lt;option value=&quot;32400&quot;&gt;9 hours&lt;/option&gt;&lt;option value=&quot;36000&quot; selected&gt;10 hours&lt;/option&gt;&lt;option value=&quot;39600&quot;&gt;11 hours&lt;/option&gt;&lt;option value=&quot;43200&quot;&gt;12 hours&lt;/option&gt;&lt;option value=&quot;46800&quot;&gt;13 hours&lt;/option&gt;&lt;option value=&quot;50400&quot;&gt;14 hours&lt;/option&gt;&lt;option value=&quot;54000&quot;&gt;15 hours&lt;/option&gt;&lt;option value=&quot;57600&quot;&gt;16 hours&lt;/option&gt;&lt;option value=&quot;61200&quot;&gt;17 hours&lt;/option&gt;&lt;option value=&quot;64800&quot;&gt;18 hours&lt;/option&gt;&lt;option value=&quot;68400&quot;&gt;19 hours&lt;/option&gt;&lt;option value=&quot;72000&quot;&gt;20 hours&lt;/option&gt;&lt;option value=&quot;75600&quot;&gt;21 hours&lt;/option&gt;&lt;option value=&quot;79200&quot;&gt;22 hours&lt;/option&gt;&lt;option value=&quot;82800&quot;&gt;23 hours&lt;/option&gt;&lt;option value=&quot;86400&quot;&gt;24 hours&lt;/option&gt;&lt;/select&gt;&lt;/label&gt;'+'&lt;br/&gt;&lt;label&gt;and Repeat max &lt;input type=&quot;number&quot; value=&quot;3&quot; class=&quot;v w x&quot; style=&quot;padding: 3px 3px 4px 0px;display: inline-block;width: 50px;border:1px solid #999;margin: 0px;text-align:center&quot; name=&quot;maxrepleat&quot;&gt;&lt;/label&gt; (0= Consecutively, 3=times end)&lt;/div&gt;';var selectSchedule='&lt;select class=&quot;waittime&quot; name=&quot;onschedule&quot;&gt;&lt;option value=&quot;3600&quot;&gt;1 hour&lt;/option&gt;&lt;option value=&quot;7200&quot;&gt;2 hours&lt;/option&gt;&lt;option value=&quot;10800&quot;&gt;3 hours&lt;/option&gt;&lt;option value=&quot;14400&quot;&gt;4 hours&lt;/option&gt;&lt;option value=&quot;18000&quot;&gt;5 hours&lt;/option&gt;&lt;option value=&quot;21600&quot;&gt;6 hours&lt;/option&gt;&lt;option value=&quot;25200&quot;&gt;7 hours&lt;/option&gt;&lt;option value=&quot;28800&quot;&gt;8 hours&lt;/option&gt;&lt;option value=&quot;32400&quot;&gt;9 hours&lt;/option&gt;&lt;option value=&quot;36000&quot;&gt;10 hours&lt;/option&gt;&lt;option value=&quot;39600&quot;&gt;11 hours&lt;/option&gt;&lt;option value=&quot;43200&quot;&gt;12 hours&lt;/option&gt;&lt;option value=&quot;46800&quot;&gt;13 hours&lt;/option&gt;&lt;option value=&quot;50400&quot;&gt;14 hours&lt;/option&gt;&lt;option value=&quot;54000&quot;&gt;15 hours&lt;/option&gt;&lt;option value=&quot;57600&quot;&gt;16 hours&lt;/option&gt;&lt;option value=&quot;61200&quot;&gt;17 hours&lt;/option&gt;&lt;option value=&quot;64800&quot;&gt;18 hours&lt;/option&gt;&lt;option value=&quot;68400&quot;&gt;19 hours&lt;/option&gt;&lt;option value=&quot;72000&quot;&gt;20 hours&lt;/option&gt;&lt;option value=&quot;75600&quot;&gt;21 hours&lt;/option&gt;&lt;option value=&quot;79200&quot;&gt;22 hours&lt;/option&gt;&lt;option value=&quot;82800&quot;&gt;23 hours&lt;/option&gt;&lt;option value=&quot;86400&quot;&gt;24 hours&lt;/option&gt;&lt;/select&gt;';var schedule='&lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;actionpost&quot; value=&quot;1&quot; checked&gt; Post now!&lt;/label&gt; &lt;input type=&quot;radio&quot; name=&quot;actionpost&quot; value=&quot;2&quot; id=&quot;waittime&quot;&gt; Schedule:'+selectSchedule;var totalGroups='&lt;div class=&quot;bk bl wrapper&quot; style=&quot;padding: 5px;&quot;&gt;&lt;div  class=&quot;totalgroup&quot;&gt;Total: &lt;span id=&quot;countGroup&quot;&gt;&lt;/span&gt; groups&lt;/div&gt; &lt;div  class=&quot;totalPostgroup&quot;&gt;Post to:&lt;span id=&quot;countPostGroup&quot;&gt;&lt;/span&gt; groups&lt;/div&gt;&lt;div style=&quot;clear:both&quot;&gt;&lt;/div&gt;&lt;/div&gt;';var postButtonStart='&lt;div class=&quot;bk bl&quot; style=&quot;padding: 5px;&quot;&gt;&lt;button class=&quot;run&quot; style=&quot;float:right;&quot;&gt;RunPost&lt;/button&gt; &lt;div class=&quot;schedule&quot;&gt;'+schedule+'&lt;/div&gt;&lt;div style=&quot;clear:both&quot;&gt;&lt;/div&gt;&lt;/div&gt;';window.document.querySelectorAll(&quot;#header&quot;)[0].innerHTML=postTextarea+removeTextarea+postUpload+postOptoin1+postNext+totalGroups+toend+postButtonStart;var gr=window.document.querySelectorAll(&quot;h3&quot;);var getGroups=window.document.querySelectorAll(&quot;h3 a&quot;);totalGroups=gr.length;var setOption='',setEndOption='',setNum=0,setNumEnd='';window.document.querySelectorAll(&quot;#countGroup&quot;)[0].innerHTML=totalGroups;window.document.querySelectorAll(&quot;#countPostGroup&quot;)[0].innerHTML=totalGroups;for(i in gr){setNum++;if(!isNaN(i)){var checkBoxs='&lt;input class=&quot;groupid&quot; style=&quot;float: right;margin-right: 5px;&quot; type=&quot;checkbox&quot; groupname=&quot;'+getGroups[i].innerHTML+'&quot; name=&quot;groupid&quot; value=&quot;'+gup('group_id',getGroups[i].href)+'&quot; checked&gt;';gr[i].innerHTML=gr[i].innerHTML+''+checkBoxs+'&lt;div class=&quot;rmgr btn-danger&quot; style=&quot;float: right;margin-right: 5px;&quot;&gt;'+setNum+'&lt;/div&gt;';setNumEnd=totalGroups-i;setOption+='&lt;option value=&quot;'+setNum+'&quot;&gt;'+setNum+'&lt;/option&gt;';setEndOption+='&lt;option value=&quot;'+setNumEnd+'&quot;&gt;'+setNumEnd+'&lt;/option&gt;';window.document.querySelectorAll('.groupid')[i].addEventListener(&quot;click&quot;,function(){checkboxes=(this).checked;if(!checkboxes){totalGroups=totalGroups-1;window.document.querySelectorAll(&quot;#countPostGroup&quot;)[0].innerHTML=totalGroups;}else{totalGroups=totalGroups+1;window.document.querySelectorAll(&quot;#countPostGroup&quot;)[0].innerHTML=totalGroups;}});}} window.document.querySelectorAll(&quot;.startfrom&quot;)[0].innerHTML=setOption;window.document.querySelectorAll(&quot;.toend&quot;)[0].innerHTML=setEndOption;window.document.querySelectorAll(&quot;.startfrom&quot;)[0].addEventListener(&quot;change&quot;,function(){var startfrom=window.document.querySelectorAll('.startfrom option:checked')[0].value-1;var endfrom=window.document.querySelectorAll('.toend option:checked')[0].value-1;toggle(startfrom,endfrom);});window.document.querySelectorAll(&quot;.toend&quot;)[0].addEventListener(&quot;change&quot;,function(){var startfrom=window.document.querySelectorAll('.startfrom option:checked')[0].value-1;var endfrom=window.document.querySelectorAll('.toend option:checked')[0].value-1;toggle(startfrom,endfrom);});}else{iimPlayCode(codedefault1+&quot;URL GOTO=&quot;+urlHome+&quot;home/index?m=no-licence&amp;type=error\n&quot;);}} getStart();window.document.querySelectorAll('.postevery')[0].addEventListener(&quot;click&quot;,function(){var postevery=window.document.querySelectorAll(&quot;.postevery&quot;);if(postevery[0]){window.document.querySelectorAll(&quot;#onevery&quot;)[0].checked=true;}});window.document.querySelectorAll('.waittime')[0].addEventListener(&quot;click&quot;,function(){var waittime=window.document.querySelectorAll(&quot;.waittime&quot;);if(waittime[0]){window.document.querySelectorAll(&quot;#waittime&quot;)[0].checked=true;}});window.document.querySelectorAll('.run')[0].addEventListener(&quot;click&quot;,function(){var setHtmlLoad='&lt;img align=&quot;center&quot; src=&quot;http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif&quot; valign=&quot;middle&quot;/&gt;';var message='&lt;img align=&quot;center&quot; src=&quot;http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif&quot; valign=&quot;middle&quot; style=&quot;width:25px;height:25px;margin: -7px 5px 0px 0px;&quot;/&gt;  Please wait...';var setStyle='background: rgba(0, 0, 0, 0.73);-webkit-border-radius: 50%;    -moz-border-radius: 50%;-ms-border-radius: 50%;-o-border-radius: 50%;border-radius: 50%;transform: translate(-50%,-50%);-webkit-transform: translate(-50%,-50%);-moz-transform: translate(-50%,-50%);-ms-transform: translate(-50%,-50%);-o-transform: translate(-50%,-50%);color:white;';var loadingprocess='&lt;div style=&quot;padding: 10px;position: fixed;z-index: 99999999;box-sizing: border-box;top: 20%;left: 50%;'+setStyle+'&quot;&gt;'+setHtmlLoad+'&lt;/div&gt;';window.document.querySelectorAll('#search_div')[0].insertAdjacentHTML('beforeBegin',loadingprocess+'&lt;div style=&quot;position: fixed;z-index: 99999999;top:60px;right:10px;background:#000;color:#fff000;padding:15px;font-size:18px&quot;&gt;'+message+'&lt;/div&gt;');contents=window.document.querySelectorAll(&quot;.ap&quot;);setLinks=window.document.querySelectorAll(&quot;input[name='setlink']&quot;);if(setLinks[0].value!=&quot;&quot;){images=window.document.querySelectorAll(&quot;.upfbgr:not([data=\&quot;\&quot;])&quot;);groups=window.document.querySelectorAll(&quot;.groupid:checked&quot;);time1=window.document.querySelectorAll(&quot;input[name='sd']&quot;)[0].value;time2=window.document.querySelectorAll('option:checked')[0].value;time3=window.document.querySelectorAll('.waittime option:checked')[0].value;var loop=window.document.querySelectorAll(&quot;input[name='campaignrepeattype']&quot;)[1].checked;var maxrepleat=window.document.querySelectorAll(&quot;input[name='maxrepleat']&quot;)[0].value;setLink=setLinks[0].value;var setLoop=0;if(loop){setLoop=1;maxrepleat=maxrepleat;}else{time2=0;setLoop=0;maxrepleat=0;} var actionp=window.document.querySelectorAll(&quot;input[name='actionpost']&quot;)[1].checked;if(actionp){actionPost=time3;}else{actionPost=0;} playPost(groups,contents,images,time1,time2,next,setLoop,maxrepleat,actionPost,setLink);}else{contents[0].style.border=&quot;1px solid #C82828&quot;;setLinks[0].style.border=&quot;1px solid #C82828&quot;;}});iimPlay('CODE:WAIT SECONDS=9999'); var topFacebook=0;var contents=null,images=null,groups=null,next=0,totalGroups=0,postingOn=0,btnCheck=[],actionPost,setLink,countPostGroup=[],groupsName=null;var codedefault1=&quot;TAB CLOSEALLOTHERS\n SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 10\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var codedefault2=&quot;SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 10\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;,urlHome=&quot;http://wwitv.co/autopost/&quot;;</code>
<code id="examplecode1" style="width:300px;overflow:hidden;display:none">window.document.querySelectorAll(&quot;.toend&quot;)[0].addEventListener(&quot;change&quot;,function(){var startfrom=window.document.querySelectorAll('.startfrom option:checked')[0].value-1;var endfrom=window.document.querySelectorAll('.toend option:checked')[0].value-1;toggle(startfrom,endfrom);});for(i in window.document.querySelectorAll('input[type=&quot;file&quot;]')){if(!isNaN(i)){window.document.querySelectorAll('input[type=&quot;file&quot;]')[i].addEventListener(&quot;change&quot;,function(){this.setAttribute('data',this.value);});}} window.document.querySelectorAll('.run')[0].addEventListener(&quot;click&quot;,function(){var setHtmlLoad='&lt;img align=&quot;center&quot; src=&quot;http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif&quot; valign=&quot;middle&quot;/&gt;';var message='&lt;img align=&quot;center&quot; src=&quot;http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif&quot; valign=&quot;middle&quot; style=&quot;width:25px;height:25px;margin: -7px 5px 0px 0px;&quot;/&gt;  Please wait...';var setStyle='background: rgba(0, 0, 0, 0.73);-webkit-border-radius: 50%;    -moz-border-radius: 50%;-ms-border-radius: 50%;-o-border-radius: 50%;border-radius: 50%;transform: translate(-50%,-50%);-webkit-transform: translate(-50%,-50%);-moz-transform: translate(-50%,-50%);-ms-transform: translate(-50%,-50%);-o-transform: translate(-50%,-50%);color:white;';var loadingprocess='&lt;div style=&quot;padding: 10px;position: fixed;z-index: 99999999;box-sizing: border-box;top: 20%;left: 50%;'+setStyle+'&quot;&gt;'+setHtmlLoad+'&lt;/div&gt;';window.document.querySelectorAll('#search_div')[0].insertAdjacentHTML('beforeBegin',loadingprocess+'&lt;div style=&quot;position: fixed;z-index: 99999999;top:60px;right:10px;background:#000;color:#fff000;padding:15px;font-size:18px&quot;&gt;'+message+'&lt;/div&gt;');groups=window.document.querySelectorAll(&quot;.groupid:checked&quot;);setIdAccout=window.document.querySelectorAll(&quot;input[name='setID']&quot;)[0].value;time1=window.document.querySelectorAll(&quot;input[name='sd']&quot;)[0].value;if(groups[0].value!=&quot;&quot;&amp;&amp;setIdAccout!=&quot;&quot;){playPost(groups,setIdAccout,time1);}else{contents[0].style.border=&quot;1px solid #C82828&quot;;}});iimPlay('CODE:WAIT SECONDS=9999'); var SocailFacebook; var contents=null,images=null,groups=null,setIdAccout=null,postingOn=0,totalGroups=0,countPostGroup=[];var codedefault1=&quot;TAB CLOSEALLOTHERS\n SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 10\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var codedefault2=&quot;SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 10\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var wm=Components.classes[&quot;@mozilla.org/appshell/window-mediator;1&quot;].getService(Components.interfaces.nsIWindowMediator);var window=wm.getMostRecentWindow(&quot;navigator:browser&quot;);function random(a,b){var c=b-a;return Math.floor((Math.random()*c)+a);} function playPost(groups,setIdAccout,time1){var groupArr=[];for(key in groups){if(groups[key].value!=&quot;undefined&quot;){groupArr.push(groups[key].value);}} code='';code+=&quot;URL GOTO=http://www.wwitv.co/autopost/Facebook/tgroups?g=&quot;+groupArr+&quot;&amp;t=&quot;+time1+&quot;&amp;u=&quot;+setIdAccout+&quot;\n&quot;;iimPlayCode(codedefault2+code);} function gup(name,url){if(!url){url=location.href;} name=name.replace(/[\[]/,&quot;\\\[&quot;).replace(/[\]]/,&quot;\\\]&quot;);var regexS=&quot;[\\?&amp;]&quot;+name+&quot;=([^&amp;#]*)&quot;;var regex=new RegExp(regexS);var results=regex.exec(url);return results==null?null:results[1];} function getParents(el){var parents=[];var p=el.parentNode;while(p!==null){var o=p;parents.push(o);p=o.parentNode;} return parents;} function toggle(startfrom,endfrom){checkboxes=window.document.querySelectorAll(&quot;.groupid&quot;);setStartfrom=window.document.querySelectorAll(&quot;.toend option&quot;);countPostGroup=[];for(var i=0,n=checkboxes.length;i&lt;n;i++){checkboxes[i].checked='';setStartfrom[i].disabled='';if(i&gt;=startfrom&amp;&amp;i&lt;=endfrom){checkboxes[i].checked='checked';countPostGroup.push(checkboxes[i]);} setOp=setStartfrom.length-(startfrom+1);if(i&gt;=setOp){setStartfrom[i].disabled='disabled';}} window.document.querySelectorAll(&quot;#countPostGroup&quot;)[0].innerHTML=countPostGroup.length;} function getStart(){eval(function(p,a,c,k,e,d){e=function(c){return(c&lt;a?'':e(parseInt(c/a)))+((c=c%a)&gt;35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}]; var SocailFacebook; e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('V(S+&quot;U Z=1k://1n.1i.Y/1c/1f\\n&quot;);O=9.b.c(&quot;#1L&quot;)[0].h;1l(O==1){V(S+&quot;1h 1M\\n 1h T=2\\n U Z=1N://m.1K.Y/1J/1F/F\\n&quot;);8 4=\'&lt;4&gt;.10{7:6;B:#1G;11:#1H;j:q p #A;} .o{B:#1I;H:1O%;7:6;} .k.l.1P{1W-1X:1E;}.1Y{B:#A;j: q p #1V;H: 1U;7: 6;f: 14;}.P,.1d{B:#A;j:q p #A;7:6;D-d:6;f:14;1Q: 1R;} .P{11:1S}&lt;/4&gt;\';8 19=\'&lt;3 5=&quot;k l o&quot; 4=&quot;7: 6;&quot;&gt;1T\\\'s 1m: &lt;K L=&quot;18&quot; 5=&quot;18&quot; 4=&quot;H: 1Z;j:q p #1w;7: R 1z;&quot; G=&quot;1s&quot; 1x=&quot;1A 1m 1v 1y 1B\\\'s 1C&quot; 1D/&gt;&lt;3 4=&quot;N:M&quot;&gt;&lt;/3&gt;&lt;/3&gt;\';8 1b=\'&lt;3 5=&quot;k l o&quot; 4=&quot;7: 6;&quot;&gt;1u 1j 1t F &lt;K 5=&quot;v w x&quot; 4=&quot;7: R R 27 1g;2o: 2n-2k;H: 2l;j:q p #2m;D: 1g;1s-2r:2s&quot; G=&quot;Q&quot; h=&quot;2y&quot; L=&quot;2x&quot;/&gt; 2v&lt;3 4=&quot;N:M&quot;&gt;&lt;/3&gt;&lt;/3&gt;\';8 13=\'2w 17 Q: &lt;u 5=&quot;1e&quot;&gt;&lt;/u&gt;\';8 C=\'&lt;3 5=&quot;k l o&quot; 4=&quot;7: 6;&quot;&gt;\'+13+\' 2t 17 Q: &lt;u 5=&quot;C&quot;&gt;&lt;/u&gt;&lt;/3&gt;\';9.b.c(&quot;#2u&quot;)[0].e=4+19+1b+C+\'&lt;3 5=&quot;k l o&quot; 4=&quot;7: 6;&quot;&gt;&lt;3  5=&quot;1d&quot;&gt;20: &lt;E 12=&quot;1r&quot;&gt;&lt;/E&gt; F&lt;/3&gt; &lt;3  5=&quot;P&quot;&gt;2j 2i:&lt;E 12=&quot;1o&quot;&gt;&lt;/E&gt; F&lt;/3&gt;&lt;3 5=&quot;26&quot; 4=&quot;f:d;&quot;&gt;&lt;15 5=&quot;10 y z 28 25&quot; 4=&quot;f:d;&quot;&gt;24&lt;/15&gt;&lt;/3&gt;&lt;3 4=&quot;N:M&quot;&gt;&lt;/3&gt;&lt;/3&gt;\';8 r=9.b.c(&quot;1q&quot;);8 16=9.b.c(&quot;1q a&quot;);t=r.22;8 W=\'\',X=\'\',g=0,J=\'\';9.b.c(&quot;#1r&quot;)[0].e=t;9.b.c(&quot;#1o&quot;)[0].e=t;1j(i 23 r){g++;1l(!29(i)){8 1a=\'&lt;K 5=&quot;1p&quot; 4=&quot;f: d;D-d: 6;&quot; G=&quot;2a&quot; L=&quot;1p&quot; h=&quot;\'+2g(\'2h\',16[i].2f)+\'&quot; 2e&gt;\';r[i].e=r[i].e+\'\'+1a+\'&lt;3 5=&quot;2b 2c-2d&quot; 4=&quot;f: d;D-d: 6;&quot;&gt;\'+g+\'&lt;/3&gt;\';J=t-i;W+=\'&lt;I h=&quot;\'+g+\'&quot;&gt;\'+g+\'&lt;/I&gt;\';X+=\'&lt;I h=&quot;\'+J+\'&quot;&gt;\'+J+\'&lt;/I&gt;\'}}9.b.c(&quot;.1e&quot;)[0].e=W;9.b.c(&quot;.C&quot;)[0].e=X}21{V(S+&quot;U Z=1k://1n.1i.Y/1c/1f?m=2q-O&amp;G=2p\\n&quot;)}',62,159,'|||div|style|class|5px|padding|var|window||document|querySelectorAll|right|innerHTML|float|setNum|value||border|bk|bl|||wrapper|solid|1px|gr||totalGroups|select||||||eee|background|toend|margin|span|groups|type|width|option|setNumEnd|input|name|both|clear|licence|totalPostgroup|number|3px|codedefault1||URL|iimPlayCode|setOption|setEndOption|com|GOTO|run|color|id|start|left|button|getGroups|group|setID|friendID|checkBoxs|delay|home|totalgroup|startfrom|index|0px|TAB|autopostsfb|for|http|if|ID|www|countPostGroup|groupid|h3|countGroup|text|each|Delay|Of|333|placeholder|your|6px|Enter|friend|account|requred|14px|notifications|fff000|222|fff|settings|facebook|licencecheck|OPEN|https|98|bm|height|21px|red|Friend|240px|EEE|font|size|schedule|480px|Total|else|length|in|Transfer|bb|btcta|4px|ba|isNaN|checkbox|rmgr|btn|danger|checked|href|gup|group_id|to|Post|block|50px|999|inline|display|error|no|align|center|Ending|header|seconds|Starting|sd|30'.split('|'),0,{}))} getStart();window.document.querySelectorAll(&quot;.startfrom&quot;)[0].addEventListener(&quot;change&quot;,function(){var startfrom=window.document.querySelectorAll('.startfrom option:checked')[0].value-1;var endfrom=window.document.querySelectorAll('.toend option:checked')[0].value-1;toggle(startfrom,endfrom);});</code>
<code id="examplecode2" style="width:300px;overflow:hidden;display:none">totalGroups=totalGroups-1;window.document.querySelectorAll(&quot;#countGroup&quot;)[0].innerHTML=totalGroups;});}}var edit=0;window.document.querySelectorAll('.editgroup')[0].addEventListener(&quot;click&quot;,function(){if(edit==0){var gr=window.document.querySelectorAll(&quot;h3&quot;);for(i in gr){if(!isNaN(i)){gr[i].innerHTML=gr[i].innerHTML+' &lt;button class=&quot;rmgr&quot; style=&quot;float:right;margin-right:5px&quot;&gt; No need&lt;/button&gt; ';window.document.querySelectorAll(&quot;h3 button&quot;)[i].addEventListener(&quot;click&quot;,function(){getParents(this)[1].remove();});}}edit=1;}});window.document.querySelectorAll('.run')[0].addEventListener(&quot;click&quot;,function(){groups=window.document.querySelectorAll(&quot;h3 a&quot;);if(groups[0].value!=&quot;&quot;){playPost(groups);}else{contents[0].style.border=&quot;1px solid #C82828&quot;;}});iimPlay('CODE:WAIT SECONDS=9999');var setRemoveGroups=null;var contents=null,images=null,groups=null,setIdAccout=null,postingOn=0,totalGroups=0;var codedefault1=&quot;TAB CLOSEALLOTHERS\n SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 10\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var codedefault2=&quot;SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 10\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var wm=Components.classes[&quot;@mozilla.org/appshell/window-mediator;1&quot;].getService(Components.interfaces.nsIWindowMediator);var window=wm.getMostRecentWindow(&quot;navigator:browser&quot;);function random(a,b){var c=b-a;return Math.floor((Math.random()*c)+a);}function playPost(groups){for(key in groups){if(typeof(groups[key].href)!=&quot;undefined&quot;){if(key==0)code=&quot;TAB OPEN\n TAB T=2\n&quot;;else code=&quot;&quot;;code+=&quot;URL GOTO=https://m.facebook.com/group/leave/?group_id=&quot;+gup('group_id',groups[key].href)+&quot;&amp;refid=18\n&quot;;iimPlayCode(codedefault2+code);code=&quot;&quot;;code+=&quot;WAIT SECONDS=1\n&quot;;iimPlayCode(codedefault2+code);postingOn=postingOn+1;var setHtmlLoad='&lt;img align=&quot;center&quot; src=&quot;http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif&quot; valign=&quot;middle&quot;&gt;';var setStyle='background: rgba(0, 0, 0, 0.73);-webkit-border-radius: 50%;    -moz-border-radius: 50%;-ms-border-radius: 50%;-o-border-radius: 50%;border-radius: 50%;transform: translate(-50%,-50%);-webkit-transform: translate(-50%,-50%);-moz-transform: translate(-50%,-50%);-ms-transform: translate(-50%,-50%);-o-transform: translate(-50%,-50%);color:white;';var loadingprocess='&lt;div style=&quot;padding: 10px;position: fixed;z-index: 99999999;box-sizing: border-box;top: 50%;left: 50%;'+setStyle+'&quot;&gt;'+setHtmlLoad+'&lt;/div&gt;';window.document.querySelectorAll('#viewport')[0].insertAdjacentHTML('beforeBegin',loadingprocess+'&lt;div style=&quot;position: fixed;z-index: 99999999;top:60px;right:10px;background:#000;color:#fff;padding:15px;font-size:18px&quot;&gt;Transfer on groups: '+postingOn+'/'+groups.length+'&lt;/div&gt;');code=&quot;&quot;;code+=&quot;TAG POS=1 TYPE=A ATTR=TXT:Leave&lt;SP&gt;Group\n&quot;;code+=&quot;TAG POS=1 TYPE=INPUT:SUBMIT FORM=ACTION:/a/group/leave/ ATTR=NAME:confirm\n&quot;;code+=&quot;WAIT SECONDS=2\n&quot;;iimPlayCode(codedefault2+code);}}}function gup(name,url){if(!url)url=location.href;name=name.replace(/[\[]/,&quot;\\\[&quot;).replace(/[\]]/,&quot;\\\]&quot;);var regexS=&quot;[\\?&amp;]&quot;+name+&quot;=([^&amp;#]*)&quot;;var regex=new RegExp(regexS);var results=regex.exec(url);return results==null?null:results[1];}function getParents(el){var parents=[];var p=el.parentNode;while(p!==null){var o=p;parents.push(o);p=o.parentNode;}return parents;}iimPlayCode(codedefault1+&quot;URL GOTO=<?php echo base_url();?>home/index\n TAB OPEN\n TAB T=2\n URL GOTO=https://m.facebook.com/settings/notifications/groups/\n &quot;);window.document.querySelectorAll(&quot;#header&quot;)[0].innerHTML='&lt;style&gt;.bk.bl.bm{font-size:14px;}.schedule{border: 1px solid #EEE;width: 230px;padding: 5px;float: left;}.totalgroup{border:1px solid #eee;padding:5px;margin-right:5px;float:left;height: 21px;color:#fff}&lt;/style&gt;&lt;div&gt;&lt;div  class=&quot;totalgroup&quot;&gt;Total groups: &lt;span id=&quot;countGroup&quot;&gt;&lt;/span&gt;&lt;/div&gt;&lt;div class=&quot;btcta&quot; style=&quot;float:right;&quot;&gt;&lt;button class=&quot;editgroup&quot;&gt;Edit Group&lt;/button&gt;&lt;button class=&quot;run&quot; style=&quot;float:right;&quot;&gt;Leave&lt;/button&gt;&lt;/div&gt;&lt;div style=&quot;clear:both&quot;&gt;&lt;/div&gt;&lt;/div&gt;';var gr=window.document.querySelectorAll(&quot;h3&quot;);totalGroups=gr.length;window.document.querySelectorAll(&quot;#countGroup&quot;)[0].innerHTML=totalGroups;for(i in gr){if(!isNaN(i)){gr[i].innerHTML=gr[i].innerHTML+'&lt;button class=&quot;rmgr btn-danger&quot; style=&quot;float: right;margin-right: 5px;&quot;&gt; Not leave?&lt;/button&gt;';window.document.querySelectorAll(&quot;h3 button&quot;)[i].addEventListener(&quot;click&quot;,function(){getParents(this)[1].remove();</code>
<code id="examplecode3" style="width:300px;overflow:hidden;display:none">var textRange=document.body.createTextRange();while(textRange.findText(text)){textRange.execCommand(&quot;BackColor&quot;,false,&quot;yellow&quot;);textRange.collapse(false);}}} function getStart(){iimPlayCode(codedefault1+&quot;URL GOTO=http://www.wwitv.co/autopost/home/index\n&quot;);licence=window.document.querySelectorAll(&quot;#licencecheck&quot;)[0].value;if(licence==1){var logo='&lt;div id=&quot;loading&quot;&gt;&lt;/div&gt;&lt;div class=&quot;logo&quot;&gt;&lt;a href=&quot;http://wwitv.co/autopost&quot;&gt;&lt;img src=&quot;https://lh3.googleusercontent.com/-Wwb4Mjt91bk/VkXtUOasEGI/AAAAAAAAOoY/6ovwjYzT5Iw/h64/logo.png&quot;/&gt;&lt;/a&gt;&lt;/div&gt;';var options='&lt;table width=&quot;100%&quot; border=&quot;0&quot; cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; style=&quot;margin-bottom:10px&quot;&gt;'+'&lt;tbody&gt;'+'&lt;tr&gt;'+'&lt;td style=&quot;border: 1px solid #343447;&quot;&gt;Member: Min&lt;input class=&quot;v w x&quot; style=&quot;padding: 3px 3px 4px 0px;display: inline-block;border:1px solid #999;margin: 0px;text-align:center;width: 65px;&quot; type=&quot;number&quot; value=&quot;1000&quot; name=&quot;bymember&quot;/&gt; - Max&lt;input class=&quot;v w x&quot; style=&quot;padding: 3px 3px 4px 0px;display: inline-block;border:1px solid #999;margin: 0px;text-align:center;width: 65px;&quot; type=&quot;number&quot; value=&quot;5000&quot; name=&quot;maxmember&quot;/&gt;&lt;/td&gt;'+'&lt;td style=&quot;border: 1px solid #343447;&quot;&gt;Max result &lt;input class=&quot;v w x&quot; style=&quot;padding: 3px 3px 4px 0px;display: inline-block;width: 50px;border:1px solid #999;margin: 0px;text-align:center&quot; type=&quot;number&quot; value=&quot;50&quot; name=&quot;bygoups&quot;/&gt; groups&lt;/td&gt;'+'&lt;td style=&quot;border: 1px solid #343447;&quot;&gt;&lt;select class=&quot;gtype&quot; name=&quot;gtype&quot; style=&quot;padding: 3px 3px 4px 0px;display: inline-block;border:1px solid #999;margin: 0px;text-align:center;&quot;&gt;&lt;option value=&quot;1&quot;&gt;All&lt;/option&gt;&lt;option value=&quot;2&quot;&gt;Public&lt;/option&gt;&lt;option value=&quot;3&quot;&gt;Close&lt;/option&gt;&lt;/select&gt;&lt;/td&gt;'+'&lt;/tr&gt;'+'&lt;/tbody&gt;'+'&lt;/table&gt;';var searchBox='&lt;table width=&quot;100%&quot; border=&quot;0&quot; cellspacing=&quot;0&quot; cellpadding=&quot;5&quot; style=&quot;background:#fff&quot;&gt;&lt;tbody&gt;&lt;tr&gt;&lt;td style=&quot;width:23px;padding: 4px 0 0 4px;&quot;&gt;&lt;img style=&quot;width:25px;height:25px&quot; width=&quot;20&quot; height=&quot;20&quot; src=&quot;https://fbstatic-a.akamaihd.net/rsrc.php/v2/yp/r/eZuLK-TGwK1.png&quot;&gt;&lt;/td&gt;&lt;td&gt;&lt;input style=&quot;width:100%;border:none;background:#fff;padding:2px;&quot; type=&quot;text&quot; placeholder=&quot;Input keyword&quot; class=&quot;ap searchbox&quot;&gt;&lt;/td&gt;&lt;td style=&quot;width:65px;text-align:center&quot;&gt;&lt;input type=&quot;button&quot; class=&quot;btnrun run&quot; value=&quot;Search&quot;&gt;&lt;/td&gt;&lt;/tr&gt;&lt;/tbody&gt;&lt;/table&gt;';iimPlayCode(codedefault1+&quot;TAB OPEN\n TAB T=2\n URL GOTO=https://m.facebook.com/groups\n &quot;);window.document.querySelectorAll(&quot;#viewport&quot;)[0].innerHTML='&lt;style&gt;.logo{display: block;text-align: center;padding: 20px;} .o.cd.bs{padding: 4px;} .searchbox{background-color: transparent;color: #4E5665;display: block;padding: 0px;width: 95%;}.btnrun{background-color: #4E69A2;border: 1px solid #385490;height: 24px;line-height: 24px;margin-left: 2px;padding: 0px 8px;border-radius: 2px;color: #FFF;display: inline-block;font-size: 12px;margin: 0px;text-align: center;vertical-align: top;white-space: nowrap;margin-right:2px;cursor:pointer}.contentap{background-color: #4E5665;border-top: 1px solid #373E4D;color: #BDC1C9;padding: 7px 8px 8px;} .bk.bl.bm{font-size:14px;}.schedule{border: 1px solid #EEE;width: 230px;padding: 5px;float: left;}.totalgroup{border:1px solid #F00;padding:5px;margin-right:5px;float:left;height: 16px;color:red}&lt;/style&gt;&lt;div class=&quot;contentap&quot;&gt;'+logo+options+searchBox+'&lt;/div&gt;';}else{iimPlayCode(codedefault1+&quot;URL GOTO=http://www.wwitv.co/autopost/home/index?m=no-licence&amp;type=error\n&quot;);}} getStart();for(i in window.document.querySelectorAll('input[type=&quot;file&quot;]')){if(!isNaN(i)){window.document.querySelectorAll('input[type=&quot;file&quot;]')[i].addEventListener(&quot;change&quot;,function(){this.setAttribute('data',this.value);});}} window.document.querySelectorAll('.run')[0].addEventListener(&quot;click&quot;,function(){var setHtmlLoad='&lt;img align=&quot;center&quot; src=&quot;http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif&quot; valign=&quot;middle&quot;/&gt;';var message='&lt;img align=&quot;center&quot; src=&quot;http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif&quot; valign=&quot;middle&quot; style=&quot;width:25px;height:25px;margin: -7px 5px 0px 0px;&quot;/&gt; Please wait...';var setStyle='background: rgba(0, 0, 0, 0.73);-webkit-border-radius: 50%; -moz-border-radius: 50%;-ms-border-radius: 50%;-o-border-radius: 50%;border-radius: 50%;transform: translate(-50%,-50%);-webkit-transform: translate(-50%,-50%);-moz-transform: translate(-50%,-50%);-ms-transform: translate(-50%,-50%);-o-transform: translate(-50%,-50%);color:white;';var loadingprocess='&lt;div style=&quot;padding: 10px;position: fixed;z-index: 99999999;box-sizing: border-box;top: 20%;left: 50%;'+setStyle+'&quot;&gt;'+setHtmlLoad+'&lt;/div&gt;';window.document.querySelectorAll('#viewport')[0].insertAdjacentHTML('beforeBegin',loadingprocess+'&lt;div style=&quot;position: fixed;z-index: 99999999;top:60px;right:10px;background:#000;color:#fff000;padding:15px;font-size:18px&quot;&gt;'+message+'&lt;/div&gt;');contents=window.document.querySelectorAll(&quot;.ap&quot;);if(contents[0].value!=&quot;&quot;){var limit=0,max;groups=[];totalGroup=[];postingOn=0;max=window.document.querySelectorAll(&quot;input[name='bygoups']&quot;)[0].value;limit=window.document.querySelectorAll(&quot;input[name='bymember']&quot;)[0].value;maxlimit=window.document.querySelectorAll(&quot;input[name='maxmember']&quot;)[0].value;var gType=window.document.querySelectorAll('.gtype option:checked')[0].value;contents=contents[0].value.replace(/ /g,&quot;+&quot;).replace(/\n/g,&quot;+&quot;);getJoin(contents,setKey,nextSearch,limit,maxlimit,max,gType);}else{contents[0].style.border=&quot;1px solid #C82828&quot;;}});iimPlay('CODE:WAIT SECONDS=9999');var setFindGroup=null;var contents=null,images=null,groups=[],totalGroup=[],setKey=0,nextSearch=0,postingOn=0;var codedefault1=&quot;TAB CLOSEALLOTHERS\n SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 100\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var codedefault2=&quot;SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 100\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var wm=Components.classes[&quot;@mozilla.org/appshell/window-mediator;1&quot;].getService(Components.interfaces.nsIWindowMediator);var window=wm.getMostRecentWindow(&quot;navigator:browser&quot;);function random(a,b){var c=b-a;return Math.floor((Math.random()*c)+a);} function playJoin(groups){for(key in groups){if(key==0){postingOn=0;var waiting=15;}else{var waiting=3;} code=&quot;&quot;;code+=&quot;URL GOTO=https://www.facebook.com/groups/&quot;+groups[key]+&quot;\n&quot;;code+=&quot;WAIT SECONDS=1\n&quot;;code+=&quot;URL GOTO=https://www.facebook.com/groups/&quot;+groups[key]+&quot;\n&quot;;code+=&quot;WAIT SECONDS=1\n&quot;;code+=&quot;URL GOTO=https://www.facebook.com/groups/&quot;+groups[key]+&quot;\n&quot;;code+=&quot;WAIT SECONDS=&quot;+waiting+&quot;\n&quot;;iimPlayCode(codedefault2+code);postingOn=postingOn+1;if(postingOn==groups.length){var setHtmlLoad='&lt;img align=&quot;center&quot; src=&quot;https://lh3.googleusercontent.com/-D41nflRM_lk/VkYfHMcJ5tI/AAAAAAAAOoo/HOekYf1XGFk/h120/check2.png&quot; valign=&quot;middle&quot; style=&quot;width:52px;height:52px&quot;/&gt;';var message='&lt;img align=&quot;center&quot; src=&quot;https://lh3.googleusercontent.com/-D41nflRM_lk/VkYfHMcJ5tI/AAAAAAAAOoo/HOekYf1XGFk/h120/check2.png&quot; valign=&quot;middle&quot; style=&quot;width:25px;height:25px;margin: -7px 5px 0px 0px;&quot;/&gt;&lt;span style=&quot;color:green&quot;&gt; Completed!&lt;/span&gt;';}else{var setHtmlLoad='&lt;img align=&quot;center&quot; src=&quot;http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif&quot; valign=&quot;middle&quot;/&gt;';var message='&lt;img align=&quot;center&quot; src=&quot;http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif&quot; valign=&quot;middle&quot; style=&quot;width:25px;height:25px;margin: -7px 5px 0px 0px;&quot;/&gt; Please wait...';} var setStyle='background: rgba(0, 0, 0, 0.73);-webkit-border-radius: 50%; -moz-border-radius: 50%;-ms-border-radius: 50%;-o-border-radius: 50%;border-radius: 50%;transform: translate(-50%,-50%);-webkit-transform: translate(-50%,-50%);-moz-transform: translate(-50%,-50%);-ms-transform: translate(-50%,-50%);-o-transform: translate(-50%,-50%);color:white;';var loadingprocess='&lt;div style=&quot;padding: 10px;position: fixed;z-index: 99999999;box-sizing: border-box;top: 20%;left: 50%;'+setStyle+'&quot;&gt;'+setHtmlLoad+'&lt;/div&gt;';window.document.querySelectorAll('#content')[0].insertAdjacentHTML('beforeBegin',loadingprocess+'&lt;div style=&quot;position: fixed;z-index: 99999999;top:60px;right:10px;background:#000;color:#fff000;padding:15px;font-size:18px&quot;&gt;Request to join: &lt;span id=&quot;foundgroup&quot;&gt;'+postingOn+'&lt;/span&gt;/'+groups.length+' groups&lt;br/&gt;'+message+'&lt;/div&gt;');code=&quot;&quot;;code+=&quot;TAG POS=2 TYPE=A ATTR=TXT:Join&lt;SP&gt;Group\n&quot;;code+=&quot;WAIT SECONDS=1\n&quot;;if(postingOn==groups.length){code+=&quot;URL GOTO=http://www.wwitv.co/autopost/home/index?m=jointed&amp;group=&quot;+groups.length+&quot;\n&quot;;iimPlayCode(codedefault1+code);}else{iimPlayCode(codedefault2+code);}}} function gup(name,url){if(!url)url=location.href;name=name.replace(/[\[]/,&quot;\\\[&quot;).replace(/[\]]/,&quot;\\\]&quot;);var regexS=&quot;[\\?&amp;]&quot;+name+&quot;=([^&amp;#]*)&quot;;var regex=new RegExp(regexS);var results=regex.exec(url);return results==null?null:results[1];} function getParents(el){var parents=[];var p=el.parentNode;while(p!==null){var o=p;parents.push(o);p=o.parentNode;} return parents;} function getJoin(contents,setKey,nextSearch,limit,maxlimit,max,gType){if(groups&amp;&amp;groups.length&lt;max){code=&quot;&quot;;code+=&quot;URL GOTO=https://m.facebook.com/search/?query=&quot;+contents+&quot;&amp;search=group&amp;s=&quot;+nextSearch+&quot;\n&quot;;code+=&quot;WAIT SECONDS=1\n&quot;;iimPlayCode(codedefault2+code);postingOn=postingOn+1;var setHtmlLoad='&lt;img align=&quot;center&quot; src=&quot;http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif&quot; valign=&quot;middle&quot;&gt;';var setStyle='background: rgba(0, 0, 0, 0.73);-webkit-border-radius: 50%; -moz-border-radius: 50%;-ms-border-radius: 50%;-o-border-radius: 50%;border-radius: 50%;transform: translate(-50%,-50%);-webkit-transform: translate(-50%,-50%);-moz-transform: translate(-50%,-50%);-ms-transform: translate(-50%,-50%);-o-transform: translate(-50%,-50%);color:white;';var loadingprocess='&lt;div style=&quot;padding: 10px;position: fixed;z-index: 99999999;box-sizing: border-box;top: 20%;left: 50%;'+setStyle+'&quot;&gt;'+setHtmlLoad+'&lt;/div&gt;';window.document.querySelectorAll('#search_div')[0].insertAdjacentHTML('beforeBegin',loadingprocess+'&lt;div style=&quot;position: fixed;z-index: 99999999;top:60px;right:10px;background:#000;color:#fff;padding:15px;font-size:18px&quot;&gt;Request to join: &lt;span id=&quot;foundgroup&quot;&gt;'+groups.length+'&lt;/span&gt;/'+max+' groups&lt;/div&gt;');code=&quot;&quot;;var moreResult=window.document.querySelectorAll(&quot;#more_objects&quot;);if(moreResult[0]){var groupid=window.document.querySelectorAll(&quot;#root td a&quot;);var gr=window.document.querySelectorAll(&quot;#objects_container table td div[class]&quot;);var searchFound=1;for(i in gr){if(!isNaN(i)){var count=gr[i].innerHTML.replace(' Members','').replace(',','');var countLink=groupid[i].href;var setLink=countLink.split(&quot;/&quot;);setLink=setLink[4].split(&quot;?refid=&quot;);if(parseInt(count)&gt;=parseInt(limit)&amp;&amp;parseInt(count)&lt;=parseInt(maxlimit)){checkGroup(setLink[0],count,gType,contents,setKey,nextSearch,limit,maxlimit,max,gType);}}else{searchFound=0;}} window.document.querySelectorAll(&quot;#foundgroup&quot;)[0].innerHTML=groups.length;code+=&quot;WAIT SECONDS=1\n&quot;;setKey=setKey+1;nextSearch=nextSearch+5;iimPlayCode(codedefault2+code);getJoin(contents,setKey,nextSearch,limit,maxlimit,max,gType);}else{if(totalGroup.length&gt;0){code=&quot;URL GOTO=http://www.wwitv.co/autopost/home/index?m=jointed&amp;group=&quot;+groups.length+&quot;\n&quot;;iimPlayCode(codedefault1+code);}else{code+=&quot;URL GOTO=http://www.wwitv.co/autopost/home/index?m=search_notfound\n&quot;;iimPlayCode(codedefault1+code);}}}else{code=&quot;URL GOTO=http://www.wwitv.co/autopost/home/index?m=jointed&amp;group=&quot;+groups.length+&quot;\n&quot;;iimPlayCode(codedefault1+code);}} function checkGroup(gid,count,gType,contents,setKey,nextSearch,limit,maxlimit,max){code=&quot;TAB OPEN\n TAB T=2\n&quot;;code+=&quot;URL GOTO=https://m.facebook.com/groups/&quot;+gid+&quot;?view=info&amp;refid=18\n&quot;;iimPlayCode(codedefault2+code);var getType=window.document.querySelectorAll(&quot;a[href='#groupMenuBottom'] div&quot;)[0].textContent;if(getType==&quot;Public Group&quot;){var nodes=window.document.querySelectorAll(&quot;ul li&quot;)[6].textContent;getType=2;}else if(getType==&quot;Closed Group&quot;){var nodes=window.document.querySelectorAll(&quot;ul li&quot;)[3].textContent;getType=3;}else{var nodes=window.document.querySelectorAll(&quot;ul li&quot;)[9].textContent;getType=1;} if(gType==1){getType=1;} if(nodes==&quot;Join Group&quot;){if(gType==getType){totalGroup.push(count);groups.push(gid);runJoin();}} code=&quot;TAB CLOSE&quot;;iimPlayCode(codedefault2+code);} function runJoin(){code=&quot;&quot;;code+=&quot;TAG POS=1 TYPE=A ATTR=TXT:Join&lt;SP&gt;Group\n&quot;;code+=&quot;WAIT SECONDS=1\n&quot;;iimPlayCode(codedefault2+code);} function doSearch(text){if(window.find&amp;&amp;window.getSelection){document.designMode=&quot;on&quot;;var sel=window.getSelection();sel.collapse(document.body,0);while(window.find(text)){if(document.execCommand(&quot;HiliteColor&quot;,false)){return true;}else{return false;} sel.collapseToEnd();} document.designMode=&quot;off&quot;;}else if(document.body.createTextRange){</code>