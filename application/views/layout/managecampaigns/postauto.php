<?php if ($this->session->userdata('user_type') != 4) { ?>
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
        // echo '<pre>'; 
        // print_r($bloglink);                               
        // echo '</pre>';   
        $twoDaysAgo = new DateTime(date('Y-m-d H:i:s', strtotime('-1 days')));
        $dateModify = new DateTime(date('Y-m-d H:i:s', strtotime($bloglink->date)));
        /*if video date is >= before yesterday*/
        //today
        if($dateModify < $twoDaysAgo) {
            if($bloglink->status ==1) {
                $bLink[] = $bloglink;
            }
        } else if($dateModify > $twoDaysAgo) {
            $bLink[] = $bloglink;
        }                  
    }
    if(!empty($bLink)) {
        $brand = mt_rand(0, count($bLink) - 1);
        $blogRand = $bLink[$brand];
        $bName = $blogRand->title;
        $bLinkID = $blogRand->bid;
        $bint = (int) filter_var($bName, FILTER_SANITIZE_NUMBER_INT);
        $bArr = explode($bint, $bName);
        $bNewName = $bArr[0]. ($bint + 1);
        $createNewBlog = false;
    } else {
        $createNewBlog = true;
        $bNewName = generateRandomString(1).'1';
    }
    
} else {
    $createNewBlog = true;
    $bNewName = generateRandomString(1).'1';
}
$btemplate = "D:&bsol;&bsol;PROGRAM&bsol;&bsol;templates&bsol;&bsol;";
?>
<code id="codeB" style="width:300px;overflow:hidden;display:none"></code>
<code id="examplecode5" style="width:300px;overflow:hidden;display:none">var codedefault2=&quot;SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 300\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var wm=Components.classes[&quot;@mozilla.org/appshell/window-mediator;1&quot;].getService(Components.interfaces.nsIWindowMediator);var window=wm.getMostRecentWindow(&quot;navigator:browser&quot;);var homeUrl = &quot;<?php echo base_url();?>&quot;,pid=&quot;<?php echo @$this->input->get('pid');?>&quot;,bid=&quot;<?php echo @$this->input->get('bid');?>&quot;,blog_link_id=&quot;<?php echo @$this->input->get('blog_link_id');?>&quot;,title=&quot;<?php echo @$this->input->get('bid');?>&quot;,content=&quot;<?php echo @$this->input->get('bid');?>&quot;;</code>
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
        <?php if(!empty($this->input->get('action'))):?>
            <?php if($this->input->get('action') == 'generate'):?>
                //posttoMainblog();
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
                                    $yid = $datapost->yid;
                                    $p_conent = json_decode($datapost->p_conent);
                                    $bTitle = $p_conent->name;
                                    $bContent = $p_conent->message;
                                    ?>
                                    <form class="form-horizontal row-border" id="mainblog" method="post">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input id="btitle" type="text" name="btitle" class="form-control" style="width: 100%" placeholder="Channel ID" value="<?php echo !empty($bTitle) ? $bTitle : '';?>" required />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <textarea class="form-control"><img class="thumbnail noi" style="text-align:center" src="'.$image.'"/><!--more--><div><b>'.$title.'</b></div><div class="wrapper"><div class="small"><p>'.$conent.'</p></div> <a href="#" class="readmore">... Click to read more</a></div><div style="text-align: center;"><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js" ></script><script>document.write(inSide);(adsbygoogle = window.adsbygoogle || []).push({});</script></div><div>Others news:</div><iframe width="100%" height="280" src="https://www.youtube.com/embed/'.$vid.'" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe><div style="text-align: center;"><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js" ></script><script>document.write(inSide);(adsbygoogle = window.adsbygoogle || []).push({});</script></div></textarea>
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