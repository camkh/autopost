<?php if ($this->session->userdata('user_type') != 4) {
 ?>
 <style>
    .butt,.butt:hover {color: #fff}
    .radio-inline{}
    .error {color: red}
    #blockuis{padding:10px;position:fixed;z-index:99999999;background:rgba(0, 0, 0, 0.73);top:20%;left:50%;transform:translate(-50%,-50%);-webkit-transform:translate(-50%,-50%);-moz-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);-o-transform:translate(-50%,-50%);}
    .khmer {font-family: 'Hanuman', serif;font-size: 30px}
</style>
<link href="https://fonts.googleapis.com/css?family=Koulen" rel="stylesheet"> 
<div style="display:none;text-align:center;font-size:20px;color:white" id="blockuis">
    <div id="loaderimg" class=""><img align="middle" valign="middle" src="http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif"/>
    </div>
    Please wait...
</div>
<?php
function generateRandomString($length = 10) {
    $characters = 'abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
if(!empty($bloglinkA[0])) {
    $bLink = array();
    foreach ($bloglinkA as $key => $bloglink) {
        if($bloglink->meta_value ==1) {
            $bLink[] = $bloglink->object_id;
        }
    }
    if(!empty($bLink)) {
        $brand = mt_rand(0, count($bLink) - 1);
        $blogRand = $bLink[$brand];
        $bLinkID = $blogRand;
        $bNewName = generateRandomString(1).'1';
        $createNewBlog = false;
    } else {
        $createNewBlog = true;
        $bNewName = generateRandomString(1).'1';
    }
    
} else {
    $createNewBlog = true;
    $bNewName = generateRandomString(1).'1';
}
if(!empty($this->input->get('checkspamurl')) && !empty($this->input->get('bid'))) {
    $bLinkID = $this->input->get('bid');
} else {
    $bLinkID = $bLinkID;
}
if(empty($bLinkID) && empty($this->input->get('createblog')) && empty($this->input->get('changeblogurl'))) {
    $currentURL = current_url(); //for simple URL
    $params = $_SERVER['QUERY_STRING']; //for parameters
    $fullURL = $currentURL . '?' . $params;
    echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/autopost?createblog=1&backto='.urlencode($fullURL).'";}, 300 );</script>';
    exit();
}
//$btemplate = "D:&bsol;&bsol;PROGRAM&bsol;&bsol;templates&bsol;&bsol;";
$setTemplate = 0;
$btemplate = 0;
if(!empty($autopost->templateLink)) {
    $setTemplate = 1;
    $btemplate = trim($autopost->templateLink);
    $btemplate = preg_replace("/\//",'&bsol;&bsol;',trim($btemplate));
    $btemplate = preg_replace('/\s+/', '&lt;SP&gt;', $btemplate);
} else {
    $setTemplate = 0;
    $btemplate = 0;
}
$backto = @$this->input->get('backto');
$backto = str_replace('blog;_link_id', 'blog_link_id', $backto);
$backto = str_replace('blink;=', 'blink=', $backto);
$backto = str_replace('autopost;=', 'autopost=', $backto);
$backto = htmlspecialchars($backto);
$gemail = $this->session->userdata ('gemail');
if(!empty($this->input->get('changeblogurl'))) {
    if(!empty($this->input->get('bid'))) {
        $bLinkID = $this->input->get('bid');
    } else {
        if(count($blogspam) < 1 ) {
            echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'facebook/shareation?post=getpost";}, 0 );</script>';
            die;
        }
        $spamRan = mt_rand(0, count($blogspam) - 1);
        $spamid = $blogspam[$spamRan];
        $bLinkID = $spamid;
    }
}
if(!empty($this->input->get('glogin'))) {
    $backto = base_url().'facebook/shareation?post=getpost';
}
$glogin = @$this->input->get('glogin');
$glogin = str_replace('blog;_link_id', 'blog_link_id', $glogin);
$glogin = str_replace('blink;=', 'blink=', $glogin);
$glogin = str_replace('autopost;=', 'autopost=', $glogin);
?>
<code id="codeB" style="width:300px;overflow:hidden;display:none"></code>
<code id="codeC" style="width:300px;overflow:hidden;display:none">macro=&quot;CODE:&quot;;macro+=&quot;URL GOTO=https://developers.facebook.com/tools/debug/sharing/?q=xxxxxxxxxxx\n&quot;;macro+=&quot;TAG POS=1 TYPE=SPAN ATTR=TXT:We&lt;SP&gt;can't&lt;SP&gt;review&lt;SP&gt;this&lt;SP&gt;website&lt;SP&gt;because&lt;SP&gt;the*\n&quot;;retcode=iimPlay(macro);var error=true;if(retcode&lt;0){error=false;}; if(!error){macro=&quot;CODE:&quot;;macro+=&quot;URL GOTO=<?php 
    if(!empty($this->input->get('checkspamurl')) && !empty($this->input->get('bid'))):?><?php echo $backto;?><?php else:?>&quot;+homeUrl+&quot;managecampaigns/ajax?lid=&quot;+bid+&quot;&amp;p=autopostblog<?php endif;?>\n&quot;;retcode=iimPlay(macro);};if(error){macro=&quot;CODE:&quot;;<?php 
    if(!empty($this->input->get('checkspamurl')) && !empty($this->input->get('bid'))):?>macro += &quot;URL GOTO=&quot; + homeUrl + &quot;managecampaigns/autopost?changeblogurl=1&amp;bid=<?php echo @$this->input->get('bid');?>&amp;backto=<?php echo urlencode(base_url().'managecampaigns/posttotlogLink?pid='.$this->input->get('pid').'&bid='.$this->input->get('bid'));?>\n&quot;;<?php else:?>macro+=&quot;URL GOTO=&quot;+homeUrl+&quot;managecampaigns/setting?blog_link_a=1&amp;bid=&quot;+bid+&quot;&amp;title=&amp;status=2\n&quot;;macro+=&quot;WAIT SECONDS=2\n&quot;;macro+=&quot;URL GOTO=&quot;+homeUrl+&quot;managecampaigns/autopost?startpost=1\n&quot;;<?php endif;?>retcode=iimPlay(macro);}iimPlay(&quot;CODE:&quot;);</code>
<code id="codeD" style="width:300px;overflow:hidden;display:none">mm=&quot;CODE:&quot;;mm+=&quot;URL GOTO=&quot;+homeUrl+&quot;managecampaigns/account\n&quot;;mm+='TAG POS=1 TYPE=DIV ATTR=TXT:<?php echo @$gemail;?>\n';mm+=&quot;WAIT SECONDS=15\n&quot;;mm+=&quot;URL GOTO=&quot;+homeUrl+&quot;managecampaigns/autopost?start=1\n&quot;;retcode=iimPlay(mm);</code>
<?php if(!empty($this->input->get('bitly'))):?><code id="bitly" style="width:300px;overflow:hidden;display:none">var links=&quot;<?php echo $this->input->get('bitly');?>&quot;,pid=&quot;<?php echo @$this->input->get('pid');?>&quot;;</code><?php endif;?>
<?php if(!empty($this->input->get('glogin'))):?><code id="codeE" style="width:300px;overflow:hidden;display:none">mm=&quot;CODE:&quot;;mm+=&quot;SET !ERRORIGNORE YES\n&quot;;mm+=&quot;URL GOTO=&quot;+homeUrl+&quot;managecampaigns/account\n&quot;;mm+=&quot;WAIT SECONDS=10\n&quot;;mm+='TAG POS=1 TYPE=DIV ATTR=TXT:<?php echo !empty($this->session->userdata ( 'gemail' )) ? $this->session->userdata ( 'gemail' ) : @$json_a->email; ?>\n';mm+=&quot;WAIT SECONDS=15\n&quot;;mm+=&quot;URL GOTO=<?php echo !empty($this->input->get('glogin')) ? @$glogin : '&quot;+homeUrl+&quot;managecampaigns/autopost?start=1'; ?>\n&quot;;retcode=iimPlay(mm);if(retcode&lt;0){errtext=iimGetLastError();macro=&quot;CODE:&quot;;macro+=&quot;URL GOTO=<?php echo !empty($this->input->get('glogin')) ? @$glogin : '&quot;+homeUrl+&quot;managecampaigns/autopost?start=1'; ?>\n&quot;;retcode=iimPlay(macro);}</code><?php endif;?>
<code id="examplecode5" style="width:300px;overflow:hidden;display:none">var codedefault2=&quot;SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 300\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var wm=Components.classes[&quot;@mozilla.org/appshell/window-mediator;1&quot;].getService(Components.interfaces.nsIWindowMediator);var window=wm.getMostRecentWindow(&quot;navigator:browser&quot;);var bname = &quot;<?php echo @$bNewName;?>&quot;,bid = &quot;<?php echo @$bLinkID;?>&quot;, homeUrl = &quot;<?php echo base_url();?>&quot;, template = &quot;<?php echo @$setTemplate;?>&quot;, tempfolder = &quot;<?php echo @$btemplate;?>&quot;,backto=&quot;<?php echo @$backto;?>&quot;;</code>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />   
<meta http-equiv="refresh" content="60"/>
    <script type="text/javascript">
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
        function load_contents(url,addidion){
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
                            if(addidion) {
                               str = addidion + str;
                            }
                            runcode(str);
                        }
                    }
                })
            }
        }
        function changeBlogURL() {
            load_contents("http://postautofb.blogspot.com/feeds/posts/default/-/changeBlogURL");
        }
        function createblog() {
            <?php if(!empty($bloglinkA)):?><?php if(count($bloglinkA)> 95 ):?>
                window.setTimeout( function(){window.location = "<?php echo base_url();?>managecampaigns/autopost?changeblogurl=1&bid=0&backto=<?php echo $backto;?>";}, 3000 );
                <?php else:?>
                    load_contents("http://postautofb.blogspot.com/feeds/posts/default/-/autoCreateBlogger");
                <?php endif;?>
            <?php else:?>
                load_contents("http://postautofb.blogspot.com/feeds/posts/default/-/autoCreateBlogger");
            <?php endif;?>
        }
        function checkBloggerPost(gettype) {
            <?php if($isAccessTokenExpired):?>
            logginfirst();
            <?php endif;?>
            $.ajax({        
                url : 'https://www.blogger.com/feeds/<?php echo @$bLinkID;?>/posts/default?max-results=1&alt=json-in-script',
                type : 'get',
                dataType : "jsonp",
                success : function (data) {
                    loading = false; //set loading flag off once the content is loaded
                    var totalResults = data.feed.openSearch$totalResults.$t,posturl='';
                    if(totalResults==0) {
                        var backto = "<?php echo base_url().'managecampaigns/posttotlogLink?pid='.$this->input->get('pid').'&bid='.$this->input->get('bid');?>";
                        window.location.href = backto;
                    }
                    if(totalResults>0) {
                        if(data.feed.entry.length>0) {
                            for (var i = 0; i < data.feed.entry.length; i++) {
                                var content = data.feed.entry;
                                for (var j = 0; j < content[i].link.length; j++) {
                                    if (content[i].link[j].rel == "alternate") {
                                        posturl = content[i].link[j].href;
                                    }
                                }
                            }
                        }
                    }
                    if(totalResults>0) {
                        var str = $("#codeC").text();
                        var res = str.replace("xxxxxxxxxxx", posturl);
                        runcode(res);
                        // if(totalResults>15) {
                        //     createblog();
                        // }
                        // if(totalResults<15) {
                        //     alert(totalResults);
                        //     var str = $("#codeC").text();
                        //     var res = str.replace("xxxxxxxxxxx", posturl);
                        //     runcode(res);
                        // }
                    }
                    
                }
            })
        }

        function logginfirst() {
            var str = $("#codeD").text();
            runcode(str);
        }
        function glogin() {
            var str = $("#codeE").text();
            runcode(str);
        }
        function bitly() {
            var str = $("#bitly").text();
            load_contents("http://postautofb.blogspot.com/feeds/posts/default/-/bitly",str);
        }
        <?php if(!empty($postAuto)):
         if(!empty($this->input->get('startpost'))):?>
            <?php if(!empty($createNewBlog)):?>
                var timeleft = 10;
                var downloadTimer = setInterval(function(){
                  timeleft -= 1;
                  if(timeleft <= 0) {
                    clearInterval(downloadTimer);
                    createblog();
                  }
                }, 1000);
            <?php endif;?>
            <?php if(empty($createNewBlog)):?>
                 var timeleft = 10;
            var downloadTimer = setInterval(function(){
              //document.getElementById("progressBar").value = 10 - timeleft;
              timeleft -= 1;
              if(timeleft <= 0) {
                clearInterval(downloadTimer);
                checkBloggerPost();
              }
            }, 1000);
            <?php endif;?>
        <?php endif;endif;?>
        <?php if(!empty($postAuto)):
         if(!empty($this->input->get('start'))):?>
            var timeleft = 10;
            var downloadTimer = setInterval(function(){
              //document.getElementById("progressBar").value = 10 - timeleft;
              timeleft -= 1;
              if(timeleft <= 0) {
                clearInterval(downloadTimer);
                checkBloggerPost();
              }
            }, 10);
        <?php endif;endif;?>
        <?php if(!empty($this->input->get('glogin'))):?>
            var timeleft = 10;
            var downloadTimer = setInterval(function(){
              //document.getElementById("progressBar").value = 10 - timeleft;
              timeleft -= 1;
              if(timeleft <= 0) {
                clearInterval(downloadTimer);
                glogin();
              }
            }, 1000);
        <?php endif;?>
        <?php if(empty($bLinkID)):?>
            createblog();
        <?php endif;?>
        <?php if(!empty($this->input->get('createblog'))):?>
            createblog();
        <?php endif;?>
        <?php if(!empty($this->input->get('changeblogurl'))):?>
            changeBlogURL();
        <?php endif;?>
        <?php if(!empty($this->input->get('checkspamurl')) && !empty($this->input->get('bid'))):?>
            checkBloggerPost();
        <?php endif;?>
        <?php if(!empty($this->input->get('bitly'))):?>
            bitly();
        <?php endif;?>
    </script>    
    <div class="page-header">
    </div>
    <div class="row">
        <div class="col-md-12">
                <div class="row">
                    <a href="javascript:;" onclick="checkBloggerPost()" class="btn btn-primary pull-right">Start now</a>
                    <div class="col-md-6">
                        <!-- blog link -->
                        <div class="widget box">
                            <div class="widget-header">
                                <h4><i class="icon-reorder"></i> Blog Link</h4>
                                <div class="toolbar no-padding">
                                    <div class="btn-group"> <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span> </div>
                                </div>
                            </div>
                            <div class="widget-content">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($bloglinkA)):
                                            foreach ($bloglinkA as $key => $linkA):?>
                                        <tr>
                                            <td><?php echo $key;?></td>
                                            <td><a href="https://www.blogger.com/blogger.g?blogID=<?php echo $linkA->object_id;?>#allposts/src=sidebar" target="_blank"><?php echo $linkA->object_id;?></a></td>
                                            <td style="width: 50%"><a href="https://www.blogger.com/blogger.g?blogID=<?php echo $linkA->object_id;?>#allposts/src=sidebar" target="_blank"><?php echo $linkA->object_id;?></a></td>
                                            <td><span class="label label-success"><?php echo $linkA->meta_value;?></span></td>
                                            <td>
                                                <ul class="table-controls">
                                                    <li><a href="javascript:void(0);" class="bs-tooltip" title="" data-original-title="Edit"><i class="icon-pencil"></i></a> </li>
                                                    <li><a href="<?php echo base_url();?>managecampaigns/setting?del=<?php echo $linkA->meta_id;?>&type=blog_linkA" class="bs-tooltip" title="" data-original-title="Delete"><i class="icon-trash" style="color: red"></i></a> </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <?php endforeach; endif;?>                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End blog link -->
                    </div>

                    <div class="col-md-6">
                        <div class="widget box widget-closed">
                            <div class="widget-header">
                                <h4><i class="icon-reorder"></i> Autopost</h4>
                                <div class="toolbar no-padding">
                                    <div class="btn-group"> <span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span> </div>
                                </div>
                            </div>
                            <div class="widget-content">
                                <form class="form-horizontal row-border" id="autopost" method="post">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label class="radio-inline">
                                                    <input type="radio" value="1" name="autopost" <?php echo !empty($autopost->autopost) ? 'checked': '';?> />
                                                    <input type="hidden" name="setPostAuto" value="1"/>
                                                    <i class="subtopmenu hangmeas">Yes</i>
                                                </label> 
                                                <label class="radio-inline">
                                                    <input type="radio" value="0" name="autopost" <?php echo empty($autopost->autopost) ? 'checked': '';?>/>
                                                    <i class="subtopmenu hangmeas">No</i>
                                                </label>                                
                                            </div>
                                            <div style="clear: both;"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label class="radio-inline">
                                                    <input type="radio" value="1" name="posttype" <?php echo !empty($autopost->posttype) ? 'checked': '';?> />
                                                    <i class="subtopmenu hangmeas">Google API</i>
                                                </label> 
                                                <label class="radio-inline">
                                                    <input type="radio" value="0" name="posttype" <?php echo empty($autopost->posttype) ? 'checked': '';?>/>
                                                    <i class="subtopmenu hangmeas">Post by Manaully</i>
                                                </label>                                
                                            </div>
                                            <div style="clear: both;"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text" name="titleExcept" class="form-control" style="width: 100%" placeholder="Title Except... Ex: 16/4/26562|16-4-2562" value="<?php echo @$autopost->titleExcept;?>" required />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text" name="bloggerTemplate" class="form-control" style="width: 100%" placeholder="blogger Template" value="<?php echo @$autopost->templateLink;?>" required />
                                            </div>
                                        </div>
                                        
                                        <div class="form-actions" style="padding: 10px 20px 10px">
                                            <input name="saveAuto" type="button" value="Save" class="btn btn-primary pull-right" />
                                            <?php if(!empty($autopost)):?><a href="javascript:;" onclick="createblog()" class="btn btn-primary pull-right">Start now</a><?php endif;?>
                                        </div>
                                </form>
                            </div>
                        </div>
                        <!-- end autopost -->
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <!-- youtube -->
                        <div class="widget box" id="YoutubeChannel">
                            <div class="widget-header">
                                <h4><i class="icon-reorder"></i> Youtube Channel</h4>
                                <div class="toolbar no-padding">
                                    <div class="btn-group"> <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span> </div>
                                </div>
                            </div>
                            <div class="widget-content">
                                <form class="form-horizontal row-border" id="ytid" method="post">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <input type="text" name="ytid" class="form-control" style="width: 100%" placeholder="Channel ID" value="<?php echo @$bitly->username;?>" required />
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="ytname" class="form-control" style="width: 100%" placeholder="Channel Name" value="<?php echo @$bitly->api;?>" required />
                                    </div>
                                </div>
                                <div class="form-actions" style="padding: 10px 20px 10px">
                                    <input name="bitly" type="submit" value="Save" class="btn btn-primary pull-right" />
                                </div> 
                                </form> 

                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($ytdata)):
                                            foreach ($ytdata as $key => $yt):?>
                                        <tr>
                                            <td><?php echo $key;?></td>
                                            <td style="width: 10%"><a href="https://www.youtube.com/channel/<?php echo $yt->ytid;?>/videos" target="_blank"><?php echo $yt->ytid;?></a></td>
                                            <td style="width: 30%"><?php echo $yt->ytname;?></td>
                                            <td style="width: 80px"><?php echo $yt->status;?></td>
                                            <td><?php 
                                            $newformat = date('Y-m-d',$yt->date);
                                            echo $newformat;?></td>
                                            <td>
                                                <ul class="table-controls">
                                                    <li><a href="<?php echo base_url();?>managecampaigns/setting?del=<?php echo $yt->ytid;?>&type=youtubeChannel" class="bs-tooltip" title="" data-original-title="Delete"><i class="icon-trash" style="color: red"></i></a> </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <?php endforeach; endif;?>                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End youtube -->
                    </div>
                </div>
        </div>
    </div>

    </div>
    <script>
        $( document ).ready(function() {
            $("input[name=randomLink]").click(function(){
                var values = $('#randomLink').serialize();
                $.ajax({
                    url: "<?php echo base_url();?>managecampaigns/setting",
                    type: "post",
                    data: values ,
                    success: function (response) {
                       alert('Saved!');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                       console.log(textStatus, errorThrown);
                    }
                });
            });

            /*autopost*/
            $("input[name=saveAuto]").click(function(){
                var values = $('#autopost').serialize();
                $.ajax({
                    url: "<?php echo base_url();?>managecampaigns/autopost",
                    type: "post",
                    data: values ,
                    success: function (response) {
                       alert('Saved!');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                       console.log(textStatus, errorThrown);
                    }
                });
            });
        });


        function getattra(e) {
            $("#singerimageFist").val(e);
            $("#imageviewFist").html('<img style="width:100%;height:55px;" src="' + e + '"/>');
        }
    </script>

    <?php

} else {
    echo '<div class="alert fade in alert-danger" >
                            <strong>You have no permission on this page!...</strong> .
                        </div>';
}
?>