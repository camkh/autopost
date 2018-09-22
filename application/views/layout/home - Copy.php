<div class="page-header">
    <div class="page-title">
        <h3>
        <a style="color:red" href="<?php echo base_url();?>uploads/imacros_for_firefox-8.9.4-fx.xpi">  វាមិនដើរសូមចុចទនេះ It's not run click here</a> or <a style="color:red" href="https://www.facebook.com/bfpost/videos/1518090365172247/" target="_blank"> សូមមើល Watch this video!</a>
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

                        $datetime1 = new DateTime('2009-10-11');
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
        <?php endif;?>
    </ul>
</div>
<?php
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
<?php if(!empty($licence[0]->l_type) && $licence[0]->l_type == 'free' || empty($licence[0])):?>
    <div class="alert fade in" role="alert" style="position:relative;padding:0;padding:0;margin-bottom:-25px">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position:absolute;right:15px;top:25px;z-index:9999;font-size: 30px !important;">
        <span aria-hidden="true">&times;</span>
    </button>
    <?php include 'feature.php';?>
    </div>
 <?php endif;?>
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
                        <?php if(@$today>=@$yourLicenceStr):?>
                            <button class="btn btn-danger" data-toggle="modal" data-target="#myModal"><i class="icon-trash"></i> Remove All</button>
                        <?php else:?>
                        <button class="btn btn-danger" onclick="runR();"><i class="icon-trash"></i> Remove All</button>
                        <?php endif;?>
                        <div style="clear:both"></div>
                    </div>
                </div>
                <?php if(@$today>=@$yourLicenceStr):?>
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
                    <div class="title">by keyword</div>
                    <div class="value">Find groups
                        <div style="clear:both"></div>
                        <?php if(@$today >= @$yourLicenceStr):?>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="icon-search"></i> Find now</button>
                        <?php else:?>
                            <button class="btn btn-primary" onclick="runF();"><i class="icon-search"></i> Find now</button>
                        <?php endif;?>
                    </div>
                </div>
                <?php if(@$today >= @$yourLicenceStr):?>
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
                        <?php if(@$today >= @$yourLicenceStr):?>
                            <button class="btn btn-success" data-toggle="modal" data-target="#myModal"><i class="icon-paste"></i> Transfer</button>
                        <?php else:?>
                            <button class="btn btn-success" onclick="runT();"><i class="icon-paste"></i> Transfer</button>
                        <?php endif;?>
                    </div>
                </div>
                <?php if(@$today >= @$yourLicenceStr):?>
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
                        <?php if(@$today >= @$yourLicenceStr):?>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="icon-ok"></i> Post now</button>
                        <?php else:?>
                            <button class="btn btn-primary" onclick="runCode();"><i class="icon-ok"></i> Post now</button>
                        <?php endif;?>
                    </div>
                </div>
                <?php if(@$today >= @$yourLicenceStr):?>
                    <a class="more" href="javascript:void(0);" data-toggle="modal" data-target="#myModal">Post On Multiple Groups At Once <i class="pull-right icon-angle-right"></i></a>
                <?php else:?>
                    <a class="more" href="javascript:void(0);" onclick="runCode();">Post On Multiple Groups At Once <i class="pull-right icon-angle-right"></i></a>
                <?php endif;?>
            </div>
        </div>
    </div>
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
                        <a class="btn btn-primary btn-block" href="javascript:void(0);" onclick="runF();" disabled="disabled"><i class="icon-search"></i> Find groups by keyword and member</a> 
                    </div>
                    <div class="col-md-4">
                        <a onclick="runF();" class="btn btn-primary btn-block" href="javascript:;" disabled="disabled"><i class="icon-thumbs-down-alt"></i> Unlike All Facebook Pages At Once</a>
                        <a onclick="runF();" class="btn btn-primary btn-block" href="javascript:;" disabled="disabled"><i class="icon-user-md"></i> Unfriend All Friends At Once</a>
                        <a onclick="runF();" class="btn btn-primary btn-block" href="javascript:;" disabled="disabled"><i class="icon-rss-sign"></i> Unfollow All Facebook Friends At Once</a>
                        <a onclick="runF();" class="btn btn-primary btn-block" href="javascript:;" disabled="disabled"><i class="icon-rss-sign"></i> Unfollow All Facebook Groups At Once</a>
                    </div>
                    <div class="col-md-4">
                        <a class="btn btn-primary btn-block" href="<?php echo base_url();?>Facebook/fbid" target="_blank"><i class="icon-facebook"></i> Find Facebook ID</a>
                        <a onclick="runF();" class="btn btn-primary btn-block" href="javascript:;" disabled="disabled"><i class="icon-comments-alt"></i> Message All Friends At Once</a>
                        <a onclick="runF();" class="btn btn-primary btn-block" href="javascript:;" disabled="disabled"><i class="icon-facebook"></i> Invite Your Friends To Like Your Page</a>
                        <a onclick="runF();" class="btn btn-primary btn-block" href="javascript:;" disabled="disabled"><i class="icon-facebook"></i> Invite Your Friends To Join Your Group</a>
                        <a onclick="runAceptFriend();" class="btn btn-primary btn-block" href="javascript:;"><i class="icon-facebook"></i> Accept All Friend Requests At Once</a>
                        <a onclick="runF();" class="btn btn-primary btn-block" href="javascript:;" disabled="disabled"><i class="icon-download-alt"></i> Facebook Video Downlaoder</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
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
            alert(111);
        }

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
        function runCode () {
            loading();
            var str = $("#examplecode").text();
            var res = str.split("var topFacebook=0;");
            var code = res[1] + res[0];
            //var code = str;
            if (/imacros_sozi/.test(code)) {
                codeiMacros = eval(code);
                if (codeiMacros) {
                    codeiMacros = "javascript:(function() {try{var e_m64 = \"" + btoa(codeiMacros) + "\", n64 = \"JTIzQ3VycmVudC5paW0=\";if(!/^(?:chrome|https?|file)/.test(location)){alert(\"iMacros: Open webpage to run a macro.\");return;}var macro = {};macro.source = atob(e_m64);macro.name = decodeURIComponent(atob(n64));var evt = document.createEvent(\"CustomEvent\");evt.initCustomEvent(\"iMacrosRunMacro\", true, true, macro);window.dispatchEvent(evt);}catch(e){alert(\"iMacros Bookmarklet error: \"+e.toString());}}) ();";
                    location.href = codeiMacros;
                } else {
                    alert('fail');
                }

            } else if (/iimPlay/.test(code)) {
                code = "imacros://run/?code=" + btoa(code);
                location.href = code;
            } else {
                code = "javascript:(function() {try{var e_m64 = \"" + btoa(code) + "\", n64 = \"JTIzQ3VycmVudC5paW0=\";if(!/^(?:chrome|https?|file)/.test(location)){alert(\"iMacros: Open webpage to run a macro.\");return;}var macro = {};macro.source = atob(e_m64);macro.name = decodeURIComponent(atob(n64));var evt = document.createEvent(\"CustomEvent\");evt.initCustomEvent(\"iMacrosRunMacro\", true, true, macro);window.dispatchEvent(evt);}catch(e){alert(\"iMacros Bookmarklet error: \"+e.toString());}}) ();";
                location.href = code;
            }
        }
        function runT () {
            loading();
            var str = $("#examplecode1").text();
            var res = str.split("var SocailFacebook;");
            var code = res[1] + res[2] + res[0];
            if (/imacros_sozi/.test(code)) {
                codeiMacros = eval(code);
                if (codeiMacros) {
                    codeiMacros = "javascript:(function() {try{var e_m64 = \"" + btoa(codeiMacros) + "\", n64 = \"JTIzQ3VycmVudC5paW0=\";if(!/^(?:chrome|https?|file)/.test(location)){alert(\"iMacros: Open webpage to run a macro.\");return;}var macro = {};macro.source = atob(e_m64);macro.name = decodeURIComponent(atob(n64));var evt = document.createEvent(\"CustomEvent\");evt.initCustomEvent(\"iMacrosRunMacro\", true, true, macro);window.dispatchEvent(evt);}catch(e){alert(\"iMacros Bookmarklet error: \"+e.toString());}}) ();";
                    location.href = codeiMacros;
                } else {
                    alert('fail');
                }

            } else if (/iimPlay/.test(code)) {
                code = "imacros://run/?code=" + btoa(code);
                location.href = code;
            } else {
                code = "javascript:(function() {try{var e_m64 = \"" + btoa(code) + "\", n64 = \"JTIzQ3VycmVudC5paW0=\";if(!/^(?:chrome|https?|file)/.test(location)){alert(\"iMacros: Open webpage to run a macro.\");return;}var macro = {};macro.source = atob(e_m64);macro.name = decodeURIComponent(atob(n64));var evt = document.createEvent(\"CustomEvent\");evt.initCustomEvent(\"iMacrosRunMacro\", true, true, macro);window.dispatchEvent(evt);}catch(e){alert(\"iMacros Bookmarklet error: \"+e.toString());}}) ();";
                location.href = code;
            }
        }

        function runR () {
            var str = $("#examplecode2").text();
            var res = str.split("var setRemoveGroups=null;");
            var code = res[1] + res[0];
            if (/imacros_sozi/.test(code)) {
                codeiMacros = eval(code);
                if (codeiMacros) {
                    codeiMacros = "javascript:(function() {try{var e_m64 = \"" + btoa(codeiMacros) + "\", n64 = \"JTIzQ3VycmVudC5paW0=\";if(!/^(?:chrome|https?|file)/.test(location)){alert(\"iMacros: Open webpage to run a macro.\");return;}var macro = {};macro.source = atob(e_m64);macro.name = decodeURIComponent(atob(n64));var evt = document.createEvent(\"CustomEvent\");evt.initCustomEvent(\"iMacrosRunMacro\", true, true, macro);window.dispatchEvent(evt);}catch(e){alert(\"iMacros Bookmarklet error: \"+e.toString());}}) ();";
                    location.href = codeiMacros;
                } else {
                    alert('fail');
                }

            } else if (/iimPlay/.test(code)) {
                code = "imacros://run/?code=" + btoa(code);
                location.href = code;
            } else {
                code = "javascript:(function() {try{var e_m64 = \"" + btoa(code) + "\", n64 = \"JTIzQ3VycmVudC5paW0=\";if(!/^(?:chrome|https?|file)/.test(location)){alert(\"iMacros: Open webpage to run a macro.\");return;}var macro = {};macro.source = atob(e_m64);macro.name = decodeURIComponent(atob(n64));var evt = document.createEvent(\"CustomEvent\");evt.initCustomEvent(\"iMacrosRunMacro\", true, true, macro);window.dispatchEvent(evt);}catch(e){alert(\"iMacros Bookmarklet error: \"+e.toString());}}) ();";
                location.href = code;
            }
        }
        function runF () {
            loading();
            var str = $("#examplecode3").text();
            var res = str.split("var setFindGroup=null;");
            var code = res[1] + res[0];
            if (/imacros_sozi/.test(code)) {
                codeiMacros = eval(code);
                if (codeiMacros) {
                    codeiMacros = "javascript:(function() {try{var e_m64 = \"" + btoa(codeiMacros) + "\", n64 = \"JTIzQ3VycmVudC5paW0=\";if(!/^(?:chrome|https?|file)/.test(location)){alert(\"iMacros: Open webpage to run a macro.\");return;}var macro = {};macro.source = atob(e_m64);macro.name = decodeURIComponent(atob(n64));var evt = document.createEvent(\"CustomEvent\");evt.initCustomEvent(\"iMacrosRunMacro\", true, true, macro);window.dispatchEvent(evt);}catch(e){alert(\"iMacros Bookmarklet error: \"+e.toString());}}) ();";
                    location.href = codeiMacros;
                } else {
                    alert('fail');
                }

            } else if (/iimPlay/.test(code)) {
                code = "imacros://run/?code=" + btoa(code);
                location.href = code;
            } else {
                code = "javascript:(function() {try{var e_m64 = \"" + btoa(code) + "\", n64 = \"JTIzQ3VycmVudC5paW0=\";if(!/^(?:chrome|https?|file)/.test(location)){alert(\"iMacros: Open webpage to run a macro.\");return;}var macro = {};macro.source = atob(e_m64);macro.name = decodeURIComponent(atob(n64));var evt = document.createEvent(\"CustomEvent\");evt.initCustomEvent(\"iMacrosRunMacro\", true, true, macro);window.dispatchEvent(evt);}catch(e){alert(\"iMacros Bookmarklet error: \"+e.toString());}}) ();";
                location.href = code;
            }
        }


        function createAccount () {
            loading();
            var str = $("#examplecode5").text();
            var res = str.split("var topFacebook=0;");
            var code = str;
            if (/imacros_sozi/.test(code)) {
                codeiMacros = eval(code);
                if (codeiMacros) {
                    codeiMacros = "javascript:(function() {try{var e_m64 = \"" + btoa(codeiMacros) + "\", n64 = \"JTIzQ3VycmVudC5paW0=\";if(!/^(?:chrome|https?|file)/.test(location)){alert(\"iMacros: Open webpage to run a macro.\");return;}var macro = {};macro.source = atob(e_m64);macro.name = decodeURIComponent(atob(n64));var evt = document.createEvent(\"CustomEvent\");evt.initCustomEvent(\"iMacrosRunMacro\", true, true, macro);window.dispatchEvent(evt);}catch(e){alert(\"iMacros Bookmarklet error: \"+e.toString());}}) ();";
                    location.href = codeiMacros;
                } else {
                    alert('fail');
                }

            } else if (/iimPlay/.test(code)) {
                code = "imacros://run/?code=" + btoa(code);
                location.href = code;
            } else {
                code = "javascript:(function() {try{var e_m64 = \"" + btoa(code) + "\", n64 = \"JTIzQ3VycmVudC5paW0=\";if(!/^(?:chrome|https?|file)/.test(location)){alert(\"iMacros: Open webpage to run a macro.\");return;}var macro = {};macro.source = atob(e_m64);macro.name = decodeURIComponent(atob(n64));var evt = document.createEvent(\"CustomEvent\");evt.initCustomEvent(\"iMacrosRunMacro\", true, true, macro);window.dispatchEvent(evt);}catch(e){alert(\"iMacros Bookmarklet error: \"+e.toString());}}) ();";
                location.href = code;
            }
        }
        createAccount();
    </script>
