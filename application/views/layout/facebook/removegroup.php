 <script type="text/javascript" src="<?php echo base_url();?>themes/layout/blueone/assets/js/libs/jquery.min.js"></script>
    <style>
        .radio-inline{}
        .error {color: red}
        #blockuis{padding:10px;position:fixed;z-index:99999999;background:rgba(0, 0, 0, 0.73);top:20%;left:50%;transform:translate(-50%,-50%);-webkit-transform:translate(-50%,-50%);-moz-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);-o-transform:translate(-50%,-50%);}
    </style>
    <div class="page-header">
    </div>
    <div class="row">
        <form method="post" id="validate">
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
                    	<?php if(!empty($_GET['id'])):?>
                    	<div class="row has-success" style="margin-bottom:10px;">
                            <div class="col-md-4">
                                <label for="imageid">ID:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" onclick="this.focus(); this.select()" class="form-control" value="<?php echo $_GET['id'];?>" />
                            </div>                         
                        </div>
                    	<?php endif;
                            if(!empty($group)) {
                                $group = array_unique($group);
                                $tranferGroup = 'code = &quot;&quot;;';
                                $i=0;
                                $countGroups = count($group);
                                foreach ($group as $groupId) {
                                    $i++;
                                    if(!empty($groupId)) {
                                        if($i==1) {
                                            $tranferGroup .= 'code+=&quot;URL GOTO=https://www.facebook.com/groups/?category=membership\n&quot;;';
                                            $tranferGroup .= 'code += &quot;WAIT SECONDS=5\n&quot;;';
                                            $tranferGroup .= 'iimPlayCode(codedefault2+code);';                    
                                            $tranferGroup .= "var setHtmlLoad = '&lt;img align=&quot;center&quot; src=&quot;http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif&quot; valign=&quot;middle&quot;/&gt;'; var message = '&lt;img align=&quot;center&quot; src=&quot;http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif&quot; valign=&quot;middle&quot; style=&quot;width:25px;height:25px;margin: -7px 5px 0px 0px;&quot;/&gt;  Please wait...'; var setStyle='background: rgba(0, 0, 0, 0.73);-webkit-border-radius: 50%;    -moz-border-radius: 50%;-ms-border-radius: 50%;-o-border-radius: 50%;border-radius: 50%;transform: translate(-50%,-50%);-webkit-transform: translate(-50%,-50%);-moz-transform: translate(-50%,-50%);-ms-transform: translate(-50%,-50%);-o-transform: translate(-50%,-50%);color:white;'; var loadingprocess = '&lt;div style=&quot;padding: 10px;position: fixed;z-index: 99999999;box-sizing: border-box;top: 20%;left: 50%;'+setStyle+'&quot;&gt;'+setHtmlLoad+'&lt;/div&gt;'; window.document.querySelectorAll('#blueBarDOMInspector')[0].innerHTML = loadingprocess + '&lt;div style=&quot;position: fixed;z-index: 99999999;top:60px;right:10px;background:#000;color:#fff000;padding:15px;font-size:18px&quot;&gt;Remove &lt;input type=&quot;text&quot; id=&quot;onGroup&quot; value=&quot;1&quot; style=&quot;width: 60px;border:1px solid #333;font-size:25px;text-align:center&quot;/&gt; / &lt;input type=&quot;text&quot; id=&quot;endGroup&quot; value=&quot;".$countGroups."&quot; style=&quot;width: 60px;border:1px solid #333;font-size:25px;text-align:center&quot;/&gt;&lt;div style=&quot;clear:both&quot;&gt;&lt;/div&gt;'+message+'&lt;/div&gt;';";
                                        } 
                                        $tranferGroup .= "window.document.querySelectorAll(&quot;#GroupDiscoverCard_membership&quot;)[0].innerHTML='&lt;div class=&quot;_4-u3 _5dwa _5dw9&quot;&gt;&lt;span class=&quot;_38my&quot;&gt;&lt;a id=&quot;leavGroups&quot; class=&quot;_54nc&quot; href=&quot;#&quot; rel=&quot;dialog-post&quot; ajaxify=&quot;/ajax/groups/membership/leave.php?group_id={$groupId}&amp;amp;ref=group_browse&quot; role=&quot;menuitem&quot;&gt;&lt;span&gt;&lt;span class=&quot;_54nh&quot;&gt;&lt;span id=&quot;leavg&quot; class=&quot;leavg&quot;&gt;Leave Group&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/a&gt;&lt;span class=&quot;_c1c&quot;&gt;&lt;/span&gt;&lt;/span&gt;&lt;div class=&quot;_3s3-&quot;&gt;&lt;/div&gt;&lt;/div&gt;';";
                                        $tranferGroup .= 'code = &quot;&quot;;';
                                        $tranferGroup .= 'code += &quot;TAG POS=1 TYPE=INPUT:TEXT ATTR=ID:onGroup CONTENT='.$i.'\n&quot;;';         
                                        $tranferGroup .= 'code += &quot;TAG POS=1 TYPE=SPAN ATTR=ID:leavg\n&quot;;';
                                        if($prevent_readd) {
                                            $tranferGroup .= 'code += &quot;TAG POS=1 TYPE=INPUT:CHECKBOX FORM=ID:u_*_* ATTR=ID:u_*_* CONTENT=YES\n&quot;;';
                                        }
                                        $tranferGroup .= 'code += &quot;TAG POS=14 TYPE=SPAN ATTR=ID:Leave<SP>Group\n&quot;;';
                                        $tranferGroup .= 'code += &quot;TAG POS=1 TYPE=BUTTON FORM=ID:u_*_* ATTR=NAME:confirmed\n&quot;;';
                                        $tranferGroup .= 'iimPlayCode(codedefault2+code);';
                                    }
                                }
                                $tranferGroup .= 'code+=&quot;URL GOTO=http://poroman.website/autopost/home/index?m=removegbyid&amp;group='.$countGroups.'\n&quot;;';
                                $tranferGroup .= 'iimPlayCode(codedefault2+code);';
                            }
                        ?>
                        <div class="row" style="margin-bottom:10px;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-2">Groups ID</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control" name="groupID" rows="3" placeholder="678395395538349,683014918384846,1710922079141944,230189347062300,491103264280401,315449331976701,308464052691832,376507559225693,143778292307959,1562092257357650,710370049011883,1651862138379346,836218939738597,246609238841313,1521717074714417,561300563975745,868199556587908,1869941679897570,894235420613189,1666916473565996,1454985684730881,494872437284210,713617928745994,728106100639213,1418524051778608,794223883991904,316223218520547,1596731777205858,154855474859055,1443631142598760,1195722333899719,282246928495421"></textarea>
                                        <label class="checkbox"><input type="checkbox" name="prevent_readd" tabindex="0"/> Prevent other members from adding you back to this group</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-12">
                                        <input name="submit" type="submit" value="Remove" class="btn btn-primary pull-right" />
                                    </div>
                                </div> 
                            </div>                            
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>

    <?php if(!empty($group)):?>
    <div style="display:none;text-align:center;font-size:20px;color:white" id="blockuis">
        <div id="loaderimg" class=""><img align="middle" valign="middle" src="http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif"></div>
        Please wait...
    </div>
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
        runCode();
    </script>
    <?php endif;?>