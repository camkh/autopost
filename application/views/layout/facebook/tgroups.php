 <script type="text/javascript" src="<?php echo base_url();?>themes/layout/blueone/assets/js/libs/jquery.min.js"></script>
    <style>
        .radio-inline{}
        .error {color: red}
        #blockuis{padding:10px;position:fixed;z-index:99999999;background:rgba(0, 0, 0, 0.73);top:20%;left:50%;transform:translate(-50%,-50%);-webkit-transform:translate(-50%,-50%);-moz-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);-o-transform:translate(-50%,-50%);}
    </style>
    <div class="page-header">
    </div>  
    <div style="display:none;text-align:center;font-size:20px;color:white" id="blockuis">
        <div id="loaderimg" class=""><img align="middle" valign="middle" src="http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif"></div>
        Please wait...
    </div>

    <?php
    if(!empty($_GET['g']) || !empty($_POST['g'])):
        $g = !empty($_POST['g']) ? $_POST['g'] : $_GET['g'];
        $u = !empty($_POST['u']) ? $_POST['u'] : $_GET['u'];
        $t = !empty($_POST['t']) ? $_POST['t'] : $_GET['t'];
        $goupsArr = explode(',', $g);
        $tranferGroup = 'code = &quot;&quot;;';
        $i=0;
        $countGroups = count($goupsArr);
        foreach ($goupsArr as $group) {
            $i++;
            if(!empty($group)) {
                if($i==1) {
                    $tranferGroup .= 'code+=&quot;URL GOTO=https://www.facebook.com/groups/'.$group.'\n&quot;;';
                    $tranferGroup .= 'iimPlayCode(codedefault2+code);';                    
                    $tranferGroup .= "var setHtmlLoad = '&lt;img align=&quot;center&quot; src=&quot;http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif&quot; valign=&quot;middle&quot;/&gt;'; var message = '&lt;img align=&quot;center&quot; src=&quot;http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif&quot; valign=&quot;middle&quot; style=&quot;width:25px;height:25px;margin: -7px 5px 0px 0px;&quot;/&gt;  Please wait...'; var setStyle='background: rgba(0, 0, 0, 0.73);-webkit-border-radius: 50%;    -moz-border-radius: 50%;-ms-border-radius: 50%;-o-border-radius: 50%;border-radius: 50%;transform: translate(-50%,-50%);-webkit-transform: translate(-50%,-50%);-moz-transform: translate(-50%,-50%);-ms-transform: translate(-50%,-50%);-o-transform: translate(-50%,-50%);color:white;'; var loadingprocess = '&lt;div style=&quot;padding: 10px;position: fixed;z-index: 99999999;box-sizing: border-box;top: 20%;left: 50%;'+setStyle+'&quot;&gt;'+setHtmlLoad+'&lt;/div&gt;'; window.document.querySelectorAll('#contentArea')[0].innerHTML = loadingprocess + '&lt;div style=&quot;position: fixed;z-index: 99999999;top:60px;right:10px;background:#000;color:#fff000;padding:15px;font-size:18px&quot;&gt;Transfer on &lt;input type=&quot;text&quot; id=&quot;onGroup&quot; value=&quot;1&quot; style=&quot;width: 60px;border:1px solid #333;font-size:25px;text-align:center&quot;/&gt; / &lt;input type=&quot;text&quot; id=&quot;endGroup&quot; value=&quot;".$countGroups."&quot; style=&quot;width: 60px;border:1px solid #333;font-size:25px;text-align:center&quot;/&gt;&lt;div style=&quot;clear:both&quot;&gt;&lt;/div&gt;'+message+'&lt;/div&gt;';";
                    $tranferGroup .= "var Addinputs = window.document.querySelectorAll(&quot;.groupAddMemberTypeaheadBox form&quot;); Addinputs[0].innerHTML = Addinputs[0].innerHTML + '&lt;button value=&quot;1&quot; type=&quot;submit&quot; class=&quot;_2g61 _4jy0 _4jy3 _4jy1 _51sy selected _42ft&quot; id=&quot;onsharenow&quot;&gt;&lt;span&gt;Add&lt;/span&gt;&lt;/button&gt;&lt;input id=&quot;setMember&quot; type=&quot;hidden&quot; value=&quot;&quot; name=&quot;members[]&quot; autocomplete=&quot;off&quot;&gt;';";
                    $tranferGroup .= 'code = &quot;&quot;;';
                } else {
                    $tranferGroup .= 'code += &quot;WAIT SECONDS='.$t.'\n&quot;;'; 
                }             
                $tranferGroup .= 'code += &quot;TAG POS=1 TYPE=INPUT:TEXT ATTR=ID:onGroup CONTENT='.$i.'\n&quot;;';
                $tranferGroup .= 'code += &quot;TAG POS=1 TYPE=INPUT:HIDDEN ATTR=NAME:group_id CONTENT='.$group.'\n&quot;;';
                $tranferGroup .= 'code += &quot;TAG POS=1 TYPE=INPUT:HIDDEN ATTR=ID:setMember CONTENT='.$u.'\n&quot;;';
                $tranferGroup .= 'code += &quot;TAG POS=1 TYPE=BUTTON ATTR=ID:onsharenow\n&quot;;';
                $tranferGroup .= 'code += &quot;TAG POS=1 TYPE=SPAN ATTR=TXT:Close\n&quot;;';
            }
        }
        $tranferGroup .= 'code+=&quot;URL GOTO='.base_url().'home/index?m=transfer&amp;group='.$countGroups.'\n&quot;;';
        $tranferGroup .= 'iimPlayCode(codedefault2+code);';
        ?>  
        <code id="examplecode5" style="width:300px;overflow:hidden;display:none">var contents=null,images=null,groups=null,setIdAccout=null,postingOn=0;var codedefault1=&quot;TAB CLOSEALLOTHERS\n SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 10\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var codedefault2=&quot;SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 10\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var wm=Components.classes[&quot;@mozilla.org/appshell/window-mediator;1&quot;].getService(Components.interfaces.nsIWindowMediator);var window=wm.getMostRecentWindow(&quot;navigator:browser&quot;);<?php echo @$tranferGroup;?>iimPlay('CODE:WAIT SECONDS=0');</code>  
    <script type="text/javascript">
        function getattra(e) {
            $("#singerimageFist").val(e);
            $("#imageviewFist").html('<img style="width:100%;height:55px;" src="' + e + '"/>');
        }
        function loading () {
            $("#blockuis").show();
        }

        function runCode () {
            loading();
            var str = $("#examplecode5").text();
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
        //runCode();
    </script>
    <center><button onclick="runCode();" class="btn btn-primary" style="font-size:30px !important"><i class="icon-ok"></i> Continue</button></center>
    <?php endif;?>