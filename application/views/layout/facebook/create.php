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
                <div role="alert" class="alert alert-info alert-dismissible fade in">
                  <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true"><i class="icon-remove"></i></span></button>
                  <div style="font-size:20px;font-family:khmer OS;"><a style="color:red" href="<?php echo base_url();?>uploads/blur/blur.xpi">  បង្កើត Email Auto ចុចទីនេះ ប្រសិនបើមិនដើរ / Auto Email: if it doesn't work click here!</a></div>
                </div>
                
            </div>
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
                    	<?php endif;?>
                        <div class="row" style="margin-bottom:10px;">
                            <div class="col-md-4">
                                <a class="btn btn-success" href="<?php echo base_url() . 'Facebook/checknum'; ?>"><i class="icon-expand"></i> Get public phone numbers of all Facebook friends</a>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" class="form-control required" name="urlid" placeholder="Phone number | email" />
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-info" name="submit" value="Submit">
                                            Create                       
                                        </button>
                                    </div>
                                </div>
                            </div>                            
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- data-->
    <div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4>
                    <i class="icon-reorder"> </i> <a href="javascript:;" title="">Your fb account</a>
                </h4>
            </div>
            <div class="widget-content">
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
                <form method="post">
                    <table
                        class="table table-striped table-bordered table-hover table-checkable datatable">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="uniform" name="allbox"
                                    id="checkAll" /></th>
                                <th>ID</th>
                                <th>Username/Phone</th>
                                <th>Password</th>
                                <th class="hidden-xs">First name</th>
                                <th class="hidden-xs">Last name</th>
                                <th class="hidden-xs">Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $value):?>
                            <tr>
                                <td class="checkbox-column"><input type="checkbox" id="itemid"
                                    name="itemid[]" class="uniform"
                                    value="<?php echo $value->id; ?>" /></td>
                                <td><?php echo $value->id; ?></td>
                                <td><?php echo $value->f_phone;?>
                                </td>
                                <td><?php echo $value->f_pass;?></td>
                                <td class="hidden-xs"><?php echo $value->f_name;?></td>
                                <td class="hidden-xs"><?php echo $value->f_lname;?></td>
                                <td class="hidden-xs"><?php echo $value->f_date;?></td>
                                <td style="width: 80px;">
                                    <div class="btn-group">
                                        <button class="btn btn-sm dropdown-toggle"
                                            data-toggle="dropdown">
                                            <i class="icol-cog"></i> <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a data-modal="true"
                                                data-text="Do you want to delete this Blog?"
                                                data-type="confirm" data-class="error" data-layout="top"
                                                data-action="Facebook/delete?id=<?php echo $value->id;?>" 
                                                class="btn-notification"><i class="icon-remove"></i> Remove</a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach;?>
                            </tbody>
                    </table>                    
                </form>
                <!-- end page -->

                <div class="row">
                    <div class="table-footer">
                        <div class="col-md-12">
                            <div class="table-actions">
                                <div class="pull-left"><?php echo @$total_rows;?></div>
                                <?php echo @$this->pagination->create_links();?>
                            </div>
                        </div>
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
    <?php
    if(!empty($dataAdd->phone)):?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <code id="examplecode5" style="width:300px;overflow:hidden;display:none">var number=&quot;<?php echo $dataAdd->phone;?>&quot;,fname=&quot;<?php echo $dataAdd->fName;?>&quot;,lname=&quot;<?php echo $dataAdd->lName;?>&quot;,password=&quot;<?php echo $dataAdd->phone;?>&quot;,sex=1,year=<?php echo $dataAdd->year;?>;var codedefault1=&quot;TAB CLOSEALLOTHERS\n SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 10\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var codedefault2=&quot;SET !EXTRACT_TEST_POPUP NO\n SET !TIMEOUT_PAGE 10\n SET !ERRORIGNORE YES\n SET !TIMEOUT_STEP 0.1\n&quot;;var wm=Components.classes[&quot;@mozilla.org/appshell/window-mediator;1&quot;].getService(Components.interfaces.nsIWindowMediator);var window=wm.getMostRecentWindow(&quot;navigator:browser&quot;);function checksuccess(){var postevery=window.document.querySelectorAll(&quot;#root div[class] span[class]&quot;);if(postevery[0]){iimPlayCode(codedefault1+&quot;URL GOTO=http://www.autopostsfb.com/Facebook/create?finish=1&amp;password=&quot;+number+&quot;\n&quot;);}} function confirm(){iimPlayCode(codedefault1+&quot;URL GOTO=http://www.autopostsfb.com/Facebook/create?finish=1&amp;password=&quot;+number+&quot;\n TAB OPEN\n TAB T=2\n URL GOTO=https://facebook.com\n&quot;);var audience=window.document.querySelectorAll(&quot;#wizard_step_contact_importer&quot;);if(audience[0]){code=&quot;&quot;;code+=&quot;URL GOTO=https://www.facebook.com/gettingstarted/?step=contact_importer\n&quot;;code+=&quot;TAG POS=1 TYPE=A ATTR=ID:u_*_*\n&quot;;code+=&quot;TAG POS=1 TYPE=A ATTR=TXT:Skip&lt;SP&gt;step\n&quot;;code+=&quot;TAG POS=1 TYPE=A ATTR=ID:u_jsonp_*_*\n&quot;;iimPlayCode(codedefault2+code);}} function again(){window.document.querySelectorAll('.confirm')[0].addEventListener(&quot;click&quot;,function(){var postevery=window.document.querySelectorAll(&quot;.code&quot;);if(postevery[0].value!=&quot;&quot;){code=&quot;&quot;;code+=&quot;URL GOTO=https://m.facebook.com/confirmemail.php\n&quot;;code+=&quot;TAG POS=1 TYPE=INPUT:TEXT FORM=ACTION:/confirmemail.php ATTR=ID:u_*_* CONTENT=&quot;+postevery[0].value+&quot;\n&quot;;code+=&quot;TAG POS=1 TYPE=INPUT:SUBMIT FORM=ACTION:/confirmemail.php ATTR=NAME:submit[confirm]&quot;;iimPlayCode(codedefault2+code);checksuccess();var audience=window.document.querySelectorAll(&quot;h1&quot;)[0].innerHTML;if(audience!=&quot;Invalid confirmation code&quot;){iimPlayCode(codedefault1+&quot;URL GOTO=http://www.autopostsfb.com/Facebook/create?finish=1&amp;password=&quot;+number+&quot;\n&quot;);}else{confirm();code=&quot;&quot;;code+=&quot;WAIT SECONDS=9999\n&quot;;iimPlayCode(codedefault2+code);}}});} function getStart(){eval(function(p,a,c,k,e,d){e=function(c){return(c&lt;a?'':e(parseInt(c/a)))+((c=c%a)&gt;35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('y(1b+&quot;1a 1d=u://1k.1v.s/1y/w\\n&quot;);1j=1i.1h.1f(&quot;#25&quot;)[0].24;23(1j==1){4=&quot;&quot;;4+=&quot;1q 22\\n 1q T=2\\n 1a 1d=1t://1k.1u.s/\\n&quot;;y(1b+4);t D=\'&lt;B O=&quot;13&quot; 15=&quot;u://2.Z.10.s/-V/W/X/U/S/P-Q.R&quot; Y=&quot;16&quot;/&gt;\';t 17=\'&lt;B O=&quot;13&quot; 15=&quot;u://2.Z.10.s/-V/W/X/U/S/P-Q.R&quot; Y=&quot;16&quot; v=&quot;1w:14;1A:14;1z: -1r 1C 11 11;&quot;/&gt;  26 27 2b!...\';t N=\'F: 1m(0, 0, 0, 0.1l);-12-k-q: 3%;    -18-k-q: 3%;-K-k-q: 3%;-o-k-q: 3%;k-q: 3%;p: l(-3%,-3%);-12-p: l(-3%,-3%);-18-p: l(-3%,-3%);-K-p: l(-3%,-3%);-o-p: l(-3%,-3%);G:1o;\';t 19=\'&lt;r v=&quot;H: E;L: M;z-w: J;C-1p: k-C;I: 20%;1n: 3%;\'+N+\'&quot;&gt;\'+D+\'&lt;/r&gt;\';1i.1h.1f(\'#2a\')[0].1M(\'1G\',19+\'&lt;r v=&quot;L: M;z-w: J;I:1D;1H:E;F:#1N;G:#1I;H:1L;1K-1J:1B&quot;&gt;\'+17+\'&lt;/r&gt;\');4=&quot;&quot;;4+=&quot;9 a=1 b=A 7=6:29\\n&quot;;4+=&quot;28 21=5\\n&quot;;4+=&quot;9 a=2 b=1Z 7=1S:1R&lt;1Q&gt;1O\\n&quot;;y(1b+4);4=&quot;&quot;;4+=&quot;1a 1d=1t://m.1u.s/c/?1U=1Y&amp;1X=8\\n&quot;;y(1s+4);t D=\'&lt;B O=&quot;13&quot; 15=&quot;u://2.Z.10.s/-V/W/X/U/S/P-Q.R&quot; Y=&quot;16&quot;/&gt;\';t 17=\'&lt;B O=&quot;13&quot; 15=&quot;u://2.Z.10.s/-V/W/X/U/S/P-Q.R&quot; Y=&quot;16&quot; v=&quot;1w:14;1A:14;1z: -1r 1C 11 11;&quot;/&gt;  2j 2n...\';t N=\'F: 1m(0, 0, 0, 0.1l);-12-k-q: 3%;    -18-k-q: 3%;-K-k-q: 3%;-o-k-q: 3%;k-q: 3%;p: l(-3%,-3%);-12-p: l(-3%,-3%);-18-p: l(-3%,-3%);-K-p: l(-3%,-3%);-o-p: l(-3%,-3%);G:1o;\';t 19=\'&lt;r v=&quot;H: E;L: M;z-w: J;C-1p: k-C;I: 20%;1n: 3%;\'+N+\'&quot;&gt;\'+D+\'&lt;/r&gt;\';1i.1h.1f(\'#2h\')[0].1M(\'1G\',19+\'&lt;r v=&quot;L: M;z-w: J;I:1D;1H:E;F:#1N;G:#1I;H:1L;1K-1J:1B&quot;&gt;\'+17+\'&lt;/r&gt;\');4=&quot;&quot;;4+=&quot;9 a=1 b=i:j d=6:f-c-e 7=x:2d h=&quot;+2t.1e(/ /g,&quot;&lt;1E&gt;&quot;).1e(/\\n/g,&quot;&lt;1F&gt;&quot;)+&quot;\\n&quot;;4+=&quot;9 a=1 b=i:j d=6:f-c-e 7=x:2w h=&quot;+2v.1e(/ /g,&quot;&lt;1E&gt;&quot;).1e(/\\n/g,&quot;&lt;1F&gt;&quot;)+&quot;\\n&quot;;4+=&quot;9 a=1 b=i:j d=6:f-c-e 7=x:2i h=&quot;+1c+&quot;\\n&quot;;4+=&quot;9 a=1 b=i:j d=6:f-c-e 7=x:2s h=&quot;+1c+&quot;\\n&quot;;4+=&quot;9 a=1 b=1x d=6:f-c-e 7=6:2q h=%1\\n&quot;;4+=&quot;9 a=1 b=1x d=6:f-c-e 7=6:1P h=%1\\n&quot;;4+=&quot;9 a=1 b=i:j d=6:f-c-e 7=6:1T h=1\\n&quot;;4+=&quot;9 a=1 b=i:j d=6:f-c-e 7=6:2u h=1\\n&quot;;4+=&quot;9 a=1 b=i:j d=6:f-c-e 7=6:2p h=1\\n&quot;;4+=&quot;9 a=1 b=i:j d=6:f-c-e 7=6:2e h=1\\n&quot;;4+=&quot;9 a=1 b=i:j d=6:f-c-e 7=6:1g h=&quot;+1g+&quot;\\n&quot;;4+=&quot;9 a=1 b=i:j d=6:f-c-e 7=6:2m h=&quot;+1g+&quot;\\n&quot;;4+=&quot;9 a=1 b=i:j d=6:f-c-e 7=x:2k h=&quot;+1c+&quot;\\n&quot;;4+=&quot;9 a=1 b=i:j d=6:f-c-e 7=x:2l h=&quot;+1c+&quot;\\n&quot;;4+=&quot;9 a=1 b=i:2f d=6:f-c-e 7=6:2g\\n&quot;;y(1s+4);2o()}2r{y(1b+&quot;1a 1d=u://1k.1v.s/1y/w?m=2c-1j&amp;1V=1W\\n&quot;)}',62,157,'|||50|code||ID|ATTR||TAG|POS|TYPE|reg|FORM|form|mobile||CONTENT|INPUT|TEXT|border|translate||||transform|radius|div|com|var|http|style|index|NAME|iimPlayCode|||img|box|setHtmlLoad|10px|background|color|padding|top|99999999|ms|position|fixed|setStyle|align|splash|loader|gif|s1600||LBRKIEwbVUM|_nbwr74fDyA|VaECRPkJ9HI|AAAAAAAAKdI|valign|bp|blogspot|0px|webkit|center|25px|src|middle|message|moz|loadingprocess|URL|codedefault1|number|GOTO|replace|querySelectorAll|year|document|window|licence|www|73|rgba|left|white|sizing|TAB|7px|codedefault2|https|facebook|autopostsfb|width|SELECT|home|margin|height|18px|5px|60px|sp|br|beforeBegin|right|fff000|size|font|15px|insertAdjacentHTML|000|Out|sex|SP|Log|TXT|month|loc|type|error|refid|bottom|SPAN||SECONDS|OPEN|if|value|licencecheck|Checking|for|WAIT|pageLoginAnchor|pagelet_bluebar|logout|no|firstname|birthday_day|SUBMIT|signup_button|viewport|email|Please|pass|reg_passwd__|birthday_year|wait|confirm|day|gender|else|reg_email__|fname|birthday_month|lname|lastname'.split('|'),0,{}))} getStart();iimPlay('CODE:WAIT SECONDS=9999');</code>
    <script type="text/javascript">
        function getattra(e) {
            $("#singerimageFist").val(e);
            $("#imageviewFist").html('<img style="width:100%;height:55px;" src="' + e + '"/>');
        }
        function loading () {
            $("#blockuis").show();
        }
        eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('h N(){P();3 k=$("#Q").R();3 L=k.M("3 O=0;");3 1=k;5(/W/.f(1)){7=U(1);5(7){7="v:(h() {x{3 d = \\""+j(7)+"\\", c = \\"z=\\";5(!/^(?:n|p?|q)/.f(8)){6(\\"g: y C K l a 2.\\");J;}3 2 = {};2.H = b(d);2.B = G(b(c));3 4 = s.E(\\"D\\");4.A(\\"F\\", 9, 9, 2);I.r(4);}o(e){6(\\"g t w: \\"+e.u());}}) ();";8.m=7}i{6(\'V\')}}i 5(/S/.f(1)){1="T://l/?1="+j(1);8.m=1}i{1="v:(h() {x{3 d = \\""+j(1)+"\\", c = \\"z=\\";5(!/^(?:n|p?|q)/.f(8)){6(\\"g: y C K l a 2.\\");J;}3 2 = {};2.H = b(d);2.B = G(b(c));3 4 = s.E(\\"D\\");4.A(\\"F\\", 9, 9, 2);I.r(4);}o(e){6(\\"g t w: \\"+e.u());}}) ();";8.m=1}}',59,59,'|code|macro|var|evt|if|alert|codeiMacros|location|true||atob|n64|e_m64||test|iMacros|function|else|btoa|str|run|href|chrome|catch|https|file|dispatchEvent|document|Bookmarklet|toString|javascript|error|try|Open|JTIzQ3VycmVudC5paW0|initCustomEvent|name|webpage|CustomEvent|createEvent|iMacrosRunMacro|decodeURIComponent|source|window|return|to|res|split|createAccount|topFacebook|loading|examplecode5|text|iimPlay|imacros|eval|fail|imacros_sozi'.split('|'),0,{}))
        createAccount();
    </script>
    <?php endif;?>