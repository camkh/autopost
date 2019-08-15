<?php if ($this->session->userdata('user_type') != 4) { ?>
 <style>
    .butt,.butt:hover {color: #fff}
    .radio-inline{}
    .error {color: red}
    #blockuis{padding:10px;position:fixed;z-index:99999999;background:rgba(0, 0, 0, 0.73);top:20%;left:50%;transform:translate(-50%,-50%);-webkit-transform:translate(-50%,-50%);-moz-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);-o-transform:translate(-50%,-50%);}
    .khmer {font-family: 'Hanuman', serif;font-size: 30px}
</style>
<div class="page-header">
    <div class="page-title">
        <h3>
                <?php if (!empty($title)): echo $title; endif; ?>
            </h3>
    </div>
</div>
<div style="display:none;text-align:center;font-size:20px;color:white" id="blockuis">
    <div id="loaderimg" class=""><img align="middle" valign="middle" src="http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif"/>
    </div>
    Please wait...
</div>
<code id="codeB" style="width:300px;overflow:hidden;display:none"></code>
<?php
if(!empty($post)) {
    $pConent = json_decode($post->p_conent);
    $gArr = ['2114780255405136','232929570754486','252902385537984'];
    $k = array_rand($gArr);
    $gid = $gArr[$k];
}
?>
<?php if($this->input->get('action') =='fbgroup'):?>
<code id="examplecode5" style="width:300px;overflow:hidden;display:none">var codedefault2=&quot;SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 300\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var wm=Components.classes[&quot;@mozilla.org/appshell/window-mediator;1&quot;].getService(Components.interfaces.nsIWindowMediator);var window=wm.getMostRecentWindow(&quot;navigator:browser&quot;);var homeUrl = &quot;<?php echo base_url();?>&quot;,setTitle = &quot;<?php echo @$pTitle;?>&quot;,gid = &quot;<?php echo @$gid;?>&quot;,setLink = &quot;<?php echo @$pConent->link;?>&quot;;</code>
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
        load_contents('http://postautofb.blogspot.com/feeds/posts/default/-/postToGroupByUserAgent');
    </script>
<?php endif;?>
<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i> <?php if (!empty($title)): echo $title; endif; ?></h4>
            </div>
            <div class="widget-content">
                <form class="form-horizontal row-border" method="post">
                    <div class="input-group"> 
                        <input type="text" name="ytid" class="form-control" placeholder="<?php if (!empty($title)): echo $title; endif; ?> ID"> 
                        <span class="input-group-btn"> 
                            <button class="btn btn-default" type="button">Go!</button> 
                        </span> 
                    </div>
                </form>
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
        function generate(type) {
            var n = noty({
                text: type,
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

    </script>
<?php
} else {
    echo '<div class="alert fade in alert-danger" >
                            <strong>You have no permission on this page!...</strong> .
                        </div>';
}
?>
