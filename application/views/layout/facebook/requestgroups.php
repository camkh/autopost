 <script type="text/javascript" src="<?php echo base_url();?>themes/layout/blueone/assets/js/libs/jquery.min.js"></script>
    <style>
        .radio-inline{}
        .error {color: red}
        #blockuis{padding:10px;position:fixed;z-index:99999999;background:rgba(0, 0, 0, 0.73);top:20%;left:50%;transform:translate(-50%,-50%);-webkit-transform:translate(-50%,-50%);-moz-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);-o-transform:translate(-50%,-50%);}
    </style>
    <div class="page-header">
    </div>
    <div class="row">
        <form method="post" id="validate" enctype="multipart/form-data">
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-header">
                        <h4>
                            <i class="icon-reorder">
                            </i>
                            <?php echo @$title;?>
                        </h4>                     
                        <div class="toolbar no-padding">
                        </div>
                    </div>
                    <div class="widget-content">                            
                        <div class="col-md-12">                                
                            <div class="form-group">
                                <textarea rows="3" cols="5" name="gids" class="form-control" placeholder="123456789,123456789,123456789,123456789"></textarea>

                            </div>
                            <div class="form-group"> 
                                <input type="submit" name="submit" value="Request" class="btn btn-primary pull-right"/>
                            </div> 
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                </div>
        </form>
    </div> 
    <?php if(!empty($gids)):?>
        <style>
    .radio-inline{}
    .error {color: red}
    #blockuis{padding:10px;position:fixed;z-index:99999999;background:rgba(0, 0, 0, 0.73);top:20%;left:50%;transform:translate(-50%,-50%);-webkit-transform:translate(-50%,-50%);-moz-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);-o-transform:translate(-50%,-50%);}
</style>
<div style="display:none;text-align:center;font-size:20px;color:white" id="blockuis">
    <div id="loaderimg" class=""><img align="middle" valign="middle" src="http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif"/>
    </div>
    Please wait...
</div>
<code id="codeB" style="width:300px;overflow:hidden;display:none"></code>   
<code id="examplecode5" style="width:300px;overflow:hidden;display:none">var codedefault2 = &quot;SET !TIMEOUT_PAGE 3600\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var mUrl = &quot;<?php echo base_url();?>&quot;;var gids = <?php echo json_encode($gids);?>;function runcode(){for(key in gids){if(gids[key]!=&quot;&quot;){var mm=&quot;&quot;;mm+='URL GOTO=https://mobile.facebook.com/groups/'+gids[key]+'?refid=27\n';iimPlayCode(codedefault2+mm);var audience=window.document.querySelectorAll(&quot;#root input[type=submit]&quot;);if(typeof audience[0]!==&quot;undefined&quot;){if(audience[0].value=='Join Group'){var mm=&quot;&quot;;mm+='TAG POS=1 TYPE=INPUT:SUBMIT FORM=ACTION:/a/group/join/?group_id=* ATTR=*\n';iimPlayCode(codedefault2+mm);}}}}};runcode();var mm=&quot;CODE:&quot;;mm+='URL GOTO='+mUrl+'Facebook/group?action=done\n';iimPlay(mm);</code>   
    <script type="text/javascript">
        function runcode() {
            var str = $("#examplecode5").text();
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
                            runcode(str);
                        }
                    }
                })
            }
        }
        runcode();
    </script>  
    <?php endif;?>
