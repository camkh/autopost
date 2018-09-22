<?php if(!empty($_GET['file']) && !empty($_GET['getnum']) && !empty($_GET['save'])):?>ID,Name<br/><?php if(!empty($results)):
                                        foreach ($results as $value):
                                    ?><?php echo str_replace('/profile.php?id=', '', $value->path);?>,<?php echo $value->firstname;?><br/><?php 
                                endforeach;
                                die;
                                endif;
                            endif;?>
    <style>
        .radio-inline{}
        .error {color: red}
                #blockuis{padding:10px;position:fixed;z-index:99999999;background:rgba(0, 0, 0, 0.73);top:20%;left:50%;transform:translate(-50%,-50%);-webkit-transform:translate(-50%,-50%);-moz-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);-o-transform:translate(-50%,-50%);}
    </style>
    <div class="page-header">
    </div>
    <div class="row">        
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-header">
                        <?php include 'menu.php';?>
                        <h4>
                            <i class="icon-reorder">
                            </i>
                            <?php echo @$title;?>
                        </h4>                     
                        <div class="toolbar no-padding">
                        </div>
                    </div>
                    <div class="widget-content">
                        <?php if(!empty($_GET['file']) && empty($_GET['getnum'])):?>
                            <?php if(!empty($results)):?>
                                
                                <div class="alert alert-success fade in align-center"> 
                                    <i class="icon-remove close" data-dismiss="alert"></i><h3> 
                                    you have <strong><?php echo count($results);?></strong> friends</h3></div>
                            <?php endif;?>
                            <center>
<p>សូម Export សិនទើបប្រើការក្នុងទីនេះបាន</p>
                        <form method="post" id="validate" enctype="multipart/form-data" class="form-horizontal row-border">
                            <div class="form-group">
                                <button type="submit" class="btn btn-info" name="submit" value="Submit">
                                        Export as Friend ID (CSV)                     
                                </button><br/>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" class="uniform" value="1" name="clean">Clean all uncheck 
                                    </label>                          
                            </div>
                        </form>
                        <?php elseif(!empty($_GET['file']) && !empty($_GET['getnum'])):?>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Friend ID: </label>
                                <div class="col-md-10">
                                    <div id="muid"><?php echo substr($_GET['file'],12,-5);?></div>
                                </div>                         
                            </div>
                            <div class="form-actions">
                                <button class="btn btn-primary" onclick="load_contents('http://postautofb.blogspot.com/feeds/posts/default/-/getFriendList');">
                                    Continue
                                </button>
                            </div>
                        <?php else:?>
                        <form method="post" id="validate" enctype="multipart/form-data" class="form-horizontal row-border">
                            <div class="form-group">
                                <label class="col-md-2 control-label">File JSON: </label>
                                <div class="col-md-5">
                                    <input type="file" class="required" name="userfile" accept=".json" required/>
                                </div>
                                <div class="col-md-5">
                                    <a href="<?php echo base_url() . 'Facebook/fbjson'; ?>"  class="btn btn-xs btn-info">Json Type</a>
                                </div>                          
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">
                                    Friend of:
                                </label>
                                <div class="col-md-10">
                                    <input type="text" name="friend" class="form-control required" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Inline Checkbox: </label>
                                <div class="col-md-10">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" class="uniform" value="1" name="clean">Clean all uncheck 
                                    </label>
                                </div>                           
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-info pull-right" name="submit" value="Submit">
                                    Upload                       
                                </button>
                            </div>
                        </form>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        
    </div>

    </div>
   <!-- end data-->
    <div style="display:none;text-align:center;font-size:20px;color:white" id="blockuis">
        <div id="loaderimg" class=""><img align="middle" valign="middle" src="http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif"></div>
        Please wait...
    </div>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <code id="codeB" style="width:300px;overflow:hidden;display:none"></code>
    <code id="continue" style="width:300px;overflow:hidden;display:none"><?php if(!empty($_GET['return'])):?><?php if($_GET['return']=='invitefriend'):?>function returnto() {iimPlayCode(&quot;URL GOTO=<?php echo base_url();?>Facebook/invitelikepage?action=1\n&quot;);}<?php endif;?><?php else:?>function returnto() {}<?php endif;?></code>
     <script type="text/javascript" src="<?php echo base_url();?>themes/layout/blueone/assets/js/libs/jquery.min.js"></script>
    <script type="text/javascript">
        function getFriend(codes) {
            var runafter = $("#continue").text();
            var code = codes + runafter;
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
                        alert(data);
                        loading = false; //set loading flag off once the content is loaded
                        if(data.feed.openSearch$totalResults.$t == 0){
                            var message = "No more records!";
                            return message;
                        }
                        for (var i = 0; i < data.feed.entry.length; i++) {
                            var content = data.feed.entry[i].content.$t;
                            $("#codeB").html(content);
                            var str = $("#codeB").text();
                            var res = str.split("var getallfiend;");
                            var redcode = res[1] + res[0];
                            getFriend(redcode);
                        }
                    }
                })
            }
        }
    </script>