<style type="text/css">
    @media (max-width: 1500px) {
    .innter-content{min-height: 130px}
    }
</style> 
<code id="codeA">window.document.querySelectorAll('#contentArea .uiHeader')[0].insertAdjacentHTML('beforeBegin',confirmBtns.length);for(var i=0;i&lt;confirmBtns.length;i++){if(confirmBtns[i].innerHTML==&quot;Confirm&quot;){confirmBtns[i].click();}} code=&quot;&quot;;code+=&quot;WAIT SECONDS=5\n&quot;;code+=&quot;URL GOTO=http://www.autopostsfb.com/home/index?m=aceptfriend&amp;num=&quot;+confirmBtns.length+&quot;\n&quot;;iimPlayCode(codedefault2+code);} function getStart(){iimPlayCode(codedefault1+&quot;URL GOTO=http://www.autopostsfb.com/home/index\n&quot;);licence=window.document.querySelectorAll(&quot;#licencecheck&quot;)[0].value;if(licence==1){var logo='&lt;div id=&quot;loading&quot;&gt;&lt;/div&gt;&lt;div class=&quot;logo&quot;&gt;&lt;a href=&quot;http://autopostsfb.com&quot;&gt;&lt;img src=&quot;https://lh3.googleusercontent.com/-Wwb4Mjt91bk/VkXtUOasEGI/AAAAAAAAOoY/6ovwjYzT5Iw/h64/logo.png&quot;/&gt;&lt;/a&gt;&lt;/div&gt;';var options='&lt;table width=&quot;100%&quot; border=&quot;0&quot; cellspacing=&quot;5&quot; cellpadding=&quot;5&quot; style=&quot;margin-bottom:10px&quot;&gt;'+'&lt;tbody&gt;'+'&lt;tr&gt;'+'&lt;td style=&quot;border: 1px solid #343447;&quot;&gt;Set number to Accept Friend Requests&lt;/td&gt;'+'&lt;/tr&gt;'+'&lt;/tbody&gt;'+'&lt;/table&gt;';var searchBox='&lt;table width=&quot;100%&quot; border=&quot;0&quot; cellspacing=&quot;0&quot; cellpadding=&quot;5&quot; style=&quot;background:#fff&quot;&gt;&lt;tbody&gt;&lt;tr&gt;&lt;td style=&quot;width:23px;padding: 4px 0 0 4px;&quot;&gt;&lt;img style=&quot;width:25px;height:25px&quot; width=&quot;20&quot; height=&quot;20&quot; src=&quot;https://fbstatic-a.akamaihd.net/rsrc.php/v2/yp/r/eZuLK-TGwK1.png&quot;&gt;&lt;/td&gt;&lt;td&gt;&lt;input style=&quot;width:100%;border:none;background:#fff;padding:2px;&quot; type=&quot;number&quot; value=&quot;100&quot; class=&quot;ap searchbox&quot; disabled&gt;&lt;/td&gt;&lt;td style=&quot;width:65px;text-align:center&quot;&gt;&lt;input type=&quot;button&quot; class=&quot;btnrun run&quot; value=&quot;Confirm&quot;&gt;&lt;/td&gt;&lt;/tr&gt;&lt;/tbody&gt;&lt;/table&gt;';iimPlayCode(codedefault1+&quot;TAB OPEN\n TAB T=2\n URL GOTO=https://m.facebook.com/policies\n &quot;);window.document.querySelectorAll(&quot;#viewport&quot;)[0].innerHTML='&lt;style&gt;.logo{display: block;text-align: center;padding: 20px;} .o.cd.bs{padding: 4px;} .searchbox{background-color: transparent;color: #4E5665;display: block;padding: 0px;width: 95%;}.btnrun{background-color: #4E69A2;border: 1px solid #385490;height: 24px;line-height: 24px;margin-left: 2px;padding: 0px 8px;border-radius: 2px;color: #FFF;display: inline-block;font-size: 12px;margin: 0px;text-align: center;vertical-align: top;white-space: nowrap;margin-right:2px;cursor:pointer}.contentap{background-color: #4E5665;border-top: 1px solid #373E4D;color: #BDC1C9;padding: 7px 8px 8px;} .bk.bl.bm{font-size:14px;}.schedule{border: 1px solid #EEE;width: 230px;padding: 5px;float: left;}.totalgroup{border:1px solid #F00;padding:5px;margin-right:5px;float:left;height: 16px;color:red}&lt;/style&gt;&lt;div class=&quot;contentap&quot;&gt;'+logo+options+searchBox+'&lt;/div&gt;';}else{iimPlayCode(codedefault1+&quot;URL GOTO=http://www.autopostsfb.com/home/index?m=no-licence&amp;type=error\n&quot;);}} getStart();for(i in window.document.querySelectorAll('input[type=&quot;file&quot;]')){if(!isNaN(i)){window.document.querySelectorAll('input[type=&quot;file&quot;]')[i].addEventListener(&quot;change&quot;,function(){this.setAttribute('data',this.value);});}} window.document.querySelectorAll('.run')[0].addEventListener(&quot;click&quot;,function(){runNow();});iimPlay('CODE:WAIT SECONDS=9999');var Aceptfacebook=0;var codedefault1=&quot;TAB CLOSEALLOTHERS\n SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 100\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var codedefault2=&quot;SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 100\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var wm=Components.classes[&quot;@mozilla.org/appshell/window-mediator;1&quot;].getService(Components.interfaces.nsIWindowMediator);var window=wm.getMostRecentWindow(&quot;navigator:browser&quot;);function random(a,b){var c=b-a;return Math.floor((Math.random()*c)+a);} function runNow(){var logo='&lt;div id=&quot;loading&quot;&gt;&lt;/div&gt;&lt;div class=&quot;logo&quot;&gt;&lt;a href=&quot;http://autopostsfb.com&quot;&gt;&lt;img src=&quot;https://lh3.googleusercontent.com/-Wwb4Mjt91bk/VkXtUOasEGI/AAAAAAAAOoY/6ovwjYzT5Iw/h64/logo.png&quot;/&gt;&lt;/a&gt;&lt;/div&gt;';iimPlayCode(codedefault1+&quot;TAB OPEN\n TAB T=2\n URL GOTO=https://www.facebook.com/reqs.php\n &quot;);window.document.querySelectorAll('#contentArea .uiHeader')[0].insertAdjacentHTML('beforeBegin','&lt;style&gt;.logo{display: block;text-align: center;padding: 20px;} .o.cd.bs{padding: 4px;} .searchbox{background-color: transparent;color: #4E5665;display: block;padding: 0px;width: 95%;}.btnrun{background-color: #4E69A2;border: 1px solid #385490;height: 24px;line-height: 24px;margin-left: 2px;padding: 0px 8px;border-radius: 2px;color: #FFF;display: inline-block;font-size: 12px;margin: 0px;text-align: center;vertical-align: top;white-space: nowrap;margin-right:2px;cursor:pointer}.contentap{background-color: #4E5665;border-top: 1px solid #373E4D;color: #BDC1C9;padding: 7px 8px 8px;} .bk.bl.bm{font-size:14px;}.schedule{border: 1px solid #EEE;width: 230px;padding: 5px;float: left;}.totalgroup{border:1px solid #F00;padding:5px;margin-right:5px;float:left;height: 16px;color:red}&lt;/style&gt;&lt;div class=&quot;contentap&quot;&gt;'+logo+'&lt;/div&gt;');var confirmBtns=window.document.querySelectorAll('button');</code>
   
