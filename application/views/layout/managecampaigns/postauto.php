<?php if ($this->session->userdata('user_type') != 4) { ?>
 <style>
    .butt,.butt:hover {color: #fff}
    .radio-inline{}
    .error {color: red}
    #blockuis{padding:10px;position:fixed;z-index:99999999;background:rgba(0, 0, 0, 0.73);top:20%;left:50%;transform:translate(-50%,-50%);-webkit-transform:translate(-50%,-50%);-moz-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);-o-transform:translate(-50%,-50%);}
    .khmer {font-family: 'Hanuman', serif;font-size: 30px}
</style>
<link href="https://fonts.googleapis.com/css?family=Koulen" rel="stylesheet"> 
<meta http-equiv="refresh" content="60"/>
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

if(!empty($bloglinkA)) {
    $bLinkID = $bloglinkA;
    $createNewBlog = false;   
} else {
    $createNewBlog = true;
    $bNewName = generateRandomString(1).'1';
}
if(empty($bLinkID) && empty($this->input->get('createblog'))) {
    $currentURL = current_url(); //for simple URL
    $params = $_SERVER['QUERY_STRING']; //for parameters
    $fullURL = $currentURL . '?' . $params;
    echo '<script language="javascript" type="text/javascript">window.setTimeout( function(){window.location = "'.base_url().'managecampaigns/autopost?createblog=1&backto='.urlencode($fullURL).'";}, 3000 );</script>';
    exit();
}
$blogLinkID = !empty($this->input->get('blog_link_id')) ? $this->input->get('blog_link_id') : @$bLinkID;
$btemplate = "D:&bsol;&bsol;PROGRAM&bsol;&bsol;templates&bsol;&bsol;";
$blogPostID = ($this->input->get('action') =='generate') ? $staticdata->blogid : $blogLinkID;
if(!empty($this->input->get('action'))) {
    if($this->input->get('action') =='bloglink') {
        $blogPostID = $bLinkID;
    }
}
$backto = $this->input->get('backto');
$backto = str_replace('blog;_link_id', 'blog_link_id', $backto);
$backto = str_replace('blink;=', 'blink=', $backto);
$backto = str_replace('autopost;=', 'autopost=', $backto);
$backto = urlencode($backto);

?>
<code id="codeB" style="width:300px;overflow:hidden;display:none"></code>
<code id="codeC" style="width:300px;overflow:hidden;display:none">macro=&quot;CODE:&quot;;macro+=&quot;URL GOTO=https://developers.facebook.com/tools/debug/sharing/?q=xxxxxxxxxxx\n&quot;;macro+=&quot;TAG POS=1 TYPE=SPAN ATTR=TXT:We&lt;SP&gt;can't&lt;SP&gt;review&lt;SP&gt;this&lt;SP&gt;website&lt;SP&gt;because&lt;SP&gt;the*\n&quot;;retcode=iimPlay(macro);var error=true;if(retcode&lt;0){error=false;}; if(!error){macro=&quot;CODE:&quot;;macro+=&quot;URL GOTO=&quot;+homeUrl+&quot;managecampaigns/ajax?lid=&quot;+bid+&quot;&amp;p=autopostblog\n&quot;;retcode=iimPlay(macro);};if(error){macro=&quot;CODE:&quot;;macro+=&quot;URL GOTO=&quot;+homeUrl+&quot;setting?blog_link_a=1&amp;bid=&quot;+bid+&quot;&amp;title=&amp;status=2\n&quot;;macro+=&quot;WAIT SECONDS=2\n&quot;;macro+=&quot;URL GOTO=&quot;+homeUrl+&quot;managecampaigns/autopost?startpost=1\n&quot;;retcode=iimPlay(macro);}</code>
<code id="examplecode5" style="width:300px;overflow:hidden;display:none">var codedefault2=&quot;SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 300\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var wm=Components.classes[&quot;@mozilla.org/appshell/window-mediator;1&quot;].getService(Components.interfaces.nsIWindowMediator);var window=wm.getMostRecentWindow(&quot;navigator:browser&quot;);var homeUrl = &quot;<?php echo base_url();?>&quot;,pid=&quot;<?php echo @$this->input->get('pid');?>&quot;,bid=&quot;<?php echo @$blogPostID;?>&quot;,blog_link_id=&quot;<?php echo @$this->input->get('blog_link_id');?>&quot;,title=&quot;&quot;,content=&quot;&quot;,backto=&quot;<?php echo @$backto;?>&quot;;</code>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />   
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
        function createblog() {
            load_contents("http://postautofb.blogspot.com/feeds/posts/default/-/autoCreateBlogger");
        }
        function checkBloggerPost(gettype) {
            $.ajax({        
                url : 'https://www.blogger.com/feeds/<?php echo @$bLinkID;?>/posts/default?max-results=1&alt=json-in-script',
                type : 'get',
                dataType : "jsonp",
                success : function (data) {
                    loading = false; //set loading flag off once the content is loaded
                    var totalResults = data.feed.openSearch$totalResults.$t,posturl='';
                    for (var i = 0; i < data.feed.entry.length; i++) {
                        var content = data.feed.entry;
                        for (var j = 0; j < content[i].link.length; j++) {
                            if (content[i].link[j].rel == "alternate") {
                                posturl = content[i].link[j].href;
                            }
                        }
                    }
                    var str = $("#codeC").text();
                    var res = str.replace("xxxxxxxxxxx", posturl);
                    runcode(res);
                    // if(totalResults>15) {
                    //     //check link 
                    // }
                    // if(totalResults<15) {
                    //     //post
                    // }
                }
            })
        }

        function posttoMainblog() {
            load_contents("http://postautofb.blogspot.com/feeds/posts/default/-/postToMainBlog");
        }
        function postToBlogAds() {
            load_contents("http://postautofb.blogspot.com/feeds/posts/default/-/postToBlogAds");
        }
        function postToBlogLink() {
            load_contents("http://postautofb.blogspot.com/feeds/posts/default/-/postToBlogLink");
        }
        <?php if(!empty($this->input->get('action'))):?>
            <?php if($this->input->get('action') =='generate'):?>
                var timeleft = 10;
                var downloadTimer = setInterval(function(){
                  //document.getElementById("progressBar").value = 10 - timeleft;
                  timeleft -= 1;
                  if(timeleft <= 0) {
                    clearInterval(downloadTimer);
                    postToBlogAds();
                  }
                }, 1000);
            <?php endif;?>
            <?php if($this->input->get('action') =='bloglink'):?>
                var timeleft = 10;
                var downloadTimer = setInterval(function(){
                  //document.getElementById("progressBar").value = 10 - timeleft;
                  timeleft -= 1;
                  if(timeleft <= 0) {
                    clearInterval(downloadTimer);
                    postToBlogLink();
                  }
                }, 1000);
            <?php endif;?>
            <?php if($this->input->get('action') =='createblog'):?>
                var timeleft = 10;
                var downloadTimer = setInterval(function(){
                  //document.getElementById("progressBar").value = 10 - timeleft;
                  timeleft -= 1;
                  if(timeleft <= 0) {
                    clearInterval(downloadTimer);
                    createblog();
                  }
                }, 1000);
            <?php endif;?>
        <?php endif;?>
    </script>    
    <div class="page-header">
    </div>
    <div class="row">
        <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <!-- youtube -->
                        <div class="widget box" id="YoutubeChannel">
                            <div class="widget-header">
                                <h4><i class="icon-reorder"></i> Set blog Link</h4>
                                <div class="toolbar no-padding">
                                    <div class="btn-group"> <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span> </div>
                                </div>
                            </div>
                            <div class="widget-content">
                                <?php if(!empty($datapost)):
                                    $p_id = $datapost->p_id;
                                    $linkTitle = $datapost->p_name.' -blid-'.$blogLinkID;
                                    $p_title = preg_replace('/\s+/', '<sp>', $linkTitle);
                                    $yid = $datapost->yid;
                                    $p_conent = json_decode($datapost->p_conent);
                                    $bTitle = $p_conent->name;
                                    //$bContent = str_replace(' ', '<sp>', $p_conent->message);
                                    $bContent = preg_replace('/\s+/', '<sp>', $p_conent->message);
                                    $bContent = str_replace('/\n/g', '<br>', $bContent);
                                    $image = $p_conent->picture;
                                    $vid = $p_conent->vid;
                                    if(empty($vid)) {
                                        preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $p_conent->link, $matches);
                                        if (!empty($matches[1])) {
                                            $content = (!empty($matches[1]) ? $matches[1] : '');
                                            $vid = $content;
                                        }
                                    }
                                    $mainLink = @$p_conent->mainLink;
                                    ?>
                                    <form class="form-horizontal row-border" id="mainblog" method="post">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input id="btitle" type="text" name="btitle" class="form-control" style="width: 100%" placeholder="Channel ID" value="<?php echo !empty($bTitle) ? $bTitle : '';?> <?php if($this->input->get('action') == 'bloglink'):?> -blid-<?php echo $blogLinkID;?><?php endif;?>" required />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <textarea class="form-control" id="getcontent"><?php if($this->input->get('action') == 'generate'):?>&lt;img class=&quot;thumbnail noi&quot; style=&quot;text-align:center&quot; src=&quot;<?php echo @$image;?>&quot;/&gt;&lt;!--more--&gt;&lt;div&gt;&lt;b&gt;<?php echo @$bTitle;?>&lt;/b&gt;&lt;/div&gt;&lt;div class=&quot;wrapper&quot;&gt;&lt;div class=&quot;small&quot;&gt;&lt;p&gt;<?php echo trim(@$bContent);?>&lt;/p&gt;&lt;/div&gt; &lt;a href=&quot;#&quot; class=&quot;readmore&quot;&gt;... Click to read more&lt;/a&gt;&lt;/div&gt;&lt;div style=&quot;text-align: center;&quot;&gt;&lt;script async src=&quot;//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js&quot; &gt;&lt;/script&gt;&lt;script&gt;document.write(inSide);(adsbygoogle = window.adsbygoogle || []).push({});&lt;/script&gt;&lt;/div&gt;&lt;div&gt;Others news:&lt;/div&gt;&lt;iframe width=&quot;100%&quot; height=&quot;280&quot; src=&quot;https://www.youtube.com/embed/<?php echo @$vid;?>&quot; frameborder=&quot;0&quot; allow=&quot;autoplay; encrypted-media&quot; allowfullscreen&gt;&lt;/iframe&gt;&lt;div style=&quot;text-align: center;&quot;&gt;&lt;script async src=&quot;//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js&quot; &gt;&lt;/script&gt;&lt;script&gt;document.write(inSide);(adsbygoogle = window.adsbygoogle || []).push({});&lt;/script&gt;&lt;/div&gt;<?php endif;?><?php if($this->input->get('action') == 'bloglink'):?><?php echo $p_title;?>&lt;br&gt;#กดแชร์ 👉 กดดูได้เลย 👇&lt;a href=&quot;<?php echo @$mainLink;?>&quot;&gt;&lt;img style=&quot;border:1px solid #000;display: none;&quot; src=&quot;<?php echo @$image;?>&quot; alt=&quot;&quot; class=&quot;wp-image-45&quot;/&gt;&lt;/a&gt;&lt;table width=&quot;100%&quot; border=&quot;0&quot; align=&quot;center&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot;&gt;&lt;tr&gt;&lt;td colspan=&quot;3&quot; style=&quot;background:#000000;height: 280px;overflow: hidden;background: no-repeat center center;background-size: cover; background: #000 center center no-repeat; background-size: 100%;border: 1px solid #000; background-image:url(<?php echo @$image;?>);&quot;&gt;&lt;a href=&quot;<?php echo @$mainLink;?>&quot; target=&quot;_top&quot; rel=&quot;nofollow&quot; style=&quot;display:block;height:280px;width:100%; text-align:center; background:url(https://3.bp.blogspot.com/-3ii7X_88VLs/XEs-4wFXMXI/AAAAAAAAiaw/d_ldK-ae830UCGsyOl0oEqqwDQwd_TqEACLcBGAs/s90/youtube-play-button-transparent-png-15.png) no-repeat center center;&quot;&gt; &lt;/a&gt;&lt;/td&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td style=&quot;background:#000 url(https://2.bp.blogspot.com/-Z_lYNnmixpM/XEs6o1hpTUI/AAAAAAAAiak/uPb1Usu-F-YvHx6ivxnqc1uSTIAkLIcxwCLcBGAs/s1600/l.png) no-repeat bottom left; height:39px; width:237px;margin:0;padding:0;&quot;&gt;&lt;a href=&quot;<?php echo @$mainLink;?>&quot; target=&quot;_top&quot; rel=&quot;nofollow&quot; style=&quot;display:block;height:39px;width:100%;&quot;&gt; &lt;/a&gt;&lt;/td&gt;&lt;td style=&quot;background:#000 url(https://1.bp.blogspot.com/-9nWJSQ3HKJs/XEs6o7cUv2I/AAAAAAAAiag/sAiHoM-9hKUOezozem6GvxshCyAMp_n_QCLcBGAs/s1600/c.png) repeat-x bottom center; height:39px;margin:0;padding:0;&quot;&gt; &lt;/td&gt;&lt;td style=&quot;background:#000 url(https://2.bp.blogspot.com/-RmcnX0Ej1r4/XEs6o-Fjn9I/AAAAAAAAiac/j50SWsyrs8sA5C8AXotVUG7ESm1waKxPACLcBGAs/s1600/r.png) no-repeat bottom right; height:39px; width:151px;margin:0;padding:0;&quot;&gt; &lt;/td&gt;&lt;/tr&gt;&lt;/table&gt;<?php endif;?></textarea>
                                            </div>
                                        </div>
                                    </form>
                                <?php endif;?>
                                <?php if(!empty($this->input->get('addbloglink'))):?>
                                <form class="form-horizontal row-border" id="blink" method="post">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <input type="text" name="blink" class="form-control" style="width: 100%" placeholder="Channel ID" value="<?php echo !empty($this->input->get('addbloglink')) ? $this->input->get('addbloglink') : '';?>" required />
                                        <input type="text" name="pid" class="form-control" style="width: 100%" placeholder="Channel ID" value="<?php echo !empty($this->input->get('pid')) ? $this->input->get('pid') : '';?>" required />
                                        <input type="text" name="bid" class="form-control" style="width: 100%" placeholder="Channel ID" value="<?php echo !empty($this->input->get('bid')) ? $this->input->get('bid') : '';?>" required />
                                        <input type="text" name="blog_link_id" class="form-control" style="width: 100%" placeholder="Channel ID" value="<?php echo !empty($this->input->get('blog_link_id')) ? $this->input->get('blog_link_id') : '';?>" required />
                                    </div>
                                </div>
                                <div class="form-actions" style="padding: 10px 20px 10px">
                                    <input id="setblink" name="bLink" type="submit" value="Save" class="btn btn-primary pull-right" />
                                </div> 
                                </form> 
                            <?php endif;?>
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
            $("input[name=autopost]").click(function(){
                var values = $('#autopost').serialize();
                $.ajax({
                    url: "<?php echo base_url();?>managecampaigns/setting",
                    data: values ,
                    type: "post",
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