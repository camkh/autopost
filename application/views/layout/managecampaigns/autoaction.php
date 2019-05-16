<?php
$gemail = $this->session->userdata ('gemail');
$uerAgent = $this->input->get('agent');
$log_id = $this->session->userdata('user_id');
$suid = $this->session->userdata ( 'sid' ); 
 if ($this->session->userdata('user_type') != 4) { ?>
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
    <meta content='text/html; charset=UTF-8' http-equiv='Content-Type'/> 
    <code id="codeB" style="width:300px;overflow:hidden;display:none"></code>
    <code id="examplecode5" style="width:300px;overflow:hidden;display:none">var i, retcode,retcodes,report,uid=&quot;<?php echo $log_id;?>&quot;;var codedefault2=&quot;SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 300\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var wm=Components.classes[&quot;@mozilla.org/appshell/window-mediator;1&quot;].getService(Components.interfaces.nsIWindowMediator);var window=wm.getMostRecentWindow(&quot;navigator:browser&quot;);var homeUrl = &quot;<?php echo base_url();?>&quot;;</code>
    <code id="codeD" style="width:300px;overflow:hidden;display:none">mm=&quot;CODE:&quot;;mm+=&quot;URL GOTO=&quot;+homeUrl+&quot;managecampaigns/account\n&quot;;mm+='TAG POS=1 TYPE=DIV ATTR=TXT:<?php echo @$gemail;?>\n';mm+=&quot;WAIT SECONDS=15\n&quot;;mm+=&quot;URL GOTO=&quot;+homeUrl+&quot;managecampaigns/autopost?start=1\n&quot;;retcode=iimPlay(mm);</code>
    <div class="page-header">
    </div>
    <div class="row">
<meta http-equiv="refresh" content="60"/>
        <div class="col-md-12">
            <div class="widget box">
                <div class="widget-header">
                    <h4>
                        <i class="icon-reorder">
                        </i>
                        Add New Post
                    </h4>                     
                    <div class="toolbar no-padding">
                    </div>
                </div>
                <div class="widget-content">
                    ssssssssssssssss
                </div>
            </div>
        </div>

    </div>

    </div>
    <script>
        $( document ).ready(function() {   
            var str = $("#codeD").text();    
            runcode(str);
        });

       function runcode(codes) {
            var str = $("#examplecode5").text();
            var code = str + codes;
            alert(code);
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
                    },
                    error: function(){
                        window.location.reload(); 
                    }
                })
            }
        }

function closeOnLoad(myLink)
{
    location.reload();
  // var newWindow = window.open(myLink, "connectWindow", "width=600,height=400,scrollbars=yes");
  // setTimeout(
  //            function()
  //            {
  //              newWindow.close();
  //            },
  //           2000
  //           );
  // return false;
}
function checkPostBeforeShare() {
    // body...
}
    </script>    
    <script>
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