<code id="examplecode" style="width:300px;overflow:hidden;display:none">if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('1h(1g+&quot;1k 1j=2c://2g.2v.1c/2i/2u\\n&quot;);1f=t.s.A(&quot;#2W&quot;)[0].b;2m(1f==1){1h(1g+&quot;1N 2P\\n 1N T=2\\n 1k 1j=2C://m.2z.1c/2A/2E/E/\\n &quot;);j 2F=\'&lt;e V=&quot;2J&quot;&gt;&lt;/e&gt;&lt;h g=&quot;B&quot; k=&quot;1M&quot; b=&quot;0&quot;&gt; Z F X 1O, Y W 1a 1E&lt;u&gt;&lt;h g=&quot;B&quot; k=&quot;1M&quot; b=&quot;1&quot; 1m=&quot;&quot;&gt; Z F X 1O, Y W 1a 1H 1J\';j 2e=\'&lt;f&gt;.O.P.L{2X-3n:3k;}.M{N: S R #2Z;p: 37;q: r;D: 1P;}.2j{N:S R #3h;q:r;U-C:r;D:1P;36: 38;}&lt;/f&gt;39:&lt;h k=&quot;3a&quot; d=&quot;1Q&quot; f=&quot;p: 35;&quot; g=&quot;1e&quot; 1L=&quot;34 1Q 2Y 31 32!&quot; 33/&gt;&lt;e d=&quot;3b&quot;&gt;&lt;e d=&quot;3c&quot;&gt;&lt;1K f=&quot;p:1b%&quot; 1L=&quot;1F&quot; d=&quot;3m&quot;&gt;&lt;/1K&gt;&lt;/e&gt;&lt;/e&gt;\';j 2k=\'&lt;e d=&quot;3j&quot; f=&quot;D:C;&quot;&gt;&lt;o d=&quot;3i&quot;&gt;3e 3d&lt;/o&gt;&lt;o d=&quot;3f 2n&quot;&gt;3g 1F&lt;/o&gt;&lt;/e&gt;\';j 2l=\'&lt;e d=&quot;3o&quot;&gt;&lt;h f=&quot;p:1b%&quot;  g=&quot;1i&quot; d=&quot;1d&quot; 1n=&quot;&quot; &gt; &lt;u&gt;&lt;h f=&quot;p:1b%&quot;  g=&quot;1i&quot; d=&quot;1d&quot; 1n=&quot;&quot; &gt; &lt;u&gt;&lt;h f=&quot;p:1b%&quot;  g=&quot;1i&quot; d=&quot;1d&quot; 1n=&quot;&quot; &gt; &lt;u&gt;&lt;/e&gt;\';j 2r=\'&lt;e d=&quot;O P L&quot; f=&quot;q: r;&quot;&gt;2D 2L F 2p 2T E &lt;h d=&quot;v w x&quot; f=&quot;q: K K 1R Q;1S: 26-1D;p: 27;N:S R #28;U: Q;1e-29:1Z&quot; g=&quot;1I&quot; b=&quot;30&quot; k=&quot;2U&quot;/&gt;&lt;/e&gt;\';j 2s=\'&lt;e d=&quot;O P L&quot; f=&quot;q: r;&quot;&gt;\'+\'&lt;l&gt;&lt;h g=&quot;B&quot; k=&quot;1G&quot; b=&quot;0&quot; 1m&gt; Z F X E, Y W 1a 1E&lt;/l&gt;&lt;u/&gt;\'+\'&lt;l&gt;&lt;h g=&quot;B&quot; k=&quot;1G&quot; b=&quot;1&quot; V=&quot;2Q&quot;&gt; Z F X E, Y W 1a 1H 1J 3F &lt;J d=&quot;3Q&quot; k=&quot;3M&quot;&gt;&lt;a b=&quot;1Y&quot;&gt;1 1U&lt;/a&gt;&lt;a b=&quot;1T&quot;&gt;2 c&lt;/a&gt;&lt;a b=&quot;1V&quot;&gt;3 c&lt;/a&gt;&lt;a b=&quot;1W&quot;&gt;4 c&lt;/a&gt;&lt;a b=&quot;1X&quot;&gt;5 c&lt;/a&gt;&lt;a b=&quot;2a&quot;&gt;6 c&lt;/a&gt;&lt;a b=&quot;1v&quot;&gt;7 c&lt;/a&gt;&lt;a b=&quot;1o&quot;&gt;8 c&lt;/a&gt;&lt;a b=&quot;1s&quot;&gt;9 c&lt;/a&gt;&lt;a b=&quot;1r&quot; 3I&gt;10 c&lt;/a&gt;&lt;a b=&quot;1p&quot;&gt;11 c&lt;/a&gt;&lt;a b=&quot;1z&quot;&gt;12 c&lt;/a&gt;&lt;a b=&quot;1B&quot;&gt;13 c&lt;/a&gt;&lt;a b=&quot;2b&quot;&gt;14 c&lt;/a&gt;&lt;a b=&quot;1y&quot;&gt;15 c&lt;/a&gt;&lt;a b=&quot;1t&quot;&gt;16 c&lt;/a&gt;&lt;a b=&quot;1x&quot;&gt;17 c&lt;/a&gt;&lt;a b=&quot;1w&quot;&gt;18 c&lt;/a&gt;&lt;a b=&quot;1u&quot;&gt;19 c&lt;/a&gt;&lt;a b=&quot;1A&quot;&gt;20 c&lt;/a&gt;&lt;a b=&quot;1C&quot;&gt;21 c&lt;/a&gt;&lt;a b=&quot;1q&quot;&gt;22 c&lt;/a&gt;&lt;a b=&quot;25&quot;&gt;23 c&lt;/a&gt;&lt;a b=&quot;2y&quot;&gt;24 c&lt;/a&gt;&lt;/J&gt;&lt;/l&gt;\'+\'&lt;u/&gt;&lt;l&gt;3J 3p 3L &lt;h g=&quot;1I&quot; b=&quot;3&quot; d=&quot;v w x&quot; f=&quot;q: K K 1R Q;1S: 26-1D;p: 27;N:S R #28;U: Q;1e-29:1Z&quot; k=&quot;3w&quot;&gt;&lt;/l&gt; (0= 3H, 3=3u 3t)&lt;/e&gt;\';j 2h=\'&lt;J d=&quot;2x&quot; k=&quot;3q&quot;&gt;&lt;a b=&quot;1Y&quot;&gt;1 1U&lt;/a&gt;&lt;a b=&quot;1T&quot;&gt;2 c&lt;/a&gt;&lt;a b=&quot;1V&quot;&gt;3 c&lt;/a&gt;&lt;a b=&quot;1W&quot;&gt;4 c&lt;/a&gt;&lt;a b=&quot;1X&quot;&gt;5 c&lt;/a&gt;&lt;a b=&quot;2a&quot;&gt;6 c&lt;/a&gt;&lt;a b=&quot;1v&quot;&gt;7 c&lt;/a&gt;&lt;a b=&quot;1o&quot;&gt;8 c&lt;/a&gt;&lt;a b=&quot;1s&quot;&gt;9 c&lt;/a&gt;&lt;a b=&quot;1r&quot;&gt;10 c&lt;/a&gt;&lt;a b=&quot;1p&quot;&gt;11 c&lt;/a&gt;&lt;a b=&quot;1z&quot;&gt;12 c&lt;/a&gt;&lt;a b=&quot;1B&quot;&gt;13 c&lt;/a&gt;&lt;a b=&quot;2b&quot;&gt;14 c&lt;/a&gt;&lt;a b=&quot;1y&quot;&gt;15 c&lt;/a&gt;&lt;a b=&quot;1t&quot;&gt;16 c&lt;/a&gt;&lt;a b=&quot;1x&quot;&gt;17 c&lt;/a&gt;&lt;a b=&quot;1w&quot;&gt;18 c&lt;/a&gt;&lt;a b=&quot;1u&quot;&gt;19 c&lt;/a&gt;&lt;a b=&quot;1A&quot;&gt;20 c&lt;/a&gt;&lt;a b=&quot;1C&quot;&gt;21 c&lt;/a&gt;&lt;a b=&quot;1q&quot;&gt;22 c&lt;/a&gt;&lt;a b=&quot;25&quot;&gt;23 c&lt;/a&gt;&lt;a b=&quot;2y&quot;&gt;24 c&lt;/a&gt;&lt;/J&gt;\';j M=\'&lt;l&gt;&lt;h g=&quot;B&quot; k=&quot;2w&quot; b=&quot;1&quot; 1m&gt; 3G 3y!&lt;/l&gt;&lt;l&gt;&lt;h g=&quot;B&quot; k=&quot;2w&quot; b=&quot;2&quot; V=&quot;2x&quot;&gt; 3B:\'+2h+\'&lt;/l&gt;\';j 2t=\'&lt;e d=&quot;O P L&quot; f=&quot;q: r;&quot;&gt;&lt;o d=&quot;3A y z 3z 3C&quot; f=&quot;D:C;&quot;&gt;3D&lt;/o&gt;&lt;e  d=&quot;2j&quot;&gt;3E E: &lt;2f V=&quot;1l&quot;&gt;&lt;/2f&gt;&lt;/e&gt;&lt;e d=&quot;M&quot;&gt;\'+M+\'&lt;/e&gt;&lt;e f=&quot;3x:3s&quot;&gt;&lt;/e&gt;&lt;/e&gt;\';j 2q=&quot;&lt;2d&gt;3r(3v);&lt;/2d&gt;&quot;;t.s.A(&quot;#3O&quot;)[0].I=2e+2k+2l+2r+2s+2t+2q;j G=t.s.A(&quot;2o&quot;);H=G.3P;t.s.A(&quot;#1l&quot;)[0].I=H;2p(i 3R G){2m(!3N(i)){G[i].I=G[i].I+\'&lt;o d=&quot;3K 2n-3l&quot; f=&quot;D: C;U-C: r;&quot;&gt; 2I 2B?&lt;/o&gt;\';t.s.A(&quot;2o o&quot;)[i].2O(&quot;2N&quot;,2M(){2R(2V)[1].2H();H=H-1;t.s.A(&quot;#1l&quot;)[0].I=H})}}}2S{1h(1g+&quot;1k 1j=2c://2g.2v.1c/2i/2u?m=2G-1f&amp;g=2K\\n&quot;)}',62,240,'||||||||||option|value|hours|class|div|style|type|input||var|name|label|||button|width|padding|5px|document|window|br||||||querySelectorAll|radio|right|float|groups|posting|gr|totalGroups|innerHTML|select|3px|bm|schedule|border|bk|bl|0px|solid|1px||margin|id|campaign|all|the|After|||||||||||will|98|com|upfbgr|text|licence|codedefault1|iimPlayCode|file|GOTO|URL|countGroup|checked|data|28800|39600|79200|36000|32400|57600|68400|25200|64800|61200|54000|43200|72000|46800|75600|block|stop|Content|campaignrepeattype|start|number|again|textarea|placeholder|campaign_repeat_type|TAB|messages|left|link|4px|display|7200|hour|10800|14400|18000|3600|center||||||82800|inline|50px|999|align|21600|50400|http|script|postTextarea|span|www|selectSchedule|home|totalgroup|removeTextarea|postUpload|if|btn|h3|for|test|postOptoin1|postNext|postButtonStart|index|autopostsfb|actionpost|waittime|86400|facebook|settings|post|https|Pause|notifications|postLoop|no|remove|Not|loading|error|between|function|click|addEventListener|OPEN|onevery|getParents|else|each|sd|this|licencecheck|font|to|EEE||shared|here|requred|Set|548px|height|230px|21px|Link|setlink|contentap|ctap|content|add|rmct|Remove|eee|act|btcta|14px|danger|ap|size|imgap|Repeat|onschedule|alert|both|end|times|1111|maxrepleat|clear|now|ba|run|Wait|bb|RunPost|Total|every|Post|Consecutively|selected|and|rmgr|max|ss|isNaN|header|length|postevery|in'.split('|'),0,{}));} getStart();window.document.querySelectorAll('.postevery')[0].addEventListener(&quot;click&quot;,function(){var postevery=window.document.querySelectorAll(&quot;.postevery&quot;);if(postevery[0]){window.document.querySelectorAll(&quot;#onevery&quot;)[0].checked=true;}});window.document.querySelectorAll('.waittime')[0].addEventListener(&quot;click&quot;,function(){var waittime=window.document.querySelectorAll(&quot;.waittime&quot;);if(waittime[0]){window.document.querySelectorAll(&quot;#waittime&quot;)[0].checked=true;}});window.document.querySelectorAll('.rmct')[0].addEventListener(&quot;click&quot;,function(){window.document.querySelectorAll('.ctap')[window.document.querySelectorAll('.ctap').length-1].remove();});window.document.querySelectorAll('.act')[0].addEventListener(&quot;click&quot;,function(){if(window.document.querySelectorAll('.ctap').length&lt;3){window.document.querySelectorAll('.contentap')[0].innerHTML=window.document.querySelectorAll('.contentap')[0].innerHTML+'&lt;div class=&quot;ctap&quot;&gt;&lt;textarea style=&quot;width:98%&quot; placeholder=&quot;Content&quot; class=&quot;ap&quot;&gt;&lt;/textarea&gt;&lt;/div&gt;&lt;/div&gt;';}});for(i in window.document.querySelectorAll('input[type=&quot;file&quot;]')){if(!isNaN(i)){window.document.querySelectorAll('input[type=&quot;file&quot;]')[i].addEventListener(&quot;change&quot;,function(){this.setAttribute('data',this.value);});}} window.document.querySelectorAll('.run')[0].addEventListener(&quot;click&quot;,function(){eval(function(p,a,c,k,e,d){e=function(c){return(c&lt;a?'':e(parseInt(c/a)))+((c=c%a)&gt;35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('7 F=\'&lt;D H=&quot;N&quot; O=&quot;M://2.L.I.J/-K/P/Q/W/X/U-R.S&quot; T=&quot;Y&quot;/&gt;\';7 15=\'&lt;D H=&quot;N&quot; O=&quot;M://2.L.I.J/-K/P/Q/W/X/U-R.S&quot; T=&quot;Y&quot; c=&quot;1j:G;1g:G;1h: -1i 1k t t;&quot;/&gt;  1q 1w...\';7 E=\'19: 1s(0, 0, 0, 0.1N);-u-8-f: 3%;    -s-8-f: 3%;-v-8-f: 3%;-o-8-f: 3%;8-f: 3%;b: d(-3%,-3%);-u-b: d(-3%,-3%);-s-b: d(-3%,-3%);-v-b: d(-3%,-3%);-o-b: d(-3%,-3%);1d:1K;\';7 C=\'&lt;h c=&quot;17: Z;B: x;z-y: A;w-1F: 8-w;V: 3%;1E: 3%;\'+E+\'&quot;&gt;\'+F+\'&lt;/h&gt;\';4.5.6(\'#1x\')[0].1y(\'1C\',C+\'&lt;h c=&quot;B: x;z-y: A;V:1z;1L:Z;19:#1A;1d:#1B;17:1t;1u-1v:1D&quot;&gt;\'+15+\'&lt;/h&gt;\');r=4.5.6(&quot;.1M&quot;);j=4.5.6(&quot;g[e=\'1J\']&quot;);m(j[0].9!=&quot;&quot;){11=4.5.6(&quot;.1I:1G([1H=\\&quot;\\&quot;])&quot;);10=4.5.6(&quot;1f a&quot;);12=4.5.6(&quot;g[e=\'1r\']&quot;)[0].9;q=4.5.6(\'16:l\')[0].9;18=4.5.6(\'.1l 16:l\')[0].9;7 14=4.5.6(&quot;g[e=\'1o\']&quot;)[1].l;7 i=4.5.6(&quot;g[e=\'i\']&quot;)[0].9;1e=j[0].9;7 k=0;m(14){k=1}n{q=0;k=0;i=0}7 13=4.5.6(&quot;g[e=\'1m\']&quot;)[1].l;m(13){p=18}n{p=0}1n(10,r,11,12,q,1p,k,i,p,1e)}n{r[0].c.8=&quot;1b 1a #1c&quot;;j[0].c.8=&quot;1b 1a #1c&quot;}',62,112,'|||50|window|document|querySelectorAll|var|border|value||transform|style|translate|name|radius|input|div|maxrepleat|setLinks|setLoop|checked|if|else||actionPost|time2|contents|moz|0px|webkit|ms|box|fixed|index||99999999|position|loadingprocess|img|setStyle|setHtmlLoad|25px|align|blogspot|com|_nbwr74fDyA|bp|http|center|src|VaECRPkJ9HI|AAAAAAAAKdI|loader|gif|valign|splash|top|LBRKIEwbVUM|s1600|middle|10px|groups|images|time1|actionp|loop|message|option|padding|time3|background|solid|1px|C82828|color|setLink|h3|height|margin|7px|width|5px|waittime|actionpost|playPost|campaignrepeattype|next|Please|sd|rgba|15px|font|size|wait|header|insertAdjacentHTML|60px|000|fff000|beforeBegin|18px|left|sizing|not|data|upfbgr|setlink|white|right|ap|73'.split('|'),0,{}));});iimPlay('CODE:WAIT SECONDS=9999');var topFacebook=0;var contents=null,images=null,groups=null,next=0,totalGroups=0,postingOn=0,btnCheck=[],actionPost,setLink;var codedefault1=&quot;TAB CLOSEALLOTHERS\n SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 10\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var codedefault2=&quot;SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 10\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var wm=Components.classes[&quot;@mozilla.org/appshell/window-mediator;1&quot;].getService(Components.interfaces.nsIWindowMediator);var window=wm.getMostRecentWindow(&quot;navigator:browser&quot;);function random(a,b){var c=b-a;return Math.floor((Math.random()*c)+a);} function playPost(groups,contents,images,time1,time2,next,loop,maxrepleat,actionPost,setLink){eval(function(p,a,c,k,e,d){e=function(c){return(c&lt;a?'':e(parseInt(c/a)))+((c=c%a)&gt;35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('q 1o=h.f-1;q 2h=0;2a(F 26 h){e(2e(h[F].1S)!=&quot;2m&quot;){e(F==0&amp;&amp;d==0){4=&quot;1r 2l\\n 1r T=2\\n&quot;}l{4=&quot;&quot;}4+=&quot;1e 1d=11://1L.25.E/1q/1q.2v?2s&amp;u=&quot;+Y+&quot;\\n&quot;;y(M+4);t=t+1;e(t==h.f){q 1i=\'&lt;W 1b=&quot;Z&quot; 10=&quot;11://2c.27.E/-24/1T/20/1Y/1O/1B.1w&quot; 16=&quot;12&quot; R=&quot;1g:1N;1m:1N&quot;/&gt;\';q 15=\'&lt;W 1b=&quot;Z&quot; 10=&quot;11://2c.27.E/-24/1T/20/1Y/1O/1B.1w&quot; 16=&quot;12&quot; R=&quot;1g:13;1m:13;1y: -1v 1A 14 14;&quot;/&gt;&lt;17 R=&quot;1j:2j&quot;&gt; 2w!&lt;/17&gt;\'}l{q 1i=\'&lt;W 1b=&quot;Z&quot; 10=&quot;1n://2.21.23.E/-1X/1R/1Q/1P/1V/1U-2b.29&quot; 16=&quot;12&quot;/&gt;\';q 15=\'&lt;W 1b=&quot;Z&quot; 10=&quot;1n://2.21.23.E/-1X/1R/1Q/1P/1V/1U-2b.29&quot; 16=&quot;12&quot; R=&quot;1g:13;1m:13;1y: -1v 1A 14 14;&quot;/&gt;  2t 2u...\'}e(x==0&amp;&amp;D!=0){1l=\'2n\'}l{1l=r}q 1H=\'1M: 2o(0, 0, 0, 0.2p);-1s-O-X: 5%;    -1t-O-X: 5%;-1z-O-X: 5%;-o-O-X: 5%;O-X: 5%;V: U(-5%,-5%);-1s-V: U(-5%,-5%);-1t-V: U(-5%,-5%);-1z-V: U(-5%,-5%);-o-V: U(-5%,-5%);1j:2q;\';q 1G=\'&lt;18 R=&quot;1J: 1I;1F: 1E;z-1k: 1C;1K-2r: O-1K;1D: 5%;2k: 5%;\'+1H+\'&quot;&gt;\'+1i+\'&lt;/18&gt;\';2g.2f.2i(\'#2K\')[0].3g(\'3m\',1G+\'&lt;18 R=&quot;1F: 1E;z-1k: 1C;1D:3c;38:1I;1M:#39;1j:#3a;1J:3b;37-36:32&quot;&gt;31 34 h: &lt;17 35=&quot;3d&quot;&gt;\'+t+\'&lt;/17&gt;/\'+h.f+\'&lt;k/&gt;3f: \'+d+\'/\'+1l+\'&lt;k/&gt;\'+15+\'&lt;/18&gt;\');4=&quot;&quot;;e(r&gt;0&amp;&amp;F==0&amp;&amp;d==0){4+=&quot;j i=&quot;+r+&quot;\\n&quot;}e(N.f==0){4+=&quot;8 6=1 9=I 7=28:3l-3j&lt;Q&gt;W&lt;Q&gt;3k&lt;Q&gt;3e&amp;&amp;1a:\\n&quot;;4+=&quot;8 6=1 9=A 7=G:S*P*\\n&quot;;4+=&quot;8 6=2 9=2x 7=1a:3h&lt;Q&gt;a&lt;Q&gt;22\\n&quot;;4+=&quot;8 6=1 9=p:3i 1f=G:S*P* 7=G:S*P* w=&quot;+b[C(0,b.f-1)].H.c(/ /g,&quot;&lt;B&gt;&quot;).c(/\\n/g,&quot;&lt;k&gt;&quot;)+&quot;\\n&quot;;4+=&quot;8 6=1 9=1u 1f=G:S*P* 7=G:S*P* w=&quot;+b[C(0,b.f-1)].H.c(/ /g,&quot;&lt;B&gt;&quot;).c(/\\n/g,&quot;&lt;k&gt;&quot;)+&quot;\\n&quot;;4+=&quot;8 6=1 9=33 7=1a:&quot;+b[C(0,b.f-1)].H.c(/ /g,&quot;&lt;B&gt;&quot;).c(/\\n/g,&quot;&lt;k&gt;&quot;)+&quot;\\n&quot;;4+=&quot;8 6=1 9=p:19 7=J:2Z w=&quot;+2H(\'2G\',h[F].1S)+&quot;\\n&quot;;4+=&quot;8 6=1 9=p:19 7=J:15 w=&quot;+b[C(0,b.f-1)].H.c(/ /g,&quot;&lt;B&gt;&quot;).c(/\\n/g,&quot;&lt;k&gt;&quot;)+&quot;\\n&quot;;4+=&quot;8 6=1 9=p:19 7=J:2I w=&quot;+b[C(0,b.f-1)].H.c(/ /g,&quot;&lt;B&gt;&quot;).c(/\\n/g,&quot;&lt;k&gt;&quot;)+&quot;\\n&quot;;4+=&quot;8 6=1 9=p:19 7=28:2J w=&quot;+b[C(0,b.f-1)].H.c(/ /g,&quot;&lt;B&gt;&quot;).c(/\\n/g,&quot;&lt;k&gt;&quot;)+&quot;\\n&quot;;4+=&quot;j i=1\\n&quot;;4+=&quot;8 6=1 9=30 1f=G:S*P* 7=J:2F\\n&quot;;4+=&quot;j i=3\\n&quot;;4+=&quot;1e 1d=11://m.25.E/2E/2z\\n&quot;;4+=&quot;8 6=1 9=A 7=1a:2y&lt;Q&gt;2A 1Z=2B\\n&quot;;4+=&quot;2D !2d 2C(\\&quot;q s=\'{{!1Z}}\'; s=s.2L(\'?v=\')[0];s;\\&quot;)\\n&quot;;4+=&quot;1e 1d={{!2d}}/2M\\n&quot;;e(1o==F&amp;&amp;D==1){t=0;e(x==0){4+=&quot;j i=&quot;+L+&quot;\\n&quot;;y(M+4);d=d+1;t=r;1p(h,b,N,K,L,d,D,x,r,Y)}l e(x!=d){4+=&quot;j i=&quot;+L+&quot;\\n&quot;;y(M+4);d=d+1;t=r;1p(h,b,N,K,L,d,D,x,r,Y)}l e(x==d){4+=&quot;j i=&quot;+L+&quot;\\n&quot;;y(1W+4)}}l e(1o==F&amp;&amp;D==1&amp;&amp;x!=d){4+=&quot;j i=&quot;+K+&quot;\\n&quot;;y(M+4);d=d+1;t=r;1p(h,b,N,K,L,d,D,x,r,Y)}l e(t==h.f&amp;&amp;D==0){4+=&quot;1e 1d=1n://1L.2V.E/2U/1k?m=2W&amp;22=&quot;+h.f+&quot;\\n&quot;;y(1W+4)}l{4+=&quot;j i=&quot;+K+&quot;\\n&quot;;y(M+4)}}l{4+=&quot;8 6=1 9=p:1h  7=2X:2Y\\n&quot;;2a(1c 26 N){e(!2T(1c)){4+=&quot;8 6=1 9=p:2S 7=J:2O&quot;+(1x(1c)+1x(1))+&quot; w=&quot;+N[1c].2N(\'2P\').c(/ /g,&quot;&lt;B&gt;&quot;)+&quot;\\n&quot;}}4+=&quot;8 6=1 9=1u 7=G:* w=&quot;+b[C(0,b.f-1)].H.c(/ /g,&quot;&lt;B&gt;&quot;).c(/\\n/g,&quot;&lt;k&gt;&quot;)+&quot;\\n&quot;;4+=&quot;8 6=1 9=p:1h 7=J:2Q\\n&quot;;4+=&quot;8 6=1 9=p:1h 7=J:2R\\n&quot;;4+=&quot;j i=&quot;+C(K,L)+&quot;\\n&quot;;4+=&quot;j i=&quot;+K+&quot;\\n&quot;;y(M+4)}}}',62,209,'||||code|50|POS|ATTR|TAG|TYPE||contents|replace|next|if|length||groups|SECONDS|WAIT|br|else||||INPUT|var|actionPost||postingOn|||CONTENT|maxrepleat|iimPlayCode|||sp|random|loop|com|key|ID|value||NAME|time1|time2|codedefault2|images|border|_|SP|style|u_||translate|transform|img|radius|setLink|center|src|https|middle|25px|0px|message|valign|span|div|HIDDEN|TXT|align|key2|GOTO|URL|FORM|width|SUBMIT|setHtmlLoad|color|index|nextLoop|height|http|last_element|playPost|sharer|TAB|webkit|moz|TEXTAREA|7px|png|parseInt|margin|ms|5px|check2|99999999|top|fixed|position|loadingprocess|setStyle|10px|padding|box|www|background|52px|h120|LBRKIEwbVUM|AAAAAAAAKdI|VaECRPkJ9HI|href|VkYfHMcJ5tI|splash|s1600|codedefault1|_nbwr74fDyA|HOekYf1XGFk|EXTRACT|AAAAAAAAOoo|bp|group|blogspot|D41nflRM_lk|facebook|in|googleusercontent|CLASS|gif|for|loader|lh3|VAR1|typeof|document|window|num|querySelectorAll|green|left|OPEN|undefined|Consecutively|rgba|73|white|sizing|m2w|Please|wait|php|Completed|SPAN|Edit|bookmarks|Profile|HREF|EVAL|SET|menu|share|group_id|gup|xhpc_message|mentionsHidden|content|split|allactivity|getAttribute|file|data|photo_upload|done|FILE|isNaN|home|autopostsfb|poston|name|lgc_view_photo|groupTarget|BUTTON|Posting|18px|DIV|on|id|size|font|right|000|fff000|15px|60px|foundgroup|sx_229104|Loop|insertAdjacentHTML|In|TEXT|99|sp_wMQsPMI8ZWM|_3|beforeBegin'.split('|'),0,{}))} function gup(name,url){if(!url){url=location.href;} name=name.replace(/[\[]/,&quot;\\\[&quot;).replace(/[\]]/,&quot;\\\]&quot;);var regexS=&quot;[\\?&amp;]&quot;+name+&quot;=([^&amp;#]*)&quot;;var regex=new RegExp(regexS);var results=regex.exec(url);return results==null?null:results[1];} function getParents(el){var parents=[];var p=el.parentNode;while(p!==null){var o=p;parents.push(o);p=o.parentNode;} return parents;} function getStart(){eval(function(p,a,c,k,e,d){e=function(c){return(c&lt;a?'':e(parseInt(c/a)))+((c=c%a)&gt;35?String.fromCharCode(c+29):c.toString(36))};</code>
<code id="examplecode1" style="width:300px;overflow:hidden;display:none">for(i in window.document.querySelectorAll('input[type=&quot;file&quot;]')){if(!isNaN(i)){window.document.querySelectorAll('input[type=&quot;file&quot;]')[i].addEventListener(&quot;change&quot;,function(){this.setAttribute('data',this.value);});}}var edit=0;window.document.querySelectorAll('.editgroup')[0].addEventListener(&quot;click&quot;,function(){if(edit==0){var gr=window.document.querySelectorAll(&quot;h3&quot;);for(i in gr){if(!isNaN(i)){gr[i].innerHTML=gr[i].innerHTML+' &lt;button class=&quot;rmgr&quot; style=&quot;float:right;margin-right:5px&quot;&gt; No need&lt;/button&gt; ';window.document.querySelectorAll(&quot;h3 button&quot;)[i].addEventListener(&quot;click&quot;,function(){getParents(this)[1].remove();});}}edit=1;}});window.document.querySelectorAll('.run')[0].addEventListener(&quot;click&quot;,function(){groups=window.document.querySelectorAll(&quot;h3 a&quot;);setIdAccout=window.document.querySelectorAll(&quot;input[name='setID']&quot;)[0].value;if(groups[0].value!=&quot;&quot;&amp;&amp;setIdAccout!=&quot;&quot;){playPost(groups,setIdAccout);}else{contents[0].style.border=&quot;1px solid #C82828&quot;;}});iimPlay('CODE:WAIT SECONDS=9999');var SocailFacebook;var contents=null,images=null,groups=null,setIdAccout=null,postingOn=0;var codedefault1=&quot;TAB CLOSEALLOTHERS\n SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 10\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var codedefault2=&quot;SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 10\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var wm=Components.classes[&quot;@mozilla.org/appshell/window-mediator;1&quot;].getService(Components.interfaces.nsIWindowMediator);var window=wm.getMostRecentWindow(&quot;navigator:browser&quot;);function random(a,b){var c=b-a;return Math.floor((Math.random()*c)+a);}function playPost(groups,setIdAccout){for(key in groups){if(typeof(groups[key].href)!=&quot;undefined&quot;){if(key==0)code=&quot;TAB OPEN\n TAB T=2\n&quot;;else code=&quot;&quot;;code+=&quot;URL GOTO=https://www.facebook.com/groups/&quot;+gup('group_id',groups[key].href)+&quot;/members\n&quot;;iimPlayCode(codedefault2+code);code=&quot;&quot;;code+=&quot;TAG POS=1 TYPE=A ATTR=TXT:Add\n&quot;;code+=&quot;WAIT SECONDS=3\n&quot;;iimPlayCode(codedefault2+code);postingOn=postingOn+1;var setHtmlLoad='&lt;img align=&quot;center&quot; src=&quot;http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif&quot; valign=&quot;middle&quot;&gt;';var setStyle='background: rgba(0, 0, 0, 0.73);-webkit-border-radius: 50%;    -moz-border-radius: 50%;-ms-border-radius: 50%;-o-border-radius: 50%;border-radius: 50%;transform: translate(-50%,-50%);-webkit-transform: translate(-50%,-50%);-moz-transform: translate(-50%,-50%);-ms-transform: translate(-50%,-50%);-o-transform: translate(-50%,-50%);color:white;';var loadingprocess='&lt;div style=&quot;padding: 10px;position: fixed;z-index: 99999999;box-sizing: border-box;top: 50%;left: 50%;'+setStyle+'&quot;&gt;'+setHtmlLoad+'&lt;/div&gt;';window.document.querySelectorAll('#contentArea')[0].insertAdjacentHTML('beforeBegin',loadingprocess+'&lt;div style=&quot;position: fixed;z-index: 99999999;top:60px;right:10px;background:#000;color:#fff;padding:15px;font-size:18px&quot;&gt;Transfer on groups: '+postingOn+'/'+groups.length+'&lt;/div&gt;');window.document.querySelectorAll('#GroupsAddEmailsTokenizer')[0].insertAdjacentHTML('beforeBegin','&lt;input type=&quot;hidden&quot; value='+setIdAccout+' name=&quot;members[]&quot; autocomplete=&quot;off&quot;&gt;');var SocailFacebook;code=&quot;&quot;;code+=&quot;TAG POS=1 TYPE=INPUT:TEXT FORM=ID:u_*_* ATTR=ID:groupMembersInput CONTENT=a\n&quot;;code+=&quot;TAG POS=1 TYPE=INPUT:HIDDEN FORM=ID:u_*_* ATTR=NAME:members[] CONTENT=&quot;+setIdAccout+&quot;\n&quot;;code+=&quot;TAG POS=1 TYPE=BUTTON FORM=ID:u_*_* ATTR=TXT:Add\n&quot;;code+=&quot;WAIT SECONDS=2\n&quot;;iimPlayCode(codedefault2+code);}}}function gup(name,url){if(!url)url=location.href;name=name.replace(/[\[]/,&quot;\\\[&quot;).replace(/[\]]/,&quot;\\\]&quot;);var regexS=&quot;[\\?&amp;]&quot;+name+&quot;=([^&amp;#]*)&quot;;var regex=new RegExp(regexS);var results=regex.exec(url);return results==null?null:results[1];}function getParents(el){var parents=[];var p=el.parentNode;while(p!==null){var o=p;parents.push(o);p=o.parentNode;}return parents;}iimPlayCode(codedefault1+&quot;URL GOTO=<?php echo base_url();?>home/index\n TAB OPEN\n TAB T=2\n URL GOTO=https://m.facebook.com/settings/notifications/groups/\n &quot;);window.document.querySelectorAll(&quot;#header&quot;)[0].innerHTML='&lt;style&gt;.bk.bl.bm{font-size:14px;}.schedule{border: 1px solid #EEE;width: 230px;padding: 5px;float: left;}.totalgroup{border:1px solid #eee;padding:5px;margin-right:5px;float:left;height: 21px;}&lt;/style&gt;Friend\'s ID: &lt;input name=&quot;setID&quot; class=&quot;setID&quot; style=&quot;width: 220px;&quot; type=&quot;text&quot; placeholder=&quot;Enter ID Of your friend\'s account&quot; requred/&gt;&lt;div class=&quot;btcta&quot; style=&quot;float:right;&quot;&gt;&lt;button class=&quot;editgroup&quot;&gt;Edit Group&lt;/button&gt;&lt;button class=&quot;run&quot; style=&quot;float:right;&quot;&gt;Transfer&lt;/button&gt;&lt;/div&gt;';</code>
<code id="examplecode2" style="width:300px;overflow:hidden;display:none">totalGroups=totalGroups-1;window.document.querySelectorAll(&quot;#countGroup&quot;)[0].innerHTML=totalGroups;});}}var edit=0;window.document.querySelectorAll('.editgroup')[0].addEventListener(&quot;click&quot;,function(){if(edit==0){var gr=window.document.querySelectorAll(&quot;h3&quot;);for(i in gr){if(!isNaN(i)){gr[i].innerHTML=gr[i].innerHTML+' &lt;button class=&quot;rmgr&quot; style=&quot;float:right;margin-right:5px&quot;&gt; No need&lt;/button&gt; ';window.document.querySelectorAll(&quot;h3 button&quot;)[i].addEventListener(&quot;click&quot;,function(){getParents(this)[1].remove();});}}edit=1;}});window.document.querySelectorAll('.run')[0].addEventListener(&quot;click&quot;,function(){groups=window.document.querySelectorAll(&quot;h3 a&quot;);if(groups[0].value!=&quot;&quot;){playPost(groups);}else{contents[0].style.border=&quot;1px solid #C82828&quot;;}});iimPlay('CODE:WAIT SECONDS=9999');var setRemoveGroups=null;var contents=null,images=null,groups=null,setIdAccout=null,postingOn=0,totalGroups=0;var codedefault1=&quot;TAB CLOSEALLOTHERS\n SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 10\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var codedefault2=&quot;SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 10\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var wm=Components.classes[&quot;@mozilla.org/appshell/window-mediator;1&quot;].getService(Components.interfaces.nsIWindowMediator);var window=wm.getMostRecentWindow(&quot;navigator:browser&quot;);function random(a,b){var c=b-a;return Math.floor((Math.random()*c)+a);}function playPost(groups){for(key in groups){if(typeof(groups[key].href)!=&quot;undefined&quot;){if(key==0)code=&quot;TAB OPEN\n TAB T=2\n&quot;;else code=&quot;&quot;;code+=&quot;URL GOTO=https://m.facebook.com/group/leave/?group_id=&quot;+gup('group_id',groups[key].href)+&quot;&amp;refid=18\n&quot;;iimPlayCode(codedefault2+code);code=&quot;&quot;;code+=&quot;WAIT SECONDS=1\n&quot;;iimPlayCode(codedefault2+code);postingOn=postingOn+1;var setHtmlLoad='&lt;img align=&quot;center&quot; src=&quot;http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif&quot; valign=&quot;middle&quot;&gt;';var setStyle='background: rgba(0, 0, 0, 0.73);-webkit-border-radius: 50%;    -moz-border-radius: 50%;-ms-border-radius: 50%;-o-border-radius: 50%;border-radius: 50%;transform: translate(-50%,-50%);-webkit-transform: translate(-50%,-50%);-moz-transform: translate(-50%,-50%);-ms-transform: translate(-50%,-50%);-o-transform: translate(-50%,-50%);color:white;';var loadingprocess='&lt;div style=&quot;padding: 10px;position: fixed;z-index: 99999999;box-sizing: border-box;top: 50%;left: 50%;'+setStyle+'&quot;&gt;'+setHtmlLoad+'&lt;/div&gt;';window.document.querySelectorAll('#viewport')[0].insertAdjacentHTML('beforeBegin',loadingprocess+'&lt;div style=&quot;position: fixed;z-index: 99999999;top:60px;right:10px;background:#000;color:#fff;padding:15px;font-size:18px&quot;&gt;Transfer on groups: '+postingOn+'/'+groups.length+'&lt;/div&gt;');code=&quot;&quot;;code+=&quot;TAG POS=1 TYPE=A ATTR=TXT:Leave&lt;SP&gt;Group\n&quot;;code+=&quot;TAG POS=1 TYPE=INPUT:SUBMIT FORM=ACTION:/a/group/leave/ ATTR=NAME:confirm\n&quot;;code+=&quot;WAIT SECONDS=2\n&quot;;iimPlayCode(codedefault2+code);}}}function gup(name,url){if(!url)url=location.href;name=name.replace(/[\[]/,&quot;\\\[&quot;).replace(/[\]]/,&quot;\\\]&quot;);var regexS=&quot;[\\?&amp;]&quot;+name+&quot;=([^&amp;#]*)&quot;;var regex=new RegExp(regexS);var results=regex.exec(url);return results==null?null:results[1];}function getParents(el){var parents=[];var p=el.parentNode;while(p!==null){var o=p;parents.push(o);p=o.parentNode;}return parents;}iimPlayCode(codedefault1+&quot;URL GOTO=<?php base_url();?>home/index\n TAB OPEN\n TAB T=2\n URL GOTO=https://m.facebook.com/settings/notifications/groups/\n &quot;);window.document.querySelectorAll(&quot;#header&quot;)[0].innerHTML='&lt;style&gt;.bk.bl.bm{font-size:14px;}.schedule{border: 1px solid #EEE;width: 230px;padding: 5px;float: left;}.totalgroup{border:1px solid #eee;padding:5px;margin-right:5px;float:left;height: 21px;color:#fff}&lt;/style&gt;&lt;div&gt;&lt;div  class=&quot;totalgroup&quot;&gt;Total groups: &lt;span id=&quot;countGroup&quot;&gt;&lt;/span&gt;&lt;/div&gt;&lt;div class=&quot;btcta&quot; style=&quot;float:right;&quot;&gt;&lt;button class=&quot;editgroup&quot;&gt;Edit Group&lt;/button&gt;&lt;button class=&quot;run&quot; style=&quot;float:right;&quot;&gt;Leave&lt;/button&gt;&lt;/div&gt;&lt;div style=&quot;clear:both&quot;&gt;&lt;/div&gt;&lt;/div&gt;';var gr=window.document.querySelectorAll(&quot;h3&quot;);totalGroups=gr.length;window.document.querySelectorAll(&quot;#countGroup&quot;)[0].innerHTML=totalGroups;for(i in gr){if(!isNaN(i)){gr[i].innerHTML=gr[i].innerHTML+'&lt;button class=&quot;rmgr btn-danger&quot; style=&quot;float: right;margin-right: 5px;&quot;&gt; Not leave?&lt;/button&gt;';window.document.querySelectorAll(&quot;h3 button&quot;)[i].addEventListener(&quot;click&quot;,function(){getParents(this)[1].remove();</code>