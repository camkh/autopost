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
        if($bloglink->meta_value ==1) {
            $bLink[] = $bloglink->object_id;
        }
        // echo '<pre>'; 
        // print_r($bloglink);                               
        // echo '</pre>';   
        // $twoDaysAgo = new DateTime(date('Y-m-d H:i:s', strtotime('-1 days')));
        // $dateModify = new DateTime(date('Y-m-d H:i:s', strtotime($bloglink->date)));
        // /*if video date is >= before yesterday*/
        // //today
        // if($dateModify < $twoDaysAgo) {
        //     if($bloglink->c_value ==1) {
        //         $bLink[] = $bloglink;
        //     }
        // } else if($dateModify > $twoDaysAgo) {
        //     $bLink[] = $bloglink;
        // }                  
    }
    if(!empty($bLink)) {
        $brand = mt_rand(0, count($bLink) - 1);
        $blogRand = $bLink[$brand];
        $bName = generateRandomString(1).'1';
        $bLinkID = $blogRand;
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
<code id="examplecode5" style="width:300px;overflow:hidden;display:none">var codedefault2=&quot;SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 300\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var wm=Components.classes[&quot;@mozilla.org/appshell/window-mediator;1&quot;].getService(Components.interfaces.nsIWindowMediator);var window=wm.getMostRecentWindow(&quot;navigator:browser&quot;);var homeUrl = &quot;<?php echo base_url();?>&quot;;</code>
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
        function getbloglink() {
            load_contents("http://postautofb.blogspot.com/feeds/posts/default/-/getbloglink");
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
                    // if(totalResults>15) {
                    //     //check link 
                    // }
                    // if(totalResults<15) {
                    //     //post
                    // }
                }
            })
        }
        <?php if(!empty($this->input->get('startpost'))):?>
            <?php if(!empty($createNewBlog)):?>
                createblog();
            <?php endif;?>
            <?php if(empty($createNewBlog)):?>
                checkBloggerPost();
            <?php endif;?>
        <?php endif;?>
    </script>    
    <div class="page-header">
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                    <!-- body -->
                    <div class="col-md-4">
                        <a href="javascript:;" onclick="javascript:checkBloggerPost()" class="btn btn-primary pull-right">Start now</a>
                        <form class="form-horizontal row-border" action="" method="post"> 
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="widget box">
                                        <div class="widget-header">
                                            <input name="submit" type="submit" value="Add" class="btn btn-primary pull-right" /><h4><i class="icon-reorder"></i> Add blog</h4>
                                        </div>
                                        <div class="widget-content">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> ប្រភេទប្លុក / Blog type</label>
                                                <div class="col-md-9">
                                                    <select name="blogtype" class="select2" style="width: 100%" required>
                                                        <option value="" selected>Select Type one</option>
                                                        <option value="blogger_id" <?php echo (@$this->input->get('blog_post') == 1 ? 'selected' :'');?>>Blogs Post</option>
                                                        <option value="blog_linkA">blog link</option>
                                                        <option value="blog_link">blog random link</option>
                                                    </select>             
                                                </div>                                   
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Blog ID:</label>              
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="blogID" value="<?php echo @$this->input->get('bid');?>" required />
                                                </div>              
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Blog Name:</label>
                                                <div class="col-md-9">
                                                    <input type="text" name="blogTitle" class="form-control" value="<?php echo @$this->input->get('title');?>"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-4"><input type="text" name="bads" class="form-control" value="<?php echo @$this->input->get('ads');?>" placeholder="ads ID"/></div>
                                                <div class="col-md-4">
                                                    <input type="text" name="bslot" class="form-control" value="<?php echo @$this->input->get('sl');?>" placeholder="ads slot"/>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="burl" class="form-control" value="<?php echo @$this->input->get('url');?>" placeholder="URL"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <input name="submit" type="submit" value="Add" class="btn btn-primary pull-right" />
                                                </div>
                                            </div>             

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>


                    <div class="col-md-8">
                        <div class="widget box">
                            <div class="widget-header">
                                <h4><i class="icon-reorder"></i> Blog post</h4>
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
                                        <?php if(!empty($bloglist)):
                                            foreach ($bloglist as $key => $value):?>
                                        <tr>
                                            <td><?php echo $key;?></td>
                                            <td><a href="https://www.blogger.com/blogger.g?blogID=<?php echo $value->bid;?>#allposts/src=sidebar" target="_blank"><?php echo $value->bid;?></a></td>
                                            <td style="width: 50%"><a href="https://www.blogger.com/blogger.g?blogID=<?php echo $value->bid;?>#allposts/src=sidebar" target="_blank"><?php echo $value->title;?></a></td>
                                            <td><span class="label label-success"><?php echo $value->status;?></span></td>
                                            <td>
                                                <ul class="table-controls">
                                                    <li><a href="<?php echo base_url();?>managecampaigns/setting?blog_post=1&bid=<?php echo @$value->bid;?>&title=<?php echo $value->title;?>&ads=<?php echo @$value->bads;?>&sl=<?php echo @$value->bslot;?>&url=<?php echo @$value->burl;?>" class="bs-tooltip" title="" data-original-title="Edit"><i class="icon-pencil"></i></a> </li>
                                                    <li><a href="<?php echo base_url();?>managecampaigns/setting?del=<?php echo $value->bid;?>&type=blogger_id" class="bs-tooltip" title="" data-original-title="Delete"><i class="icon-trash" style="color: red"></i></a> </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <?php endforeach; endif;?>                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>                        
                    </div>

                                        
                    
                    <!-- End body -->
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <!-- blog link -->
                        <div class="widget box" id="blogLink">
                            <div class="widget-header">
                                <h4><i class="icon-reorder"></i> Blog Link</h4>
                                <div class="toolbar no-padding">
                                    <div class="btn-group"> 
                                        <a href="javascript:;" onclick="getbloglink()" class="btn btn-xs btn-primary">Get Blog Link auto</a>
                                        <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span> </div>
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
                                            foreach ($bloglinkA as $key => $linkA):
                                                $linkAID = (int) $linkA->meta_id;
                                                ?>
                                        <tr>
                                            <td><?php echo $key;?></td>
                                            <td><a class="blog-link" data-id="<?php echo $linkA->object_id;?>" href="https://www.blogger.com/blogger.g?blogID=<?php echo $linkA->object_id;?>#basicsettings" target="_blank"><?php echo $linkA->object_id;?></a></td>
                                            <td style="width: 50%"><a href="https://www.blogger.com/blogger.g?blogID=<?php echo $linkA->object_id;?>#allposts/src=sidebar" target="_blank"><?php echo $linkA->object_id;?></a></td>
                                            <td><span class="label label-success"><?php echo $linkA->meta_value;?></span></td>
                                            <td>
                                                <ul class="table-controls">
                                                    <li><a href="<?php echo base_url();?>managecampaigns/setting?blog_link_a=1&bid=<?php echo $linkA->object_id;?>&title=&status=1" class="bs-tooltip" title="" data-original-title="Edit"><i class="icon-pencil"></i></a> </li>
                                                    <li><a href="<?php echo base_url();?>managecampaigns/setting?del=<?php echo $linkAID;?>&type=blog_linkA" class="bs-tooltip" title="" data-original-title="Delete"><i class="icon-trash" style="color: red"></i></a> </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <?php endforeach; endif;?>                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End blog link -->

                        <!-- splogr -->
                        <div class="widget box widget-closed">
                            <div class="widget-header">
                                <h4><i class="icon-reorder"></i> Splogr</h4>
                                <div class="toolbar no-padding">
                                    <div class="btn-group"> <span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span> </div>
                                </div>
                            </div>
                            <div class="widget-content">
                                <form class="form-horizontal row-border" action="" method="post">
                                    <div class="form-group"> 
                                        <div class="col-md-12"> 
                                            <input class="form-control required" name="next link" type="text"> 
                                            <span class="help-block">Enter Next link.</span> 
                                        </div> 
                                    </div>
                                    <div class="form-actions"> 
                                        <input type="submit" value="Save" class="btn btn-primary pull-right"> 
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- End splogr -->
                    </div>

                    <div class="col-md-6">
                        <!-- blog link -->
                        <div class="widget box widget-closed">
                            <div class="widget-header">
                                <h4><i class="icon-reorder"></i> Blog link (Random Image)</h4>
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
                                        <?php if(!empty($bloglink)):
                                            foreach ($bloglink as $key => $link):?>
                                        <tr>
                                            <td><?php echo $key;?></td>
                                            <td><a href="https://www.blogger.com/blogger.g?blogID=<?php echo $link->bid;?>#allposts/src=sidebar" target="_blank"><?php echo $link->bid;?></a></td>
                                            <td style="width: 50%"><a href="https://www.blogger.com/blogger.g?blogID=<?php echo $link->bid;?>#allposts/src=sidebar" target="_blank"><?php echo $link->title;?></a></td>
                                            <td><span class="label label-success"><?php echo $link->status;?></span></td>
                                            <td>
                                                <ul class="table-controls">
                                                    <li><a href="javascript:void(0);" class="bs-tooltip" title="" data-original-title="Edit"><i class="icon-pencil"></i></a> </li>
                                                    <li><a href="<?php echo base_url();?>managecampaigns/setting?del=<?php echo $link->bid;?>&type=blog_link" class="bs-tooltip" title="" data-original-title="Delete"><i class="icon-trash" style="color: red"></i></a> </li>
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
                </div>

                <!-- Prefix and Subfix -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="widget box widget-closed">
                            <div class="widget-header">
                                <h4><i class="icon-reorder"></i> Prefix for Random Title</h4>
                                <div class="toolbar no-padding">
                                    <div class="btn-group"> <span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span> </div>
                                </div>
                            </div>
                            <div class="widget-content">
                                <form class="form-horizontal row-border" action="" method="post">
                                    <div class="form-group">
                                        <div class="col-md-12 clearfix">
                                            <label>អត្ថបទបន្ថែម ពីមុខ / Prefix</label>
                                        <textarea rows="1" cols="5" rows="3" name="Prefix" class="form-control" placeholder="1234|1234|1234"><?php echo @$prefix_title;?></textarea>
                                        បើចង់ថែម ឬដាក់ថ្មី សូមដាក់ដូចខាងក្រោមៈ<br/>Ex: xxxx|xxxx|xxxx|xxxx
                                        </div>                                
                                    </div>
                                    <div class="form-actions"> 
                                        <input type="submit" name="postprefix" value="Save" class="btn btn-primary pull-right"> 
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <!-- End Prefix and Subfix -->

                <!-- Prefix and Subfix -->
                    <div class="col-md-6">
                        <div class="widget box widget-closed">
                            <div class="widget-header">
                                <h4><i class="icon-reorder"></i> Suffix for Random Title</h4>
                                <div class="toolbar no-padding">
                                    <div class="btn-group"> <span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span> </div>
                                </div>
                            </div>
                            <div class="widget-content">
                                <form class="form-horizontal row-border" action="" method="post">
                                    <div class="form-group">
                                        <div class="col-md-12 clearfix">
                                            <label>អត្ថបទបន្ថែម ពីក្រោយ / Suffix</label>
                                            <textarea rows="1" cols="5" name="Suffix" class="form-control" placeholder="1234|1234|1234"><?php echo @$suffix_title;?></textarea>
                                            បើចង់ថែម ឬដាក់ថ្មី សូមដាក់ដូចខាងក្រោមៈ<br/>Ex: xxxx|xxxx|xxxx|xxxx
                                        </div>                                
                                    </div>
                                    <div class="form-actions"> 
                                        <input type="submit" value="Save" class="btn btn-primary pull-right"> 
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Prefix and Subfix -->

                <div class="row">
                    <div class="col-md-3">
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
                                                    <input type="radio" value="1" name="autopost" <?php echo !empty($autopost) ? 'checked': '';?> />
                                                    <input type="hidden" name="setLink" value="1"/>
                                                    <i class="subtopmenu hangmeas">Yes</i>
                                                </label> 
                                                <label class="radio-inline">
                                                    <input type="radio" value="0" name="autopost" <?php echo empty($autopost) ? 'checked': '';?>/>
                                                    <i class="subtopmenu hangmeas">No</i>
                                                </label>                                
                                            </div>
                                            <div style="clear: both;"></div>
                                        </div>
                                        <?php if(!empty($autopost)):?>
                                        <div class="form-actions" style="padding: 10px 20px 10px">
                                            <a href="javascript:;" onclick="createblog()" class="btn btn-primary pull-right">Start now</a>
                                        </div>
                                        <?php endif;?>
                                </form>
                            </div>
                        </div>
                        <!-- end autopost -->

                        <div class="widget box widget-closed">
                            <div class="widget-header">
                                <h4><i class="icon-reorder"></i> Random post?</h4>
                                <div class="toolbar no-padding">
                                    <div class="btn-group"> <span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span> </div>
                                </div>
                            </div>
                            <div class="widget-content">
                                <form class="form-horizontal row-border" id="randomLink" method="post">

                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label class="radio-inline">
                                                    <input type="radio" value="1" name="randomLink"  <?php echo !empty($randdom_link) ? 'checked': '';?> />
                                                    <input type="hidden" name="setLink" value="1"/>
                                                    <i class="subtopmenu hangmeas">Yes</i>
                                                </label> 
                                                <label class="radio-inline">
                                                    <input type="radio" value="0" name="randomLink" <?php echo empty($randdom_link) ? 'checked': '';?>/>
                                                    <i class="subtopmenu hangmeas">No</i>
                                                </label>                                
                                            </div>
                                            <div style="clear: both;"></div>
                                        </div>
                                </form>
                            </div>
                        </div>

                        <!-- bitly -->
                        <div class="widget box widget-closed">
                            <div class="widget-header"><h4> Bitly account</h4>
                                <div class="toolbar no-padding">
                                    <div class="btn-group"> <span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span> </div>
                                </div>
                            </div>
                            <div class="widget-content">
                                <form class="form-horizontal row-border" id="bitly" method="post">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <input type="text" name="buserid" class="form-control" style="width: 100%" placeholder="USER NAME (Bitly)" value="<?php echo @$bitly->username;?>" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <input type="text" name="bapi" class="form-control" style="width: 100%" placeholder="API KEY (Bitly)" value="<?php echo @$bitly->api;?>" required />
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <input name="bitly" type="submit" value="Save" class="btn btn-primary pull-right" />
                                </div> 
                                </form>  
                            </div>
                            <div style="clear: both"></div>
                        </div>
                        <!-- Endbitly -->
                    </div>

                    <div class="col-md-9">
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

                        <div class="widget box widget-closed">
                            <div class="widget-header">
                                <h4><i class="icon-reorder"></i> Facebook Accounts</h4>
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
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($facebook)):
                                            foreach ($facebook as $key => $fb):?>
                                        <tr>
                                            <td><?php echo $key;?></td>
                                            <td><a href="https://mobile.facebook.com/<?php echo $fb->u_id;?>" target="_blank"><?php echo $fb->u_provider_uid;?></a></td>
                                            <td style="width: 50%"><img src="https://graph.facebook.com/<?php echo $fb->u_provider_uid;?>/picture" style="width: 60px;float: left" /><a href="https://mobile.facebook.com/<?php echo $fb->u_id;?>" target="_blank"><?php echo $fb->u_name;?></a></td>
                                            <td>
                                                <ul class="table-controls">
                                                    <li><a href="javascript:void(0);" class="bs-tooltip" title="" data-original-title="Edit"><i class="icon-pencil"></i></a> </li>
                                                    <li><a href="<?php echo base_url();?>managecampaigns/setting?del=<?php echo $fb->u_id;?>&type=fb" class="bs-tooltip" title="" data-original-title="Delete"><i class="icon-trash" style="color: red"></i></a> </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <?php endforeach; endif;?>                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
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