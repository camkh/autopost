<?php if ($this->session->userdata('user_type') != 4) { ?>
<div class="page-header">
	<div class="page-title">
		<h3>
                <?php if (!empty($title)): echo $title; endif; ?>
            </h3>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
                    <?php include 'menu.php';?>
                    <h4>
                        <i class="icon-reorder">
                        </i>
                        Managed Table
                    </h4>
                </div>
			<div class="widget-content">
				<div class="row">
					<button onclick="runCode();">Run</button>

				</div>

				<div class="row">
					<div class="dataTables_header clearfix">
						<div class="col-md-6">
							<div id="DataTables_Table_0_length" class="dataTables_length">
								<label> <select name="DataTables_Table_0_length" size="1"
									aria-controls="DataTables_Table_0" class="select2-offscreen"
									tabindex="-1">
										<option value="5" selected="selected">5</option>
										<option value="10">10</option>
										<option value="25">25</option>
										<option value="50">50</option>
										<option value="-1">All</option>
								</select> records per page
								</label>
							</div>
						</div>
						<div class="col-md-6">
							<div class="dataTables_filter" id="DataTables_Table_0_filter">
								<form method="post">
									<label>
										<div class="input-group">
											<span class="input-group-addon"> <i class="icon-search"> </i>
											</span> <input type="text" aria-controls="DataTables_Table_0"
												class="form-control" name="filtername" />
										</div>
									</label>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- end page -->
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

        function runCode () {
        	var str = $("#examplecode").text();
        	var res = str.split("var SocailFacebook;||");
        	var code = res[1] + res[0];
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
    </script>
<?php
} else {
	echo '<div class="alert fade in alert-danger" >
                            <strong>You have no permission on this page!...</strong> .
                        </div>';
}
?>
<code id="examplecode" style="width:300px;overflow:hidden;display:none">data=&quot;&quot; &gt; &lt;br&gt;&lt;input style=&quot;width:98%&quot;  type=&quot;file&quot; class=&quot;upfbgr&quot; data=&quot;&quot; &gt; &lt;br&gt;&lt;/div&gt;';var postOptoin1='&lt;div class=&quot;bk bl bm&quot; style=&quot;padding: 5px;&quot;&gt;Pause between posting for each groups &lt;input class=&quot;v w x&quot; style=&quot;padding: 3px 3px 4px 0px;display: inline-block;width: 50px;border:1px solid #999;margin: 0px;text-align:center&quot; type=&quot;number&quot; value=&quot;15&quot; name=&quot;sd&quot;/&gt;&lt;/div&gt;';var postNext='&lt;div class=&quot;bk bl bm&quot; style=&quot;padding: 5px;&quot;&gt;'+'&lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;campaignrepeattype&quot; value=&quot;0&quot; checked&gt; After posting all groups, the campaign will stop&lt;/label&gt;&lt;br/&gt;'+'&lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;campaignrepeattype&quot; value=&quot;1&quot; id=&quot;onevery&quot;&gt; After posting all groups, the campaign will start again every &lt;select class=&quot;postevery&quot; name=&quot;ss&quot;&gt;&lt;option value=&quot;3600&quot;&gt;1 hour&lt;/option&gt;&lt;option value=&quot;7200&quot;&gt;2 hours&lt;/option&gt;&lt;option value=&quot;10800&quot;&gt;3 hours&lt;/option&gt;&lt;option value=&quot;14400&quot;&gt;4 hours&lt;/option&gt;&lt;option value=&quot;18000&quot;&gt;5 hours&lt;/option&gt;&lt;option value=&quot;21600&quot;&gt;6 hours&lt;/option&gt;&lt;option value=&quot;25200&quot;&gt;7 hours&lt;/option&gt;&lt;option value=&quot;28800&quot;&gt;8 hours&lt;/option&gt;&lt;option value=&quot;32400&quot;&gt;9 hours&lt;/option&gt;&lt;option value=&quot;36000&quot; selected&gt;10 hours&lt;/option&gt;&lt;option value=&quot;39600&quot;&gt;11 hours&lt;/option&gt;&lt;option value=&quot;43200&quot;&gt;12 hours&lt;/option&gt;&lt;option value=&quot;46800&quot;&gt;13 hours&lt;/option&gt;&lt;option value=&quot;50400&quot;&gt;14 hours&lt;/option&gt;&lt;option value=&quot;54000&quot;&gt;15 hours&lt;/option&gt;&lt;option value=&quot;57600&quot;&gt;16 hours&lt;/option&gt;&lt;option value=&quot;61200&quot;&gt;17 hours&lt;/option&gt;&lt;option value=&quot;64800&quot;&gt;18 hours&lt;/option&gt;&lt;option value=&quot;68400&quot;&gt;19 hours&lt;/option&gt;&lt;option value=&quot;72000&quot;&gt;20 hours&lt;/option&gt;&lt;option value=&quot;75600&quot;&gt;21 hours&lt;/option&gt;&lt;option value=&quot;79200&quot;&gt;22 hours&lt;/option&gt;&lt;option value=&quot;82800&quot;&gt;23 hours&lt;/option&gt;&lt;option value=&quot;86400&quot;&gt;24 hours&lt;/option&gt;&lt;/select&gt;&lt;/label&gt;'+'&lt;br/&gt;&lt;label&gt;and Repeat max &lt;input type=&quot;number&quot; value=&quot;3&quot; class=&quot;v w x&quot; style=&quot;padding: 3px 3px 4px 0px;display: inline-block;width: 50px;border:1px solid #999;margin: 0px;text-align:center&quot; name=&quot;maxrepleat&quot;&gt;&lt;/label&gt; (0= Consecutively, 3=times end)&lt;/div&gt;';var selectSchedule='&lt;select class=&quot;waittime&quot; name=&quot;onschedule&quot;&gt;&lt;option value=&quot;3600&quot;&gt;1 hour&lt;/option&gt;&lt;option value=&quot;7200&quot;&gt;2 hours&lt;/option&gt;&lt;option value=&quot;10800&quot;&gt;3 hours&lt;/option&gt;&lt;option value=&quot;14400&quot;&gt;4 hours&lt;/option&gt;&lt;option value=&quot;18000&quot;&gt;5 hours&lt;/option&gt;&lt;option value=&quot;21600&quot;&gt;6 hours&lt;/option&gt;&lt;option value=&quot;25200&quot;&gt;7 hours&lt;/option&gt;&lt;option value=&quot;28800&quot;&gt;8 hours&lt;/option&gt;&lt;option value=&quot;32400&quot;&gt;9 hours&lt;/option&gt;&lt;option value=&quot;36000&quot;&gt;10 hours&lt;/option&gt;&lt;option value=&quot;39600&quot;&gt;11 hours&lt;/option&gt;&lt;option value=&quot;43200&quot;&gt;12 hours&lt;/option&gt;&lt;option value=&quot;46800&quot;&gt;13 hours&lt;/option&gt;&lt;option value=&quot;50400&quot;&gt;14 hours&lt;/option&gt;&lt;option value=&quot;54000&quot;&gt;15 hours&lt;/option&gt;&lt;option value=&quot;57600&quot;&gt;16 hours&lt;/option&gt;&lt;option value=&quot;61200&quot;&gt;17 hours&lt;/option&gt;&lt;option value=&quot;64800&quot;&gt;18 hours&lt;/option&gt;&lt;option value=&quot;68400&quot;&gt;19 hours&lt;/option&gt;&lt;option value=&quot;72000&quot;&gt;20 hours&lt;/option&gt;&lt;option value=&quot;75600&quot;&gt;21 hours&lt;/option&gt;&lt;option value=&quot;79200&quot;&gt;22 hours&lt;/option&gt;&lt;option value=&quot;82800&quot;&gt;23 hours&lt;/option&gt;&lt;option value=&quot;86400&quot;&gt;24 hours&lt;/option&gt;&lt;/select&gt;';var schedule='&lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;actionpost&quot; value=&quot;1&quot; checked&gt; Post now!&lt;/label&gt;&lt;label&gt;&lt;input type=&quot;radio&quot; name=&quot;actionpost&quot; value=&quot;2&quot; id=&quot;waittime&quot;&gt; Wait:'+selectSchedule+'&lt;/label&gt;';var postButtonStart='&lt;div class=&quot;bk bl bm&quot; style=&quot;padding: 5px;&quot;&gt;&lt;button class=&quot;run y z ba bb&quot; style=&quot;float:right;&quot;&gt;RunPost&lt;/button&gt;&lt;div  class=&quot;totalgroup&quot;&gt;Total groups: &lt;span id=&quot;countGroup&quot;&gt;&lt;/span&gt;&lt;/div&gt;&lt;div class=&quot;schedule&quot;&gt;'+schedule+'&lt;/div&gt;&lt;div style=&quot;clear:both&quot;&gt;&lt;/div&gt;&lt;/div&gt;';window.document.querySelectorAll(&quot;#header&quot;)[0].innerHTML=postTextarea+removeTextarea+postUpload+postOptoin1+postNext+postButtonStart;var gr=window.document.querySelectorAll(&quot;h3&quot;);totalGroups=gr.length;window.document.querySelectorAll(&quot;#countGroup&quot;)[0].innerHTML=totalGroups;for(i in gr){if(!isNaN(i)){gr[i].innerHTML=gr[i].innerHTML+'&lt;button class=&quot;rmgr btn-danger&quot; style=&quot;float: right;margin-right: 5px;&quot;&gt; Not post?&lt;/button&gt;';window.document.querySelectorAll(&quot;h3 button&quot;)[i].addEventListener(&quot;click&quot;,function(){getParents(this)[1].remove();totalGroups=totalGroups-1;window.document.querySelectorAll(&quot;#countGroup&quot;)[0].innerHTML=totalGroups;});}}window.document.querySelectorAll('.postevery')[0].addEventListener(&quot;click&quot;,function(){var postevery=window.document.querySelectorAll(&quot;.postevery&quot;);if(postevery[0]){window.document.querySelectorAll(&quot;#onevery&quot;)[0].checked=true;}});window.document.querySelectorAll('.waittime')[0].addEventListener(&quot;click&quot;,function(){var waittime=window.document.querySelectorAll(&quot;.waittime&quot;);if(waittime[0]){window.document.querySelectorAll(&quot;#waittime&quot;)[0].checked=true;}});window.document.querySelectorAll('.rmct')[0].addEventListener(&quot;click&quot;,function(){window.document.querySelectorAll('.ctap')[window.document.querySelectorAll('.ctap').length-1].remove();});window.document.querySelectorAll('.act')[0].addEventListener(&quot;click&quot;,function(){if(window.document.querySelectorAll('.ctap').length&lt;3){window.document.querySelectorAll('.contentap')[0].innerHTML=window.document.querySelectorAll('.contentap')[0].innerHTML+'&lt;div class=&quot;ctap&quot;&gt;&lt;textarea style=&quot;width:98%&quot; placeholder=&quot;Content&quot; class=&quot;ap&quot;&gt;&lt;/textarea&gt;&lt;/div&gt;&lt;/div&gt;';}});for(i in window.document.querySelectorAll('input[type=&quot;file&quot;]'))if(!isNaN(i))window.document.querySelectorAll('input[type=&quot;file&quot;]')[i].addEventListener(&quot;change&quot;,function(){this.setAttribute('data',this.value);});window.document.querySelectorAll('.run')[0].addEventListener(&quot;click&quot;,function(){contents=window.document.querySelectorAll(&quot;.ap&quot;);setLinks=window.document.querySelectorAll(&quot;input[name='setlink']&quot;);if(setLinks[0].value!=&quot;&quot;){images=window.document.querySelectorAll(&quot;.upfbgr:not([data=\&quot;\&quot;])&quot;);groups=window.document.querySelectorAll(&quot;h3 a&quot;);time1=window.document.querySelectorAll(&quot;input[name='sd']&quot;)[0].value;time2=window.document.querySelectorAll('option:checked')[0].value;time3=window.document.querySelectorAll('.waittime option:checked')[0].value;var loop=window.document.querySelectorAll(&quot;input[name='campaignrepeattype']&quot;)[1].checked;var maxrepleat=window.document.querySelectorAll(&quot;input[name='maxrepleat']&quot;)[0].value;setLink=setLinks[0].value;var setLoop=0;if(loop){setLoop=1;}else{setLoop=0;}var actionp=window.document.querySelectorAll(&quot;input[name='actionpost']&quot;)[1].checked;if(actionp){actionPost=time3;}else{actionPost=0;} playPost(groups,contents,images,time1,time2,next,setLoop,maxrepleat,actionPost,setLink);}else{contents[0].style.border=&quot;1px solid #C82828&quot;;setLinks[0].style.border=&quot;1px solid #C82828&quot;;}});iimPlay('CODE:WAIT SECONDS=9999');var SocailFacebook;||var contents=null,images=null,groups=null,next=0,totalGroups=0,postingOn=0,btnCheck=[],actionPost,setLink;var codedefault1=&quot;TAB CLOSEALLOTHERS\n SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 10\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var codedefault2=&quot;SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 10\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var wm=Components.classes[&quot;@mozilla.org/appshell/window-mediator;1&quot;].getService(Components.interfaces.nsIWindowMediator);var window=wm.getMostRecentWindow(&quot;navigator:browser&quot;);function random(a,b){var c=b-a;return Math.floor((Math.random()*c)+a);}function playPost(groups,contents,images,time1,time2,next,loop,maxrepleat,actionPost,setLink){var last_element=groups.length-1;var num=0;for(key in groups){if(typeof(groups[key].href)!=&quot;undefined&quot;){if(key==0)code=&quot;TAB OPEN\n TAB T=2\n&quot;;else code=&quot;&quot;;code+=&quot;URL GOTO=https://www.facebook.com/sharer/sharer.php?m2w&amp;u=&quot;+setLink+&quot;\n&quot;;iimPlayCode(codedefault2+code);if(next=='finish'){setHtmlLoad='Post finished!';setStyle='background:green;color:white;font-size:20px;';}else{setHtmlLoad='&lt;img align=&quot;center&quot; src=&quot;http://2.bp.blogspot.com/-_nbwr74fDyA/VaECRPkJ9HI/AAAAAAAAKdI/LBRKIEwbVUM/s1600/splash-loader.gif&quot; valign=&quot;middle&quot;&gt;';setStyle='background: rgba(0, 0, 0, 0.73);-webkit-border-radius: 50%;    -moz-border-radius: 50%;-ms-border-radius: 50%;-o-border-radius: 50%;border-radius: 50%;transform: translate(-50%,-50%);-webkit-transform: translate(-50%,-50%);-moz-transform: translate(-50%,-50%);-ms-transform: translate(-50%,-50%);-o-transform: translate(-50%,-50%);color:white;';}var loadingprocess='&lt;div style=&quot;padding: 10px;position: fixed;z-index: 99999999;box-sizing: border-box;top: 50%;left: 50%;'+setStyle+'&quot;&gt;'+setHtmlLoad+'&lt;/div&gt;';postingOn=postingOn+1;nextLoop=maxrepleat;if(maxrepleat==0){nextLoop='Consecutively';}window.document.querySelectorAll('#content')[0].insertAdjacentHTML('beforeBegin',loadingprocess+'&lt;div style=&quot;position: fixed;z-index: 99999999;top:60px;right:10px;background: rgba(58, 58, 58, 0.5);color:rgba(243, 243, 243, 243);padding:15px;font-size:16px&quot;&gt;Posting on groups: '+postingOn+'/'+groups.length+'&lt;br/&gt;Loop: '+next+'/'+nextLoop+'&lt;/div&gt;');code=&quot;&quot;;if(actionPost&gt;0&amp;&amp;key==0&amp;&amp;next==0){code+=&quot;WAIT SECONDS=&quot;+actionPost+&quot;\n&quot;;}if(images.length==0){code+=&quot;TAG POS=1 TYPE=I ATTR=CLASS:_3-99&lt;SP&gt;img&lt;SP&gt;sp_wMQsPMI8ZWM&lt;SP&gt;sx_229104&amp;&amp;TXT:\n&quot;;code+=&quot;TAG POS=2 TYPE=SPAN ATTR=TXT:In&lt;SP&gt;a&lt;SP&gt;group\n&quot;;code+=&quot;TAG POS=1 TYPE=INPUT:TEXT FORM=ID:u_*_* ATTR=ID:u_*_* CONTENT=&quot;+contents[random(0,contents.length-1)].value.replace(/ /g,&quot;&lt;sp&gt;&quot;).replace(/\n/g,&quot;&lt;br&gt;&quot;)+&quot;\n&quot;;code+=&quot;TAG POS=1 TYPE=TEXTAREA FORM=ID:u_*_* ATTR=ID:u_*_* CONTENT=&quot;+contents[random(0,contents.length-1)].value.replace(/ /g,&quot;&lt;sp&gt;&quot;).replace(/\n/g,&quot;&lt;br&gt;&quot;)+&quot;\n&quot;;code+=&quot;TAG POS=1 TYPE=DIV ATTR=TXT:&quot;+contents[random(0,contents.length-1)].value.replace(/ /g,&quot;&lt;sp&gt;&quot;).replace(/\n/g,&quot;&lt;br&gt;&quot;)+&quot;\n&quot;;code+=&quot;TAG POS=1 TYPE=INPUT:HIDDEN ATTR=NAME:groupTarget CONTENT=&quot;+gup('group_id',groups[key].href)+&quot;\n&quot;;code+=&quot;TAG POS=1 TYPE=BUTTON FORM=ID:u_0_s ATTR=NAME:share\n&quot;;code+=&quot;WAIT SECONDS=&quot;+time1+&quot;\n&quot;;if(last_element==key&amp;&amp;loop==1){postingOn=0;if(maxrepleat==0){code+=&quot;WAIT SECONDS=&quot;+time2+&quot;\n&quot;;iimPlayCode(codedefault2+code);next=next+1;playPost(groups,contents,images,time1,time2,next,loop,maxrepleat,actionPost,setLink);}else if(maxrepleat!=next){code+=&quot;WAIT SECONDS=&quot;+time2+&quot;\n&quot;;iimPlayCode(codedefault2+code);next=next+1;playPost(groups,contents,images,time1,time2,next,loop,maxrepleat,actionPost,setLink);}else if(maxrepleat==next){code+=&quot;WAIT SECONDS=&quot;+time2+&quot;\n&quot;;iimPlayCode(codedefault1+code);}}else if(last_element==key&amp;&amp;loop==0&amp;&amp;maxrepleat!=next){code+=&quot;WAIT SECONDS=&quot;+time1+&quot;\n&quot;;iimPlayCode(codedefault2+code);next=next+1;playPost(groups,contents,images,time1,time2,next,loop,maxrepleat,actionPost,setLink);}else if(last_element==key&amp;&amp;maxrepleat==next&amp;&amp;loop==0){iimPlayCode(codedefault1+code);}else{iimPlayCode(codedefault2+code);}}else{code+=&quot;TAG POS=1 TYPE=INPUT:SUBMIT  ATTR=name:lgc_view_photo\n&quot;;for(key2 in images){if(!isNaN(key2)){code+=&quot;TAG POS=1 TYPE=INPUT:FILE ATTR=NAME:file&quot;+(parseInt(key2)+parseInt(1))+&quot; CONTENT=&quot;+images[key2].getAttribute('data').replace(/ /g,&quot;&lt;sp&gt;&quot;)+&quot;\n&quot;;}}code+=&quot;TAG POS=1 TYPE=TEXTAREA ATTR=ID:* CONTENT=&quot;+contents[random(0,contents.length-1)].value.replace(/ /g,&quot;&lt;sp&gt;&quot;).replace(/\n/g,&quot;&lt;br&gt;&quot;)+&quot;\n&quot;;code+=&quot;TAG POS=1 TYPE=INPUT:SUBMIT ATTR=NAME:photo_upload\n&quot;;code+=&quot;TAG POS=1 TYPE=INPUT:SUBMIT ATTR=NAME:done\n&quot;;code+=&quot;WAIT SECONDS=&quot;+random(time1,time2)+&quot;\n&quot;;iimPlayCode(codedefault2+code);}}}}function gup(name,url){if(!url)url=location.href;name=name.replace(/[\[]/,&quot;\\\[&quot;).replace(/[\]]/,&quot;\\\]&quot;);var regexS=&quot;[\\?&amp;]&quot;+name+&quot;=([^&amp;#]*)&quot;;var regex=new RegExp(regexS);var results=regex.exec(url);return results==null?null:results[1];}function getParents(el){var parents=[];var p=el.parentNode;while(p!==null){var o=p;parents.push(o);p=o.parentNode;}return parents;} iimPlayCode(codedefault1+&quot;URL GOTO=<?php echo base_url();?>\n TAB OPEN\n TAB T=2\n URL GOTO=https://m.facebook.com/settings/notifications/groups/\n &quot;);var postLoop='&lt;input type=&quot;radio&quot; name=&quot;campaign_repeat_type&quot; value=&quot;0&quot;&gt; After posting all messages, the campaign will stop&lt;br&gt;&lt;input type=&quot;radio&quot; name=&quot;campaign_repeat_type&quot; value=&quot;1&quot; checked=&quot;&quot;&gt; After posting all messages, the campaign will start again';var postTextarea='&lt;style&gt;.bk.bl.bm{font-size:14px;}.schedule{border: 1px solid #EEE;width: 230px;padding: 5px;float: left;}.totalgroup{border:1px solid #eee;padding:5px;margin-right:5px;float:left;height: 21px;}&lt;/style&gt;Link:&lt;input name=&quot;setlink&quot; class=&quot;link&quot; style=&quot;width: 548px;&quot; type=&quot;text&quot; placeholder=&quot;Set link to shared here!&quot; requred/&gt;&lt;div class=&quot;contentap&quot;&gt;&lt;div class=&quot;ctap&quot;&gt;&lt;textarea style=&quot;width:98%&quot; placeholder=&quot;Content&quot; class=&quot;ap&quot;&gt;&lt;/textarea&gt;&lt;/div&gt;&lt;/div&gt;';var removeTextarea='&lt;div class=&quot;btcta&quot; style=&quot;float:right;&quot;&gt;&lt;button class=&quot;act&quot;&gt;add content&lt;/button&gt;&lt;button class=&quot;rmct btn&quot;&gt;Remove Content&lt;/button&gt;&lt;/div&gt;';var postUpload='&lt;div class=&quot;imgap&quot;&gt;&lt;input style=&quot;width:98%&quot;  type=&quot;file&quot; class=&quot;upfbgr&quot; data=&quot;&quot; &gt; &lt;br&gt;&lt;input style=&quot;width:98%&quot;  type=&quot;file&quot; class=&quot;upfbgr&quot;</code>