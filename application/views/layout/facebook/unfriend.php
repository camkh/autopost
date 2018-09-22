    <style>
        .radio-inline{}
        .error {color: red}
        #blockuis{padding:10px;position:fixed;z-index:99999999;background:rgba(0, 0, 0, 0.73);top:20%;left:50%;transform:translate(-50%,-50%);-webkit-transform:translate(-50%,-50%);-moz-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);-o-transform:translate(-50%,-50%);}
    </style>
    <script type="text/javascript" src="<?php echo base_url();?>themes/layout/blueone/assets/js/libs/jquery.min.js"></script>   
    <div class="page-header">
    </div>
    <div style="display:none;text-align:center;font-size:20px;color:white" id="blockuis">
        <div id="loaderimg" class=""><img align="middle" valign="middle" src="http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif"></div>
        Please wait...
    </div>
    <?php
    $log_id = $this->session->userdata('user_id');
    $email = $this->session->userdata('email');
    $type = $this->session->userdata('user_type');
    ?>
    <div class="row">        
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-header">
                        <?php include 'menu.php';?>
                        <h4>
                            <i class="icon-reorder"></i> <?php echo @$title;?></h4>                            
                                <div class="btn-group"> <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span> </div>
                            </div>
                    </div>
                    <div class="widget-content">
                            <div class="form-actions">
                                <center><button class="btn btn btn-google-plus" onclick="load_contents ('http://postautofb.blogspot.com/feeds/posts/default/-/unfriendAllatOnce');">
                        Remove now
                    </button></center>
                            </div>
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
    <script type="text/javascript">
        function runcode(codes) {
            var code = codes;
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